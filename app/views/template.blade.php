<!DOCTYPE html>
<html lang="en">
  
<!-- Mirrored from condorthemes.com/cleanzone/form-elements.html by HTTrack Website Copier/3.x [XR&CO'2013], Mon, 31 Mar 2014 14:35:05 GMT -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/images/favicon.png">
    
    <title>伊丽文管理系统</title>
    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Slider -->
	<link rel="stylesheet" type="text/css" href="/js/bootstrap.slider/css/slider.css" />
  
  
  <!-- Custom styles for this template -->
  <link href="/css/style.css" rel="stylesheet" />
  <link href="/js/jquery.icheck/skins/square/blue.css" rel="stylesheet">
  <link rel="stylesheet" href="/js/bootstrap.datetimepicker/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" href="/css/xcConfirm.css">
</head>


<body>



<!-- Fixed navbar -->
<div id="head-nav" style="height: 50px; line-height: 50px;" class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
     
      <a class="navbar-brand" href="#"><span>伊丽文管理系统</span></a>
      <a class="navbar-brand" href="/logout"><span>退出</span></a>
    </div>
  </div>
</div>


<div id="cl-wrapper">
  <div class="cl-sidebar">
  	<div class="cl-toggle"><i class="fa fa-bars"></i></div>
  	<div class="cl-navblock">
      <div class="menu-space">
        <div class="content">
          <div class="side-user">
            <div class="avatar"><img src="/images/avatar1_50.jpg" alt="Avatar" /></div>
            <div class="info">
              <a href="#">伊丽文</a>
              <img src="/images/state_online.png" alt="Status" /> <span>线上办公</span>
            </div>
          </div>
          <ul class="cl-vnavigation">
            @include('silde')     
          </ul>
        </div>
      </div>
      <div class="text-right collapse-button" style="padding:7px 9px;">
        <button id="sidebar-collapse" class="btn btn-default" style=""><i style="color:#fff;" class="fa fa-angle-left"></i></button>
      </div>
  	</div>
  </div>
  
  <style>
    #pcont {padding: 0 20px;}
  </style>
  <div class="container-fluid" id="pcont">

    	@yield('content')

  </div> 

  

</div>  
</body>


</html>
<script src="/js/jquery.js"></script>
<script src="/js/masonry-docs.min.js" type="text/javascript"></script>
<!-- <script src="/js/jquery.select2/select2.min.js" type="text/javascript"></script> -->
<!-- <script src="/js/jquery.parsley/dist/parsley.js" type="text/javascript"></script> -->
<script src="/js/bootstrap.slider/js/bootstrap-slider.js" type="text/javascript"></script>
<!-- <script type="text/javascript" src="/js/jquery.nanoscroller/jquery.nanoscroller.js"></script> -->
<!-- <script type="text/javascript" src="/js/jquery.nestable/jquery.nestable.js"></script> -->
<script type="text/javascript" src="/js/behaviour/general.js"></script>
<!-- <script src="/js/jquery.ui/jquery-ui.js" type="text/javascript"></script> -->
<!-- <script type="text/javascript" src="/js/bootstrap.switch/bootstrap-switch.js"></script> -->
<script type="text/javascript" src="/js/bootstrap.datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js"></script>
<script type="text/javascript" src="/js/jquery.icheck/icheck.min.js"></script>
<!-- <script type="text/javascript" src="/js/bootstrap.daterangepicker/moment.min.js"></script> -->
<!-- <script type="text/javascript" src="/js/bootstrap.daterangepicker/daterangepicker.js"></script> -->
<script src="/js/xcConfirm.js" type="text/javascript" charset="utf-8"></script>
<script src='/js/command.js' type="text/javascript"></script>


<script type="text/javascript">
  $(document).ready(function(){
    //initialize the javascript
    App.init();

  });


</script>
<script>

function loadDiv(text) {
     var div = "<div id='_layer_'> <div id='_MaskLayer_' style='filter: alpha(opacity=30); -moz-opacity: 0.3; opacity: 0.3;background-color: #000; width: 100%; height: 100%; z-index: 2147000001; position: fixed;" + "left: 0; top: 0; overflow: hidden; display: none'></div><div id='_wait_' style='z-index: 2147000002; position: fixed; width:430px;height:218px; display: none'  ><center><h3>" + "" + text + "<img src='/images/loading.gif' width=80 height=80 /></h3><button style='display: none;' class='btn btn-danger' onclick='LayerHide()'>关闭</button></center></div></div>"; 
   return div; 
}

function LayerShow(text) {
    var addDiv= loadDiv(text);  
    var element = $(addDiv).appendTo(document.body);     $(window).resize(Position);  
    var deHeight = $(document).height();    
    var deWidth = $(document).width();    
    Position();     
    $("#_MaskLayer_").show();   
    $("#_wait_").show();
}

function Position() {  
    $("#_MaskLayer_").width($(document).width());   
    var deHeight = $(window).height();     
    var deWidth = $(window).width();     
    $("#_wait_").css({ left: (deWidth - $("#_wait_").width()) / 2 + "px", top: (deHeight - $("#_wait_").height()) / 2 + "px" }); 
}

function LayerHide() { 
    $("#_MaskLayer_").hide(); 
    $("#_wait_").hide(); 
    del(); 
}

function del() { 
    var delDiv = document.getElementById("_layer_");     delDiv.parentNode.removeChild(delDiv); 
}
</script>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
  <!-- <script src="/js/behaviour/voice-commands.js"></script> -->
<script src="/js/bootstrap/dist/js/bootstrap.min.js"></script>
@yield('script')
