<?php
require_once '../../functions.php';

if (empty($_GET['id'])){
    exit('<h1>缺少必要参数</h1>');
}

$id = $_GET['id'];

$row = xiu_execute("delete from comments where id in (" . $id . ");");

header('Content-Type: appliction/json');

echo json_encode($row > 0);
