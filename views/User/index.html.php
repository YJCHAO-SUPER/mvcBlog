<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>屁桃</title>
</head>
<body>
    <?php view('Common.header')?>
    <h2 style="text-align: center;">首页</h2>

    <form action="" method="get" style="text-align: center">
        查询：<input type="text" name="keyword" value="<?php echo @$_GET['keyword']; ?>">
        <input type="hidden" name="page" value="<?php echo isset($_GET['page'])?$_GET['page']:1;?>">
        <input type="submit" value="搜索">
    </form>

    <br>
    <br>
    
    <table border="1" style="width: 80%;font-size: 20px;text-align: center;margin: 0 auto;">
        <th>ID</th>
        <th>标题</th>
        <th>显示</th>
        <th>阅读数</th>
        <th>加入时间</th>
        <th>修改时间</th>

        <?php foreach($allArticle as $v){ ?>
        <tr>
            <td><?php echo $v['id']?></td>
            <td><a href="/blog/showContent?id=<?php echo $v['id']?>"><?php echo $v['title']?></a> </td>
            <td><?php echo $v['display']?></td>
            <td><?php echo $v['read_num']?></td>
            <td><?php echo $v['created_at']?></td>
            <td><?php echo $v['updated_at']?></td>
        </tr>
        <?php } ?>
    </table>

    <?php if($pageData['currentPage']!=1){ ?>
        <a href="?keyword=<?php echo $_GET['keyword'];?>&page=1">首页</a>
    <?php } ?>
    <?php if($pageData['currentPage']== 1){  ?>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=1" style="color: orangered"><?php echo 1; ?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=2"><?php echo 2; ?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=3"  ><?php echo 3; ?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=4"><?php echo 4; ?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=5"><?php echo 5; ?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['totalPage'];?>">尾页</a>
     <?php   } ?>
    <?php if($pageData['currentPage']== 2){  ?>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=2" style="color: orangered"><?php echo 2; ?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=3"><?php echo 3; ?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=4"><?php echo 4;?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=5"><?php echo 5; ?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=6"><?php echo 6; ?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['totalPage'];?>">尾页</a>
    <?php   } ?>
    <?php if($pageData['currentPage']> 2 && $pageData['currentPage']< $pageData['totalPage']-2 ){  ?>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['currentPage']-2; ?>"><?php echo $pageData['currentPage']-2; ?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['currentPage']-1; ?>"><?php echo $pageData['currentPage']-1; ?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['currentPage']; ?>" style="color: orangered"><?php echo $pageData['currentPage'];   ?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['currentPage']+1; ?>"><?php echo $pageData['currentPage']+1; ?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['currentPage']+2; ?>"><?php echo $pageData['currentPage']+2; ?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['totalPage'];?>">尾页</a>
    <?php   } ?>
    <?php if($pageData['currentPage']== $pageData['totalPage']-1){  ?>
        <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['totalPage']-5;?>"><?php echo $pageData['totalPage']-5;?></a>
        <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['totalPage']-4;?>"><?php echo $pageData['totalPage']-4;?></a>
        <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['totalPage']-3;?>"><?php echo $pageData['totalPage']-3;?></a>
        <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['totalPage']-2;?>"><?php echo $pageData['totalPage']-2;?></a>
        <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['totalPage']-1;?>" style="color: orangered"><?php echo $pageData['totalPage']-1;?></a>
        <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['totalPage'];?>">尾页</a>
    <?php   } ?>
    <?php if($pageData['currentPage']== $pageData['totalPage']-2){  ?>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['totalPage']-6;?>"><?php echo $pageData['totalPage']-6;?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['totalPage']-5;?>"><?php echo $pageData['totalPage']-5;?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['totalPage']-4;?>"><?php echo $pageData['totalPage']-4;?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['totalPage']-3;?>"><?php echo $pageData['totalPage']-3;?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['totalPage']-2;?>" style="color: orangered"><?php echo $pageData['totalPage']-2;?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['totalPage'];?>">尾页</a>
    <?php   } ?>
    <?php if($pageData['currentPage']!=1&&$pageData['currentPage']== $pageData['totalPage']){  ?>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['totalPage']-4;?>"><?php echo $pageData['totalPage']-4;?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['totalPage']-3;?>"><?php echo $pageData['totalPage']-3;?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['totalPage']-2;?>"><?php echo $pageData['totalPage']-2;?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['totalPage']-1;?>"><?php echo $pageData['totalPage']-1;?></a>
            <a href="?keyword=<?php echo $_GET['keyword'];?>&page=<?php echo $pageData['totalPage'];?>" style="color: orangered"><?php echo $pageData['totalPage'];?></a>
    <?php   } ?>


    <?php view('Common.footer')?>
</body>
</html>