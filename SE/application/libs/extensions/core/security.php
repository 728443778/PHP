<?php
class Security
{
    /**
     * 
     * @param type $str 待加密字符串
     * @param type $ext 加密后缀
     */
    public static function common($str,$ext="md5")
    {
        return md5(md5($str.$ext));
    }

    /**
     * 安全过滤输入
     * @param type $string  过滤的字符串
     * @param type $isurl   是否是url
     * @return type
     */
    public static function check_str($string, $isurl = false) 
    {
        if(empty($string))
        {
            return ;
        }
        $string = preg_replace('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/', '', $string);
        $string = str_replace(array("\0", "%00", "\r"), '', $string);
        empty($isurl) && $string = preg_replace("/&(?!(#[0-9]+|[a-z]+);)/si", '&', $string);
        $string = str_replace(array("%3C", '<'), '<', $string);
        $string = str_replace(array("%3E", '>'), '>', $string);
        $string = str_replace(array('"', "'", "\t", ' '), array('“', '‘', ' ', ' '), $string);
        return trim($string);
    }

    /**
     * 安全过滤类-过滤javascript,css,iframes,object等不安全参数 过滤级别高
     * @param type $value 过滤的值
     * @return type
     */
    public static function fliter_script($value) 
    {
        $value = preg_replace("/(javascript:)?on(click|load|key|mouse|error|abort|move|unload|change|dblclick|move|reset|resize|submit)/i", "&111n\\2", $value);
        $value = preg_replace("/(.*?)<\/script>/si", "", $value);
        $value = preg_replace("/(.*?)<\/iframe>/si", "", $value);
        $value = preg_replace("//iesU", '', $value);
        return $value;
    }
    
    /**
     * 过滤HTML标签
     * @param type $value 过滤的内容
     * @return type
     */
    public static function fliter_html($value) 
    {
        if (function_exists('htmlspecialchars'))
        {
            return htmlspecialchars($value);
        }
        return str_replace(array("&", '"', "'", "<", ">"), array("&", "\"", "'", "<", ">"), $value);
    }

    /**
     * 过滤要进入数据库的内容 过滤级别高 
     * @param type $value
     * @return type
     */
    public static function fliter_sql($value) 
    {
        $sql = array("select", 'insert', "update", "delete", "\'", "\/\*",
            "\.\.\/", "\.\/", "union", "into", "load_file", "outfile");
        $sql_re = array("", "", "", "", "", "", "", "", "", "", "", "");
        return str_replace($sql, $sql_re, $value);
    }
    
    /**
     * 通用普通过滤函数
     * @param type $value   过滤的内容 
     * @return type
     */
    public static function fliter_escape($value)
    {
        if (is_array($value)) 
        {
            foreach ($value as $k => $v) 
            {
                $value[$k] = self::fliter_str($v);
            }
        } 
        else 
        {
            $value = self::fliter_str($value);
        }
        return $value;
    }

    /**
     * 字符串过滤
     * @param type $value   需要过滤的内容
     * @return type
     */
    public static function fliter_str($value) 
    {
        $badstr = array("\0", "%00", "\r", '&', ' ', '"', "'", "<", ">", "\t", "%3C", "%3E");
        $newstr=array('','','','&',' ','"',"'","<",">","\t","<",">");
        $value  = str_replace($badstr, $newstr, $value);
        $value  = preg_replace('/&((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $value);
        return $value;
    }

    /**
     * 
     * @param type $fileName
     * @return boolean
     */
    public static function filter_dir($fileName)
    {
        $tmpname = strtolower($fileName);
        $temp = array(':/', "\0", "..");
        if (str_replace($temp, '', $tmpname) !== $tmpname) 
        {
            return false;
        }
        return $fileName;
    }

    /**
     * 过滤目录
     * @param type $path
     * @return type
     */
    public static function filter_path($path) 
    {
        $path = str_replace(array("'", '#', '=', '`', '$', '%', '&', ';'), '', $path);
        return rtrim(preg_replace('/(\/){2,}|(\\){1,}/', '/', $path), '/');
    }
    
    /**
     * 
     * @param type $string
     * @return type
     */
    public static function filter_phptag($string) 
    {
        return str_replace(array(''), array('<?', '?>'), $string);
    }
    
    /**
     * 返回函数
     * @param type $value
     * @return type
     */
    public static function str_out($value) 
    {
        $badstr = array("<", ">", "%3C", "%3E");
        $newstr = array("<", ">", "<", ">");
        $value = str_replace($newstr, $badstr, $value);
        return stripslashes($value);
    }

    /**
     *
     *
     */
    public static function intval($str)
    {
        return intval($str);
    }

}