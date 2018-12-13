<?php
require_once '../functions.php';
$user = xiu_get_current_user();

function update(){
    //判断内容是否为空
    if(empty($_POST['avatar'])){
        $GLOBALS['message'] = '请上传头像';
        return;
    }
    if(empty($_POST['slug'])){
        $GLOBALS['message'] = '请填写别名';
        return;
    }
    if(empty($_POST['nickname'])){
        $GLOBALS['message'] = '请填写昵称';
        return;
    }
    if(empty($_POST['bio'])){
        $GLOBALS['message'] = '请填写简介';
        return;
    }
    $avatar = $_POST['avatar'];
    $slug = $_POST['slug'];
    $nickname = $_POST['nickname'];
    $bio = $_POST['bio'];
    $id = $_GET['id'];

    $rows = xiu_execute("update users set slug = '{$slug}',nickname = '{$nickname}',avatar = '{$avatar}',bio = '{$bio}' where id = '{$id}';");

    $GLOBALS['message'] = $rows <= 0 ? "更新失败" : "更新成功";

    header('location: /admin/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  update();
}



?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
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
        <h1>我的个人资料</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if (isset($message)): ?>
        <div class="alert alert-danger">
          <strong>错误！</strong><?php echo $message ?>
        </div>
      <?php endif; ?>
      <form class="form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?id=<?php echo $user['id'] ?>" autocomplete="off" novalidate>
        <div class="form-group">
          <label class="col-sm-3 control-label">头像</label>
          <div class="col-sm-6">
            <label class="form-image">
              <input id="avatar" type="file">
              <img src="<?php echo $user['avatar']; ?>">
              <input type="hidden" name="avatar" value="<?php echo $user['avatar']; ?>">
              <i class="mask fa fa-upload"></i>
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="email" class="col-sm-3 control-label">邮箱</label>
          <div class="col-sm-6">
            <input id="email" class="form-control" name="email" type="type" value="<?php echo $user['email']; ?>" placeholder="邮箱" readonly>
            <p class="help-block">登录邮箱不允许修改</p>
          </div>
        </div>
        <div class="form-group">
          <label for="slug" class="col-sm-3 control-label">别名</label>
          <div class="col-sm-6">
            <input id="slug" class="form-control" name="slug" type="type" value="<?php echo $user['slug']; ?>" placeholder="别名">
            <p class="help-block">没个<strong>鸟用</strong></p>
          </div>
        </div>
        <div class="form-group">
          <label for="nickname" class="col-sm-3 control-label">昵称</label>
          <div class="col-sm-6">
            <input id="nickname" class="form-control" name="nickname" type="type" value="<?php echo $user['nickname'] ?>" placeholder="昵称">
            <p class="help-block">限制在 2-16 个字符</p>
          </div>
        </div>
        <div class="form-group">
          <label for="bio" class="col-sm-3 control-label">简介</label>
          <div class="col-sm-6">
            <textarea id="bio" class="form-control" name="bio" placeholder="请输入简介" cols="30" rows="6"><?php echo $user['bio']; ?></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-primary">更新</button>
            <a class="btn btn-link" href="password-reset.php">修改密码</a>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php $current_page = 'profile'; ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script type="text/javascript">
    $('#avatar').on('change',function () {
      var $this = $(this);
      var files = $this.prop('files');
      if (!files.length) return;

      var file = files[0];

      var data = new FormData();

      data.append('avatar',file);

      var xhr = new XMLHttpRequest();

      xhr.open('POST','/admin/api/upload.php');

      xhr.send(data);
      xhr.onload = function () {
//        console.log(this.responseText);
        $this.siblings('img').attr('src',this.responseText);
        $this.siblings('input').val(this.responseText);
      }

    })
  </script>
  <script>NProgress.done()</script>
</body>
</html>
