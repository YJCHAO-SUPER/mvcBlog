<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2018/9/3
 * Time: 16:41
 */
namespace models;

use PDO;

class Blogs extends Base
{

//  获取所有文章分页，接收分页和搜索的参数
    function getAllArticle($page,$keyword=null){

//        ****分页******************************************
        $pageSize = 20;
        $offset = $page*$pageSize-$pageSize;
        $limit = " limit $offset,$pageSize";

//        ****搜索**********************************************
        $word = "  where title like '%{$keyword}%' or content like '%{$keyword}%' ";
        $word = $keyword?$word:'';


        $stmt = self::$pdo->prepare("select * from blogs ".$word. " order by id desc ".$limit );
        $stmt->execute();
        $allArticle = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = self::$pdo->prepare("select count(*) from blogs".$word);
        $stmt->execute();
        $num = ceil($stmt->fetch(PDO::FETCH_COLUMN)/$pageSize);
        $result = array(
           'allArticle'=>$allArticle,
           'num'=>$num
        );
        return $result;
    }

//    根据id获取一篇文章
    function getBlogById($id){

//        预处理 查询一条sql语句
        $stmt = self::$pdo->prepare("select * from blogs where id=?");
//        执行
        $stmt->execute([$id]);
//        以关联数组的形式返回
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

//    添加文章
    function addArticle($id,$title,$content,$display,$read_num){
        $sql = "insert into blogs(user_id,title,content,display,read_num) values(?,?,?,?,?)";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([$id,$title,$content,$display,$read_num]);
        return self::$pdo->lastInsertId();
    }

//  文章总数
    function totalArticle(){
        $stmt = self::$pdo->prepare("select count(*) from blogs");
        $stmt->execute();
        $total= $stmt->fetch(PDO::FETCH_ASSOC);
        return $total;
    }

//    获取所有的文章
    function getAllContent(){

        $stmt = self::$pdo->prepare("select * from blogs");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;

    }

//    获取浏览量
    function getRead($id){

        $stmt = self::$pdo->prepare("select read_num from blogs where id=?");
        $stmt->execute([$id]);
        $num = $stmt->fetch(PDO::FETCH_COLUMN);
        return $num;
    }

//    把redis的浏览量保存到数据库
    function saveRead($id,$read_num){

        $stmt = self::$pdo->prepare("update blogs set read_num=? where id=?");
        $stmt->execute([$read_num,$id]);

    }

//    根据id获取自己的所有文章
    function getBlogsById($id){

        $stmt = self::$pdo->prepare("select * from blogs where user_id=? ");
        $stmt->execute([$id]);
        $myBlog = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $myBlog;

    }

//    根据文章id查用户id
    function getUserId($id){

        $stmt = self::$pdo->prepare("select user_id from blogs where id=?");
        $stmt->execute([$id]);
        $userId = $stmt->fetch(PDO::FETCH_COLUMN);
        return $userId;

    }

//    修改文章
    function updateBlog($title,$content,$display,$id){
        $sql = "update blogs set title=?,content=?,display=? where id=?";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([$title,$content,$display,$id]);

    }

//    删除文章
    function deleteArticle($id){

        $stmt = self::$pdo->prepare("delete from blogs where id=?");
        $stmt->execute([$id]);

    }


}