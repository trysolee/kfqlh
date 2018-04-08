<?php


# 上传 临时文件 , 
# 
# 1 . 包含 经纬度 
# 
# TODO 暂时 取消多个文件上传 , 暂时不需要临时文件
# 
 header("Content-Type: text/html;charset=utf-8");

include_once "getdb.php";
include_once "ret.php";

$R = new ret();

if(empty($_POST['latitude']) || empty($_POST['longitude'] || empty( $_FILES["file"]) )){
    // ========================  参数不完整
    $R->setOPT('upload', 'ERR');
    $R->setOPT('errMsg', '参数缺失');

    $R->toStr();
    return;
}

function newTmpFile($latitude, $longitude)
{
    $t = strtotime('now');

    return [
        'name' => 'user',
        'DAT'  => [
            // 'JID'   => 1,
            'JSON' => [
                'name'     => $userName,
                'role'     => [
                    $pro['JID'] => $pro['JSON']['role'],
                ],
                'proID'    => $pro['JID'],
                'inUserID' => $pro['UID'],
            ],
            'FT'   => $t,
            'LT'   => $t,

        ],
    ];
}


$inCode     = $_GET['code'];
$inIN       = $_GET['in'];
$inUserName = $_GET['username'];

 if (file_exists("upload/" . $_FILES["file"]["name"]))
      {
      echo $_FILES["file"]["name"] . " already exists. ";
      }
    else
      {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "upload/" . $_POST['MAX_FILE_SIZE'] . $_FILES["file"]["name"]);

      echo "Stored in: " . "upload/" .  $_POST['MAX_FILE_SIZE'] . $_FILES["file"]["name"];

      }
echo '__';
echo $_POST['latitude'];
echo '__';
echo $_POST['longitude'];
echo '__';
echo $_POST['selArr'];

?>
