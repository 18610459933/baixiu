<?php
//验证是否登陆
require_once '../functions.php';
xiu_get_current_user();

//获取文章状态和日期格式
function convert_status($status){
  $dict = array(
    "published" => "已发布",
      "drafted" => "草稿",
      "trashed" => "回收站"
  );
  return isset($dict[$status]) ? $dict[$status] : "未知";
}
function convert_date($created){
  $timestamp = strtotime($created);
  return date('Y年m月d日 <b\r> H : i : s',$timestamp);
}

//筛选的计算-----------------------------------------------------------------
//获取所有的分类
$categories = xiu_fetch_all("select * from categories;");
$where = "1 = 1";
$search = "";
if (isset($_GET['category']) && $_GET['category'] !== "all"){
    $where .= " and posts.category_id=" . $_GET['category'];
    $search .= "&category=" . $_GET['category'];
}
if (isset($_GET['status']) && $_GET['status'] !== "all"){
    $where .= " and posts.status = '{$_GET['status']}'";
    $search .= "&status=" . $_GET['status'];
}

//页码的计算------------------------------------------------------------------------
//获取全部数据的数量
$total_count = (int)xiu_fetch_one("select 
	count(1)
	as num
from posts
inner join categories on posts.category_id = categories.id
inner join users on posts.user_id = users.id
where {$where};")['num'];

//计算页码
$page = empty($_GET['page']) ? 1 : (int)$_GET['page'];  // 当前展示页
$size = 15; //每页展示多少条数据
$offset = ($page - 1) * $size;  //计算跳过多少条数据
$visiable = 5; //显示几个页码
$region = ($visiable - 1) / 2; // 左右区间
$begin = $page - $region; //开始页码
$end = $begin + $visiable; //结束页码
$total_pages = (int)ceil($total_count/$size); // 计算共有多少页数

//开始必须 > 0
if ($begin < 1){
  $begin = 1;
  $end = $visiable + $begin;
}
//最后也必须 <= $end
if ($end > $total_pages + 1) {
    // end 超出范围
    $end = $total_pages + 1;
    // end 修改意味着必须要改 begin
    $begin = $end - $visiable;
    if ($begin < 1) {
        $begin = 1;
    }
}
//判断小于0和大于最大页数的情况
if($page < 1){
//  小于最小页数的情况
  header('location: /admin/posts.php?page=1'. $search);
}
if ($page > $total_pages){
//  大于最大页数的情况
  header('location: /admin/posts.php?page=' . $total_pages . $search);
}

$posts = xiu_fetch_all("select 
	posts.id,
	posts.title,
	users.nickname as user_name,
	categories.name as category_name,
	posts.created,
	posts.status
from posts
inner join categories on posts.category_id = categories.id
inner join users on posts.user_id = users.id
where {$where}
order by posts.created desc
limit {$offset} , {$size};");


?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
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
        <h1>所有文章</h1>
        <a href="post-add.php" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <div class="pull-left">
          <a id="btn_delete" class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        </div>
        <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <select name="category" class="form-control input-sm">
            <option value="all">所有分类</option>
            <?php foreach ($categories as $item): ?>
              <option value="<?php echo $item['id'] ?>"<?php echo isset($_GET['category']) && $_GET['category'] == $item['id'] ? " selected" : ""; ?>><?php echo $item['name'] ?></option>
            <?php endforeach ?>
          </select>
          <select name="status" class="form-control input-sm">
            <option value="all">所有状态</option>
            <option value="published"<?php echo isset($_GET['status']) && $_GET['status'] == "published" ? " selected" : ""; ?>>已发布</option>
            <option value="drafted"<?php echo isset($_GET['status']) && $_GET['status'] == "drafted" ? " selected" : ""; ?>>草稿</option>
            <option value="trashed"<?php echo isset($_GET['status']) && $_GET['status'] == "trashed" ? " selected" : ""; ?>>回收站</option>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">
          <?php if ($page - 1 > 0) : ?>
            <li><a href="?page=<?php echo $page - 1 . $search; ?>">上一页</a></li>
          <?php endif; ?>
          <?php for ($i = $begin; $i < $end; $i++): ?>
            <li<?php echo $page === $i ? ' class="active"' : ''; ?>><a href="?page=<?php echo $i . $search; ?>"><?php echo $i; ?></a></li>
          <?php endfor ?>
          <?php if ($page + 1 <= $total_pages) : ?>
            <li><a href="?page=<?php echo $page + 1 . $search; ?>">下一页</a></li>
          <?php endif; ?>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($posts as $item): ?>
            <tr>
              <td class="text-center"><input type="checkbox" data-id="<?php echo $item['id']; ?>"></td>
              <td><?php echo $item['title']; ?></td>
              <td><?php echo $item['user_name']; ?></td>
              <td><?php echo $item['category_name']; ?></td>
              <td class="text-center"><?php echo convert_date($item['created']); ?></td>
              <td class="text-center"><?php echo convert_status($item['status']); ?></td>
              <td class="text-center">
                <a href="/admin/post-add.php?id=<?php echo $item['id'] ?>" class="btn btn-default btn-xs">编辑</a>
                <a href="/admin/posts-delete.php?id=<?php echo $item['id'] ?>" class="btn btn-danger btn-xs">删除</a>
              </td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php $current_page = 'posts'; ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script type="text/javascript">
    $(function($){
      var $theadInput = $("thead input");
      var $tbodyInput = $("tbody input");
      var $btnDelete = $("#btn_delete");
      var $inputAll = [];
      $theadInput.on('change',function(){
        var $checked = $(this).prop('checked');
        $tbodyInput.prop('checked',$checked).change();
      });
      $tbodyInput.on('change',function(){
        var id = $(this).data('id');
        if ($(this).prop('checked')){
          $inputAll.push(id);
        }else{
          $inputAll.splice($inputAll.indexOf(id),1);
        }
        $inputAll.length ? $btnDelete.fadeIn() : $btnDelete.fadeOut();
        $btnDelete.attr('href','/admin/posts-delete.php?id=' + $inputAll);
      })
    })
  </script>
  <script>NProgress.done()</script>
</body>
</html>
