@extends('template')

@section('content')

<div class="page-head">
 	<h2>公司品牌</h2>
 	<ol class="breadcrumb">
 
    	<li><a href="#">公司品牌</a></li>
    	<li class="active">添加品牌</li>
  	</ol>
</div>


<div class="row ">


	<form class="form-horizontal" name="add">
 		<div class="control-group">
    		<label class="control-label" for="company_name">公司名称</label>
    		<div class="controls">
    			<select name="company_id" id="company_id">
					@foreach ($company as $c_item)
						<option value="{{$c_item->id}}" @if ($c_item->id == $company_id) selected="selected" @endif >{{$c_item->company_name}}</option>
					@endforeach
    			</select>
    		</div>
  		</div>

  		<div class="control-group">
    		<label class="control-label" for="company_sign">品牌名称</label>
    		<div class="controls">
    			<input type="text" id="company_sign" name="company_sign" placeholder="填写品牌名称">
    		</div>
  		</div>
		
		<div class="control-group">
			<label class="control-label" for=""></label>
			<div class="controls">
  				<input type="button" class="sign_add btn btn-primary" value="添加品牌">
  			</div>
		</div>
	</form>
</div>

@stop


@section('script')

<script>

	$('.sign_add').click(function () {

		var sign = {};

		var company_id = $('select[name=company_id]').find('option:selected').val();
		var company_sign = $('input[name=company_sign]').val();
	
		if (!company_id || !company_sign) {
			return window.wxc.xcConfirm('公司名称与品牌名称必填', window.wxc.xcConfirm.typeEnum.info);
		}

		sign.company_id = company_id;
		sign.company_sign = company_sign;



		var send_data = {
			
			'data': {
				'sign': sign,
			}
		};

		var txt= "确定添加品牌？";
		var option = {
			title: "添加品牌",
			btn: parseInt("0011",2),
			onOk: function(){

				$.post('/sign/add/data', send_data, function (data) {

					if (data.status == 1) {

						window.wxc.xcConfirm(data.message, window.wxc.xcConfirm.typeEnum.success);
						setTimeout(function () {
							window.location.href = '/sign/list?id=' + data.sign_id;
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