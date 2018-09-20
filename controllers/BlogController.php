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
use models\Users;

class BlogController extends BaseController
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
//        var_dump($blogContent);

        $getAvatarArray = $blog->getLikeUserByArticleId($id);
//        var_dump($result);


        view('Content.content',[
            'blogContent' => $blogContent,
            'getAvatarArray' => $getAvatarArray
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
            $redis = new Redis();
            $redis->writeRead();
            sleep(60);

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

//    点赞
    function submitLike(){

        if(isset($_SESSION['id'])){

            $articleId = $_GET['id'];
            $userId = $_SESSION['id'];
            $blogs = new Blogs();
            // 一：去数据库找点赞表，如果已经点赞过，则返回已经点赞过啦
            //如何判断已经点赞过了？
            $result = $blogs->findLike($userId,$articleId);
            if($result){
                $data = array(
                  'code'=>401,
                  'statu'=>false,
                  'msg'=>'你已经点赞过啦！'
                );
                $this->response($data);
            }else{
                //二：如果没有点赞过，则插入数据库
                $blogs->like($userId,$articleId);
                $data = array(
                    'code' => 200,
                    'statu' => true,
                    'msg' => '点赞成功！'
                );
                $this->response($data);
            }

        }else{
            header("location:/user/login");
        }

    }


}