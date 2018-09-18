<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2018/9/18
 * Time: 14:52
 */
namespace controllers;

use models\Comments;

class CommentController extends BaseController
{
//  添加评论
    function add(){

        if(isset($_SESSION['id'])){

            // 接收原始数据
            $data = file_get_contents('php://input');
            // 转成数组
            $_POST = json_decode($data, TRUE);

            $articleId = $_POST['articleId'];
            $content = $_POST['content'];
            $userId = $_SESSION['id'];

            $comments = new Comments();
            $comments->addComment($articleId,$content,$userId);

        }else{
            header("location:/user/login");
        }

    }

//    获取评论
    function getComments(){

        $id = $_GET['id'];
        $comments = new Comments();
        $getComments = $comments->getCommentsById($id);
        $data = array(
          'code'  => 200,
          'statu' => true,
          'data' => $getComments
        );
        $this->response($data);


    }


}