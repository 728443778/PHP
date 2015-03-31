<?php
/*********************************************************************************************
 * 模板引擎 此模板引擎类没有雨框架关联所以可以直接拿出来单独使用
 * 完成时间   2014年10月16日17:54:21
 * 模板引擎标签 <{}>  或者 <?php ?> 没写支持自定义标签，后续工作太杂了
 * 作者      侯成华
 * 邮箱      728443778@qq.com
 * 修改时间   2015年3月30日15:52:15
 * 内容      增加了对头部和尾部的支持  使用方法：在本框架中写一个控制器基类，把模板的头部和尾部进行初始化 然后其他控制器继承自改基类
 **********************************************************************************************/
class template
{
    /**
     * 
     * @param type $template
     * @param type $genhtml
     */
    public function __construct($template=null,$genhtml=null)
    {
        $this->template=$template;
        $this->genhtml=$genhtml;
    }
    
    /**
     * 新增加的用于支持模板头部和尾部渲染的
     * @param type $file
     */
    public function initHead($file)
    {
        $this->headfile= $file;
    }
    
    /**
     * 新增加的用于支持模板头部和尾部渲染的
     * @param type $file
     */
    public function initFoot($file)
    {
        $this->footfile=  $file;
    }
    
    /**
     * 
     * @return type
     */
    public function init()
    {
        $this->logOpen();
        if (!file_exists($this->template)) 
        {
            $this->logWrite("模板文件不存在");
            return ;
        }
        $this->tempfile = file_get_contents($this->template);
    }

    /**
     * 自定义标签 后续工作没有做 太杂了
     * @param type $lefttag
     * @param type $righttag
     */
    public function setTag($lefttag,$righttag)
    {
        if(empty($lefttag))
        {
            $this->lefttag='<{';
        }
        else
        {
            $this->lefttag=$lefttag;
        }
        if(empty($righttag))
        {
            $this->righttag='}>';
        }
        else
        {
            $this->righttag=$righttag;
        }
    }
    
    /**
     * 编译模板文件
     */
    public function compile() 
    {
        $pattern = array(
            '/\<\{\s*\$([a-zA-Z_]+[0-9_]*)\s*\}\>/',
            '/\<\{\s*if\s*(.*)\s*\}\>/',
            '/\<\{\s*elseif\s*(.*)\s*\}\>/',
            '/\<\{\s*else\s*(.*)\s*\}\>/',
            '/\<\{\s*end\s*\}\>/',
            '/\<\{\s*while(.*)\s*\}\>/'
        );
        $replace = array(
            '<?php echo  $this->vars[${1}]; ?>',
            '<?php if${1} { ?>',
            '<?php elseif${1} { ?>',
            '<?php else${1} { ?>',
            ' } ?>',
            '<?php while${1} { ?>'
        );
        if(!empty($this->headfile))
        {
            $file=  file_get_contents($this->headfile);
        }
        $file.=$this->tempfile;
        if(!empty($this->footfile))
        {
            $file.=file_get_contents($this->footfile);
        }
        if (!$this->compfile = preg_replace($pattern, $replace,$file ))
        {
            $this->logWrite("编译失败");
        }
    }
    
    /**
     * 获得生成的静态文件目录
     * @return string
     */
    public function getGenHTML()
    {
        return $this->genhtml;
    }
    
     /**
     * 设置生成静态文件
     * @param $genhtml   生成静态文件的目录
     */
    public function setGenhtml($genhtml)
    {
        $this->genhtml=$genhtml;
    }
    
    
    public function getTemplate()
    {
        return $this->template;
    }
    
    /**
     * 设置模板文件
     * @param type $dirfile
     */
    public function setTemplate($dirfile)
    {
        $this->template=$dirfile;
    }
    
    /**
     * 注入变量-变量值
     * @param type $var
     * @param type $value
     */
    public function assign($var, $value) 
    {
        $this->vars[$var] = $value;
    }
    
    /**
     * 生成文件
     * @return type
     */
    public function genHTML() {
        $this->init();
        $this->compile();
        ob_start();
        ob_clean();
        eval('?>' . $this->compfile);
        $contents = ob_get_contents();
        ob_end_clean();
        $temphandle = fopen($this->genhtml, "w");
        if (!$temphandle)
        {
            $this->logWrite("没有打开或创建目标文件");
            return;
        }
        if (!fwrite($temphandle, $contents))
        {
            $this->logWrite("生成文件时写入失败");
        }
        fclose($temphandle);
    }

    

    /**
     * 低效的渲染
     * @param $file 渲染文件
     */
    public function display($file=null)
    {
        if(!empty($file))
        {
            $this->setTemplate($file);
        }
        $this->init();
        $this->compile();
        //似乎显得多余，但是为了避免标签的多重使用 还是加上这个
        extract($this->vars,EXTR_OVERWRITE);
        eval('?>' . $this->compfile);
    }

    /**
     * 性能损失很低的模板渲染
     * 标签必须是 <?php ?>格式的
     * @param type $file
     * @return type
     */
    public function displayE($file)
    {
        if(empty($file))
        {
            return ;
        }
        extract($this->vars,EXTR_OVERWRITE);
        try
        {
            if(!empty($this->headfile))
            {
                include($this->headfile);
            }           
            include($file);
            if(!empty($this->footfile))
            {
                include($this->footfile);
            }
        }
        catch (Exception $e) 
        {
            $this->logWrite($e->getmessage());
        }
    }
    
    /**
     * 打开或者创建日志文件
     */
    protected function logOpen() 
    {
        $this->handleLog = fopen($this->errlog, "a");
        if(!$this->handleLog)
        {
            error_log("fail to open error.log");
            return ;
        }
        return ;
    }

    /**
     *  设置日志文件路径
     * @param type $newDir 新的日志文件路径
     */
    public function logSetDir($newDir) 
    {
        $this->errlog = $newDir;
    }

    /**
     * 向日志文件中写入信息
     * @param type $msg 需要写入的消息
     * @return type 写入失败返回1
     */
    protected function logWrite($msg) 
    {
        if($this->handleLog)
        {
            fwrite($this->handleLog, date("Y-m-d H:i:s", time()) . "$msg" . "\n");
            return ;
        }
        error_log($msg);
    }
    

    /**
     * 关闭日志文件
     */
    protected function logClose() 
    {
        fclose($this->handleLog);
    }

    /**
     * 析构函数
     */
    public function __destruct() 
    {
        $this->logClose();
    }
    
    /**
     *模板文件
     * @var string 模板文件 
     */
    protected $template;
    protected $vars;
    protected $handleLog;
    protected $errorlog;
    
    /**
     *生成的静态文件
     * @var string
     */
    protected $genhtml;
    
    /**
     *模板文件内容
     * @var string
     */
    protected $tempfile;
    
    /**
     *编译后的模板文件
     * @var string
     */
    protected $compfile;
    
    /**
     * 如果要自定义左右标签后 后续工作太杂了
     * 算了
     */
    
    /**
     *模板引擎左标签
     * @var string
     */
    protected $lefttag;
    
    /**
     *模板引擎右标签
     * @var string
     */
    protected $righttag;
    
    /**
     * 新增加的模板头部内容文件路径
     * @var string 
     */
    protected $headfile='';
    
    /**
     * 新增加的模板尾部内容文件路径
     * @var type 
     */
    protected $footfile='';
}

