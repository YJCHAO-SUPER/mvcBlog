<style>
    .head{
        color: darkorange;
        margin-right: 20px;
    }
    a{
        text-decoration: none;
    }
    .er{
        text-align: center;
        font-size: 20px;
    }
    h1{
        color: brown;
    }
    .avatar{
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }
</style>

<h1>智聊系统</h1>
<div class="er">

    <div>

        <a class="head" href="/index/index">首页</a>


        <?php  if(isset($_SESSION['id'])) { ?>

            <a class="head" href="/blog/MyBlogList">日志列表页</a>
            <a class="head" href="/write/create">写日志</a>
            <a class="head"  href="/user/avatar">上传头像</a>
            <a class="head" href="/excel/makeExcel">导出Excel</a>
            <img class="avatar" src="http://localhost:9999/<?php echo $_SESSION['avatar']; ?>" alt="">
            <?php echo isset($_SESSION['email'])?$_SESSION['email']:'' ?>
            <a class="head" href="/user/logout">退出</a>

        <?php }else{ ?>

            <a class="head" href="/user/regist">注册</a>
            <a class="head" href="/user/login">登录</a>

        <?php } ?>


    </div>
    <hr>

</div>