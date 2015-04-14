<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class baseModel extends Model
{
    public function __construct($config=array())
    {
        parent::__construct($config);
    }

    /**
     * 在本业务中统计id的个数
     * @param $id
     */
    public function countId($array=array())
    {

    }

    /**
     * 通过手机号获取id号  常用语与检测手机号是否已经被注册
     * @param $phoneNum
     * @return Resource
     */
    public function getIdByPhone($phoneNum)
    {
        $items=$this->select(array('id'))->where('phone=:phone',array(':phone'=>$phoneNum))->getAll();
        return $items;
    }

    /**
     * 通过id获得所有的项，常用于查询信息 由于是获得所有信息所以 在访问量大的时候 应对其进行重载
     * @param $id
     * @return Resource
     */
    public function getAllById($id)
    {
        $items=$this->select(array('*'))->where('id=:id',array(':id'=>$id))->getAll();
        return $items;
    }
}

