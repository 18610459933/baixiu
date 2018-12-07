<?php
require_once '../functions.php';
xiu_get_current_user();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Comments &laquo; Admin</title>
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
        <h1>所有评论</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <div class="btn-batch pull-left" style="display: none">
          <button class="btn btn-info btn-sm">批量批准</button>
          <button class="btn btn-warning btn-sm">批量拒绝</button>
          <button class="btn btn-danger btn-sm">批量删除</button>
        </div>
        <ul class="pagination pagination-sm pull-right">
          <li><a href="#">上一页</a></li>
          <li><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li><a href="#">3</a></li>
          <li><a href="#">下一页</a></li>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover table-responsive">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th width="70">作者</th>
            <th>评论</th>
            <th width="200">评论在</th>
            <th width="90">提交于</th>
            <th>状态</th>
            <th class="text-center" width="140">操作</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <?php $current_page = 'comments'; ?>
  <?php include 'inc/sidebar.php'; ?>

  <script id="comments_tmpl" type="text/x-jsrender">
    {{for comments}}
      <tr>
        <td class="text-center"><input type="checkbox" data-id="{{:id}}"></td>
        <td>{{:author}}</td>
        <td>{{:content}}</td>
        <td>《{{:post_title}}》</td>
        <td>{{:created}}</td>
        <td>{{:status}}</td>
        <td class="text-center">
          {{if status == 'held'}}
            <a href="post-add.php" class="btn btn-info btn-xs">批准</a>
            <a href="post-add.php" class="btn btn-warning btn-xs">拒绝</a>
          {{/if}}
          <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
        </td>
      </tr>
    {{/for}}
  </script>
  <script type="text/javascript" src="/static/assets/vendors/jquery/jquery.js"></script>
  <script type="text/javascript" src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script type="text/javascript" src="/static/assets/vendors/jsrender/jsrender.js"></script>
  <script type="text/javascript" src="/static/assets/vendors/twbs-pagination/jquery.twbsPagination.js"></script>
  <script type="text/javascript">

    function loadPage (page){
      $.get("/admin/api/comments.php",{page:page},function (rec) {
        $('.pagination').twbsPagination({
          totalPages: rec.total_pages,
          visiblePages:3,
          initiateStartPageClick:false,
          first:'首页',
          prev:'上一页',
          next:'下一页',
          last:'尾页',
          onPageClick: function (evt, page) {
            loadPage(page);
          }
        });
//        console.log(rec);
        var html = $("#comments_tmpl").render({comments : rec.comments});
        $('tbody').html(html);
      });
    }

    loadPage(1);
  </script>
  <script>NProgress.done()</script>
</body>
</html>
