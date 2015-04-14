<?php
class loginController extends baseController
{
	public function index()
	{
		$this->displayE('login.html');
	}

    /**
     * 登录
     */
    public function login()
    {
        $userphone=$this->getParamters('phone');
        $passwd=Security::md5($this->getParamters('password'));
        //loadModel可以再需要使用的地方使用，这里是演示
        $this->loadModel('user');
        $items=$this->user->getAllByPhone($userphone);
        if($items['pass']!=$passwd || empty($items['pass']))
        {
            $this->error('登录手机号或者密码错误','login',2);
            return ;
        }
        elseif($items['pass']==$passwd)
        {
            if($items['status']==2 || $items['status']==0)
            {
                $this->error('账号未激活，或被禁用','./',2);
                return ;
            }
            $_SESSION["user_id"] = $items["id"];
            $_SESSION["username"] = $items["username"];
            $_SESSION["group_id"] = $items["groupid"];
            $_SESSION["user_rs"]= $items;
            $this->error('登录成功','./',2);
        }

    }

    /**
     * 注销
     */
    public function logout()
    {
        $_SESSION["user_id"] = null;
        $_SESSION["user_name"] = null;
        $_SESSION["group_id"] = null;
        $_SESSION["user_rs"] = null;
        $this->error('注销成功','./',2);
    }
}