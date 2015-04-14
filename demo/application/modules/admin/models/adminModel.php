<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2015/4/10
 * Time: 9:32
 */

class adminModel extends baseModel
{
    public function getAllById($id)
    {
        return $this->select('*')->where('id=:id',array(':id'=>$id))->getFirst();
    }

    public function getAllByName($name)
    {
        $items=$this->select('*')->where('name=:name',array(':name'=>$name))->getFirst();
        return $items;
    }

    /**
     * 在这里做基于角色的权限判断
     * @param $id
     * @return bool|string
     */
    public function get_module_id($id)
    {
        $items=$this->select('*')->where('id=:id AND status=1',array(':id'=>$id))->getFirst();
        if(!$items)
        {
            return false;
        }
        if($items['if_system'])
        {
            return 'all';
        }
        if(!$items['popedom'])
        {
            return false;
        }
        $popedom=explode(',',$items['popedom']);
        $idlist=array();
        foreach($popedom as $key=>$value)
        {
            $tmp=explode(':',$value);
            if($tmp[0])
            {
                $idlist[]=$tmp[0];
            }
        }
        if(count($idlist)>0)
        {
            return array_unique($idlist);
        }
        return false;
    }

    /**
     * @param type $id  更新数据的id
     * @param null|type $set 集合
     */
    public function updateData($id,$set)
    {
       return  $this->where('id=:id',array(':id'=>$id))->update($set);
    }
}