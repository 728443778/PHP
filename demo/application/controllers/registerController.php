<?php
class registerController extends baseController
{
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('user');
        $this->loadModel('user_group');
       // var_dump($this->user);
    }
	public function index()
	{		
		$this->displayE('register.html');
	}

    /**
     * 用户注册流程可以不考虑过滤 利用pdo过滤
     */
	public function register()
	{
		$username=$this->getParamters('name');
        $userphone=$this->getParamters('phone');
        $passwd=$this->getParamters('password');
        $job=$this->getParamters('job');
        if(empty($username) || empty($userphone) || empty($passwd) || empty($job))
        {
            $this->error('填写的信息不完整','register',2);
            return ;
        }
        $reg='/^1[358][0-9]{9}$/';
        if(!preg_match($reg,$userphone))
        {
            $this->error('手机号不符规则，请填写正确的手机号','register',2);
            return ;
        }
        $data=array(
            'username'=>$username,
            'pass'=>Security::md5($passwd),
            'phone'=>$userphone,
            'job'=>$job,
            'regdate'=>time(),
            'status'=>1,
            'groupid'=>$this->user_group->getDefault()['id'],
            'fxstatus'=>0,
            'company'=>0,
            'thumb_id'=>0,
            'bankAccount'=>0,
            'cardCode'=>0,
            'bankName'=>0,
        );
        if($this->user->register($data))
        {
            $user_id=$this->user->getLastId();
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $data["username"];
            $_SESSION["group_id"] = $data["groupid"];
            $tmp_array = $data;
            $tmp_array["id"] = $user_id;
            $_SESSION["user_rs"] = $tmp_array;
            $this->error('注册成功','./',2);
            return ;
        }
        else
        {
            $this->error('注册失败，请稍后重新注册', 'register', 2);
            return ;
        }
	}

    /**
     *  检查手机号是否已注册
     */
    public function checkphone()
    {
        $phone=$this->getParamters('phone');
        if(!$phone)
        {
            echo 1;
            return ;
        }
        $items=$this->user->getIdByPhone($phone);
        if(empty($items[0]))
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