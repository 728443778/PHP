<?php
class indexController extends Controller
{
    public function init()
    {
        //echo "init";
    }
    public function index()
    {
        $this->assign('content',"O(∩_∩)O哈哈~O(∩_∩)O~");
        $this->display('index.html');
    }
    public function valicode()
    {
        valicode::genValiCode();
        echo "valicode";
    }
    public function testCache()
    {
        $redis=cache::init();
        echo $redis->get('myphone');
        $redis->close();
    }
}

