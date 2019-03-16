 @extends('../layouts/master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">跨國學位資料修改</h1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">	
		<div class="panel panel-default">
			<div class="panel-body">
				<form action="{{url('transnational_degree',$id)}}" method="post">
				{{ method_field('PATCH') }}
					{{ csrf_field() }}
					@include("../layouts/select_edit")

					@if($errors->has('nation'))
                    <p class="text-danger">{{$errors->first('nation')}}</p>
                    @endif
					<div class="form-group">
						<label for="nation">國家</label>
						<input type="text" name="nation" class="form-control" value="{{$nation}}">
					</div>

					@if($errors->has('chtName'))
                        <p class="text-danger">{{$errors->first('chtName')}}</p>
                    @endif
					<div class="form-group">
						<label for="">中文校名</label>
						<input type="text" class="form-control" name="chtName" value="{{$chtName}}">
					</div>

					@if($errors->has('engName'))
                        <p class="text-danger">{{$errors->first('engName')}}</p>
                    @endif
					<div class="form-group">
						<label for="">英文校名</label>
						<input type="text" class="form-control" name="engName" value="{{$engName}}">
					</div>

					<div class="form-group">
						<label for="stuLevel">授予身分</label>
						<select name="stuLevel" id="stuLevel_option" class="form-control">
							<option value="1">博士班</option>
							<option value="2">碩士班</option>
							<option value="3">學士班</option>
						</select>
					</div>

					@if($errors->has('year'))
                        <p class="text-danger">{{$errors->first('year')}}</p>
                    @endif
					<div class="form-group">
						<label for="year">修業年限</label>
						<textarea name="year" id="year" cols="30" rows="3" class="form-control">{{$year}}</textarea>
					</div>

					@if($errors->has('classMode'))
                        <p class="text-danger">{{$errors->first('classMode')}}</p>
                    @endif
					<div class="form-group">
						<label for="classMode">授課方式</label>
						<textarea type="text" name="classMode" class="form-control">{{$classMode}}</textarea>
					</div>

					@if($errors->has('degreeMode'))
                        <p class="text-danger">{{$errors->first('degreeMode')}}</p>
                    @endif
					<div class="form-group">
						<label for="degreeMode">學位授予方式</label>
						<textarea name="degreeMode" id="degreeMode" cols="30" rows="3" class="form-control">{{$degreeMode}}</textarea>
					</div>
					
					@if($errors->has('comments'))
                        <p class="text-danger">{{$errors->first('comments')}}</p>
                    @endif
					<div class="form-group">
						<label for="comments">備註</label>
						<textarea name="comments" id="comments" cols="30" rows="3" class="form-control">{{$comments}}</textarea>
					</div>

					<button class="btn btn-success">修改</button>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#edit_startDate").datepicker({
		format: 'yyyy-mm-dd',
		setDate: "{{$startDate}}",
	})
	$("#edit_endDate").datepicker({
		format: 'yyyy-mm-dd',
		setDate: "{{$endDate}}",
	})
</script>
@endsection