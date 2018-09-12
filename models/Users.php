<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2018/9/7
 * Time: 20:02
 */
namespace models;

use PDO;

class Users extends Base
{
//      添加用户
    function add($email,$password){

        $stmt = self::$pdo->prepare("insert into users (email,password) values(?,?)");
        $stmt->execute([$email,$password]);

    }

//    修改用户激活码
    function modify($email){
        $stmt = self::$pdo->prepare("update users set is_active=1 WHERE email=?");
        $stmt->execute([$email]);
    }

//    查登录的账号密码
    function getUser($email){

        $stmt = self::$pdo->prepare("select * from users where email=?");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result){
            return $result;
        }else{
            return false;
        }


    }

}