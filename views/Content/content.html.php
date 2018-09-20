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
        .head1{
            border: 1px solid red;
            padding: 10px;
            margin: 10px;
        }
        .en{
            color: red;
        }
        .co{
            color: deepskyblue;
        }
        .comm{
            border: 1px solid deepskyblue;
            padding: 10px;
            margin: 10px;
            height: 72px;
            overflow: hidden;
        }
        .left{
            float: left;
            height: 100%;
            width: 25%;
        }
        .left img{
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
        .left span{
            display: block;
        }
        .right{
            float: right;
            width: 75%;
        }
        .time1{
            color: #8c8c8c;
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
        <span class="en">点赞头像</span>
        <div class="head1">
            <?php foreach($getAvatarArray as $v){ ?>
            <img class="wan" src="http://localhost:9999\\<?php echo $v['avatar']; ?>" alt="">
            <?php } ?>
        </div>
        <hr>
        <span class="co">评论</span>

        <div class="comm" v-for="(v,k) in show">
            <div class="left">
                <img :src="v.img" alt="">
                <span>{{ v.email }}</span>
            </div>
            <div class="right">
                <div class="content">{{ v.content }}</div>
                <div class="time1">{{ v.time1 }}</div>
            </div>
        </div>

        <br>
        <div class="write">
            <textarea v-model="content" name="content"cols="70" rows="10"></textarea><br>
            <input type="submit" value="发表评论" @click="sendComment">
        </div>
    </div>
    <script>
        function getNowFormatDate() {
            var date = new Date();
            var seperator1 = "-";
            var seperator2 = ":";
            var month = date.getMonth() + 1;
            var strDate = date.getDate();
            if (month >= 1 && month <= 9) {
                month = "0" + month;
            }
            if (strDate >= 0 && strDate <= 9) {
                strDate = "0" + strDate;
            }
            var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate
                + " " + date.getHours() + seperator2 + date.getMinutes()
                + seperator2 + date.getSeconds();
            return currentdate;
        }
            const app = new Vue ({
                el:'#app',
                data(){
                    return{
                        'readnum':0,
                        'content':'',
                        'show': []
                    }
                },
                created:function(){
                    axios({
                        method:'get',
                        url:'/blog/getReadNum?id='+<?php echo $blogContent['id']?>
                    }).then((res)=>{
                        this.readnum = res.data
                    }),

                    axios({
                        method:'get',
                        url:'/comment/getComments?id='+<?php echo $blogContent['id']?>
                    }).then((res)=>{
                        if(res.data.statu === true){
                            for(let i =0 ;i< res.data.data.length; i++ ) {
                                this.show.push({
                                    'img': res.data.data[i].avatar,
                                    'email': res.data.data[i].email,
                                    'content': res.data.data[i].content,
                                    'time1': res.data.data[i].created_at

                                })
                            }

                        }
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
                    },
                    sendComment(){
                        let data = {
                            content: this.content,
                            articleId: <?php echo $_GET['id']?>,
                            _token: '<?=csrf()?>'
                        };
                        axios({
                            method:'post',
                            url:'/comment/add',
                            data:data
                        }).then((res)=>{
                            this.show.unshift({
                                content: this.content,
                                img: '<?php echo $_SESSION['avatar']; ?>',
                                email:  '<?php echo $_SESSION['email']; ?>',
                                time1:  getNowFormatDate()
                            }),
                            this.content = ''
                        })
                    }
                }
            })
    </script>
    <?php view('Common.footer')?>
</body>
</html>