<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>头像</title>
</head>
<body>
<?php view('Common.header')?>

    <h2>上传头像</h2>
    <form action="/user/setavatar" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="<?=csrf()?>">
        <div>
            头像：<input type="file" name="avatar" id="img">
        </div>
        <br>
        <div>
            <input type="submit" value="提交">
        </div>
    </form>


<?php view('Common.footer')?>
</body>
</html>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script>
let img  = document.getElementById('img');
img.onchange = function(){

    let img1 = this.files[0];
    let Size = img1.size;
    let perSize = 10240;
    let count = Math.ceil(Size/perSize);
    let name = "img_"+Math.random(1,99999);
    for(let i=0;i<count;i++){
        let img0 = img1.slice(i*perSize,i*perSize+perSize);
        let f = new FormData();
        f.append('img',img0);
        f.append('i',i);
        f.append('count',count);
        f.append('name',name);
        f.append('perSize',perSize);
        $.ajax({

            type : "POST",
            url : "/user/uploadBig",
            data : f,
            contentType: false,
            processData: false,
            success:function(data){

            }

        });
    }


}

</script>


