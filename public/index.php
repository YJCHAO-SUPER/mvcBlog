<?php
// 使用 redis 保存 SESSION
ini_set('session.save_handler', 'redis');
// 设置 redis 服务器的地址、端
ini_set('session.save_path', 'tcp://127.0.0.1:6379?database=3');

session_start();

// 如果用户以 POST 方式访问网站时，需要验证令牌
    if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            // 接收原始数据
            $data = file_get_contents('php://input');
            // 转成数组
            $start = json_decode($data, TRUE);
            if($start!=null) {
                $_POST = $start;
            }
            if(!isset($_FILES['img'])){
                if(!isset($_POST['_token']))
                    die('违法操作！');

                if($_POST['_token'] != $_SESSION['token'])
                    die('违法操作！');
            }


        }

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

//    防止csrf攻击
    function csrf()
        {
//            session_destroy();
            if(!isset($_SESSION['token']))
            {
                // 生成一个随机的字符串
                $token = md5( rand(1,99999) . microtime() );
                $_SESSION['token'] = $token;
            }else {
                $token = $_SESSION['token'];
            }
            return $token;
        }

    //    防止XSS攻击
    function e($content){
        // 1. 生成配置对象
        $config = \HTMLPurifier_Config::createDefault();

        // 2. 配置
        // 设置编码
        $config->set('Core.Encoding', 'utf-8');
        $config->set('HTML.Doctype', 'HTML 4.01 Transitional');
        // 设置缓存目录
        $config->set('Cache.SerializerPath', ROOT.'\\cache');
        // 设置允许的 HTML 标签
        $config->set('HTML.Allowed', 'div,b,strong,i,em,a[href|title],ul,ol,ol[start],li,p[style],br,span[style],img[width|height|alt|src],*[style|class],pre,hr,code,h2,h3,h4,h5,h6,blockquote,del,table,thead,tbody,tr,th,td');
        // 设置允许的 CSS
        $config->set('CSS.AllowedProperties', 'font,font-size,font-weight,font-style,margin,width,height,font-family,text-decoration,padding-left,color,background-color,text-align');
        // 设置是否自动添加 P 标签
        $config->set('AutoFormat.AutoParagraph', TRUE);
        // 设置是否删除空标签
        $config->set('AutoFormat.RemoveEmpty', true);

        // 3. 过滤
        // 创建对象
        $purifier = new \HTMLPurifier($config);
        // 过滤
        $clean_html = $purifier->purify($content);

        return $clean_html;
    }


//    定义config
    function config($configName){
        $config = include ROOT."/config/config.php";
        return $config[$configName];
    }

    $controller = new $fullcontroller;
    $controller->$action();

