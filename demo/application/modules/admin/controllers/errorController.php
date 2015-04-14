<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2015/4/10
 * Time: 9:43
 */

class errorController extends Controller
{
    public function init()
    {
        $this->initHead(Application::getInstance()->getDispatcher()->getDirViews().DIRECTORY_SEPARATOR.'head.html');
        $this->initFoot(Application::getInstance()->getDispatcher()->getDirViews().DIRECTORY_SEPARATOR.'foot.html');
    }
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