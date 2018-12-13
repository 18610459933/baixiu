<?php

if (empty($_FILES['avatar'])){
    exit('<h1>请传入数据</h1>');
}
$avatar = $_FILES['avatar'];

if ($avatar['error'] !== UPLOAD_ERR_OK){
    exit('<h1>上传失败</h1>');
}

$ext = pathinfo($avatar['name'],PATHINFO_EXTENSION);

$target = '../../static/uploads/img-' . uniqid() . '.' . $ext;

if (!move_uploaded_file($avatar['tmp_name'],$target)){
    exit('<h1>上传失败</h1>');
}

echo substr($target,5);

