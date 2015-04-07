<?php
/**
 * 
 */
class DB extends PDO
{
    /**
     * 
     * @param type $db
     * @param type $config
     * @return type
     * @throws Exception
     */
    public static function getInstance($db=-1,$config=null)
    {
        if(!(self::$_instance instanceof  DB))
        {
            if(empty($config))
            {
                try
                {
                    self::$_instance=new self(
                            Application::getInstance()->getConfig()['db']['dsn'],
                            Application::getInstance()->getConfig()['db']['username'],
                            Application::getInstance()->getConfig()['db']['passwd'],
                            Application::getInstance()->getConfig()['db']['options']
                            );
                }
                catch (PDOException $ex) 
                {
                    throw new Exception($ex->getMessage(),$ex->getCode());
                }
                catch (Exception $e)
                {
                    
                }
                self::$_instance->query('set names '.Application::getInstance()->getConfig()['db']['charset']);
            }
            else
            {
                try
                {
                    self::$_instance=new self($config['dsn'],$config['username'],$config['passwd'],$config['options']);
                }
                catch (PDOException $ex)
                {
                    throw new Exception($ex->getMessage(),$ex->getCode());
                }
                self::$_instance->query('set names '.$config['charset']);
            }
        }
        if($db!==-1)
        {
            $db=self::$_instance;
        }
        return self::$_instance;
    }
    
    public function setTransOk($ok=true)
    {
        $this->trans_ok=$ok;
    }
    
    /**
     * 
     * @return type
     */
    public function trans_start()
    {
        if(!$this->trans_started && $this->beginTransaction())
        {
            $this->trans_started=true;
        }
        return $this->trans_started;
    }
    
    /**
     * 
     */
    public function trans_stop()
    {
        if($this->inTransaction())
        {
            if($this->trans_ok)
            {
                $this->commit();
            }
            else
            {
                $this->rollBack();
            }
        }
        $this->trans_started=false;
    }
    
    /**
     * 
     */
    private function __clone()
    {
        ;
    }

    /**
     * @param $dsn
     * @param $username
     * @param $passwd
     * @param array $options
     */
    private function __construct($dsn, $username, $passwd, $options=array())
    {
        parent::__construct($dsn, $username, $passwd, $options);
    }
    public $trans_ok=true;
    public $trans_started=false;
    private static $_instance;
}