<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class baseController extends Controller
{
    public function init()
    {
        $this->initHead(Application::getInstance()->getDispatcher()->getDirViews().DIRECTORY_SEPARATOR.'head.html');
        $this->initFoot(Application::getInstance()->getDispatcher()->getDirViews().DIRECTORY_SEPARATOR.'foot.html');
    }
    
    public function initHead($file = null) 
    {
        parent::initHead($file);
    }
    
    public function initFoot($file = null) 
    {
        parent::initFoot($file);
    }
}

