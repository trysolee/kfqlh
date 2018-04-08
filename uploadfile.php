<?php

#
#  新建 帖子
#  
#  1 . 图片 , 可有可无
#  
#  2 . 没有图片 就没有 '经纬度'
#  
#  
#  

include_once "ret.php";
include_once "getdb.php";


if (empty($_POST['latitude'])
    || empty($_POST['longitude'])
    || empty($_POST['selArr'])
    || empty($_FILES["file"])) {
    // ========================  参数不完整
    $R->setOPT('upload', 'ERR');
    $R->setOPT('errMsg', '参数缺失');

    $R->toStr();
    return;
}

if (file_exists("upload/" . $_FILES["file"]["name"])) {
    echo $_FILES["file"]["name"] . " already exists. ";
} else {
    move_uploaded_file($_FILES["file"]["tmp_name"],
        "upload/" . $_POST['MAX_FILE_SIZE'] . $_FILES["file"]["name"]);

    echo "Stored in: " . "upload/" . $_POST['MAX_FILE_SIZE'] . $_FILES["file"]["name"];

}
echo '__';
echo $_POST['latitude'];
echo '__';
echo $_POST['longitude'];
echo '__';
echo $_POST['selArr'];
