<?php
define('DS',DIRECTORY_SEPARATOR);
define('ROOT',  dirname(__FILE__));
define('PUBLIC_DIR',ROOT.DS.'public');
define('APPLICATION',ROOT.DS.'application');
define("WEB_ROOT",'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']));
define("WEB_PUBLIC",WEB_ROOT.DS.'public');
require_once(APPLICATION.DS.'bootstrap.php');
Application::getInstance()->run(APPLICATION.DS.'config'.DS.'config.php');
//echo "<br/><hr/>var_dump(config)<br/>";
var_dump(Application::getInstance()->getConfig());
//echo "<br/><hr/>var_dump(dispatcher)<br/>";
echo "<hr/>";
var_dump(Application::getInstance()->getDispatcher());