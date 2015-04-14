<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2015/4/9
 * Time: 10:31
 */

/**
 * 该类不能继承自baseController类 如果要继承自baseController类 应该重写init方法
 * Class loginController
 */
class loginController  extends errorController
{
    public function index()
    {
        $this->displayE('index.html');
    }

    public function valicode()
    {
        valicode::genValiCode();
        $_SESSION['valicode']=valicode::getCode();
    }

    public function login()
    {
        $username=$this->getParamters('username');
        $password=$this->getParamters('password');
        $valicode=$_SESSION['valicode'];
        if($this->getParamters('chk')!=$valicode)
        {
            $this->error('验证码错误',createUrl('index','login','admin'),2);
        }
        if(empty($username) || empty($password))
        {
            $this->error('用户名或者密码为空',createUrl('index','login','admin'),2);
        }
        $this->loadModel('admin');
        $items=$this->admin->getAllByName($username);
        if(empty($items['pass']) || $items['pass']!=Security::md5($password) )
        {
            $this->error('用户名或者密码错误',createUrl('index','login','admin'),2);
        }
        if(!$items['if_system'] || !$items['langid'])
        {
            $this->error('登录账户没有配置语言包权限',createUrl('index','login','admin'),2);
        }
        $this->loadModel('lang');
        $langid=$items['if_system']?'':$items['langid'];
        $chk_admin=$this->lang->getListChk($langid);
        if(!$chk_admin)
        {
            $this->error('登录账号没有符合要求的内容管理权限',createUrl('index','login','admin'),2);
        }
        $_SESSION['sys_lang_id']=$chk_admin['langid'];
        $_SESSION['admin_id']=$items['id'];
        $_SESSION['admin_name']=$username;
        $this->admin->updateData($_SESSION['admin_id'],array('logindate'=>time()));
        $this->redirect(createUrl('index','index','admin'));
    }

    public function logout()
    {
        $_SESSION['sys_lang_id']=null;
        $_SESSION['admin_id']=null;
        $_SESSION['admin_name']=null;
        $this->error('注销成功',createUrl('index','login','admin'),2);
    }
}