<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2018/9/10
 * Time: 16:57
 */
namespace models;

use PDO;

class ActivationCodes extends Base
{

//    添加激活码
    function addCode($code,$email){
        try {
            $stmt = self::$pdo->prepare("insert into activation_codes(activation,email) VALUES(?,?)");
            $stmt->execute([$code,$email]);
        }catch (\PDOException $e) {
            var_dump($e->getMessage());
        }
    }

//    查询激活码
    function getActive($key){

        $stmt = self::$pdo->prepare("select * from activation_codes where activation=?");
        $stmt->execute([$key]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;

    }

//    删除激活码
    function deleteAction($email){
        var_dump($email);
        $stmt = self::$pdo->prepare("delete from activation_codes where email=?");
        $stmt->execute([$email]);

    }


}