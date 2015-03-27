<?php

/**
 * Class valicode
 */
class valicode
{

    /**
     * 默认验证码源
     * @var string
     */
    private static $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

    /**
     * @var string 生成的验证码
     */
    private static $code;

    /**
     * 验证码的位数
     * @var int
     */
    private static $codelen = 5;

    /**
     * 生存验证码图片的宽度
     * @var int
     */
    private static $width = 160;

    /**
     * 生存验证码图片的高度
     * @var int
     */
    private static $height = 40;

    /**
     * 验证码图片资源
     * @var resource
     */
    private static $img;

    /**
     * 验证码的字体
     * @var string
     */
    private static $font='C:/Windows/Fonts/ELEPHNTI.TTF';

    /**
     * 验证码字体大小
     * @var int
     */
    private static $fontsize = 20;

    /**
     * 验证码的字体色
     * @var
     */
    private static $fontcolor;

    /**
     * 验证码源是否来自文件
     * @var bool
     */
    private static $isfile;

    /**
     * 文件验证码源
     * @var string 路径
     */
    private static $file;

    /**
     * 根据配置文件生存对应的或者默认的验证码
     */
    public static function genValiCode()
    {
        self::init();
        if(Application::getInstance()->getConfig()['system']['valicode']['dynamic'])
        {
            self::creategifbg();
            return ;
        }
        if(Application::getInstance()->getConfig()['system']['valicode']['math'])
        {
            self::createMath();
            return ;
        }
        self::createBg();
        self::createCode();
        self::createLine();
        self::createFont();
        self::outPut();
    }

    /**
     * 初始化过程
     */
    public static function init()
    {
        $file=  Application::getInstance()->getConfig()['system']['valicode']['filesource'];
        $width=Application::getInstance()->getConfig()['system']['valicode']['width'];
        $length=Application::getInstance()->getConfig()['system']['valicode']['length'];
        $height=Application::getInstance()->getConfig()['system']['valicode']['height'];
        $font=Application::getInstance()->getConfig()['system']['valicode']['font'];
        $fontsize=Application::getInstance()->getConfig()['system']['valicode']['fontsize'];
        if($width)
        {
            self::$width=$width;
        }
        if($height)
        {
            self::$height=$height;
        }
        if($fontsize)
        {
            self::$fontsize=$fontsize;
        }
        if($font)
        {
            self::$font=$font;
        }
        if($length)
        {
            self::$codelen=$length;
        }
        if($file)
        {
            self::readfile($file);
        }
    }

    /**
     * 生存数学计算的验证码
     */
    private function createMath()
    {
            //分析 没必要写了 抓取出来 在计算也是一样的
    }

    /**
     * 读取文件内容
     * @param $file 文件路径
     */
    private static function readfile($file)
    {
        if(file_exists($file))
        {
            if(($arr=stat($file)))
            {
                if($arr[7]>(2*1024*1024))
                {
                    Application::getInstance()->showlog("验证码文件内容超过2M,不科学");
                    return ;
                }
                else
                {
                    $filecontent=file_get_contents($file);
                    if(empty($filecontent))
                    {
                        Application::getInstance()->showlog("验证码文件内容为空或者文件打开失败");
                        return ;
                    }
                    if(($arr=explode(',',$filecontent)))
                    {
                        self::$charset=$arr;
                        return ;
                    }
                    if(($arr=explode('\n',$filecontent)))
                    {
                        self::$charset=$arr;
                        return ;
                    }
                    if(($arr=explode('\r\n',$filecontent)))
                    {
                        self::$charset=$arr;
                        return ;
                    }

                }
            }
            else
            {
                Application::getInstance()->showlog("获取验证码文件信息失败");
            }
        }
        else
        {
            Application::getInstance()->showlog("验证码文件不存在");
            return ;
        }
    }

    /**
     *不需要构造函数
    public function __construct()
    {
        self::$font = 'C:/Windows/Fonts/ELEPHNTI.TTF';
    }
    */

    /**
     *生存验证码的验证码
     */
    private static function createCode()
    {
        $_len = strlen(self::$charset) - 1;
        for ($i = 0; $i < self::$codelen; $i++) {
            self::$code .= self::$charset[mt_rand(0, $_len)];
        }
    }

    /**
     * 创建图片资源
     */
    private static function createBg()
    {
        self::$img = imagecreatetruecolor(self::$width, self::$height);
        $color = imagecolorallocate(self::$img, mt_rand(157, 255), mt_rand(157, 255), mt_rand(157, 255));
        imagefilledrectangle(self::$img, 0, self::$height, self::$width, 0, $color);
    }

    /**
     * 生成gif图片的验证码
     */
    private static function creategifbg()
    {
        self::createCode();
        $imagedata=array();
        for($i = 0; $i < 32; $i++)
        {
            ob_start();
            self::createBg();
            self::createLine();
            self::createFont();
            imagegif(self::$img);
            imagedestroy(self::$img);
            $imagedata[] = ob_get_contents();
            ob_clean();
            ++$i;
        }
        $gif = new GIFEncoder($imagedata);
        header('Content-type:image/gif');
        echo $gif->GetAnimation();
    }


    /**
     * 对验证码图片中的验证码应用字体
     */
    private static function createFont()
    {
        $_x = self::$width / self::$codelen;
        for ($i = 0; $i < self::$codelen; $i++) {
            self::$fontcolor = imagecolorallocate(self::$img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            imagettftext(self::$img, self::$fontsize, mt_rand(-30, 30), $_x * $i + mt_rand(1, 5), self::$height / 1.4, self::$fontcolor, self::$font, self::$code[$i]);
        }
    }

    /**
     * 对验证码图片添加干扰线
     */
    private static function createLine()
    {
        for ($i = 0; $i < 6; $i++) {
            $color = imagecolorallocate(self::$img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            imageline(self::$img, mt_rand(0, self::$width), mt_rand(0, self::$height), mt_rand(0, self::$width), mt_rand(0, self::$height), $color);
        }
        for ($i = 0; $i < 100; $i++) {
            $color = imagecolorallocate(self::$img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
            imagestring(self::$img, mt_rand(1, 5), mt_rand(0, self::$width), mt_rand(0, self::$height), '*', $color);
        }
    }

    /**
     * 输出普通的验证码
     */
    private static function outPut()
    {
        header('Content-type:image/png');
        imagepng(self::$img);
        imagedestroy(self::$img);
    }

    /**
     * 生成普通验证码的函数
     */
    public static function doimg()
    {
        self::createBg();
        self::createCode();
        self::createLine();
        self::createFont();
        self::outPut();
    }

    /**
     * 返回验证码图片验证码码
     * @return string
     */
    public static function getCode()
    {
        return strtolower(self::$code);
    }

}

?>