---
title: PHP实现滑动验证码 
date: 2018.8.1 19:00:00
id: verification-by-slide-picture
category: php
---

参考源码[https://github.com/binwind8/tncode](https://github.com/binwind8/tncode)

演示地址[http://aso.39gs.com/tncode/index.html](http://aso.39gs.com/tncode/index.html)

参考上述源码，实现了通过接口访问，验证滑动验证码功能。

## 原理：
后台生成滑动验证码，记录小图偏移量。然后前台用户滑动图片，通过js传递偏移量到后台，如果与后台记录偏移量相同或者在误差允许范围内，则代码验证通过。

偏移量可以记录X轴，也可以记录Y轴
验证码X轴 = 浏览器在电脑屏上的X轴  +  验证码在浏览器中的X轴
验证码Y轴 = 浏览器在电脑屏上的Y轴  +  验证码在浏览器中的Y轴

## Demo

本示例通过记录X轴变量实现。
### Api接口一
获取图片
```
    /**
     * 获取图片校验码接口
     */
    public function getVerificationImage(){
        D('VerificationCode')->make();
    }
```

### Api接口二
```
   /**
     * 图片校验码校验接口
     */
    public function verificationImage(){
        //偏移量
        $offset = I('offset', 0, 'trim,intval');

        $check = D('VerificationCode')->verification($offset);
        if($check){
            success_json();
        }else{
            error_json('验证错误');
        }
    }

```

### Model核心文件

Model文件`VerificationCode.class.php`
```
<?php
/**
 * Class TnCode
 * tncode 1.2 author:weiyingbin email:277612909@qq.com
 * @ object webiste: http://www.39gs.com/archive/259.html
 * @ https://github.com/binwind8/tncode
 */

namespace Common\Model;

class VerificationCodeModel extends \Think\Model
{
    protected $autoCheckFields = false;
    protected $tableName = "tw_job";

    var $im = null;
    var $im_fullbg = null;
    var $im_bg = null;
    var $im_slide = null;
    var $bg_width = 240;
    var $bg_height = 150;
    var $mark_width = 50;
    var $mark_height = 50;
    var $bg_num = 6;
    var $_x = 0;
    var $_y = 0;
    //容错象素 越大体验越好，越小破解难道越高
    var $_fault = 3;

    /**
     * @var 验证码缓存键
     */
    private $cacheCode = 'verify_code_key';
    /**
     * 错误次数
     * @var int
     */
    private $cacheCodeErr = 0;

    function __construct(){
        error_reporting(0);
   }

    public function make(){
        $this->_init();
        $this->_createSlide();
        $this->_createBg();
        $this->_merge();
        $this->_imgout();
        $this->_destroy();
    }

    public function verification($offset=''){
        $right = $this->cacheInfo($this->cacheCode);
        if(!$right || !$offset){
            return false;
        }

        $ret = abs($right - $offset) <= $this->_fault;
        if(!$ret){
            //错误次数
            $errorNum = $this->cacheInfo($this->cacheCodeErr);
            $this->cacheInfo($this->cacheCodeErr, $errorNum + 1);
            //错误10次必须刷新
            if($errorNum > 9){
                $this->cacheInfo($this->cacheCode, null);
            }
            return false;
        }
        return true;
    }

    private function cacheInfo($key, $val = null, $expire = 180){
	    //可以使用多种方式缓存，session，数据库，redis等
        return D('Cache')->cache($key,$val,$expire);
    }

    private function _init(){
        $bg = mt_rand(1,$this->bg_num);
        $file_bg = './Public/images/verification/bg/'.$bg.'.png';
        $this->im_fullbg = imagecreatefrompng($file_bg);
        $this->im_bg = imagecreatetruecolor($this->bg_width, $this->bg_height);
        imagecopy($this->im_bg,$this->im_fullbg,0,0,0,0,$this->bg_width, $this->bg_height);
        $this->im_slide = imagecreatetruecolor($this->mark_width, $this->bg_height);
        $value = $this->_x = mt_rand(50,$this->bg_width-$this->mark_width-1);
        $this->_y = mt_rand(0,$this->bg_height-$this->mark_height-1);

        $this->cacheInfo($this->cacheCode, $value);
        $this->cacheInfo($this->cacheCodeErr, 0);
    }

    private function _imgout(){
        if(function_exists('imagewebp')){//优先webp格式，超高压缩率
            $type = 'webp';
            $quality = 40;//图片质量 0-100
        }else{
            $type = 'png';
            $quality = 7;//图片质量 0-9
        }
        header('Content-Type: image/'.$type);
        $func = "image".$type;
        $func($this->im,null,$quality);
    }

    private function _merge(){
        $this->im = imagecreatetruecolor($this->bg_width, $this->bg_height*3);
        imagecopy($this->im, $this->im_bg,0, 0 , 0, 0, $this->bg_width, $this->bg_height);
        imagecopy($this->im, $this->im_slide,0, $this->bg_height , 0, 0, $this->mark_width, $this->bg_height);
        imagecopy($this->im, $this->im_fullbg,0, $this->bg_height*2 , 0, 0, $this->bg_width, $this->bg_height);
        imagecolortransparent($this->im,0);//16777215
    }

    private function _createBg(){
        $file_mark = './Public/images/verification/img/mark.png';
        $im = imagecreatefrompng($file_mark);
        header('Content-Type: image/png');
        //imagealphablending( $im, true);
        imagecolortransparent($im,0);//16777215
        //imagepng($im);exit;
        imagecopy($this->im_bg, $im, $this->_x, $this->_y  , 0  , 0 , $this->mark_width, $this->mark_height);
        imagedestroy($im);
    }

    private function _createSlide(){
        $file_mark = './Public/images/verification/img/mark2.png';
        $img_mark = imagecreatefrompng($file_mark);
        imagecopy($this->im_slide, $this->im_fullbg,0, $this->_y , $this->_x, $this->_y, $this->mark_width, $this->mark_height);
        imagecopy($this->im_slide, $img_mark,0, $this->_y , 0, 0, $this->mark_width, $this->mark_height);
        imagecolortransparent($this->im_slide,0);//16777215
        //header('Content-Type: image/png');
        //imagepng($this->im_slide);exit;
        imagedestroy($img_mark);
    }

    private function _destroy(){
        imagedestroy($this->im);
        imagedestroy($this->im_fullbg);
        imagedestroy($this->im_bg);
        imagedestroy($this->im_slide);
    }
}

```

## 优化
后台可以针对用户产生的行为轨迹数据进行机器学习建模，结合访问频率、地理位置、历史记录等多个维度信息。这样能够增加破解难度。

也可以使用专用Api服务商，如[网易云验证码](https://www.163yun.com/product/captcha?tag=M_zhihu_32027538_jd)
