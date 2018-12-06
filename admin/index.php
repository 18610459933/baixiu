<?php
require_once '../functions.php';
xiu_get_current_user();

$posts_count = xiu_fetch_one('select count(1) as num from posts;');

$posts_status = xiu_fetch_one("select count(1) as status from posts where status = 'drafted';");

$categories = xiu_fetch_one("select count(1) as categories from categories;");

$comments = xiu_fetch_one("select count(1) as comments from comments;");

$held = xiu_fetch_one("select count(1) as held from comments where status = 'held';");

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
      <div class="jumbotron text-center">
        <h1>One Belt, One Road</h1>
        <p>Thoughts, stories and ideas.</p>
        <p><a class="btn btn-primary btn-lg" href="post-add.php" role="button">写文章</a></p>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">站点内容统计：</h3>
            </div>
            <ul class="list-group">
              <li class="list-group-item"><strong><?php echo $posts_count['num'] ?></strong>篇文章（<strong><?php echo $posts_status['status'] ?></strong>篇草稿）</li>
              <li class="list-group-item"><strong><?php echo $categories['categories']?></strong>个分类</li>
              <li class="list-group-item"><strong><?php echo $comments['comments'] ?></strong>条评论（<strong><?php echo $held['held']?></strong>条待审核）</li>
            </ul>
          </div>
        </div>
        <div class="col-md-4">
          <div id="map" style="width: 600px;height:400px;"></div>
        </div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </div>

  <?php $current_page = 'index'; ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script type="text/javascript" src="../static/assets/vendors/chart/chart.js"></script>
  <script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('map'));

    // 指定图表的配置项和数据
    var option = {
      tooltip: {
        trigger: 'item',
        formatter: "{a} <br/>{b}: {c} ({d}%)"
      },
      legend: {
        orient: 'vertical',
        x: 'left',
        data:['文章','分类','评论','草稿','评论待审核']
      },
      series: [
        {
          name:'栏目名称',
          type:'pie',
          selectedMode: 'single',
          radius: [0, '30%'],

          label: {
            normal: {
              position: 'inner'
            }
          },
          labelLine: {
            normal: {
              show: false
            }
          },
          data:[
            {value:<?php echo $posts_count['num'] ?>, name:'文章', selected:true},
            {value:<?php echo $categories['categories']?>, name:'分类'},
            {value:<?php echo $comments['comments'] ?>, name:'评论'}
          ]
        },
        {
          name:'信息展示',
          type:'pie',
          radius: ['40%', '55%'],
          label: {
            normal: {
              formatter: '{a|{a}}{abg|}\n{hr|}\n  {b|{b}：}{c}  {per|{d}%}  ',
              backgroundColor: '#eee',
              borderColor: '#aaa',
              borderWidth: 1,
              borderRadius: 4,
              // shadowBlur:3,
              // shadowOffsetX: 2,
              // shadowOffsetY: 2,
              // shadowColor: '#999',
              // padding: [0, 7],
              rich: {
                a: {
                  color: '#999',
                  lineHeight: 22,
                  align: 'center'
                },
                // abg: {
                //     backgroundColor: '#333',
                //     width: '100%',
                //     align: 'right',
                //     height: 22,
                //     borderRadius: [4, 4, 0, 0]
                // },
                hr: {
                  borderColor: '#aaa',
                  width: '100%',
                  borderWidth: 0.5,
                  height: 0
                },
                b: {
                  fontSize: 16,
                  lineHeight: 33
                },
                per: {
                  color: '#eee',
                  backgroundColor: '#334455',
                  padding: [2, 4],
                  borderRadius: 2
                }
              }
            }
          },
          data:[
            {value:<?php echo $posts_status['status'] ?>, name:'草稿'},
            {value:<?php echo $held['held']?>, name:'评论待审核'}
          ]
        }
      ]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
  </script>
  <script>NProgress.done()</script>
</body>
</html>
