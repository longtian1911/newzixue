<?php
/**
 *Image.class.php
 *Create on 2020/1/23 7:22 下午
 *Create by lxllsy520
 */
class Image{
    //要保存的路径
    protected $path;
    //是否启用随机名字
    protected $isRandName;
    //要保存的图像类型
    protected $type;
    //通过构造方法对成员属性进行初始化
    public function __construct($path = './', $isRandName = true, $type = 'png')
    {
        $this->path = $path;
        $this->isRandName = $isRandName;
        $this->type = $type;
    }

    //对外公开的水印方法
    public function water($image, $water, $postion, $tmd = 100, $prefix = 'water_'){

    }
    //对外公开的缩放方法
    public function suofang(){

    }
}





















