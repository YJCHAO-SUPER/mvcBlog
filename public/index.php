<?php
// 使用 redis 保存 SESSION
ini_set('session.save_handler', 'redis');
// 设置 redis 服务器的地址、端
ini_set('session.save_path', 'tcp://127.0.0.1:6379?database=3');

session_start();


//  定义根目录
    define('ROOT',dirname(__DIR__));

//    引入扩展文件
    require(ROOT.'/vendor/autoload.php');

    if(isset($_SERVER['REQUEST_URI'])) {
        // 获取路径
        $URI = $_SERVER['REQUEST_URI'];

//  把字符串转换为数组
        $getURI = explode('/',$URI);

//  获取控制器名和方法名
        $ConName = $getURI[1] ? ucfirst($getURI[1]) : "Index";
        $actionName = isset($getURI[2]) ? $getURI[2] : "index";

    }else if(php_sapi_name() == 'cli') {
        $ConName = $argv[1] ? ucfirst($argv[1]) : "Index";
        $actionName = isset($argv[2]) ? $argv[2] : "index";
    }

//    把方法名和参数分开
    $num = stripos($actionName,'?');
    $action = $num!=0?substr($actionName,0,$num):$actionName;
//    var_dump($action);

//  自动引入
    function autoload($className){
        include ROOT.'\\'.$className.".php";
    }
    spl_autoload_register('autoload');

//    new完整控制器
    $fullcontroller = "controllers\\".$ConName."Controller";
//    echo $fullcontroller;


//  定义view方法
    function view($viewName,$data=[]){
//        echo $viewName;
        extract($data);
        $viewName = str_replace('.','\\',$viewName);
        include ROOT.'\\views\\'.$viewName.'.html.php';
    }

//    定义config
    function config($configName){
        $config = include ROOT."/config/config.php";
        return $config[$configName];
    }

    $controller = new $fullcontroller;
    $controller->$action();

