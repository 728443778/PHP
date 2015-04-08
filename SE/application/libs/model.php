<?php

/**
 * Class Model
 */
class Model
{
    /**
     * 
     * @param type $config
     */
    public function __construct($config=array()) 
    {
        $this->dbhandler=DB::getInstance($this->dbhandler,$config);
        $this->tbprefix= empty($config)?Application::getInstance()->getConfig()['db']['tbprefix']:$config['tbprefix'];
        $this->debug=Application::getInstance()->getConfig()['system']['debug'] ? true : false;
    }

    /*
     * 没有实现 在控制器中通过载入模型的方式实现model的table实现
    public static $inse;

    public static function &init($config=array())
    {
        $name=__CLASS__;
        self::$inse=new $name($config);
        self::$inse->table=__CLASS__;
        return self::$inse;
    }

    */

    /**
     * 
     * @param type $v 处理str
     * @return type
     */
    public function field($v)
    {
        //strpos  查找字符串首次出现的位置，返回在字符串中首次出现的数字位置
        if (strpos($v, '*') !== false)
        {
            return $v;
        }
        $prefix = $suffix = '';
        if (strpos($v, '(') !== false)
        {
            //字段里有函数(SUM/AVG...)操作
            $offsetLeft = strrpos($v, '(') + 1;
            $offsetRight = strpos($v, ')');
            $prefix = substr($v, 0, $offsetLeft);
            $suffix = substr($v, $offsetRight);
            $v = substr($v, $offsetLeft, $offsetRight - $offsetLeft);
        }
        return strpos($v, '.') === false ? 
                $prefix . '`' . $v . '`' . $suffix : 
                $prefix . substr($v, 0, strpos($v, '.') + 1) . '`' . substr($v, strpos($v, '.') + 1) . '`' . $suffix;
    }
    
    /**
     * 
     * @param type $table 表名
     * @return type
     */
    public function table($table)
    {
        return '`'.$this->tbprefix.$table.'`';
    }
    
    /**
     * 
     * @param type $fields
     * @return \Model
     */
    public function select($fields=array())
    {
        if(!empty($fields))
        {
            $this->ar_select=' ';
        }
        if(is_string($fields))
        {
            $this->ar_select=$fields;
        }
        else
        {
            foreach ($fields as $value)
            {
                $this->ar_select.=$this->field($value).',';
                $this->ar_select=  substr($this->ar_select, 0,-1);
            }
        }
        if($this->cache_ar)
        {
            $this->cache_select=1;
        }
        return $this;
    }
    
    /**
     * 
     * @param type $table   完整表名
     * @param type $where   条件
     * @param type $type
     * @return \Model
     */
    public function join($table, $where, $type=self::JOIN_LFET)
    {
        $this->ar_join .= $type. ''.$table.' ON '.$where;
        if($this->cache_ar)
        {
            $this->cache_join = 1;
        }
        return $this;
    }
    
    /**
     * 
     * @param type $field
     * @param type $arg
     * @param type $not
     * @return type
     */
    public function where_in($field, $arg = array(), $not = false)
    {
        $sql = $this->field($field) . ($not ? ' NOT IN (' : ' IN (');
        $tmp = '';
        foreach ($arg as $item)
            $tmp .= $this->dbhandler->quote($item) . ',';
        $sql .= substr($tmp, 0, -1);
        return $sql . ') ';
    }
    
    /**
     * 
     * @param type $field   字段
     * @param type $min     整型 
     * @param type $max     整型
     * @param type $not     是否是not
     * @return type
     */
    public function between($field, $min, $max, $not = false)
    {
        return $this->field($field) .
                ' BETWEEN ' . $this->dbhandler->quote($min, PDO::PARAM_INT) .
                ' AND ' . $this->dbhandler->quote($max, PDO::PARAM_INT);
    }
    
    /**
     * 
     * @param type $where
     * @param type $args
     * @return \Model
     */
    public function where($where = ' 1', $args = array())
    {
        $this->ar_where = $where;
        $this->ar_where_bind = $args;
        if ($this->cache_ar)
        {
            $this->cache_where = 1;
        }
        return $this;
    }
    
    /**
     * 
     * @param type $order_by 排序字段
     * @param type $type     规则   
     * @return \Model
     */
    public function order($order_by, $type = self::ORDER_ASC)
    {
        $this->ar_order = ' ORDER BY ';
        if (is_string($order_by))
        {
            $this->ar_order .= $order_by . ' ' . $type;
        }
        else
        {
            foreach ($order_by as $field => $type)
            {
                $this->ar_order .= $field . ' ' . $type . ',';
            }
            $this->ar_order = substr($this->ar_order, 0, -1);
        }
        if ($this->cache_ar)
        {
            $this->cache_order = 1;
        }
        return $this;
    }
    
    /**
     * 
     * @param type $group_by    分组字段
     * @return \Model
     */
    public function group($group_by)
    {
        $this->ar_group = ' GROUP BY ';
        if (is_string($group_by))
        {
            $this->ar_group .= $group_by;
        }
        else 
        {
            foreach ($group_by as $field)
            {
                $this->ar_group .= $field . ',';
            }
            $this->ar_group = substr($this->ar_group, 0, -1);
        }
        if ($this->cache_ar)
        {
            $this->cache_group = 1;
        }
        return $this;
    }

    /**
     * 
     * @param type $num     限制数
     * @param type $offset  便宜
     * @return \Model
     */
    public function limit($num, $offset=0)
    {
        $this->ar_limit = ' LIMIT '.$offset.','.$num;
        if($this->cache_ar)
        {
            $this->cache_limit = 1;
        }
        return $this;
    }
    
    /**
     * 
     * @param type $condition   条件
     * @return \Model
     */
    public function having($condition)
    {
        $this->ar_having = ' HAVING ' . $condition;
        if ($this->cache_ar)
        {
            $this->cache_having = 1;
        }
        return $this;
    }
    
    /**
     * 开始事务
     * @return type 返回真 代表开始了一个事务或者已经开始了一个事务
     */
    public function trans_start()
    {
        return $this->dbhandler->trans_start();
    }
    
    /**
     * 关闭/提交事务
     */
    public function trans_stop()
    {
        $this->dbhandler->trans_stop();
    }

    /**
     *
     */
    public function cache_start()
    {
        $this->cache_ar = 1;
    }
    
    /**
     * 关闭缓存
     */
    public function cache_stop() 
    {
        $this->cache_ar = 0;
    }
    
    /**
     * 清除缓存
     */
    public function cache_flush() 
    {
        $this->ar_group = '';
        $this->ar_having = '';
        $this->ar_join = '';
        $this->ar_limit = '';
        $this->ar_order = '';
        $this->ar_select = '*';
        $this->ar_where = ' 1';
        $this->ar_where_bind = array();
        $this->cache_group = 0;
        $this->cache_having = 0;
        $this->cache_join = 0;
        $this->cache_limit = 0;
        $this->cache_order = 0;
        $this->cache_select = 0;
        $this->cache_where = 0;
    }
    
    /**
     * 
     * @param type $table
     * @return boolean
     */
    public function get($table = null) 
    {
        if ($table === null)
        {
            $table = $this->table($this->table);
        }
        $stmt = $this->prepareGet($table);
        if ($stmt === false)
        {
            $this->clearAr();
            return false;
        }
        if ($this->queryStmt($stmt) === false)
        {
            $this->clearAr();
            return false;
        }
        $this->clearAr();
        return $this->procStmt($stmt);
    }

    /**
     * 获得表前缀
     * @return string
     */
    public function getTbprefix()
    {
        return $this->tbprefix;
    }

    /**
     * 设置表前缀
     * @param $tbprefix string
     */
    public function setTbprefix($tbprefix)
    {
        $this->tbprefix=$tbprefix;
    }

    /**
     * 获取第一行数据
     * @param type $table
     * @return boolean
     */
    public function getFirst($table = null) 
    {
        if ($table === null)
        {
            $table = $this->table($this->table);
        }
        $stmt = $this->prepareGet($table);
        if ($stmt === false) 
        {
            $this->clearAr();
            return false;
        }
        if ($this->queryStmt($stmt) === false) 
        {
            $this->clearAr();
            return false;
        }
        $this->clearAr();
        return $stmt->fetch(PDO::FETCH_COLUMN);
    }
    
    /**
     * 
     * @param type $table
     * @return boolean
     */
    public function getRow($table=null) 
    {
        if($table===null)
        {
            $table = $this->table ($this->table);
        }
        $stmt = $this->prepareGet($table);
        if($stmt===false) 
        {
            $this->clearAr();
            return false;
        }
        if($this->queryStmt($stmt)===false) 
        {
            $this->clearAr();
            return false;
        }
        $this->clearAr();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 返回查询的结果集
     * @param string null $table
     * @return Resource
     */
    public function getAll($table=null)
    {
        if($table===null)
        {
            $table = $this->table ($this->table);
        }
        $stmt = $this->prepareGet($table);
        if($stmt===false)
        {
            $this->clearAr();
            return false;
        }
        if($this->queryStmt($stmt)===false)
        {
            $this->clearAr();
            return false;
        }
        $this->clearAr();
        return $stmt->fetchAll();
    }

    /**
     * 查询结果集行数
     * @param type $table
     * @return boolean
     */
    public function count($table=null) 
    {
        if($table===null)
        {
            $table = $this->table ($this->table);
        }
        $this->ar_select = 'COUNT(*) AS num';
        $stmt = $this->prepareGet($table);
        if($stmt===false) 
        {
            $this->clearAr();
            return false;
        }
        if($this->queryStmt($stmt)===false) 
        {
            $this->clearAr();
            return false;
        }
        $this->clearAr();
        $result = $this->procStmt($stmt, PDO::FETCH_COLUMN);
        return empty($result) ? 0 : (int)$result[0];
    }

    /**
     * 查询是否有符合条件的记录
     * @param type $table
     * @return boolean
     */
    public function hasOne($table = null) 
    {
        if ($table === null)
        {
            $table = $this->table($this->table);
        }
        $this->ar_select = '1';
        $stmt = $this->prepareGet($table);
        if ($stmt === false) 
        {
            $this->clearAr();
            return false;
        }
        if ($this->queryStmt($stmt) === false) 
        {
            $this->clearAr();
            return false;
        }
        $this->clearAr();
        $result = $this->procStmt($stmt, PDO::FETCH_COLUMN);
        return empty($result) ? 0 : (int) $result[0];
    }
    
    /**
     * 
     * @param type $table
     * @param type $set
     * @return boolean
     */
    public function update($table, $set) 
    {
        $sql = 'UPDATE ' . $table . $this->ar_join . ' SET ';
        foreach ($set as $field => $val) 
        {
            if (substr($field, 0, 1) !== ':')
            {
                $sql .= $field . '=' . $val . ',';
            }
            else 
            {
                $tmp = substr($field, 1);
                $sql .= $this->field($tmp) . '=' . $field . ',';
            }
        }
        $sql = substr($sql, 0, -1);
        $sql .= ' WHERE ' . $this->ar_where . $this->ar_order . $this->ar_limit;
        $stmt = $this->dbhandler->prepare($sql);
        if ($stmt === false) 
        {
            $this->onError('Get stmt failed,sql maybe invalid:' . $sql);
            $this->clearAr();
            return false;
        }
        foreach ($set as $field => $val) 
        {
            if (substr($field, 0, 1) === ':')
            {
                $stmt->bindValue($field, $val);
            }
        }
        foreach ($this->ar_where_bind as $key => $val)
        {
            $stmt->bindValue($key, $val);
        }
        $this->clearAr();
        return $this->queryStmt($stmt);
    }
    
    /**
     * 
     * @param type $table
     * @return boolean
     */
    public function delete($table = null) 
    {
        if ($table === null)
        {
            $table = $this->table($this->table);
        }
        $stmt = $this->dbhandler->prepare('DELETE FROM ' . $table . ' WHERE ' . $this->ar_where . $this->ar_order . $this->ar_limit);
        if ($stmt === false) 
        {
            $this->onError('Get stmt failed,sql maybe invalid:' . 'DELETE FROM ' . $table . ' WHERE ' . $this->ar_where . $this->ar_order . $this->ar_limit);
            $this->clearAr();
            return false;
        }
        foreach ($this->ar_where_bind as $k => $v)
        {
            $stmt->bindValue($k, $v);
        }
        $this->clearAr();
        return $this->queryStmt($stmt);
    }

    /**
     * 
     * @param type $table  这里应该是表的全名
     * @param type $set  
     * @param type $multi
     * @param type $replace
     * @param type $dup 是否在sql末尾加入 on duplicate update语句，与$replace参数冲突
     * @return boolean
     */
    public function insert($table, $set, $multi = false, $replace = false, $dup = false)
    {
        $sql = $replace ? 'REPLACE INTO ' : 'INSERT INTO ';
        $sql .= $table . '(';
        $binds = array();
        $dupSql = ' ON DUPLICATE KEY UPDATE ';
        if ($multi)
        {
            foreach ($set as $item)
            {
                $keys = array_keys($item);
                break;
            }
            foreach ($keys as $v) 
            {
                $v = $this->field($v);
                $sql .= $v . ',';
                if ($dup)
                {
                    $dupSql .= $v . '=VALUES(' . $v . '),';
                }
            }
            $sql = substr($sql, 0, -1) . ') VALUES ';
            foreach ($set as $item) 
            {
                $sql .= '(';
                foreach ($keys as $v) 
                {
                    $binds[] = $item[$v];
                    $sql .= '?,';
                }
                $sql = substr($sql, 0, -1);
                $sql .= '),';
            }
            $sql = substr($sql, 0, -1);
        } 
        else 
        {
            $tmp = ') VALUES (';
            foreach ($set as $field => $val)
            {
                $field = $this->field($field);
                $sql .= $field . ',';
                $tmp .= '?,';
                $binds[] = $val;
                if ($dup)
                {
                    $dupSql .= $field . '=VALUES(' . $field . '),';
                }
            }
            $sql = substr($sql, 0, -1) . substr($tmp, 0, -1) . ')';
        }
        if ($dup)
        {
            $sql .= substr($dupSql, 0, -1);
        }
        $stmt = $this->dbhandler->prepare($sql);
        if ($stmt === false) 
        {
            $this->onError('Get stmt failed,sql maybe invalid:' . $sql);
            $this->clearAr();
            return false;
        }
        foreach ($binds as $k => $v)
        {
            $stmt->bindValue( ++$k, $v);
        }
        unset($sql, $tmp, $binds);
        $this->clearAr();
        return $this->queryStmt($stmt);
    }

    /**
     * 
     * @return type
     */
    public function last_id() 
    {
        return $this->dbhandler->lastInsertId();
    }
    
    /**
     * 
     * @param type $cons
     * @return string
     */
    public function buildWhere($cons = array()) 
    {
        $sql = ' 1 ';
        foreach ($cons as $con)
        {
            if (!isset($con[1]))
            {
                $con[] = ' AND ';
            }
            $sql .= $con[1] . $con[0];
        }
        return $sql;
    }

    public function Debug($flage=false)
    {
        $this->debug=$flage;
    }

    /**
     * 
     * @param type $msg
     */
    public function onError($msg)
    {
        if($this->debug)
        {
            echo __CLASS__.':'.$msg;
        }
        else
        {
            error_log(__CLASS__.':'.$msg);
        }
    }

    /**
     * 对表名进行预处理，返回PDOStatement对象 这里的表名是自定义的全表名
     * @param string $table
     * @return boolean
     */
    private function prepareGet($table)
    {
        $stmt = $this->dbhandler->prepare('SELECT ' . $this->ar_select . ' FROM ' . $table . $this->ar_join . ' WHERE ' . $this->ar_where . $this->ar_group . $this->ar_order . $this->ar_having . $this->ar_limit);
        if ($stmt === false)
        {
            $this->onError('Get stmt object failed, sql maybe invalid:' . 'SELECT ' . $this->ar_select . ' FROM ' . $table . $this->ar_join . ' WHERE ' . $this->ar_where . $this->ar_group . $this->ar_order . $this->ar_having . $this->ar_limit);
            return false;
        }
        foreach ($this->ar_where_bind as $k => $v)
        {
            $stmt->bindValue($k, $v);
        }
        return $stmt;
    }
    
    /**
     * 初始化所有查询内容
     */
    private function clearAr() 
    {
        if (!$this->cache_group)
        {
            $this->ar_group = ' ';
        }
        if (!$this->cache_having)
        {
            $this->ar_having = ' ';
        }
        if (!$this->cache_join)
        {
            $this->ar_join = ' ';
        }
        if (!$this->cache_limit)
        {
            $this->ar_limit = ' ';
        }
        if (!$this->cache_order)
        {
            $this->ar_order = ' ';
        }
        if (!$this->cache_select)
        {
            $this->ar_select = '*';
        }
        if (!$this->cache_where) 
        {
            $this->ar_where = ' 1';
            $this->ar_where_bind = array();
        }
    }
    
    /**
     * 执行一条单独的sql语句
     * @param type $sql
     * @return type
     */
    public function query($sql)
    {
        try 
        {
            $stmt = $this->dbhandler->query($sql);
            if ($stmt === false)
           {
                if ($this->dbhandler->trans_started)
                {
                    $this->dbhandler->trans_ok = false;
                }
                $info = $this->dbhandler->errorInfo();
                $this->onError('SQL:' . $sql . '[' . $info[1] . ']' . $info[2] . $this->end_line);
            }
            return $this->procStmt($stmt);
        } 
        catch (Exception $ex) 
        {
            if ($this->dbhandler->trans_started)
            {
                $this->dbhandler->trans_ok = false;
            }
            $info = $this->dbhandler->errorInfo();
            $this->onError('SQL:' . $sql . '[' . $info[1] . ']' . $info[2] . '[' . $ex->getCode() . ']' . $ex->getMessage() . $this->end_line);
        }
    }

    /**
     * 执行sql后的PDOStatement对象
     * @param PDOStatement $stmt
     * @param type $mode    PDO Mode
     * @param type $args    参数
     * @return type
     */
    protected function procStmt(&$stmt, $mode = PDO::FETCH_ASSOC, $args = array())
    {
        if (empty($args))
        {
            return $stmt->fetchAll($mode);
        }
        else
        {
            return $stmt->fetchAll($mode, $args);
        }
    }

    /**
     * 
     * @param PDOStatement $stmt
     * @return type
     */
    protected function queryStmt(&$stmt)
    {
        $bool = false;
        try 
        {
            $bool = $stmt->execute();
            if ($this->dbhandler->trans_started)
            {
                $this->dbhandler->trans_ok = $bool;
            }
            if (!$bool)
            {
                $info = $stmt->errorInfo();
                $this->onError($stmt->queryString . '[' . $info[1] . ']' . $info[2] . $this->end_line);
            }
        } 
        catch (Exception $ex)
        {
            if ($this->dbhandler->trans_started)
            {
                $this->dbhandler->trans_ok = $bool;
            }
            $info = $stmt->errorInfo();
            $this->onError($stmt->queryString . '[' . $info[1] . ']' . $info[2] . '[' . $ex->getCode() . ']' . $ex->getMessage() . $this->end_line);
        }
        return $bool;
    }

    /**
     * 获取表的名字
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * 设置表的名字 不包含前缀
     * @param string $name
     */
    public function setTable($name)
    {
        $this->table=$name;
    }
    /**
     * PDO对象
     * @var type 
     */
    public $dbhandler;
    
    /**
     *表前缀
     * @var type 
     */
    public $tbprefix;
    const JOIN_LFET = ' LEFT JOIN ';
    const JOIN_RIGHT = ' RIGHT JOIN ';
    const JOIN_INNER = ' INNER JOIN ';
    const JOIN = 'JOIN';
    const ORDER_DESC = ' DESC ';
    const ORDER_ASC = ' ASC ';

    /**
     * 表名
     * @var string
     */
    protected $table;
    
    /**
     * select 头
     * @var string
     */
    private $ar_select = '*';

    /**
     * where部分
     * @var string
     */
    private $ar_where = '1';

    /**
     * 绑定的元素集合
     * @var array
     */
    private $ar_where_bind = array();

    /**
     * join部分
     * @var string
     */
    private $ar_join = '';

    /**
     * order部分
     * @var string
     */
    private $ar_order = '';

    /**
     * limit部分
     * @var string
     */
    private $ar_limit = '';

    /**
     * group部分
     * @var string
     */
    private $ar_group = '';

    /**
     * having部分
     * @var string
     */
    private $ar_having = '';
    private $cache_select = 0;
    private $cache_where = 0;
    private $cache_join = 0;
    private $cache_order = 0;
    private $cache_limit = 0;
    private $cache_group = 0;
    private $cache_having = 0;
    private $cache_ar = 0;
    protected $end_line = "\r\n";

    /**
     * 是否处于调试模式
     * 调试模式打开，会直接在终端输出错误信息，否则记录php系统日志
     * @var bool
     */
    protected $debug=true;
}