<?php
class indexController extends baseController
{
    /**
     * 一些初始化操作，可以写在构造函数里，也可以写在init方法里
     */
    public function init()
    {
        parent::init();
        $this->loadModel('admin');
        $this->loadModel('module');
    }

    public function index()
    {
        $this->displayE('index.html');
    }

    public function top()
    {
        $popedom = $this->admin->get_module_id($_SESSION["admin_id"]);
        //加载头部信息
        //读取头部信息
        $rslist = $this->module->top(0,1);
        if(!is_array($rslist)) $rslist = array();
        $newlist = array();
        $tmp_i = 0;
        foreach($rslist AS $key=>$value)
        {
            $value["left_url"] = createUrl('left','index','admin',array('id'=>$value['id']));
            if($value["js_function"])
            {
                $value["onclick"] = $value["js_function"]."()";
                $newlist[] = $value;
                $tmp_i++;
                continue;
            }
            if($popedom == "all")
            {
                $value["onclick"] = "change_this('".$tmp_i."','".$value["left_url"]."')";
                $newlist[] = $value;
                $tmp_i++;
                continue;
            }
            if($popedom && $popedom != "all")
            {
                //判断子级是否有适合的权限
                $sonlist = $this->module->left($value["id"],1);
                $tmp_son = false;
                if($sonlist && is_array($sonlist) && count($sonlist)>0)
                {
                    foreach($sonlist AS $k=>$v)
                    {
                        if(in_array($v["id"],$popedom))
                        {
                            $tmp_son = true;
                            break;
                        }
                    }
                }
                if($tmp_son)
                {
                    $value["onclick"] = "change_this('".$tmp_i."','".$value["left_url"]."')";
                    $newlist[] = $value;
                    $tmp_i++;
                    continue;
                }
            }
        }
        $this->assign("rslist",$newlist);
        //加载语言包
        $this->loadmodel("lang");
        $tmp_langlist = $this->lang->get_list();
        if($tmp_langlist)
        {
            $langlist = array();
            foreach($tmp_langlist AS $key=>$value)
            {
                if($value["status"])
                {
                    $langlist[] = $value;
                }
            }
            if(count($langlist)<1)
            {
                unset($langlist);
            }
        }
        if(!$langlist)
        {
            $langlist = array();
            $langlist[0]["langid"] = "zh";
            $langlist[0]["title"] = "简体中文";
            $langlist[0]["status"] = 1;
        }
        $admin_rs = $this->admin->getAllById($_SESSION["admin_id"]);
        if(!$admin_rs["if_system"])
        {
            $lang_popedom = sys_id_list($admin_rs["langid"]);
            if($lang_popedom && is_array($lang_popedom) && count($lang_popedom)>0)
            {
                $new_langlist = array();
                foreach($langlist AS $key=>$value)
                {
                    if(in_array($value["langid"],$lang_popedom))
                    {
                        $new_langlist[] = $value;
                    }
                }
                if(count($new_langlist)>0)
                {
                    $langlist = $new_langlist;
                }
            }
        }
        //判断是否有语言权限
        $this->assign("langlist",$langlist);
        $this->displayE("top.html");
    }

    public function testCache()
    {
        $redis=cache::init();
        echo $redis->get('myphone');
        $redis->close();
    }

    public function left()
    {
        $popedom = $this->admin->get_module_id($_SESSION["admin_id"]);
        $id = Security::int($this->getParamters("id"));
        $rslist = $this->module->left($id,1);
        if(!is_array($rslist)) $rslist = array();
        $newlist = array();
        foreach($rslist AS $key=>$value)
        {
            $ctrl_init = $value["ctrl_init"] ? $value["ctrl_init"] : "right";
            $func_init = $value["func_init"] ? $value["func_init"] : "index";
            if($popedom && is_array($popedom))
            {
                if(in_array($value["id"],$popedom))
                {
                    $value["menu_url"] = $this->url($ctrl_init.",".$func_init,"module_id=".$value["id"]);
                    $newlist[] = $value;
                }
            }
            else
            {
                if($popedom)
                {
                    $value["menu_url"] = $this->url($ctrl_init.",".$func_init,"module_id=".$value["id"]);
                    $newlist[] = $value;
                }
            }
        }
        $this->tpl->assign("rslist",$newlist);
        $this->tpl->p("left");
    }
}

