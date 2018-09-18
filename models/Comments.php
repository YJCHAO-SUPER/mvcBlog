<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2018/9/18
 * Time: 14:53
 */
namespace models;

use PDO;

class Comments extends Base
{
//  添加评论
    function addComment($articleId,$content,$userId){

        $stmt = self::$pdo->prepare("insert into comments(blog_id,user_id,content) VALUES (?,?,?)");
        $stmt->execute([$articleId,$userId,$content]);

    }

//    获取评论
    function getCommentsById($blogId){

        $stmt = self::$pdo->prepare("select a.*,b.email,b.avatar from comments a LEFT JOIN users b on a.user_id = b.id where blog_id=? order by created_at desc");
        $stmt->execute([$blogId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;

    }


}