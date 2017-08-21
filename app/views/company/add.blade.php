@extends('template')

@section('content')

<div class="page-head">
 	<h2>合作公司</h2>
 	<ol class="breadcrumb">
 
    	<li><a href="#">合作公司</a></li>
    	<li class="active">添加合作公司</li>
  	</ol>
</div>


<div class="row ">


	<form class="form-horizontal" name="add">
 		<div class="control-group">
    		<label class="control-label" for="company_name">公司名称</label>
    		<div class="controls">
    			<input type="text" id="company_name" name="company_name" placeholder="填写公司名称">
    		</div>
  		</div>

  		<div class="control-group">
    		<label class="control-label" for="company_faren">公司法人</label>
    		<div class="controls">
    			<input type="text" id="company_faren" name="company_faren" placeholder="填写公司法人">
    		</div>
  		</div>
		
		<div class="control-group">
			<label class="control-label" for=""></label>
			<div class="controls">
  				<input type="button" class="company_add btn btn-primary" value="添加合作公司">
  			</div>
		</div>
	</form>
</div>

@stop


@section('script')

<script>

	$('.company_add').click(function () {

		var company = {};

		var company_name = $('input[name=company_name]').val();
		var company_faren = $('input[name=company_faren]').val();
	
		if (!company_name || !company_faren) {
			return window.wxc.xcConfirm('公司名称与公司法人必填', window.wxc.xcConfirm.typeEnum.info);
		}

		company.company_name = company_name;
		company.company_faren = company_faren;



		var send_data = {
			
			'data': {
				'company': company,
			}
		};

		var txt= "确定添加公司？";
		var option = {
			title: "添加公司",
			btn: parseInt("0011",2),
			onOk: function(){

				$.post('/company/add/data', send_data, function (data) {

					if (data.status == 1) {

						window.wxc.xcConfirm(data.message, window.wxc.xcConfirm.typeEnum.success);
						setTimeout(function () {
							window.location.href = '/?id='+ data.company_id;
						}, 800);

					} else {
						return window.wxc.xcConfirm(data.message, window.wxc.xcConfirm.typeEnum.error);
					}

				});

			},
		}

		window.wxc.xcConfirm(txt, "custom", option);

	});

</script>

@stop