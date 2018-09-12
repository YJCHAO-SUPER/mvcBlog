<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $blogContent['title']?></title>
    <script src="https://cdn.bootcss.com/vue/2.5.17-beta.0/vue.min.js"></script>
    <script src="https://cdn.bootcss.com/axios/0.18.0/axios.min.js"></script>
</head>
<body>
    <?php view('Common.header')?>
    <div id="app">
        <h2><?php echo $blogContent['title']?></h2>
        <span>写于:<?php echo $blogContent['created_at']?></span>
        <span>浏览量：{{ readnum }}</span>
        <p><?php echo $blogContent['content']?></p>
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
                }
            })
    </script>
    <?php view('Common.footer')?>
</body>
</html>