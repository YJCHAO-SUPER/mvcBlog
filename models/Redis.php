<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2018/9/6
 * Time: 17:16
 */
namespace models;

class Redis
{
    public $client = null;

    function __construct()
    {
        $this->client = new \Predis\Client([
            'scheme' => 'tcp',
            'host'   => '127.0.0.1',
            'port'   => 6379,
        ]);
    }

//    把浏览量加入redis里
    function  setReadNum($id,$num){
        $this->client->set("blog-$id",$num);
    }

//    根据id把浏览量从redis里取出来
    function getReadNum($id){
        $result = $this->client->exists("blog-$id");
        if($result){
            $num = $this->client->get("blog-$id");
        }else{
            $blog = new Blogs();
            $num = $blog->getRead($id);
            $this->setReadNum($id,$num);
        }

        return $num;

    }

//    从redis里把浏览量取出来
    function writeRead(){

        $arr =  $this->client->keys("blog-*");
        for($i=0;$i<count($arr);$i++){
            $key = $arr[$i];
            $num = $this->client->get($key);
            $id = substr($key,5);

            $blogs = new Blogs();
            $blogs->saveRead($id,$num);
        }
    }

//    把发邮件的数据放到redis里面
    function pushMail($mailJson) {
        $this->client->lpush('email',$mailJson);
    }


}