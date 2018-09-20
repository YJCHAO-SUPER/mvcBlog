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

//      取出旧头像路径
    function getOldAvatar($userId){

        $stmt = self::$pdo->prepare("select avatar from users where id=?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_COLUMN);
        return $result;

    }

//    更新新的头像
    function updateNewAvatar($newPath,$userId){

        $stmt = self::$pdo->prepare("update users set avatar=? where id=?");
        $stmt->execute([$newPath,$userId]);

    }

//    计算活跃用户
    function  computeActiveUsers(){

//        取日志的分值
        $stmt = self::$pdo->query("SELECT user_id,COUNT(*)*5 fz FROM blogs WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK) GROUP BY user_id");
        $data1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        var_dump($data1);
//        die();

//        取评论的分值
        $stmt = self::$pdo->query("SELECT user_id,COUNT(*)*3 fz FROM comments WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK) GROUP BY user_id");
        $data2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

//        取点赞的数量
        $stmt = self::$pdo->query("SELECT user_id,COUNT(*) fz FROM praises WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK) GROUP BY user_id");
        $data3 = $stmt->fetchAll(PDO::FETCH_ASSOC);

//        合并数组 建一个空数组
        $arr = [];

//        合并第一个数组到空数组里
        foreach ($data1 as $v){
            $arr[$v['user_id']] = $v['fz'];
        }

//        把第二个数组的数据合并到第一个数组
        foreach ($data2 as $k => $v){
            if(isset($data1[$k])){
                $data1[$k] += $v;
            }else{
                $data1[$k] = $v;
            }
        }

//        把第三个数组的数据合并到第一个数组
        foreach ($data3 as $k => $v){
            if(isset($data1[$k])){
                $data1[$k] += $v;
            }else{
                $data1[$k] = $v;
            }
        }

//        倒序排序
        arsort($arr);

//        取出前20个键 第四个参数保留键
        $data = array_slice($arr,0,20,TRUE);
//        var_dump($data);

//        从数组中取出键
        $userIds = array_keys($data);
//        var_dump($userIds);
//        数组转字符串
        $userIds = implode(',',$userIds);

//        取出用户的头像和邮件
         $sql = "select id,email,avatar from users where id in($userIds)";
         $stmt = self::$pdo->query($sql);
         $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

//         把计算结果保存到Redis中
        $redis = \libs\Redis::getInstance();
//        因为redis里面只能保存字符串 所以把字符串转成数组
        $redis->set('active_user',json_encode($data));

    }

//    取出活跃用户
    function getActiveUsers(){
        $redis = \libs\Redis::getInstance();
        $user = $redis->get('active_user');
        return json_decode($user,true);
    }


}