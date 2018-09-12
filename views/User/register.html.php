<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>注册</title>
</head>
<body>
<?php view('Common.header'); ?>
    <h2>注册</h2>

    <form action="/user/register" method="post">
        
        <div>
            邮箱：<input type="text" name="email">
        </div>
        
        <div>
            密码：<input type="password" name="password">
        </div>
        
        <div>
            <input type="submit" value="注册">
        </div>
        
    </form>
    <?php view('Common.footer'); ?>
</body>
</html>