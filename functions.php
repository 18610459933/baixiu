<?php
require_once 'config.php';
//调用一次
session_start();

/**
 * 获取当前登录用户信息，如果没有获取到则自动跳转到登录页面
 * @return mixed
 */
function xiu_get_current_user (){
    if (empty($_SESSION['current_login_user'])){
        header('location:/admin/login.php');
        exit();
    }
    return $_SESSION['current_login_user'];
}

/**
 * 通过一个数据库查询 获取多条数据
 */
function xiu_fetch_all ($sql){
    $conn = mysqli_connect(XIU_DB_HOST,XIU_DB_USER,XIU_DB_PASS,XIU_DB_NAME);
    mysqli_set_charset($conn,"utf8");
    if (!$conn){
        exit('<h1>数据库连接失败</h1>');
    }
    $query = mysqli_query($conn,$sql);

    if (!$query){
        return false;
    }

    while ($row = mysqli_fetch_assoc($query)){
        $result[] = $row;
    }

    mysqli_free_result($query);
    mysqli_close($conn);

    return $result;
}

/**
 * 通过一个数据库查询 获取单条数据
 */
function xiu_fetch_one ($sql){
    $res = xiu_fetch_all($sql);
    return isset($res) ? $res[0] : "";
}

/**
 * 数据库添加操作
 */
function xiu_execute($sql){
    $conn = mysqli_connect(XIU_DB_HOST,XIU_DB_USER,XIU_DB_PASS,XIU_DB_NAME);
    mysqli_set_charset($conn,"utf8");
    if (!$conn){
        exit('<h1>数据库连接失败</h1>');
    }
    $query = mysqli_query($conn,$sql);

    if (!$query){
        return false;
    }
    $affected_rows = mysqli_affected_rows($conn);
    mysqli_close($conn);

    return $affected_rows;
}



