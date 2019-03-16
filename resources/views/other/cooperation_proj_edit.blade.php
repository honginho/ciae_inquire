@extends('../layouts/master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">國際合作交流計畫資料修改</h1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">	
		<div class="panel panel-default">
			<div class="panel-body">
				<form action="{{url('cooperation_proj',$id)}}" method="post">
				{{ method_field('PATCH') }}
					{{ csrf_field() }}
					@include("../layouts/select_edit")

					@if($errors->has('name'))
                        <p class="text-danger">{{$errors->first('name')}}</p>
                    @endif
					<div class="form-group">
						<label for="">主辦人</label>
						<input type="text" class="form-control" name="name" value="{{$name}}" />
					</div>
					
					@if($errors->has('projOrg'))
                        <p class="text-danger">{{$errors->first('projOrg')}}</p>
                    @endif
					<div class="form-group">
						<label for="projOrg">合作機構</label>
						<textarea name="projOrg" id="projOrg" cols="30" rows="3" class="form-control">{{$projOrg}}</textarea>
					</div>

					@if($errors->has('projName'))
                        <p class="text-danger">{{$errors->first('projName')}}</p>
                    @endif
					<div class="form-group">
						<label for="projName">計畫名稱</label>
						<textarea name="projName" id="projName" cols="30" rows="3" class="form-control">{{$projName}}</textarea>
					</div>
					
					@if($errors->has('startDate')||$errors->has('endDate'))
                        <p class="text-danger col-md-6">{{ $errors->first('startDate')}}</p>                      
                        <p class="text-danger col-md-6">{{ $errors->first('endDate')}}</p>
                    @endif
					<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
						<label for="startDate">開始時間</label>
						<input type="text" name="startDate" class="form-control" value="{{$startDate}}" id="edit_startDate">
					</div>
					<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
						<label for="endDate">結束時間</label>
						<input type="text" name="endDate" class="form-control" value="{{$endDate}}" id="edit_endDate">
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