<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2018/9/18
 * Time: 18:26
 */
namespace libs;

class Uploader
{

    private function __construct() {}

    private function __clone() {}

    private static $instance = null;

    public static function make(){

        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;

    }

//    文件名允许的后缀
    private $allowSub = ['image/jpeg','image/jpg','image/ejpeg','image/png','image/gif','image/bmp'];

    public function upload($file,$subDir){

//        一级目录
        $Dir = ROOT."\\public\\uploads\\";
//        生成随机的文件名
        $name = md5(time().rand(1,9999));
//        二级目录
        $sub= $Dir.$subDir;
        if(!is_dir($sub)) {
            mkdir($sub,0777);
        }
//        文件名后缀
        if(!in_array($file['type'],$this->allowSub)){
            return false;
        }
        $ext = strrchr($_FILES['avatar']['name'],'.');
//        完整的文件目录
        $full = $sub.'\\'.$name.$ext;
//        移动文件
        move_uploaded_file($file['tmp_name'],$full);
        $filepath = "uploads\\".$subDir."\\".$name.$ext;
        return $filepath;
    }

}