<?php
class indexController extends Controller {
    public function init()
    {
        //echo "init";
    }
    public function index()
    {
        echo "index";
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

