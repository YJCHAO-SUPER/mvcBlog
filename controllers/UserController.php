<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2018/9/7
 * Time: 20:01
 */
namespace controllers;

use libs\Mail;
use libs\Uploader;
use models\ActivationCodes;
use models\Redis;
use models\Users;

class UserController
{
//    显示注册页面
    function regist(){

        view('User.register',[]);

    }

//    接收注册表单，把值传给model
    function register() {

        $email = $_POST['email'];
        $password = $_POST['password'];

        $users = new Users();
        $code = uniqid();
        $activation = new ActivationCodes();
        $activation->addCode($code,$email);
        $users->add($email,$password);


        $arr = explode('@',$email);
        $name  = $arr[0];
        $title= "欢迎注册mvcBlog哈哈";
        $content = "欢迎注册mvcblog,请访问http://localhost:8888/user/active?key=$code";

//       $mail  = new Mail();
//       $mail->send($title,$content,[$email,$name]);

        $mailJson = array(
            'title'=>$title,
            'content'=>$content,
            'emailArr'=>[$email,$name]
        );

        $mailJson = json_encode($mailJson);

        $redis = new Redis();
        $redis->pushMail($mailJson);

        echo 'ok';
        header("location:/user/login");
    }

//    从消息队列中取出数据  发送email数据到发邮件的model里
    function sendMail() {
        // 设置 socket 永不超时
        ini_set('default_socket_timeout', -1);
        $redis = new \Predis\Client([
            'scheme' => 'tcp',
            'host'   => '127.0.0.1',
            'port'   => 6379,
        ]);
        while(true)
        {

            $mailString = $redis->brpop('email',0);
            $arr = $mailString[1];
            $mailArr = json_decode($arr,true);

            $mail = new Mail();
            $mail->send($mailArr['title'],$mailArr['content'],[$mailArr['emailArr'][0],$mailArr['emailArr'][1]]);

        }
    }

//    验证激活码
    function active(){

        $key = $_GET['key'];

        $activation = new ActivationCodes();
        $result = $activation->getActive($key);
        if($result) {
            $email = $result['email'];
            $users = new Users();
            $users->modify($email);
            $activation->deleteAction($email);
        }else{
            echo "激活失败！";
        }



    }

//    接收登录表单 ，显示登录页面
    function login(){

        if(isset($_POST['email']) && isset($_POST['password'])){

            $email = $_POST['email'];
            $password = $_POST['password'];
            if ($email!='' && $password!=''){

                $users = new Users();
                $my = $users->getUser($email);

                if ($my){
                    if($my['email']!=$email && $my['password']!=$password){

                        $error = "账号或者密码输入错误";
                        view("User.login",['error'=>$error]);

                    }else{

                        $_SESSION['id'] = $my['id'];
                        $_SESSION['email'] = $my['email'];
                        $_SESSION['avatar'] = $my['avatar'];
                        header("location: /index/index");

                    }

                }else {
                    $error = "没有该账号";
                    view("User.login",['error'=>$error]);
                }


            }else{
                $error = "失败";
                view("User.login",['error'=>$error]);
            }

        }else{
            view("User.login",[]);
        }


    }

//    退出
    function logout(){
        unset($_SESSION['email']);
        unset($_SESSION['id']);
        header("location: /user/login");
    }

//    显示头像页面
    function avatar(){
        if(isset($_SESSION['id'])){
            view("User.avatar",[]);
        }else{
            header("location:/user/login");
        }
    }

//    上传头像
    function setAvatar(){
////        设置图片目录
//         $uploadDir = ROOT."\\public\\uploads";
////         当天的日期
//         $date =  date("Ymd");
////          截取图片后缀
//         $ext= strrchr($_FILES['avatar']['name'],'.');
////         判断是不是目录  如果没有这个目录就创建
//         if(!is_dir($uploadDir.'\\'.$date)){
//             mkdir($uploadDir.'\\'.$date,0777);
//         }
////        生成唯一的图片名
//        $name = md5(time().rand(1,9999));
////         完整的文件名
//        $fullName = $uploadDir.'\\'.$date.'\\'.$name.$ext;
////        将图片从服务器的临时文件移动到指定目录
//        move_uploaded_file($_FILES['avatar']['tmp_name'],$fullName);

//        调用类 获取图片路径
        $file = $_FILES['avatar'];
        $uploader = Uploader::make();
        $newPath = $uploader->upload($file,'big_img');

//        取出数据库旧图片路径 删除
        $userId = $_SESSION['id'];
        $users = new Users();
        $old = $users->getOldAvatar($userId);
        unlink(ROOT."\\public\\".$old);

//        把新的头像更新数据库
        $users->updateNewAvatar($newPath,$userId);

    }

//     显示上传多张图片页面
    function  upload(){
        if(isset($_SESSION['id'])){
            view("User.AllAvatar",[]);
        }else{
            header("location:/user/login");
        }
    }

//    上传多张图片
    function uploadAll(){

//        设置图片目录
        $uploadDir = ROOT."\\public\\uploads";
//         当天的日期
        $date =  date("Ymd");
//         判断是不是目录  如果没有这个目录就创建
        if(!is_dir($uploadDir.'\\'.$date)){
            mkdir($uploadDir.'\\'.$date,0777);
        }
//        循环五张图片
//        $_FILES['avatar']['name'] as $k => $v  $k取出name值的下标
        foreach($_FILES['avatar']['name'] as $k => $v){
//              生成唯一的图片名
            $name = md5(time().rand(1,9999));
//              截取图片后缀
            $ext= strrchr($v,'.');
//              移动图片
//            $_FILES['avatar']['tmp_name'][$k]  临时文件的下标
            move_uploaded_file($_FILES['avatar']['tmp_name'][$k],$uploadDir.'\\'.$date.'\\'.$name.$ext);

        }


    }

//    上传片合成图
    function uploadBig(){

        $img = $_FILES['img'];
        $i = $_POST['i'];
        $count = $_POST['count'];
        $perSize = $_POST['perSize'];
        $name = "big_".$_POST['name'];

        move_uploaded_file($img['tmp_name'],ROOT.'\\tmp\\'.$i);
        $redis = \libs\Redis::getInstance();
        $all = $redis->incr($name);
        if($all == $count){
            $handle = fopen(ROOT.'\\public\\uploads\\big\\'.$name.'.png','a');
            for ($i=0;$i<$count;$i++){
                fwrite($handle,file_get_contents(ROOT.'\\tmp\\'.$i));
                unlink(ROOT.'\\tmp\\'.$i);
            }
            fclose($handle);
            $redis->del($name);
        }

    }


}