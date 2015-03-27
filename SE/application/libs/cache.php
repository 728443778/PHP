<?php
/**
 * 完成时间     2015年3月26日15:56:05
 * 作者         侯成华
 * 邮箱         728443778@qq.com
 */

/**
 * 缓存类
 * 
 */
class cache
{

    /**
     * 获得reids实例
     * @return cache
     *
     */
    public static function getInstance()
    {
        if(! self::$_instance instanceof cache)
        {
            self::$_instance=new cache();
        }
        return self::$_instance;
    }

    /**
     * 自由链接
     * @param type $config 服务器配置
     * @param type $isMaster    是否是主服务器
     * @return type             返回错误标识代码
     */
    public function connect($config=array('host'=>'127.0.0.1','port'=>'6379'),$isMaster=false)
    {
        $func=$this->_pconnect?'pconnect':'connect';
        if($isMaster)
        {
            $this->_linkHandler['master']=new Redis();
            $ret=$this->_linkHandler['master']->{$func}($config['host'],$config['port'],$this->_conntime);
        }
        else
        {
            $this->_linkHandler['slave'][$this->_sHandler]=new Redis(); 
            $ret=$this->_linkHandler['slaves'][$this->_sHandler]->{$func}($config['host'],$config['port']);
            ++$this->_sHandler;
        }
        return $ret;
    }
    
    /**
     * 返回Redis对象得到更灵活的操作
     * @param bool $ismaster 是否获得主Redis
     * @return Redis 但$ismaster为true时，返回主Redis对象，为false时，返回从Redis的数组
     */
    public function getRedis($ismaster=true)
    {
        if($ismaster)
        {
            return $this->_linkHandler['master'];
        }
        else
        {
            return $this->_linkHandler['slaves'];
        }
    }
    
    /**
     * 写缓存
     * @param string $key   缓存key
     * @param string $value 缓存value
     * @param int $expire   生存时间；0表示永久生存
     * @return int          返回错误代码
     */
    public function set($key,$value,$expire)
    {
        $ret=0;
        if($expire)
        {
            $ret=$this->getRedis()->setex($key,$expire,$value);
        }
        else
        {
            $ret=$this->getRedis()->set($key,$value);
        }
        return $ret;
    }
    
    /**
     * 
     */
    public function auth($str=null)
    {
        if(empty($str))
        {
            $this->getRedis()->auth($this->_auth);        
            return ;
        }
        $this->getRedis()->auth($str);    
    }
    
    /**
     * 
     * @return type
     */
    public static function init()
    {
        $redis=self::getInstance();
        $redis->auth();
        return $redis;
    }
    
    /**
     * 读缓存
     * @param string || array $key  缓存key
     * @return bool || string 成功返回string，失败返回false
     */
    public function get($key)
    {
        $func=is_array($key)?'mGet':'get';
        if($this->_isMS)
        {
            //使用了主从配置
            return $this->_getSlaveRedis()->{$func}($key);
        }
        return $this->getRedis()->{$func}($key);
    }

    /**
     * 魔术方法
     * @param type $name 回调函数名
     * @param type $arguments 回调函数参数
     * @return type
     */
    public function __call($name, $arguments)
    {
        return call_user_func($name,$arguments);
    }

    /**
     * 存储key  不存在则存储，存在则存储失败
     * @param type $key
     * @param type $value
     * @return type
     */
    public function setnx($key,$value)
    {
        return $this->getRedis()->setnx($key,$value);
    }
    
    /**
     *
     * @param type $key     需要删除的key 可以使数组
     * @return int          删除键的数量
     */
    public function remove($key)
    {
        return $this->getRedis()->delete($key);
    }

    /**
     * key对象的value加加操作
     * @param type $key
     * @param type $default
     * @return type  操作后的值
     */
    public function incr($key,$default=1)
    {
        return $this->getRedis()->incrBy($key,$default);
    }

    /**
     * 键key的valude值减减操作
     * @param type $key
     * @param type $default
     * @return type  操作后的值
     */
    public function decr($key,$default=1)
    {
        return $this->getRedis()->decrBy($key,$default);
    }

    /**
     *
     * @param type $key
     * @param type $value
     * @return type
     */
    public function lpush($key,$value)
    {
        return $this->getRedis()->lPush($key,$value);
    }

    /**
     *
     * @param type $key
     * @return type
     */
    public function lpop($key)
    {
        return $this->getRedis()->lPop($key);
    }

    /**
     *
     * @param type $key
     * @param type $started
     * @param type $end
     * @return type
     */
    public function lrange($key,$started,$end)
    {
        return $this->getRedis()->lrange($key,$started,$end);
    }

    /**
     *
     * @param type $name
     * @param type $key
     * @param type $value
     * @return type
     */
    public function hset($name,$key,$value)
    {
        if(is_array($value))
        {
            return $this->getRedis()->hSet($name,$key,  serialize($value));
        }
        return $this->getRedis()->hSet($name,$key,$value);
    }

    /**
     *
     * @param type $name
     * @param type $key
     * @param type $serialize
     * @return type
     */
    public function hget($name,$key=null,$serialize=true)
    {
        if($key)
        {
            $row=$this->getRedis()->hGet($name,$key);
            if($row && $serialize)
            {
                unserialize($row);
            }
            return $row;
        }
        return $this->getRedis()->getAll($name);
    }
    
    /**
     *
     * @param type $name
     * @param type $key
     * @return type
     */
    public function hdel($name,$key=null)
    {
        if($key)
        {
            return $this->getRedis()->hDel($name,$key);
        }
        return $this->getRedis()->hDel($name);
    }

    /**
     * 开始一个事务
     * @return type
     */
    public function multi()
    {
        return $this->getRedis()->multi();
    }

    /**
     * 提交一个事务
     * @return type
     */
    public function exec()
    {
        return $this->getRedis()->exec();
    }
    
    /**
     * m/s配置的返回从服务器的操作句柄
     * @return type
     */
    private function _getSlaveRedis()
    {
        if($this->_sHandler<=1)
        {
            return $this->_linkHandler['slaves']['0'];
        }
        $hash=$this->_hashId(mt_rand(),$this->_sHandler);
        return $this->_linkHandler['slaves'][$hash];
    }

    /**
     * 根据ID得到hash值
     * @param type $id
     * @param type $m
     * @return type
     */
    private function _hashId($id,$m=10)
    {
        $k=md5($id);
        $l=  strlen($k);
        $b=  bin2hex($k);
        $h=0;
        $i=0;
        for($i;$i<$l;++$i)
        {
            $h+=substr($b, $i*2,2);
        }
        $hash=($h*1)%$m;
        return $hash;
    }
    
    /**
     * 清空数据库
     * @return type
     */
    public function clear()
    {
        return $this->getRedis()->flushDB();
    }
    
    /**
     * 关闭连接
     * @param enum $flag 0 关闭主服务器连接 1 关闭从服务器连接 2 关闭所有连接
     * @return int || array  返回关闭标号
     */
    public function close($flag=2)
    {
        $ret=0;
        switch($flag)
        {
            case 0:
                $ret=$this->getRedis()->close();
                break;
            case 1:
                $i=0;
                $ret=array();
                for($i;$i<$this->_sHandler;++$i)
                {
                    $ret[]=$this->_linkHandler['slaves'][$i]->close();
                }
                break;
            case 2:
                $i=0;
                $ret=array();
                $this->getRedis()->close();
                for($i;$i<$this->_sHandler;++$i)
                {
                    $ret[]=$this->_linkHandler['slaves'][$i]->close();
                }
                break;
        }
        return $ret;
    }

    /**
     * 私有的构造函数
     * 并连接配置文件中的服务器
     */
    private function __construct() 
    {
        $this->_host=Application::getInstance()->getConfig()['system']['cache']['host'];
        $this->_port=Application::getInstance()->getConfig()['system']['cache']['port'];
        $this->_conntime=Application::getInstance()->getConfig()['system']['cache']['conntime'];
        $this->_expire=Application::getInstance()->getConfig()['system']['cache']['expire'];
        $this->_auth=Application::getInstance()->getConfig()['system']['cache']['auth'];
        $this->_pconnect=Application::getInstance()->getConfig()['system']['cache']['pconnect'];
        $this->_isMS=Application::getInstance()->getConfig()['system']['cache']['isMS'];
        $this->_slaves=Application::getInstance()->getConfig()['system']['cache']['slaves'];
        try
        {
            $this->_linkHandler['master']=new Redis();
            $func=$this->_pconnect?'pconnect':'connect';
            $this->_linkHandler['master']->{$func}($this->_host,$this->_port,$this->_conntime);
            if($this->_isMS)
            {
                if(!empty($this->_slaves))
                {
                    foreach($this->_slaves as $value)
                    {
                        $arr=explode(':',$value);
                        $this->_linkHandler['slaves'][$this->_sHandler]=new Redis();
                        $this->_linkHandler['slaves'][$this->_sHandler]->{$func}($value[0],$value[1],$this->_conntime);
                    }
                }
            }
        } 
        catch (Exception $ex) 
        {
            Application::getInstance()->showlog($ex->getFile().' '.$ex->getLine().' : '.$ex->getMessage());
        }
    }

    /**
     * 不能被反序列化
     */
    private function __wakeup()
    {

    }

    /**
     * 不能被序列化
     */
    private function __sleep()
    {

    }

    /**
     * 不能被克隆
     */
    private function __clone()
    {

    }

    /**
     * @var string Redis服务器主机地址
     */
    private $_host;

    /**
     * @var string Redis服务器工作端口号
     */
    private $_port;

    /**
     * @var int 生存周期 单位秒
     */
    private $_expire;

    /**
     * @var int 连接时间
     */
    private $_conntime;

    /**
     * @var string 验证码
     */
    private $_auth;

    /**
     * @var bool 是否持久连接 用在高访问的时候
     */
    private $_pconnect;

    /**
     * @var int id号
     */
    private $_cacheid;

    /**
     * @var array 数据
     */
    private $_data;

    /**
     * @var cache cache的实例
     */
    private static $_instance;

    /**
     * 是否是主从配置
     * @var bool
     */
    private $_isMS;

    /**
     *  从属redis句柄
     * @var int
     */
    private $_sHandler;

    /**
     *保存从属服务器的连接信息
     * @var array 
     */
    private $_slaves;
    /**
     *服务器连接句柄
     * @var handler
     */
    private $_linkHandler=array(
        'master'=>null,         //支持一台主redis
        'slaves'=>array(),      //多台从属redis
    );
}

