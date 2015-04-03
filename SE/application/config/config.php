<?php
return ['db' => ['dsn' => 'mysql:dbname=partner;host=192.168.61.2;port=3306',
        'username' => 'partner',
        'passwd' => 'ls5687451',
        'options' => [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                      PDO::ATTR_TIMEOUT => 2,
                      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
        'charset'=>'utf8',
        'tbprefix' => 'partner_',
    ],
    'router' => ['static' => false, 'all' => true
    ],
    'system' => [
        'cache'=>[
            'active'=>false,        // 是否使用缓存服务器
            'name'=>'Redis',        //名字
            'host'=>'192.168.61.3', //服务器地址 主缓存主机ip地址
            'port'=>'6379',         //端口号
            'conntime'=>60,         //持续连接时间  一分钟 可以更低 比如说3秒
            'expire'=>604800,       //生存时间一周，单位秒
            'auth'  =>'728443778',  //安全验证密码
            'pconnect'=>TRUE,      //是否长连接
            'isMS'=>false,          //是否主从配置的缓存服务器
            'slaves'=>[              //如果isMS为真，缓存服务器将连接这些从服务器
                    '192.168.61.4:6379',
                    '192.168.61.5:6379',
                    '192.168.61.6:6379',
                    '192.168.61.7:6379',
            ],
        ],
        'dir' => [
            'root' => ROOT,                 //必须配置的节点
            'application' => APPLICATION,   //必须配置的节点
            'public' => PUBLIC_DIR,         //必须配置的节点
            'views' =>'',
            'models'=>'',
            'modules'=>'',
            'libs'=>'',
            'controllers'=>'',
            'cache'=>'',
        ],
        'ext' => [
            'extensions'=>true,          //是否启用扩展库
            'config'=>true,             //使用使用模块配置文件
        ],
        'valicode'=>[
            'filesource'=>false,   //配置验证码文件源，如果验证码来自于文件，请填写验证码文件源的完整路径，包括文件名，文件内容格式为','分隔或者'\n'分隔，格式只能有一种
            'length'=>5,            //验证码的位数  ，不填写，默认
            'width'=>160,           //验证码的宽度  ，不填写，默认
            'height'=>40,           //验证码的高度  ，不填写，默认
            'font'  =>'',           //验证码的字体路径  ，不填写，默认
            'fontsize'=>20,         //验证码的字体大小
            'dynamic'=>false,       //动态验证码
            'math'=>false,          //数学计算验证码
            ],
        'debug' => true,
        'template'=>[
            'errorlog'=>APPLICATION . DS . 'cache' . 'error.log', //模板日志文件路径
        ],
        'name' => '全民经纪人', //站点名称
        'modules' => ['index', 'admin' //拥有的模块
        ],
        'default' => ['module' => 'index',
            'controller' => 'index',
            'action' => 'index',
        ],
        'id' => ['module' => 'm',
            'controller' => 'c',
            'action' => 'a',
        ],
    ],
];

