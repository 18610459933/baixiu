<?php
//引入封装的函数
require_once "../../functions.php";

$comments = xiu_fetch_all("select * from comments;");

$json = json_encode($comments);

header("Content-Type: application/json");

echo $json;










