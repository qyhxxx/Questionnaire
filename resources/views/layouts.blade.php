<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>DASHGUM - FREE Bootstrap Admin Template</title>

    <!-- Bootstrap core CSS -->
    <link href="/bkt_1_dashgumfree/assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="/bkt_1_dashgumfree/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="/bkt_1_dashgumfree/assets/css/zabuto_calendar.css">
    <link rel="stylesheet" type="text/css" href="/bkt_1_dashgumfree/assets/js/gritter/css/jquery.gritter.css" />
    <link rel="stylesheet" type="text/css" href="/bkt_1_dashgumfree/assets/lineicons/style.css">

    <!-- Custom styles for this template -->
    <link href="/bkt_1_dashgumfree/assets/css/style.css" rel="stylesheet">
    <link href="/bkt_1_dashgumfree/assets/css/style-responsive.css" rel="stylesheet">

    <script src="/bkt_1_dashgumfree/assets/js/chart-master/Chart.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<section id="container" >
    <!-- **********************************************************************************************************************************************************
    TOP BAR CONTENT & NOTIFICATIONS
    *********************************************************************************************************************************************************** -->
    <!--header start-->
    <header class="header black-bg">
        @section('header')
        <div class="sidebar-toggle-box">
            <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
        </div>
        <!--logo start-->
        <a href="index.html" class="logo"><b>天外天问卷中心后台管理系统</b></a>
        <!--logo end-->
        <div class="top-menu">
            <ul class="nav pull-right top-menu">
                <li><a class="logout" href="{{ url('logout') }}">退出登录</a></li>
            </ul>
        </div>
        @show
    </header>
    <!--header end-->

    <!-- **********************************************************************************************************************************************************
    MAIN SIDEBAR MENU
    *********************************************************************************************************************************************************** -->
    <!--sidebar start-->
    <aside>
        <div id="sidebar"  class="nav-collapse ">
            <!-- sidebar menu start-->
            @section('sidebar')
            <ul class="sidebar-menu" id="nav-accordion">

                <p class="centered"><a href="profile.html"><img src="/bkt_1_dashgumfree/assets/img/ui-sam.jpg" class="img-circle" width="60"></a></p>
                <h5 class="centered">{{ $twt_name }}</h5>

                <li class="sub-menu">
                    <a href="javascript:;" >
                        <i class="fa fa-book"></i>
                        <span>问卷管理</span>
                    </a>
                    <ul class="sub">
                        <li class="active"><a  href="{{ url('admin/questionnaire/list') }}">问卷列表</a></li>
                        <li class="active"><a  href="{{ url('admin/questionnaire/deletedList') }}">已删除问卷列表</a></li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;" >
                        <i class="fa fa-cogs"></i>
                        <span>用户管理</span>
                    </a>
                    <ul class="sub">
                        <li><a  href="calendar.html">用户列表</a></li>
                        <li><a  href="gallery.html">添加用户</a></li>
                    </ul>
                </li>

            </ul>
            @show
            <!-- sidebar menu end-->
        </div>
    </aside>
    <!--sidebar end-->

    <!-- **********************************************************************************************************************************************************
    MAIN CONTENT
    *********************************************************************************************************************************************************** -->
    <!--main content start-->
    <section id="main-content">
        @yield('content')
    </section>

    <!--main content end-->
</section>

<!-- js placed at the end of the document so the pages load faster -->
<script src="/bkt_1_dashgumfree/assets/js/jquery.js"></script>
<script src="/bkt_1_dashgumfree/assets/js/jquery-1.8.3.min.js"></script>
<script src="/bkt_1_dashgumfree/assets/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="/bkt_1_dashgumfree/assets/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="/bkt_1_dashgumfree/assets/js/jquery.scrollTo.min.js"></script>
<script src="/bkt_1_dashgumfree/assets/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="/bkt_1_dashgumfree/assets/js/jquery.sparkline.js"></script>


<!--common script for all pages-->
<script src="/bkt_1_dashgumfree/assets/js/common-scripts.js"></script>

<script type="text/javascript" src="/bkt_1_dashgumfree/assets/js/gritter/js/jquery.gritter.js"></script>
<script type="text/javascript" src="/bkt_1_dashgumfree/assets/js/gritter-conf.js"></script>

<!--script for this page-->
<script src="/bkt_1_dashgumfree/assets/js/sparkline-chart.js"></script>
<script src="/bkt_1_dashgumfree/assets/js/zabuto_calendar.js"></script>

<script type="application/javascript">
    $(document).ready(function () {
        $("#date-popover").popover({html: true, trigger: "manual"});
        $("#date-popover").hide();
        $("#date-popover").click(function (e) {
            $(this).hide();
        });

        $("#my-calendar").zabuto_calendar({
            action: function () {
                return myDateFunction(this.id, false);
            },
            action_nav: function () {
                return myNavFunction(this.id);
            },
            ajax: {
                url: "show_data.php?action=1",
                modal: true
            },
            legend: [
                {type: "text", label: "Special event", badge: "00"},
                {type: "block", label: "Regular event", }
            ]
        });
    });


    function myNavFunction(id) {
        $("#date-popover").hide();
        var nav = $("#" + id).data("navigation");
        var to = $("#" + id).data("to");
        console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
    }
</script>


</body>
</html>
