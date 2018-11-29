<?php
require_once '../functions.php';
xiu_get_current_user();

$categories = xiu_fetch_all("select * from categories;");

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Navigation menus &laquo; Admin</title>
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
        <h1>导航菜单</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if (isset($message)): ?>
        <?php if ($success): ?>
          <div class="alert alert-danger">
            <strong>正确！</strong><?php echo $message ?>
          </div>
          <?php else: ?>
          <div class="alert alert-danger">
            <strong>错误！</strong><?php echo $message ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>
      <div class="row">
        <div class="col-md-4">
          <form>
            <h2>添加新导航链接</h2>
            <div class="form-group">
              <label for="text">文本</label>
              <input id="text" class="form-control" name="text" type="text" placeholder="文本">
            </div>
            <div class="form-group">
              <label for="title">标题</label>
              <input id="title" class="form-control" name="title" type="text" placeholder="标题">
            </div>
            <div class="form-group">
              <label for="href">链接</label>
              <input id="href" class="form-control" name="href" type="text" placeholder="链接">
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a id="btn_delete" class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>文本</th>
                <th>标题</th>
                <th>链接</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($categories as $item): ?>
                <tr>
                  <td class="text-center"><input type="checkbox" data-id="<?php echo $item['id']; ?>"></td>
                  <td><i class="fa fa-glass"><?php echo $item['slug'] ?></td>
                  <td><?php echo $item['name'] ?></td>
                  <td>#</td>
                  <td class="text-center">
                    <a href="nav-menus-delete.php?id=<?php echo $item['id'] ?>" class="btn btn-danger btn-xs">删除</a>
                  </td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <?php $current_page = 'nav-menus'; ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script type="text/javascript">
    $(function($){
      var $input = $('body input');
      var $btnDelete = $('#btn_delete');
      var checkedAll = [];
      $input.on('change',function(){
//        console.log($(this).data('id'));
        var id =$(this).data('id');
        if($(this).prop('checked')){
          checkedAll.push(id);
        }else{
          checkedAll.splice(checkedAll.indexOf(id),1);
        }

        checkedAll.length ? $btnDelete.fadeIn() : $btnDelete.fadeOut();

//        两种方法用attr 或者 prop
        $btnDelete.attr('href','/admin/nav-menus-delete.php?id=' + checkedAll);
      })


    })
  </script>
  <script>NProgress.done()</script>
</body>
</html>
