<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2015/4/10
 * Time: 16:13
 */

class moduleModel extends baseModel
{
    /**
     * 直接写sql查询 很灵活
     * @param string $groupid
     * @param string $status
     */
    public function top($groupid='',$status='0')
    {

        $sql='select *  from yehnet_module_group where 1=1';
        if($status)
        {
            $sql.=" and status ='1'";
        }
        if($groupid)
        {
            $sql.=" and id ='".$groupid."'";
        }
        $items=$this->query($sql);
        return $items;
    }

    public function left($groupid=0,$status=0)
    {
        if(!$groupid)
        {
            return false;
        }
        $groupid=intval($groupid);
        $sql = "SELECT * FROM yehnet_module WHERE (group_id='".$groupid."' OR group_id='0') ";
        //是否有状态限制
        if($status)
        {
            $sql .= " AND status='1' ";
        }
        $sql .= " ORDER BY taxis ASC,id DESC ";
        $rslist = $this->query($sql);
        return $rslist;
    }
}