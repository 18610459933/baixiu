<?php
  require_once '../config.php';
  session_start();
  function login(){
    if (empty($_POST['email'])){
      $GLOBALS['message'] = '用户名或密码错误！';
      return;
    }
    if (empty($_POST['password'])){
      $GLOBALS['message'] = '密码或用户名错误!';
      return;
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    $conn = mysqli_connect(XIU_DB_HOST,XIU_DB_USER,XIU_DB_PASS,XIU_DB_NAME);
    mysqli_set_charset($conn,"utf8");
    if (!$conn){
      exit('<h1>数据库连接失败</h1>');
    }
    $query = mysqli_query($conn,"select * from users where email = '{$email}' limit 1;");
    if (!$query){
      $GLOBALS['message'] = '登录失败，请重试';
      return;
    }
    $user = mysqli_fetch_assoc($query);
    if (!$user){
      $GLOBALS['message'] = '邮箱或密码错误！';
      return;
    }
    if ($user['password'] !== $password){
      $GLOBALS['message'] = '密码或邮箱错误！';
      return;
    }

    $_SESSION['current_login_user'] = $user;

//    完成后跳转主页
    header('location: /admin/index.php');
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    login();
  }
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/animate/animate.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
</head>
<body>
  <div class="login">
    <form class="login-wrap<?php echo isset($message) ? ' shake animated':''; ?>" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" autocomplete="off" novalidate>
      <img class="avatar" src="/static/assets/img/default.png">
      <!-- 有错误信息时展示 -->
       <?php if (isset($message)): ?>
         <div class="alert alert-danger">
           <strong>错误！</strong><?php echo $message; ?>
         </div>
       <?php endif ?>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" type="email" name="email" class="form-control" placeholder="邮箱" autofocus>
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" type="password" name="password" class="form-control" placeholder="密码">
      </div>
      <button class="btn btn-primary btn-block" href="index.php">登 录</button>
    </form>
  </div>

  <script type="text/javascript" src="/static/assets/vendors/jquery/jquery-1.min.js"></script>
  <script type="text/javascript">
    $(function ($) {
      var emailFormat = /^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/;
      $('#email').on('blur',function () {
        var value = $(this).val();
        if (!value || !emailFormat.test(value)) return;
        $.get('/admin/api/avatar.php',{email:value},function(res){
          if(!res) return;
          $('.avatar').fadeOut(function(){
            $(this).on('load',function(){
              $(this).fadeIn();
            }).attr('src',res);
          })
        })
      })
    })
  </script>
</body>
</html>
