<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2015/4/10
 * Time: 14:50
 */

class langModel extends baseModel
{
    public function getListChk($langid)
    {
        if($langid)
        {
            $langid = explode(',', $langid);
            $items=$this->select('*')->where('status=1 AND  '.$this->where_in('langid',$langid))->getFirst();
        }
        else
        {
            $items=$this->select('*')->where('status=1')->getFirst();
        }
        return $items;
    }

    public function get_list()
    {
        $sql = "SELECT * FROM yehnet_lang ORDER BY taxis ASC,langid ASC";
        return $this->query($sql);
    }
}