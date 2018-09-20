<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>头像</title>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="/cropper/cropper.min.js"></script>
    <link rel="stylesheet" href="/cropper/cropper.min.css">
    <style>
        .img-container {
            width: 500px;
            height: 500px;
            float:left;
        }
        .img-preview {
            float: left;
            overflow: hidden;
            margin-left: 20px;
        }
        .preview-lg {
            width: 240px;
            height: 240px;
        }
        .preview-md {
            width: 80px;
            height: 80px;
        }
        .select {
            width: 100%;
            float: left;
        }
    </style>
</head>
<body>
<?php view('Common.header')?>

    <h2>上传头像</h2>
    <form action="/user/setavatar" method="post" enctype="multipart/form-data">

        <input type="hidden" name="_token" value="<?=csrf()?>">

        <!-- 显示原图 -->
        <div class="img-container">
            <img id="image" src="" alt="Picture">
        </div>
        <!-- 预览图片 -->
        <div class="docs-preview">
            <div class="img-preview preview-lg"></div>
            <div class="img-preview preview-md"></div>
        </div>

        <div class="select">
            <div>
                选择头像：
                <input id="img" type="file" name="avatar">
            </div>
            <div>
                <input type="submit" value="上传">
            </div>
            <!-- 保存裁切时的区域信息 -->
            <input type="hidden" name="x" id="x">
            <input type="hidden" name="y" id="y">
            <input type="hidden" name="w" id="w">
            <input type="hidden" name="h" id="h">
        </div>

    </form>


<?php view('Common.footer')?>
</body>
</html>
<script>
    // 选中图片
    var $image = $('#image')
    console.log($image);

    var x = $("#x")
    var y = $("#y")
    var w = $("#w")
    var h = $("#h")

    // 当选择图片时触发函数
    $("#img").change(function(){

        /* 预览图片 */
        // this.files ： 当前选中的图片数组
        // 把选中的图片转成字符串（图片的临时地址，在浏览器中可以直接访问并显示图片）
        var url = getObjectUrl( this.files[0] )
        // 把图片的地址设置到 img 标签的 src 属性上
        $image.attr('src', url)

        // 先消毁原插件
        $image.cropper("destroy")

        /* 启动 cropper 插件 */
        $image.cropper({
            aspectRatio: 1,                              // 缩略图1:1的比例
            preview:'.img-preview',                      // 显示缩略图的框
            viewMode:3,                                  // 显示模式
            // 裁切时触发事件
            crop: function(event) {
                x.val(event.detail.x);             // 裁切区域左上角x坐标
                y.val(event.detail.y);             // 裁切区域左上角y坐标
                w.val(event.detail.width);         // 裁切区域宽高
                h.val(event.detail.height);        // 裁切区域高度
            }
        })

    });

    // 预览时需要使用下面这个函数转换一下(为了兼容不同的浏览器，所以要判断支持哪一种函数就使用哪一种)
    function getObjectUrl(file) {
        var url = null;
        if (window.createObjectURL != undefined) {
            url = window.createObjectURL(file)
        } else if (window.URL != undefined) {
            url = window.URL.createObjectURL(file)
        } else if (window.webkitURL != undefined) {
            url = window.webkitURL.createObjectURL(file)
        }
        return url
    }

// let img  = document.getElementById('img');
// img.onchange = function(){
//
//     let img1 = this.files[0];
//     let Size = img1.size;
//     let perSize = 10240;
//     let count = Math.ceil(Size/perSize);
//     let name = "img_"+Math.random(1,99999);
//     for(let i=0;i<count;i++){
//         let img0 = img1.slice(i*perSize,i*perSize+perSize);
//         let f = new FormData();
//         f.append('img',img0);
//         f.append('i',i);
//         f.append('count',count);
//         f.append('name',name);
//         f.append('perSize',perSize);
//         $.ajax({
//
//             type : "POST",
//             url : "/user/uploadBig",
//             data : f,
//             contentType: false,
//             processData: false,
//             success:function(data){
//
//             }
//
//         });
//     }


// }

</script>


