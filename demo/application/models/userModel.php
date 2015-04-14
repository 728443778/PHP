<?php

/**
 * Class userModel
 */
class userModel extends baseModel
{
	public function __construct($config=array())
	{
		parent::__construct($config);
        //可以不用指定了
		//$this->table='user';
	}

    /**
     * 用户注册
     * @param $data
     */
    public function register($data)
    {
        $table=$this->table($this->table);
        return $this->insert($table,$data);
    }

    /**
     * 获得上次插入的ID
     * @return int  id
     */
    public function getLastId()
    {
        return $this->last_id();
    }

    public function getPassByPhone($phoneNum)
    {
        $items=$this->select(array('pass'))->where('phone=:phone',array(':phone'=>$phoneNum))->getFirst();
        return $items;
    }

    public function getAllByPhone($phoneNum)
    {
        $items=$this->select(array('*'))->where('phone=:phone',array(':phone'=>$phoneNum))->getFirst();
        return $items;
    }
}