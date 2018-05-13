
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html lang="en" style="overflow: hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>天津大学心理健康教育中心后台</title>

    <!-- Bootstrap core CSS -->
    <link href="/assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="/assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/assets/css/zabuto_calendar.css">
    <link rel="stylesheet" type="text/css" href="/assets/js/gritter/css/jquery.gritter.css">
    <link rel="stylesheet" type="text/css" href="/assets/lineicons/style.css">
    <link rel="stylesheet" href="/Trumbowyg/dist/ui/trumbowyg.min.css">
    <!-- Custom styles for this template -->
    <link href="/assets/css/style.css" rel="stylesheet">
    <link href="/assets/css/style-responsive.css" rel="stylesheet">

    <script src="/assets/js/chart-master/Chart.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">.jqstooltip { position: absolute;left: 0px;top: 0px;display: block;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;border: 1px solid white;z-index: 10000;}.jqsfield { color: white;padding: 5px 5px 8px 5px;font: 10px arial, san serif;text-align: left;}</style></head>
<body style="">

<section id="container">
    <!--sidebar start-->
    <aside>
        <div id="sidebar" class="nav-collapse " tabindex="5000" style="overflow: hidden; outline: none;">
            <!-- sidebar menu start-->
            <ul class="sidebar-menu" id="nav-accordion">

                <p class="centered"><a href="profile.html"><img src="/assets/img/ui-sam.jpg" class="img-circle" width="60"></a></p>
                <h5 class="centered">心理中心后台</h5>

                <li class="sub-menu dcjq-parent-li">
                    <a href="javascript:;" class="dcjq-parent">
                        <i class="fa fa-desktop"></i>
                        <span>新闻公告</span>
                        <span class="dcjq-icon"></span></a>
                    <ul class="sub" style="display: none;">
                        <li><a href="/manager/list_view/picnews">新闻列表</a></li>
                        <li><a href="/manager/add_view/picnews">添加新闻</a></li>
                        <li><a href="/manager/list_viewnp/notice">公告列表</a></li>
                        <li><a href="/manager/add_viewnp/notice">添加公告</a></li>
                    </ul>
                </li>

                <li class="sub-menu dcjq-parent-li">
                    <a href="javascript:;" class="dcjq-parent">
                        <i class="fa fa-cogs"></i>
                        <span>简介</span>
                        <span class="dcjq-icon"></span></a>
                    <ul class="sub" style="display: none;">
                        <li><a href="/manager/list_view/info/1">中心简介</a></li>
                        <li><a href="/manager/add_view/info/1">添加中心简介</a></li>
                        <li><a href="/manager/list_view/info/2">心协简介</a></li>
                        <li><a href="/manager/add_view/info/2">添加心协简介</a></li>
                        <li><a href="/manager/list_view/info/3">成员介绍</a></li>
                        <li><a href="/manager/add_view/info/3">添加成员介绍</a></li>
                    </ul>
                </li>
                <li class="sub-menu dcjq-parent-li">
                    <a href="javascript:;" class="dcjq-parent">
                        <i class="fa fa-book"></i>
                        <span>咨询导航栏</span>
                        <span class="dcjq-icon"></span></a>
                    <ul class="sub" style="display: none;">
                        <li><a href="/manager/contentnp/daohang/1">服务范围</a></li>
                        <li><a href="/manager/contentnp/daohang/2">来访须知</a></li>
                        <li><a href="/manager/contentnp/daohang/3">预约方式</a></li>
                        <li><a href="/manager/contentnp/daohang/4">专家介绍</a></li>
                    </ul>
                </li>


            </ul>
            <!-- sidebar menu end-->
        </div>
    </aside>
    <!--sidebar end-->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            @yield('content')
        </section>
    </section>

    <!--main content end-->

</section>

{{--<!-- js placed at the end of the document so the pages load faster -->--}}
{{--<script src="/assets/js/jquery.js"></script>--}}
{{--<script src="/assets/js/jquery-1.8.3.min.js"></script>--}}
{{--<script src="/assets/js/bootstrap.min.js"></script>--}}
{{--<script class="include" type="text/javascript" src="/assets/js/jquery.dcjqaccordion.2.7.js"></script>--}}
{{--<script src="/assets/js/jquery.scrollTo.min.js"></script>--}}
{{--<script src="/assets/js/jquery.nicescroll.js" type="text/javascript"></script>--}}
{{--<script src="/assets/js/jquery.sparkline.js"></script>--}}


{{--<!--common script for all pages-->--}}
{{--<script src="/assets/js/common-scripts.js"></script><div id="ascrail2000" class="nicescroll-rails" style="width: 3px; z-index: auto; background: rgb(64, 64, 64); cursor: default; position: fixed; top: 0px; left: 207px; height: 759px; display: none; opacity: 0;"><div style="position: relative; top: 0px; float: right; width: 3px; height: 0px; background-color: rgb(78, 205, 196); background-clip: padding-box; border-radius: 10px;"></div></div><div id="ascrail2000-hr" class="nicescroll-rails" style="height: 3px; z-index: auto; background: rgb(64, 64, 64); top: 756px; left: 0px; position: fixed; cursor: default; display: none; opacity: 0;"><div style="position: relative; top: 0px; height: 3px; width: 0px; background-color: rgb(78, 205, 196); background-clip: padding-box; border-radius: 10px; left: 0px;"></div></div><div id="ascrail2001" class="nicescroll-rails" style="width: 6px; z-index: 1000; background: rgb(64, 64, 64); cursor: default; position: fixed; top: 0px; height: 100%; right: 0px; opacity: 0;"><div style="position: relative; top: 590px; float: right; width: 6px; height: 169px; background-color: rgb(78, 205, 196); background-clip: padding-box; border-radius: 10px;"></div></div><div id="ascrail2001-hr" class="nicescroll-rails" style="height: 6px; z-index: 1000; background: rgb(64, 64, 64); position: fixed; left: 0px; width: 100%; bottom: 0px; cursor: default; display: block; opacity: 0;"><div style="position: relative; top: 0px; height: 6px; width: 966px; background-color: rgb(78, 205, 196); background-clip: padding-box; border-radius: 10px; left: 0px;"></div></div>--}}

{{--<script type="text/javascript" src="/assets/js/gritter/js/jquery.gritter.js"></script>--}}
{{--<script type="text/javascript" src="/assets/js/gritter-conf.js"></script>--}}

{{--<!--script for this page-->--}}
{{--<script src="/assets/js/sparkline-chart.js"></script>--}}
{{--<script src="/assets/js/zabuto_calendar.js"></script>--}}



{{--<script type="application/javascript">--}}
    {{--$(document).ready(function () {--}}
        {{--$("#date-popover").popover({html: true, trigger: "manual"});--}}
        {{--$("#date-popover").hide();--}}
        {{--$("#date-popover").click(function (e) {--}}
            {{--$(this).hide();--}}
        {{--});--}}

        {{--$("#my-calendar").zabuto_calendar({--}}
            {{--action: function () {--}}
                {{--return myDateFunction(this.id, false);--}}
            {{--},--}}
            {{--action_nav: function () {--}}
                {{--return myNavFunction(this.id);--}}
            {{--},--}}
            {{--ajax: {--}}
                {{--url: "show_data.php?action=1",--}}
                {{--modal: true--}}
            {{--},--}}
            {{--legend: [--}}
                {{--{type: "text", label: "Special event", badge: "00"},--}}
                {{--{type: "block", label: "Regular event", }--}}
            {{--]--}}
        {{--});--}}
    {{--});--}}


    {{--function myNavFunction(id) {--}}
        {{--$("#date-popover").hide();--}}
        {{--var nav = $("#" + id).data("navigation");--}}
        {{--var to = $("#" + id).data("to");--}}
        {{--console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);--}}
    {{--}--}}
{{--</script>--}}

@yield('js')

</body>
</html>


