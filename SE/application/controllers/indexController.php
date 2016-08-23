<?php
class indexController extends baseController
{
    public function index()
    {
        $this->display('index.html');
    }
    public function valicode()
    {
        valicode::genValiCode();
    }
    public function testCache()
    {
        $redis=cache::init();
        echo $redis->get('myphone');
        $redis->close();
    }
}

