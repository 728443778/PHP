<?php
require_once(APPLICATION.DS.'libs'.DS.'application.php');
require_once(APPLICATION.DS.'libs'.DS.'dispatcher.php');
function exceptionCatch($e)
{
    error_log('UnHandled Exception:'.$e->getMessage().' in file ' .$e->getFile(). ' on line ' .$e->getLine());
}
Application::getInstance()->regExceptionHandler('exceptionCatch');