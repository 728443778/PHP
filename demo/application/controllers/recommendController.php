<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2015/4/8
 * Time: 17:12
 * 作者： 侯成华
 * 邮箱： 728443778@qq.com
 */

class recommendController extends  baseController
{
    public function index()
    {
        $this->displayE('index.html');
    }

    public function checkCustomer()
    {
        $phone=$this->getParamters('cellphone');
        $this->loadModel('customer');
        $itmes=$this->customer->getIdByPhone($phone);
        if(empty($itmes[0]['id']))
        {
            echo 0;
            return ;
        }
        else
        {
            echo 1;
            return ;
        }
    }
}