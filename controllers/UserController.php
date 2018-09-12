<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2018/9/7
 * Time: 20:01
 */
namespace controllers;

use libs\Mail;
use models\ActivationCodes;
use models\Redis;
use models\Users;

class UserController
{
//    显示页面
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


}