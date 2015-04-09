<?php
/**************************************************************************************************
 * 应用程序类，不能继承
 * 作者         侯成华
 * 完成时间     2015年3月16日15:58:47
 * 邮箱         728443778@qq.com
 * 
 * 
 ***************************************************************************************************/

final class Application
{
    /**
     * 打印消息，根据配置 选择是否打印到浏览器显示
     * @param $msg
     */
    public function showlog($msg)
    {
        if(!self::getInstance()->getConfig()['system']['debug'])
        {
            error_log($msg);
            return ;
        }
        else
        {
            echo '<br/><font color="red">'.$msg.'</font>';
            return ;
        }
    }
    
    /**
     * 
     * @return type
     */
    public static function getInstance()
    {
        if(!(self::$_instance instanceof Application))
        {
            self::$_instance=new Application();
        }
        return self::$_instance;
    }
    
    /**
     * 
     * @return type
     */
    public function getDispatcher()
    {
        return Dispatch::getInstance();
    }
    
    /**
     * 设置应用程序的路径 
     * @param type $dir
     */
    public function setDirectory($dir)
    {
        if (!empty($dir))
        {
            //在我的机器上 设置的权限是不能写的 也就是说只有管理员和文件所有者可以更改
            //self::getInstance()->getConfig()['system']['dir']['application']=$dir;
        }
    }
    
    /**
     * 获得应用程序的路径
     * @return type
     */
    public function getDirectory()
    {
        return self::getInstance()->getConfig()['system']['dir']['application'];
    }
    
    /**
     * 获得系统配置
     * @return type
     */
    public function getConfig()
    {
        return $this->_config;
    }
    
    /**
     * 
     * @param string $config
     */
    public function run($config=null)
    {
        if(empty($config))
        {
            $config='./config.php';
        }
        $this->_readConfig($config);
        $this->_initSystem();
        $this->_run();
    }
    
    public function _run()
    {
        spl_autoload_register('Application::autoload');
        $controllerName= $this->getDispatcher()->getController();
        $action=$this->getDispatcher()->getAction();
        $action=rtrim($action);
        $controller=  rtrim($controllerName.'Controller');
        $inse=new $controller();
        $inse->init();
        $inse->$action();
    }
    
    /**
     * 
     * @param type $fileName
     */
    static public function autoload($fileName)
    {
        $dirApp = Dispatch::getInstance()->getDirApp();
        $dirControllers=  Dispatch::getInstance()->getDirControllers();
        $dirModels=  Dispatch::getInstance()->getDirModels();
        $dirModules=  Dispatch::getInstance()->getDirModules();
        $dirLibs=  Dispatch::getInstance()->getDirLibs();
        $module = Application::getInstance()->getDispatcher()->getModule();
        if (($module== Application::getInstance()->getConfig()['system']['default']['module']))
        {
            if(file_exists($dirControllers. DIRECTORY_SEPARATOR .$fileName. '.php'))
            {
                require($dirControllers. DIRECTORY_SEPARATOR .$fileName. '.php');
                return ;
            }
            if(file_exists($dirModels . DIRECTORY_SEPARATOR .$fileName. '.php'))
            {
                require($dirModels. DIRECTORY_SEPARATOR .$fileName. '.php');
                return ;
            }
        } 
        else
        {
            if(file_exists($dirModels[0]. DIRECTORY_SEPARATOR .$fileName. '.php'))
            {
                require($dirModels[0]. DIRECTORY_SEPARATOR .$fileName. '.php');
                return ;
            }
            if(file_exists($dirModules.DIRECTORY_SEPARATOR. $module.DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR .$fileName. '.php'))
            {
                require($dirModules.DIRECTORY_SEPARATOR. $module .DIRECTORY_SEPARATOR. 'controllers' . DIRECTORY_SEPARATOR .$fileName. '.php');
                return ;
            }
            if(file_exists($dirModels[1]. DIRECTORY_SEPARATOR .$fileName. '.php'))
            {
                require($dirModels[1]. DIRECTORY_SEPARATOR .$fileName. '.php');
                return ;
            }
        }
        if (Application::getInstance()->getConfig()['system']['ext']['extensions'] == true)
        {
            if(file_exists($dirLibs. DIRECTORY_SEPARATOR . 'extensions' .$fileName. '.php'))
            {
                require_once($dirLibs. DIRECTORY_SEPARATOR . 'extensions' .$fileName. '.php');
                return ;
            }
            if(file_exists($dirLibs. DIRECTORY_SEPARATOR . 'extensions' .DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.$fileName. '.php'))
            {
                require_once($dirLibs. DIRECTORY_SEPARATOR . 'extensions' .DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.$fileName. '.php');
                return ;
            }        
        }   
        if(file_exists($dirLibs. DIRECTORY_SEPARATOR .$fileName. '.php'))
        {
            require_once($dirLibs . DIRECTORY_SEPARATOR .$fileName. '.php');
            return ;
        }
    }
    
    /**
     * 
     * @param type $errorHandler
     * @return type
     */
    public function regErrorHandler($errorHandler)
    {
        if(function_exists($errorHandler))
        {
            set_error_handler($errorHandler);
            return ;
        }
        return ;
    }
    
    /**
     * 
     * @param type $exceptionHandler
     * @return type
     */
    public function regExceptionHandler($exceptionHandler)
    {
        if(function_exists($exceptionHandler))
        {
            set_exception_handler($exceptionHandler);
            return ;
        }
        return ;
    }
    
    /**
     * 
     * @param type $config
     */
    private function _readConfig($config)
    {
        $this->_config=  require($config);
    }
    
    /**
     * 
     */
    private function _initSystem()
    {
        $this->_initReport();
        $this->_initDispatch();
        if($this->getConfig()['system']['ext']['config'])
        {
            $module=$this->getDispatcher()->getModule();
            if($module!=$this->getConfig()['system']['default']['module'])
            {
                $config=include($this->getDispatcher()->getDirModules().DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');
                $config=$config?$config:array();
                $this->_config=  array_merge($this->_config,$config);
            }
        }
    }
    
    /**
     * 
     * @return type
     */
    private function _initReport()
    {
        if($this->getConfig()['system']['debug'])
        {
            error_reporting(E_ALL);
            ini_set('display_errors','on');
            return ;
        }
        else
        {
            error_reporting(E_ALL);
            ini_set('display_errors','off');
            ini_set('log_errors','on');
        }
    }
    
    private function __clone()
    {
        
    }
    
    private function __sleep() 
    {
        ;
    }
    
    private function __wakeup()
    {
        ;
    }
    
    /**
     * 
     */
    private function _initDispatch()
    {
        $this->getDispatcher()->init();
    }
    
    private function __construct()
    {     
        
    }
    
    private static $_instance=NULL;
    private $_config=array();
}
