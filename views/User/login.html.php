<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登录</title>
</head>
<body>
    <?php view('Common.header'); ?>
    <h2>登录</h2>

    <form action="/user/login" method="post">
        <?php echo @$error;?>
        <input type="hidden" name="_token" value="<?=csrf()?>">
        <div>
            邮箱：<input type="text" name="email">
        </div>
        
        <div>
            密码：<input type="password" name="password">
        </div>
        
        <div>
            <input type="submit" value="登录">
        </div>
        
    </form>
    <?php view('Common.footer'); ?>
</body>
</html>