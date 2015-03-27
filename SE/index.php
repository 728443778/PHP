<?php
define('DS',DIRECTORY_SEPARATOR);
define('ROOT',  dirname(__FILE__));
define('PUBLIC_DIR',ROOT.DS.'public');
define('APPLICATION',ROOT.DS.'application');
require_once(APPLICATION.DS.'bootstrap.php');
Application::getInstance()->run(APPLICATION.DS.'config'.DS.'config.php');
//echo "<br/><hr/>var_dump(config)<br/>";
//var_dump(Application::getInstance()->getConfig());
//echo "<br/><hr/>var_dump(dispatcher)<br/>";
//var_dump(Application::getInstance()->getDispatcher());