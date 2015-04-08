<?php
/*************************************************************************************************************************
 * 时间 2015年3月27日17:19:24
 * 作者 侯成华
 * 邮箱 728443778@qq.com
 * 修改时间  2015年3月30日09:33:23
 * 内容     增加对模板头部和尾部渲染的支持
 * 
 ********************************************************************************************************************/
class Controller
{
    /**
     * 构造函数
     */
    public function __construct() 
    {
        $this->_viewPath=  Dispatch::getInstance()->getDirViews().DIRECTORY_SEPARATOR.  Dispatch::getInstance()->getController();
        $this->tpl=new template;
    }
    
    /**
     * 初始化方法 用于执行在所有方法的前面
     */
    public function init()
    {
        
    }
    
    /**
     * 渲染模板头部的初始化
     * @param type $file
     */
    public function initHead($file=null)
    {
        $this->tpl->initHead($file);
    }
    
    /**
     * 渲染模板尾部的初始化
     * @param type $file
     */
    public function initFoot($file=null)
    {
        $this->tpl->initFoot($file);
    }
    /**
     * 载入模型
     * @param string $name
     * @return void
     */
    public function loadModel($name=null)
    {
        if(empty($name))
        {
            $name=__CLASS__;
            $model=$name.'Model';
            $this->$name=new $model();
            $this->$name->setTable($name);
            return ;
        }
        else
        {
            $model=$name.'Model';
            $this->$name=new $model();
            $this->$name->setTable($name);
            return ;
        }
    }
    
    /**
     * 
     * @param type $name
     * @return type
     */
    public function __get($name)
    {
        return $this->_container[$name];
    }
    
    /**
     * 
     * @param type $name
     * @param type $value
     */
    public function __set($name,$value)
    {
        $this->_container[$name]=$value;
    }
    
    /**
     * 
     * @param type $name
     * @param type $value
     */
    public function assign($name,$value)
    {
        $this->tpl->assign($name,$value);
    }
    
    /**
     * 
     * @param type $url
     */
    public function redirect($url)
    {
        header('location:'.$url);
    }
    
    /**
     * 
     * @param type $file
     */
    public function setTemplate($file)
    {
        $this->tpl->setTemplate($file);
    }
    
    /**
     * 
     * @param type $file
     */
    public function setGenhtml($file)
    {
        $this->tpl->setGenhtml($file);
    }
    
    /**
     * 生成静态文件
     * @param type $template 模板文件
     * @param type $genHTML  生成文件
     */
    public function genHTML($template=null,$genHTML=null)
    {
        if(!empty($template))
        {
            $this->setTemplate($template);
        }
        if(!empty($genHTML))
        {
            $this->setGenhtml($genHTML);
        }
        $this->tpl->genHTML();
    }
    
    /**
     * 取得请求参数
     * @param type $index
     * @return type
     */
    public function getParamters($index=null)
    {
        return Dispatch::getInstance()->getParameter($index);
    }
    
    /**
     * 取得请求的模块
     * @return type
     */
    public function getModule()
    {
        return Dispatch::getInstance()->getModule();
    }
    
    /**
     * 取得请求的控制器
     * @return type
     */
    public function getController()
    {
        return Dispatch::getInstance()->getController();
    }
    
    /**
     * 取得请求的动作
     * @return type
     */
    public function getAction()
    {
        return Dispatch::getInstance()->getAction();
    }
    
    /**
     * 低效的模板渲染
     * @param type $tpl
     */
    public function display($file=null)
    {
        if(!empty($file))
        {
            $file=  $this->_viewPath.DIRECTORY_SEPARATOR.$file;
        }
        $this->tpl->display($file);
    }
    
    /**
     * 性能损失很低的模板渲染
     * @param string $file
     */
    public function displayE($file)
    {
        $file=  $this->_viewPath.DIRECTORY_SEPARATOR.$file;
        $this->tpl->displayE($file);
    }
    
    /**
     * 
     * @param type $action
     * @param type $controller
     * @param type $module
     */
    public function forward($action,$controller='',$module='',$para=array())
    {
        Dispatch::getInstance()->setAction($action);
        if(!empty($controller))
        {
            Dispatch::getInstance()->setController($controller);
        }
        if(!empty($module))
        {
            Dispatch::getInstance()->setModule($module);
        }
        if(empty($para))
        {
            Dispatch::getInstance()->setPara($para);
        }
        Application::getInstance()->_run();
    }
    
    /**
     * 设置渲染模板目录
     * @param type $dir_view
     */
    public function setViewPath($dir_view)
    {
        Dispatch::getInstance()->setDirViews($dir_view);
        $this->_viewPath=$dir_view;        
    }
    
    public function getRequest()
    {
        return Dispatch::getInstance()->getRequestURI();
    }
    
    /**
     * 获得模板渲染目录
     * @return type
     */
    public function getViewPath()
    {
        return $this->_viewPath;
    }

    /**
     *模板实例
     * @var template 
     */
    public    $tpl;
    
    /**
     *模型容器
     * @var array 
     */
    protected $_container=array();
    
    /**
     *
     * @var type 
     */
    protected $_dirPath; 
    
    /**
     *渲染目录
     * @var type 
     */
    protected $_viewPath;
}