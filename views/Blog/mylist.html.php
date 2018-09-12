<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>我的日志列表</title>
</head>
<body>
    <?php view('Common.header'); ?>

        <h2>日志列表</h2>
        <ul>
            <?php foreach($selfBlog as $v){ ?>
            <li>
                <a href="/blog/showContent?id=<?php echo $v['id']?>"><?php echo $v['title'];?></a>&nbsp;&nbsp;&nbsp;
                <?php echo $v['created_at'];?>&nbsp;&nbsp;&nbsp;
                <a href="/write/showUpdate?id=<?php echo $v['id']?>">修改</a>&nbsp;&nbsp;&nbsp;
                <a href="/write/deleteBlog?id=<?php echo $v['id']?>">删除</a>
            </li>
            <?php } ?>
        </ul>


    <?php view('Common.footer'); ?>
</body>
</html>