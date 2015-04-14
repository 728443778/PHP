<?php
class indexController extends baseController
{
    public function index()
    {
        $this->displayE('index.html');
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

    public function test()
    {
        $this->forward('index','msg');
    }
}

