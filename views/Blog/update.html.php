<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.bootcss.com/quill/1.3.6/quill.snow.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/quill/1.3.6/quill.min.js"></script>
</head>
<body>
    <?php view("Common.header")?>

    <h2>修改日志</h2>
    <br>
    <form action="/write/updateBlog" method="post">
        <input type="hidden" name="id" value="<?php echo $nowBlog['id'];?>">
    标题：<input type="text" name="title" value="<?php echo $nowBlog['title'] ?>" ><br>

    内容：<br>
                <div id="editor">
                    <?php echo $nowBlog['content'] ?>
                </div>
                <input type="hidden" id="hiddenContent" name="content">
              <br>

    是否公开：<input type="radio" name="display" value="1 " <?php echo $nowBlog['display']==1?'checked':'' ?>  >是
                    <input type="radio" name="display" value="0"  <?php echo $nowBlog['display']==0?'checked':'' ?>>否<br>

    <button>提交</button>

    </form>
    <?php view("Common.footer")?>
    <script>

        function quillGetHTML(inputDelta) {
            let tempCont = document.createElement("div");
            (new Quill(tempCont)).setContents(inputDelta);
            return tempCont.getElementsByClassName("ql-editor")[0].innerHTML;
        }

        var quill = new Quill('#editor', {
            theme: 'snow'
        });
        let element = document.querySelector("#hiddenContent")
        let content = quillGetHTML(quill.getContents());
        element.value =  content
        quill.on('editor-change', () =>{
            let content = quillGetHTML(quill.getContents());
            element.value =  content
        })
    </script>
</body>
</html>