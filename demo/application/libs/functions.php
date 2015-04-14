<?php

/**
 * 创建url
 * @param string $action  方法
 * @param string $controller  控制器
 * @param string $model 模块
 * @param array $para 参数
 * @return string  基于web的路径
 */
function createUrl($action='',$controller='',$model='',$para=array())
{
    $url=WEB_ROOT;
    if($model)
    {
        $url.=WEB_DS.$model;
    }
    if($controller)
    {
        $url.=WEB_DS.$controller;
    }
    if($action)
    {
        $url.=WEB_DS.$action;
    }
    if($para)
    {
        $tmp='';
        foreach($para as $key=>$value)
        {
            $tmp.=WEB_DS.$key.WEB_DS.$value;
        }
        $url.=$tmp;
    }
    return $url;
}

/**
 * 与业务相关的函数
 * @param $id
 * @param string $type
 * @param string $implode_code
 * @return bool
 */
function sys_id_list($id,$type="strval",$implode_code=",")
{
    if (!in_array($type, array("strval", "intval", "floatval")))
    {
        $type = "strval";
    }
    if (!$id || (!is_array($id) && !is_string($id)))
    {
        return false;
    }
    if (is_string($id))
    {
        $new_list = array();
        $idlist = explode($implode_code, $id);
        foreach ($idlist AS $key => $value)
        {
            $value = trim($value);
            if ($value)
            {
                $new_list[] = $type($value);
            }
        }
    }
    else
    {
        $new_list = array();
        foreach($id AS $key=>$value)
        {
            $value = trim($value);
            if($value)
            {
                $new_list[] = $type($value);
            }
        }
    }
    $new_list = array_unique($new_list);
    if(count($new_list)>0)
    {
        return $new_list;
    }
    else
    {
        return false;
    }
}