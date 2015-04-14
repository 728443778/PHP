<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2015/4/8
 * Time: 11:07
 * 作者 侯成华
 * 邮箱 728443778@qq.com
 */
class user_groupModel extends  baseModel
{
    /**
     * 获得默认用户组
     */
    public function getDefault()
    {
        $items=$this->select(array('*'))->where("ifdefault='1' AND group_type='user'")->getFirst();
        return $items;
    }
}