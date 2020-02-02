<?php
/**
 *单列设计模式.php
 *Create on 2020/2/2 12:24 下午
 *Create by lxllsy520
 */
class Dog{
    private function __construct()
    {
    }
    //静态属性保存单列对象
    static private $instance;
    //通过金泰方法来创建单列对象
    static public function getnstance(){
        //判断$instance是否为空，如果为空则new一个对象，如果不为空，则直接返回
        if (!self::$instance){
            self::$instance = new self();
        }
        return self::$instance;
    }
}