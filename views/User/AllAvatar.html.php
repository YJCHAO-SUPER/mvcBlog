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
    <form action="/user/uploadAll" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="<?=csrf()?>">
        <div>
            头像：<input type="file" name="avatar[]">
                      <input type="file" name="avatar[]">
                      <input type="file" name="avatar[]">
                      <input type="file" name="avatar[]">
                      <input type="file" name="avatar[]">
        </div>
        <br>
        <div>
            <input type="submit" value="提交所有">
        </div>
    </form>


<?php view('Common.footer')?>
</body>
</html>