<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2018/9/4
 * Time: 8:24
 */
namespace models;

use PDO;

class Base
{
    public static $pdo = null;
    function __construct()
    {
//      先判断$pdo是否为空，如果是空的就连接数据库，这样就可以只连接一次数据库
        if(self::$pdo === null){
            //        连接数据库
            self::$pdo = new PDO("mysql:host=localhost;dbname=mvcblog","root","");
            self::$pdo->exec("SET NAMES utf8");
        }

    }
}