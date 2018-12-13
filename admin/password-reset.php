<?php
require_once '../functions.php';
$user = xiu_get_current_user();

function change_password(){

  if (empty($_POST['oldPassword'])){
    $GLOBALS['message'] = '请输入原始密码';
    $GLOBALS['success'] = false;
    return;
  }
  if (empty($_POST['newPassword'])){
    $GLOBALS['message'] = '请输入新密码';
    $GLOBALS['success'] = false;
    return;
  }
  if (empty($_POST['confirm'])){
    $GLOBALS['message'] = '请输入确认密码';
    $GLOBALS['success'] = false;
    return;
  }

  $oldPassword = $_POST['oldPassword'];
  $newPassword =$_POST['newPassword'];
  $confirm =$_POST['confirm'];
  $id = $_GET['id'];
  $users = xiu_fetch_all("select * from users where id = '{$id}';")[0];

  if ($oldPassword !== $users['password']){
    $GLOBALS['message'] = '密码错误';
    $GLOBALS['success'] = false;
    return;
  }

  if($newPassword !== $confirm){
    $GLOBALS['message'] = '新密码两次输入不一致';
    $GLOBALS['success'] = false;
    return;
  }
  $rows = xiu_execute("update users set password = '{$confirm}' where id = '{$id}';");

  $GLOBALS['success'] = $rows >0;
  $GLOBALS['message'] = $rows <= 0 ? "修改失败" : "修改成功";

  header("location: /admin/login.php");

}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  change_password();
}


?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Password reset &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <?php include 'inc/navbar.php' ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>修改密码</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if (isset($message)): ?>
          <?php if ($success): ?>
          <div class="alert alert-success">
            <strong>正确！</strong><?php echo $message ?>
          </div>
          <?php else: ?>
          <div class="alert alert-danger">
            <strong>错误！</strong><?php echo $message ?>
          </div>
          <?php endif; ?>
      <?php endif; ?>
      <form class="form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?id=<?php echo $user['id']; ?>" autocomplete="off" novalidate>
        <div class="form-group">
          <label for="old" class="col-sm-3 control-label">旧密码</label>
          <div class="col-sm-7">
            <input id="old" class="form-control" type="password" name="oldPassword" placeholder="旧密码">
          </div>
        </div>
        <div class="form-group">
          <label for="password" class="col-sm-3 control-label">新密码</label>
          <div class="col-sm-7">
            <input id="password" class="form-control" type="password" name="newPassword" placeholder="新密码">
          </div>
        </div>
        <div class="form-group">
          <label for="confirm" class="col-sm-3 control-label">确认新密码</label>
          <div class="col-sm-7">
            <input id="confirm" class="form-control" name="confirm" type="password" placeholder="确认新密码">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-7">
            <button type="submit" class="btn btn-primary">修改密码</button>
            <button type="button" class="btn btn-info" id="show_password">显示密码</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php $current_page = 'password-reset'; ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script type="text/javascript">
    var $showPassword = $('#show_password');
    var $input = $('form input');
    $showPassword.on('click',function(){
      if ($showPassword.html() === "显示密码"){
        $input.attr('type','text');
        $(this).html('隐藏密码');
      }else{
        $input.attr('type','password');
        $(this).html('显示密码');
      }
    })
  </script>
  <script>NProgress.done()</script>
</body>
</html>
