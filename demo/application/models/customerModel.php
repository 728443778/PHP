<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2015/4/8
 * Time: 17:13
 */

class customerModel extends  baseModel
{
    /**
     * 来自第三方的数据库  不明白 都是电话 为啥字段名不统一 所以这里需要重载
     * @param $phoneNum
     * @return Resource
     */
    public function getIdByPhone($phoneNum)
    {
        $items=$this->select(array('id'))->where('cellphone=:phone',array(':phone'=>$phoneNum))->getAll();
        return $items;
    }
}