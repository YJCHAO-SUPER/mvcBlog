<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2018/9/11
 * Time: 16:52
 */
namespace controllers;

use models\Blogs;

class WriteController
{

//    显示写日志的页面
    function create(){
        if(isset($_SESSION['id'])){
            view("Blog.write",[]);
        }else{
            header("location:/user/login");
        }
    }

//    提交日志
    function write(){

        $id = $_SESSION['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $display = $_POST['display'];

        $blogs = new Blogs();
        $lastInsertId = $blogs->addArticle($id,$title,$content,$display,0);

        header("location:/blog/showContent?id=$lastInsertId");

    }

//    显示修改日志
    function  showUpdate(){
        $id = $_GET['id'];
        $blogs = new Blogs();
        $userId = $blogs->getUserId($id);
        if($userId == $_SESSION['id']){
            $nowBlog = $blogs->getBlogById($id);
            view('Blog.update',[
               'nowBlog'=>$nowBlog
            ]);
        }else{
            echo "你只可以修改自己的日志！";
        }

    }

//    修改日志
    function updateBlog(){
        $blogId = $_POST['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $display = $_POST['display'];

        $blogs = new Blogs();
        $blogs->updateBlog($title,$content,$display,$blogId);

        header("location:/blog/showContent?id=$blogId");

    }

//    删除日志
    function deleteBlog(){
        $id = $_GET['id'];
        $blogs = new Blogs();
        $userId = $blogs->getUserId($id);
        if($userId == $_SESSION['id']){
            $blogs->deleteArticle($id);
            header("location:/blog/MyBlogList");
        }
    }

}