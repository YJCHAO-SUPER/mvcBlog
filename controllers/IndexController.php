<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2018/9/3
 * Time: 14:58
 */
namespace controllers;

use models\Blogs;

class IndexController
{
    function index(){
//        echo "helloWorld";

        $title = "屁桃";
        $content = "连梓豪居头";
        $display = 1;
        $read_num = 10;

//        ********分页********************************************
        $blog = new Blogs();
        $total = $blog->totalArticle()['count(*)'];
        $page =isset($_GET['page'])?$_GET['page']:1;


//        随机生成文章
//        for ($i=0;$i<100;$i++){
//            $title = $this->getChar(rand(5,8));
//            $content = $this->getChar(rand(100,200));
//            $display = rand(0,1);
//            $read_num = rand(0,100);
//            $blog->addArticle($title,$content,$display,$read_num);
//        }

//        *********搜索***************************************************
        $keyword = isset($_GET['keyword'])?$_GET['keyword']:null;

        $result = $blog->getAllArticle($page,$keyword);
//        echo "<pre>";
//        var_dump($result);
        $pageData = array(
            'currentPage'=>$page,
            'totalPage'=>$result['num']
        );

        view("User.index",[
            'allArticle'=>$result['allArticle'],
            'pageData'=>$pageData
        ]);

//        include ROOT."\\public\\index.html";
    }

//    随机生成汉字
    private function getChar($num)  // $num为生成汉字的数量
    {
        $b = '';
        for ($i=0; $i<$num; $i++) {
            // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
            $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
            // 转码
            $b .= iconv('GB2312', 'UTF-8', $a);
        }
        return $b;
    }
}