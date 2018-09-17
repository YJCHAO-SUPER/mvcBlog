<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $blogContent['title']?></title>
    <script src="https://cdn.bootcss.com/vue/2.5.17-beta.0/vue.min.js"></script>
    <script src="https://cdn.bootcss.com/axios/0.18.0/axios.min.js"></script>
    <style>
        .wan{
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <?php view('Common.header')?>
    <div id="app">
        <h2><?php echo $blogContent['title']?></h2>
        <span>写于:<?php echo $blogContent['created_at']?></span>
        <span>浏览量：{{ readnum }}</span>
        <p><?php echo e($blogContent['content'])?></p>
        <br>
        <input type="submit" value="点赞" @click="submitLike">
        <hr>
        <?php foreach($getAvatarArray as $v){ ?>
        <img class="wan" src="<?php echo $v['avatar']; ?>" alt="">
        <?php } ?>
    </div>
    <script>
            const app = new Vue ({
                el:'#app',
                data(){
                    return{
                        'readnum':0
                    }
                },
                created:function(){
                    axios({
                        method:'get',
                        url:'/blog/getReadNum?id='+<?php echo $blogContent['id']?>
                    }).then((res)=>{
                        this.readnum = res.data
                    })
                },
                methods: {
                    submitLike() {
                        axios({
                            method:'get',
                            url:'/blog/submitLike?id='+<?php echo $blogContent['id']?>
                        }).then((res)=>{
                            if(res.data.statu==true){
                                alert("点赞成功！");
                            }else {
                                alert("你已经点赞过了！");
                            }
                        })
                    }
                }
            })
    </script>
    <?php view('Common.footer')?>
</body>
</html>