<?php
/**
 *Image.class.php
 *Create on 2020/1/23 7:22 下午
 *Create by lxllsy520
 */

$image = new Image();
//$image->water('1.png', '2.png', 0,40);
$image->suofang('1.png', 100,100);
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

    /**
     * @param $image  原图片
     * @param $water  水印图片
     * @param $postion  水印图片的位置
     * @param int $tmd  水印图片的透明度
     * @param string $prefix  图片前缀
     */
    public function water($image, $water, $postion, $tmd = 100, $prefix = 'water_'){
        //判断这两个图片是否存在
        if(!file_exists($image) || !file_exists($water)){
            die('图片资源不存在');
        }
        //得到原图片的宽度和高度以及水印图片的宽度和高度
        $imageInfo = self::getImageInfo($image);
        $waterInfo = self::getImageInfo($water);
        //判断水印图片能否贴上来
        if (!$this->checkImage($imageInfo, $waterInfo)){
            exit('水印图片太大');
        }
        //打开图片
        $imageRes = self::openAnyImage($image);
        $waterRes = self::openAnyImage($water);
        //根据水印图片的位置计算水印图片的坐标
        $pos = $this->getPosition($postion, $imageInfo, $waterInfo);
        //将水印图片贴过来
        imagecopymerge($imageRes, $waterRes, $pos['x'], $pos['y'], 0 , 0, $waterInfo['width'], $waterInfo['height'], $tmd);
        //得到要保存图片的文件名
        $newName = $this->createNewName($image, $prefix);
        //得到保存图片的路径,也就是文件的全路径
        $newPath = rtrim($this->path, '/') . '/' . $newName;
        //保存图片
        $this->saveImage($imageRes, $newPath);
        //销毁资源
        imagedestroy($imageRes);
        imagedestroy($waterRes);
        return $newPath;
    }
    //对外公开的缩放方法

    /**
     * @param $image 需要缩放的图片
     * @param $width 缩放后的宽度
     * @param $height 缩放后的高度
     * @param string $prefix 缩放后图片的前缀
     */
    public function suofang($image, $width, $height, $prefix = 'sf_'){
        //得到图片原来的宽度和高度
        $info = self::getImageInfo($image);
        //根据图片原来的宽度和最重要缩放宽度计算得到图片不变形的宽高
        $sizee = $this->getNewSize($width, $height, $info);
        //打开图片资源
        $imageRes = self::openAnyImage($image);
        //进行缩放
        $newRes = $this->kidOfImage($imageRes, $sizee, $info);
        //保存图片
        $newName = $this->createNewName($image, $prefix);
        $newPath = rtrim($this->path, '/') . '/' . $newName;
        $this->saveImage($newRes, $newPath);
        //销毁图形资源
        imagedestroy($imageRes);
        imagedestroy($newRes);
    }

    //静态方法，根据图片的路径得到图片的信息，宽度、高度、mime类型
    static public function getImageInfo($imagePath){
        //getimagesize()获取图片的信息，返回值为一个数组 具体查询手册
        $info = getimagesize($imagePath);
        //保存图形宽度
        $data['width'] = $info[0];
        $data['height'] = $info[1];
        $data['mime'] = $info['mime'];
        return $data;
    }

    //判断水印图片是否大于原图片
    protected function checkImage($imageInfo, $waterInfo){
        if (($waterInfo['width'] > $imageInfo['width']) || ($waterInfo['height'] > $imageInfo['height'])){
            return false;
        }
        return true;
    }

    //根据图片类型打开任意图片
    static public function openAnyImage($imagePath){
        //得到图像的mime类型
        $mime = self::getImageInfo($imagePath)['mime'];
        //根据不同的mime类型使用不同的函数进行打开图形
        switch ($mime){
            case 'image/png':
                $image = imagecreatefrompng($imagePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($imagePath);
                break;
            case 'image/jpeg':
                $image = imagecreatefromjpeg($imagePath);
                break;
            case 'image/wbmp':
                $image = imagecreatefromwbmp($imagePath);
                break;
        }
        return $image;
    }

    //根据位置计算水印图片的坐标
    protected function getPosition($position, $imageInfo, $waterInfo){
        switch ($position){
            case 1:
                $x = 0;
                $y = 0;
                break;
            case 2:
                $x = ($imageInfo['width'] - $waterInfo['width']) / 2;
                $y = 0;
                break;
            case 3:
                $x = $imageInfo['width'] - $waterInfo['width'];
                $y = 0;
                break;
            case 4:
                $x = 0;
                $y = ($imageInfo['height'] - $waterInfo['height']) / 2;
                break;
            case 5:
                $x = ($imageInfo['width'] - $waterInfo['width']) / 2;
                $y = ($imageInfo['height'] - $waterInfo['height']) / 2;
                break;
            case 6:
                $x = $imageInfo['width'] - $waterInfo['width'];
                $y = ($imageInfo['height'] - $waterInfo['height']) / 2;
                break;
            case 7:
                $x = 0;
                $y = $imageInfo['height'] - $waterInfo['height'];
                break;
            case 8:
                $x = ($imageInfo['width'] - $waterInfo['width']) / 2;
                $y = $imageInfo['height'] - $waterInfo['height'];
                break;
            case 9:
                $x = $imageInfo['width'] - $waterInfo['width'];
                $y = $imageInfo['height'] - $waterInfo['height'];
                break;
            case 0:
                $x = mt_rand(0, $imageInfo['width'] - $waterInfo['width']);
                $y = mt_rand(0, $imageInfo['height'] - $waterInfo['height']);
                break;
        }
        return ['x' => $x, 'y' => $y];
    }

    //得到文件名函数
    protected function createNewName($imagePath, $prefix){
        if ($this->isRandName){
            $name = $prefix . uniqid() . '.' . $this->type;
        }else{
            $name = $prefix . pathinfo($imagePath)['filename'] . '.' . $this->type;
        }
        return $name;
    }

    //保存图片资源函数
    protected function saveImage($imageRes, $newPath){
        //imgepng 还是使用imagegif来保存
        $func = 'image' . $this->type;
        //通过边路函数进行保存
        $func($imageRes, $newPath);
    }

    //缩放函数处理透明色函数
    protected function kidOfImage($srcImg, $size, $imgInfo){
        //传入新的尺寸，创建一个指定尺寸的图片
        $newImg = imagecreatetruecolor($size['old_w'], $size['old_h']);
        //定义透明色
        $otsc = imagecolortransparent($srcImg);
        if ($otsc >= 0){
            //取得透明色
            $transparentcolor = imagecolorsforindex($srcImg, $otsc);
            //创建透明色
            $newtransparentcolor = imagecolorallocate($newImg, $transparentcolor['red'], $transparentcolor['green'],$transparentcolor['blue']);
        }else{
            //将黑色作为透明色，因为创建图形后在第一次分配颜色是背景默认为黑色
            $newtransparentcolor = imagecolorallocate($newImg, 0, 0, 0);
        }
        //背景填充透明
        imagefill($newImg, 0, 0, $newtransparentcolor);
        imagecolortransparent($newImg, $newtransparentcolor);
        imagecopyresampled($newImg, $srcImg, $size['x'], $size['y'], 0, 0, $size['new_w'], $size['new_h'], $imgInfo['width'], $imgInfo['height']);
        return $newImg;
    }

    //缩放函数计算宽度和高度
    /**
     * @param $width 最终缩放的宽度
     * @param $heigh 最终缩放的高度
     * @param $imgInfo 原始图片的宽度和高度
     */
    protected function getNewSize($width, $heigh, $imgInfo){
        $size['old_w'] = $width;
        $size['old_h'] = $heigh;
        $scaleWidth = $width / $imgInfo['width'];
        $scaleHeight = $heigh / $imgInfo['height'];
        $scaleFinal = min($scaleWidth, $scaleHeight);
        $size['new_w'] = round($imgInfo['width']*$scaleFinal);
        $size['new_h'] = round($imgInfo['height'] * $scaleFinal);
        if ($scaleWidth < $scaleHeight){
            $size['x'] = 0;
            $size['y'] = round(abs($size['new_h'] - $heigh) / 2);
        }else{
            $size['y'] = 0;
            $size['x'] = round(abs($size['new_w'] - $width) / 2);
        }
        return $size;
    }
}





















