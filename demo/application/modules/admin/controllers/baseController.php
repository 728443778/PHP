<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2015/4/9
 * Time: 10:28
 */

class baseController extends Controller
{
    public function init()
    {
        //parent::init();
        if(empty($_SESSION['admin_id']))
        {
            $this->forward('index','login');
        }
    }

}