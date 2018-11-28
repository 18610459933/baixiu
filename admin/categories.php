<?php
require_once '../functions.php';
xiu_get_current_user();

function add_category(){
  if (empty($_POST['name']) || empty($_POST['slug'])){
    $GLOBALS['message'] = '请输入完整信息';
    $GLOBALS['success'] = false;
    return;
  }
  $name = $_POST['name'];
  $slug = $_POST['slug'];

  $rows = xiu_execute("insert into categories values (null, '{$slug}', '{$name}');");

  $GLOBALS['success'] = $rows > 0;
  $GLOBALS['message'] = $rows <= 0 ? "添加失败" : "添加成功";
}
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  add_category();
}

$categories = xiu_fetch_all("select * from categories;");

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
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
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if (isset($message)): ?>
        <?php if ($success): ?>
          <div class="alert alert-success">
            <strong>成功！</strong><?php echo $message ?>
          </div>
          <?php else: ?>
            <div class="alert alert-danger">
              <strong>错误！</strong><?php echo $message ?>
            </div>
        <?php endif; ?>
      <?php endif; ?>
      <div class="row">
        <div class="col-md-4">
          <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" autocomplete="off">
            <h2>添加新分类目录</h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
<!--                                                              /admin/categories-delete.php-->
            <a id="btn_delete" class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>名称</th>
                <th>Slug</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($categories as $item): ?>
                <tr>
                  <td class="text-center"><input type="checkbox" data-id="<?php echo $item['id']; ?>"></td>
                  <td><?php echo $item['name']; ?></td>
                  <td><?php echo $item['slug']; ?></td>
                  <td class="text-center">
                    <a href="javascript:;" class="btn btn-info btn-xs">编辑</a>
                    <a href="categories-delete.php?id=<?php echo $item['id'] ?>" class="btn btn-danger btn-xs">删除</a>
                  </td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <?php $current_page = 'categories'; ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script type="text/javascript">
    $(function($){
      var $input = $('body input');
      var $btnDelete = $('#btn_delete');
      var checkedAll = [];
      $input.on('change',function(){
        var id = $(this).data('id');
        if ($(this).prop('checked')) {
           checkedAll.push(id);
        }else{
          checkedAll.splice(checkedAll.indexOf(id),1);
        }
        checkedAll.length ? $btnDelete.fadeIn() : $btnDelete.fadeOut();
//        $btnDelete.prop('search','?id=' + checkedAll);
        $btnDelete.attr('href','/admin/categories-delete.php?id=' + checkedAll);
      });
    });
  </script>
  <script>NProgress.done()</script>
</body>
</html>
