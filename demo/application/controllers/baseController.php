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

    /**
     * 统一的错误提示
     * @param $msg
     * @param $url
     * @param $time
     */
    public function error($msg,$url,$time)
    {
        if(!$msg)
        {
            $this->redirect($url);
        }
        $this->assign('msg',$msg);
        $this->assign('error_url',$url);
        $this->assign('time',$time);
        $this->assign('micro_time',$time*1000);
        $this->tpl->displayE(Dispatch::getInstance()->getDirViews().DIRECTORY_SEPARATOR.'error.html');
        exit;
    }
}

