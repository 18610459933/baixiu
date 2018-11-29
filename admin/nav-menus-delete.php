<?php
require_once '../functions.php';

if (empty($_GET['id'])){
    exit("<h1>缺少必要参数</h1>");
}

$id = $_GET['id'];

$rows = xiu_execute("delete from categories where id in (" . $id . ");");

header("location:/admin/nav-menus.php");