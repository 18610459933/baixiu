<?php
require_once '../functions.php';
xiu_get_current_user();

//编辑信息------------------------------------------------------------------
if (isset($_GET['id'])){
    $current_edit_posts = xiu_fetch_one("select * from posts where id = " . $_GET['id']);
}
$categories = xiu_fetch_all("select * from categories;");
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

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
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
    <?php if (isset($current_edit_posts)): ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>编辑文章</h1>
      </div>
      <form class="row">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题" value="<?php echo $current_edit_posts['title']; ?>">
          </div>
          <div class="form-group">
            <label for="content">内容</label>
            <script id="container" name="content" type="text/plain"><?php echo $current_edit_posts['content']; ?></script>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名</label>
            <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo $current_edit_posts['slug']; ?>">
            <p class="help-block">https://zce.me/post/<strong>slug</strong></p>
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <img class="help-block thumbnail" style="display: none">
            <input id="feature" class="form-control" name="feature" type="file">
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category">
              <?php foreach ($categories as $item): ?>
                <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" class="form-control" name="created" type="datetime-local">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <option value="published"<?php echo isset($_GET['status']) && $_GET['status'] == "published" ? " selected" : ""; ?>>已发布</option>
              <option value="drafted"<?php echo isset($_GET['status']) && $_GET['status'] == "drafted" ? " selected" : ""; ?>>草稿</option>
              <option value="trashed"<?php echo isset($_GET['status']) && $_GET['status'] == "trashed" ? " selected" : ""; ?>>回收站</option>
            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">保存</button>
          </div>
        </div>
      </form>
    </div>
    <?php else: ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>写文章</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <form class="row">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题">
          </div>
          <div class="form-group">
            <label for="content">内容</label>
            <script id="container" name="content" type="text/plain">尽情的发挥吧！！！</script>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名</label>
            <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
            <p class="help-block">https://zce.me/post/<strong>slug</strong></p>
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <img class="help-block thumbnail" style="display: none">
            <input id="feature" class="form-control" name="feature" type="file">
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category">
                <?php foreach ($categories as $item): ?>
                  <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
                <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" class="form-control" name="created" type="datetime-local">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <option value="published"<?php echo isset($_GET['status']) && $_GET['status'] == "published" ? " selected" : ""; ?>>已发布</option>
              <option value="drafted"<?php echo isset($_GET['status']) && $_GET['status'] == "drafted" ? " selected" : ""; ?>>草稿</option>
              <option value="trashed"<?php echo isset($_GET['status']) && $_GET['status'] == "trashed" ? " selected" : ""; ?>>回收站</option>
            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">保存</button>
          </div>
        </div>
      </form>
    </div>
    <?php endif; ?>
  </div>

  <?php $current_page = 'post-add'; ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script type="text/javascript" src="/static/assets/vendors/ueditor/ueditor.config.js"></script>
  <script type="text/javascript" src="/static/assets/vendors/ueditor/ueditor.all.js"></script>
  <script type="text/javascript">
    var ue = UE.getEditor('container',{
      toolbars: [
        ['fullscreen', 'source', 'undo', 'redo'],
        [
          'bold', 'italic', 'underline', 'fontborder',
          'strikethrough', 'superscript', 'subscript',
          'removeformat', 'formatmatch', 'autotypeset',
          'blockquote', 'pasteplain', '|', 'forecolor',
          'backcolor', 'insertorderedlist', 'insertunorderedlist',
          'selectall', 'cleardoc'
        ]
      ],
      initialFrameHeight:500
    });

  </script>
  <script>NProgress.done()</script>
</body>
</html>
