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
        <div class="btn-batch pull-left deleteAll" style="display: none">
          <button class="btn btn-info btn-sm">批量批准</button>
          <button class="btn btn-warning btn-sm">批量拒绝</button>
          <button class="btn btn-danger btn-sm">批量删除</button>
        </div>
        <ul class="pagination pagination-sm pull-right"></ul>
      </div>
      <table class="table table-striped table-bordered table-hover table-responsive">
        <thead>
          <tr width="100%" class="text-center">
            <th width="2%"><input type="checkbox"></th>
            <th width="5%">作者</th>
            <th width="55%">评论</th>
            <th width="13%">评论在</th>
            <th width="10%">提交于</th>
            <th width="5%">状态</th>
            <th width="10%">操作</th>
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
      <tr {{if status == "held"}} class="warning" {{else status == "rejected"}} class="danger" {{/if}} data-id="{{:id}}">
        <td class="text-center"><input type="checkbox" id="checked"></td>
        <td>{{:author}}</td>
        <td>{{:content}}</td>
        <td>《{{:post_title}}》</td>
        <td>{^{:created}}</td>
        <td>
          {{if status == "held"}} 待审核
          {{else status == 'approved'}} 已批准
          {{else status == 'rejected'}} 已拒绝
          {{else status == 'trashed'}} 回收站
          {{/if}}
        </td>
        <td class="text-center">
          {{if status == 'held'}}
            <a href="post-add.php" class="btn btn-info btn-xs">批准</a>
            <a href="post-add.php" class="btn btn-warning btn-xs">拒绝</a>
          {{/if}}
          <a href="javascript:;" class="btn btn-danger btn-xs" id='btn_delete'>删除</a>
        </td>
      </tr>
    {{/for}}
  </script>
  <script type="text/javascript" src="/static/assets/vendors/jquery/jquery.js"></script>
  <script type="text/javascript" src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script type="text/javascript" src="/static/assets/vendors/jsrender/jsrender.js"></script>
  <script type="text/javascript" src="/static/assets/vendors/twbs-pagination/jquery.twbsPagination.js"></script>
  <script type="text/javascript">
    function dateFtt(fmt,date){
      var o = {
        "M+" : date.getMonth()+1,                 //月份
        "d+" : date.getDate(),                    //日
        "h+" : date.getHours(),                   //小时
        "m+" : date.getMinutes(),                 //分
        "s+" : date.getSeconds(),                 //秒
        "q+" : Math.floor((date.getMonth()+3)/3), //季度
        "S"  : date.getMilliseconds()             //毫秒
      };
      if(/(y+)/.test(fmt))
        fmt=fmt.replace(RegExp.$1, (date.getFullYear()+"").substr(4 - RegExp.$1.length));
      for(var k in o)
        if(new RegExp("("+ k +")").test(fmt))
          fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
      return fmt;
    }
//    console.log(dateFtt('yyyy年MM月dd日 \n hh:mm:ss', new Date("2018-10-05 10:08:06")));
    var $tbody = $("table tbody");
    var $headInput= $("table thead input");
    var $deleteAll = $(".deleteAll");
    var checkedAll = [];
    var current = 1;
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
        current = page;
        $('table input').prop('checked',false);
        checkedAll = [];
        checkedAll.length ? $deleteAll.fadeIn() : $deleteAll.fadeOut();
      });
    }
    loadPage(1);
//    删除评论-----------------------------------------------------
    $tbody.on('click','#btn_delete',function(){
//      获取到删除a标签和input共有的id
      $tr = $(this).parent().parent();
      var id = $tr.data("id");
//      发送一个ajax请求，告诉服务器要删除的id
      $.get('/admin/api/comments-delete.php',{id:id},function(res){
        if (!res) return;
//        $tr.remove();  // 使用remove删除会遇到最后一个删除时，导致空页面
        loadPage(current); //创建个变量接受当前页 从新加载
      })

    });

//    单选与多选----------------------------------------------------
//    全选
    $headInput.on('change',function(){
      var $checked = $(this).prop('checked');
      $("tbody input").prop("checked",$checked).change();
    });
//    单选
    $tbody.on('change','#checked',function(){
      var id = $(this).parent().parent().data('id');
      if ($(this).prop('checked')) {
        checkedAll.push(id);
      }else{
        checkedAll.splice(checkedAll.indexOf(id),1);
      }
      checkedAll.length ? $deleteAll.fadeIn() : $deleteAll.fadeOut();
//      console.log(checkedAll);
    });


  </script>
  <script>NProgress.done()</script>
</body>
</html>
