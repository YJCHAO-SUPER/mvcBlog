<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2018/9/6
 * Time: 14:38
 */
namespace controllers;

use models\Blogs;
use models\Redis;

class BlogController
{
//    首页生成静态页面
    function getBlogHtml(){
        $blog = new Blogs();
        $total = $blog->totalArticle()['count(*)'];
        $page =isset($_GET['page'])?$_GET['page']:1;

//        *********搜索***************************************************
        $keyword = isset($_GET['keyword'])?$_GET['keyword']:null;

        $result = $blog->getAllArticle($page,$keyword);

        $pageData = array(
            'currentPage'=>$page,
            'totalPage'=>$result['num']
        );

//        开启缓冲区
        ob_start();

        view("User.index",[
            'allArticle'=>$result['allArticle'],
            'pageData'=>$pageData
        ]);

//        取出缓冲区内容
        $str = ob_get_contents();

//        生成静态页
        file_put_contents(ROOT.'\\public\\index.html',$str);

        ob_clean();


    }

//    根据id获取一篇文章
    function showContent(){

        $id = $_GET['id'];

        $blog = new Blogs();
        $blogContent = $blog->getBlogById($id);
//        var_dump($content);

        view('Content.content',[
            'blogContent' => $blogContent
        ]);

//        include ROOT.'\\public\\content\\'.$id.'.html';

    }

//    文章内容静态页面
    function contentHtml(){

        $blog = new Blogs();
        $allContent = $blog->getAllContent();

        foreach ($allContent as $v){
            ob_start();

            view('Content.content',[
                'blogContent' => $v
            ]);

            $str = ob_get_contents();

            file_put_contents(ROOT.'\\public\\content\\'.$v['id'].'.html',$str);
//            var_dump(ROOT.'\\public\\content\\'.$v['id'].'.html');

            ob_clean();

        }

    }

//    获取浏览量
    function getReadNum(){
        $id = $_GET['id'];
        $redis = new Redis();
        $result = $redis->getReadNum($id);
        $result++;
        $redis->setReadNum($id,$result);
        echo $result;
    }

//    把所有redis里的阅读量存到数据库
    function getRedisRead(){
        while (true){
            sleep(60);
            $redis = new Redis();
            $redis->writeRead();
        }
    }

//    日志列表页，显示自己的文章列表
    function MyBlogList(){

        if(isset($_SESSION['id'])){

            $id = $_SESSION['id'];
            $blogs = new Blogs();
            $selfBlog = $blogs->getBlogsById($id);

            view('Blog.mylist',[
                'selfBlog'=>$selfBlog
            ]);

        }else{
            header("location:/user/login");
        }

    }


}