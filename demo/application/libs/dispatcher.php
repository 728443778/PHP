<?php
/***************************************************************************************************************
 * 路由类
 * 作者         侯成华
 * 时间         2015年3月15日16:01:28
 * 邮箱         728443778@qq.com
 * 
 * 
 ***************************************************************************************************************/
class Dispatch
{
    private function __construct()
    {
        /*
       $this->_action=NULL;
       $this->_request_uri=NULL;
       php版本大于5.4才能使用这种获取配置的方式
         * */
        $this->_parameters = array();
        $this->_module = Application::getInstance()->getConfig()['system']['default']['module'];
        $this->_controller = Application::getInstance()->getConfig()['system']['default']['controller'];
        $this->_action = Application::getInstance()->getConfig()['system']['default']['action'];
    }
    
    /**
     * 不能被序列化
     */
    private function __sleep()
    {
        ;
    }
    
    /**
     * 不能被反序列化
     */
    private function __wakeup() 
    {
        ;
    }
    
    /**
     * 获取路由实例
     * @return type  
     */
    public static function getInstance()
    {
        $class=__CLASS__;
        if(!(self::$_instace instanceof $class))
        {
            self::$_instace=new $class();
        }
        return self::$_instace;
    }
    
    /**
     * 路由的初始化工作
     */
    public function init()
    {
        $this->_initParameters();
        $this->_initRouter();
        $this->_initDir();
    }
    
    /**
     * 返回请求的URI
     * @return type
     */
    public function getRequestURI()
    {
        return $this->_request_uri;
    }
    
    /**
     * 返回模块
     * @return type
     */
    public function getModule()
    {
        return $this->_module;
    }
    
    /**
     * 获得应用程序的目录
     * @return type
     */
    public function getDirApp()
    {
        return $this->_dirApp;
    }
    
    /**
     * 获得控制器的目录
     * @return type
     */
    public function getDirControllers()
    {
        return $this->_dirControllers;
    }
    
    /**
     * 获得模型的目录
     * @return type
     */
    public function getDirModels()
    {
        return $this->_dirModels;
    }
    
    /**
     * 获得视图的目录
     * @return type
     */
    public function getDirViews()
    {
        return $this->_dirViews;
    }
    
    /**
     * 获得缓存的目录
     * @return type
     */
    public function getDirCache()
    {
        return $this->_dirCache;
    }
    
    /**
     * 获得模块的目录
     * @return type
     */
    public function getDirModules()
    {
        return $this->_dirModules;
    }
    
    /**
     * 取得库的目录
     * @return string
     */
    public function getDirLibs()
    {
        return $this->_dirLibs;
    }
    
    /**
     * 设置控制器的目录
     * @param type $dir
     */
    public function setDirControllers($dir)
    {
        $this->_dirControllers=$dir;
    }
    
    /**
     * 设置模型的目录
     * @param type $dir
     */
    public function setDirModels($dir)
    {
        $this->_dirModels=$dir;
    }
    
    /**
     * 设置模块的目录
     * @param type $dir
     */
    public function setDirModules($dir)
    {
        $this->_dirModules=$dir;
    }
    
    /**
     * 设置视图的目录
     * @param type $dir
     */
    public function setDirViews($dir)
    {
        $this->_dirViews=$dir;
    }
    
    /**
     * 设置库的目录
     * @param type $dir
     */
    public function setDirLibs($dir)
    {
        $this->_dirLibs=$dir;
    }
    
    /**
     * 返回请求的控制器
     * @return type
     */
    public function getController()
    {
        return $this->_controller;
    }
    
    /**
     * 返回请求的方法
     * @return type
     */
    public function getAction()
    {
        return $this->_action;
    }
    
    /**
     * 设置方法
     * @param string $action
     */
    public function setAction($action)
    {
        $this->_action=$action;
    }
    
    /**
     * 设置控制器
     * @param string $controller
     */
    public function setController($controller)
    {
        $this->_controller=$controller;
    }
    
    /**
     * 设置module
     * @param string $module
     */
    public function setModule($module)
    {
        $this->_module=$module;
    }
    
    /**
     * 设置参数
     * @param array $para
     * @return void
     */
    public function setPara($para)
    {
        if(is_array($para))
        {
            foreach ($para as $key=>$value)
            {
                $this->_parameters[$key]=$value;
            }
        }
        return ;
    }
    
    /**
     * 返回参数
     * @param type $index 索引
     * @return type
     */
    public function getParameter($index=NULL)
    {
        if($index===NULL)
        {
            return $this->_parameters;
        }
        return $this->_parameters[$index];
    }
    
    /**
     * 路由初始化
     */
    private function _initRouter()
    {
        $this->_request_uri=$_SERVER['REQUEST_URI'];
        $pthinfo=$_SERVER['PATH_INFO'];
        $query_string=$_SERVER['QUERY_STRING'];
        if(Application::getInstance()->getConfig()['router']['all'])
        {
            if(empty($pthinfo) && empty($query_string))
            {
                //return ;
            }
            elseif(empty ($pthinfo))
            {
                $this->query_string($query_string);
            }
            elseif(empty($query_string))
            {
                $this->path_info($pthinfo);
            }
            else
            {
                //当两种路由都不为空时，使用静态路由
                $this->path_info($pthinfo);
                $this->_initParameters();
            }
        }
        elseif(Application::getInstance()->getConfig()['router']['static'])
        {
            if(empty($pthinfo))
            {
                //return ;
            }
            else
            {
                $this->path_info($pthinfo);
                //return ;
            }
        }
        else
        {
            if(empty($query_string))
            {
                //return ;
            }
            else
            {
                $this->query_string($query_string);
               // return ;
            }
        }
        self::getInstance()->noModule();
    }
    
    /**
     * 
     */
    private function noModule()
    {
        if(!in_array($this->_module, Application::getInstance()->getConfig()['system']['modules']))
        {
            Application::getInstance()->showlog('这个'.$this->_module.' module是没有注册的module : The '.$this->_module.' module of the module is not registered');
            exit();
        }
    }
    
    /**
     * 
     * @param type $query_string
     */
    private function query_string($query_string)
    {
        $controllerID=  Application::getInstance()->getConfig()['system']['id']['controller'];
        $moduleID=  Application::getInstance()->getConfig()['system']['id']['module'];
        $actionID=  Application::getInstance()->getConfig()['system']['id']['action'];
        if($_GET[$controllerID])
        {
            $this->_controller=  isset($_GET[$controllerID])?$_GET[$controllerID]:$this->_controller;
        }
        if($_GET[$moduleID])
        {
            $this->_module=  isset($_GET[$moduleID])? $_GET[$moduleID] : $this->_module ;
        }
        if($_GET[$actionID])
        {
            $this->_action=  isset($_GET[$actionID])?$_GET[$actionID]:$this->_action;
        }
    }
    
    /**
     * 伪静态路由的解析
     * @param type $path_info
     * @return type
     */
    private function path_info($path_info)
    {
        $array=explode('/',$path_info);
        if(empty($array))
        {
            return ;
        }
        //发现请求的第一个参数能在网站程序根目录下找到，则函数返回
        if($files=scandir(Application::getInstance()->getConfig()['system']['dir']['root']))
        {
            if(in_array($array[1],$files))
            {
                return ;
            }
        }
        $num=count($array);
        if($num==2)
        {
            $this->_controller=$array[1];
        }
        elseif($num==3)
        {
            $this->_controller=$array[1];
            $this->_action=empty($array[2])?$this->_action:$array[2];
        }
        else
        {
            $this->_module=$array[1];
            $this->_controller=$array[2];
            $this->_action=empty($array[3])?$this->_action:$array[3];
            if($num>4)
            {
                for($i=4;$i<$num;$i+=2)
                {
                    $this->_parameters[$array[$i]]=$array[$i+1];
                }
                unset($i);
                return ;
            }
        }
    }
    
    /**
     * 应用程序路径的初始化
     * 
     */
    private function _initDir()
    {
        $dirCache=Application::getInstance()->getConfig()['system']['dir']['cache'];
        $dirControllers=Application::getInstance()->getConfig()['system']['dir']['controllers'];
        $dirLibs=Application::getInstance()->getConfig()['system']['dir']['libs'];
        $dirModels=Application::getInstance()->getConfig()['system']['dir']['models'];
        $dirModules=Application::getInstance()->getConfig()['system']['dir']['modules'];
        $dirViews=Application::getInstance()->getConfig()['system']['dir']['views'];
        $this->_dirApp=     Application::getInstance()->getConfig()['system']['dir']['application'];
        if($this->getModule()!=Application::getInstance()->getConfig()['system']['default']['module'])
        {
            $this->_dirLibs=empty($dirLibs)?$this->_dirApp.DIRECTORY_SEPARATOR.'libs':$dirLibs;
            $this->_dirModules=empty($dirModules)?$this->_dirApp.DIRECTORY_SEPARATOR.'modules':$dirModules;
            $this->_dirCache= $this->_dirApp.DIRECTORY_SEPARATOR.$this->_module.DIRECTORY_SEPARATOR.'cache';
            $this->_dirControllers=$this->_dirModules.DIRECTORY_SEPARATOR.$this->_module.DIRECTORY_SEPARATOR.'controllers';
            $this->_dirModels=array('0'=>empty($dirModels)?$this->_dirApp.DIRECTORY_SEPARATOR.'models':$dirModels,
                '1'=>$this->_dirModules.DIRECTORY_SEPARATOR.$this->_module.DIRECTORY_SEPARATOR.'models');
            $this->_dirViews=$this->_dirModules.DIRECTORY_SEPARATOR.$this->_module.DIRECTORY_SEPARATOR.'views';
            return ;
        }
        $this->_dirLibs=  empty($dirLibs)?$this->_dirApp.DIRECTORY_SEPARATOR.'libs':$dirLibs;
        $this->_dirModules=  empty($dirModules)?$this->_dirApp.DIRECTORY_SEPARATOR.'modules':$dirModules;
        $this->_dirCache=  empty($dirCache)?$this->_dirApp.DIRECTORY_SEPARATOR.'cache': $dirCache;
        $this->_dirControllers=  empty($dirControllers)?$this->_dirApp.DIRECTORY_SEPARATOR.'controllers':$dirControllers;
        $this->_dirModels=  empty($dirModels)?$this->_dirApp.DIRECTORY_SEPARATOR.'models':$dirModels;
        $this->_dirViews=  empty($dirViews)?$this->_dirApp.DIRECTORY_SEPARATOR.'views':$dirViews;
    }
    
    /**
     * 合并所有的get参数 和post参数 如果参数有冲突，已post覆盖get参数
     */
    private function _initParameters()
    {
        $this->_parameters=  array_merge($_GET,$_POST);
    }

    /**
     *
     */
    private function __clone()
    {
        ;
    }
    
    /**
     *请求的uri
     * @var string
     */
    protected $_request_uri;
    
    /**
     *请求的控制器
     * @var string
     */
    protected $_controller;
    
    /**
     *请求的方法
     * @var string
     */
    protected $_action;
    
    /**
     *请求的
     * @var array
     */
    protected $_parameters;
    
    /**
     *请求的模块
     * @var string  
     */
    protected $_module;
    
    /**
     *视图目录
     * @var string 
     */
    protected $_dirViews;
    
    /**
     *应用目录
     * @var string 
     */
    protected $_dirApp;
    
    /**
     * 库目录
     * @var string 
     */
    protected $_dirLibs;
    
    /**
     *控制器目录
     * @var string 
     */
    protected $_dirControllers;
    
    /**
     *缓存目录
     * @var string  
     */
    protected $_dirCache;
    
    /**
     *模型目录
     * @var string  
     */
    protected $_dirModels;
    
    /**
     *模块目录
     * @var string 
     */
    protected $_dirModules;
    
    /**
     *路哟实例
     * @var Dispatcher 
     */
    private static $_instace=NULL;
}