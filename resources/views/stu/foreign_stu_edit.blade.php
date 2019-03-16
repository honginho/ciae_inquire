@extends('../layouts/master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">修讀正式學位之外國學生資料修改</h1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">	
		<div class="panel panel-default">
			<div class="panel-body">
				<form action="{{url('foreign_stu',$id)}}" method="post">
				{{ method_field('PATCH') }}
					{{ csrf_field() }}
					@include("../layouts/select_edit")
						@if($errors->has('stuID'))
                        <p class="text-danger">{{$errors->first('stuID')}}</p>
                        @endif
						<div class="form-group">
							<label for="stuID">學號</label>
							<input type="text" name="stuID" class="form-control" value="{{$stuID}}">
						</div>

						@if($errors->has('chtName'))
                            <p class="text-danger">{{$errors->first('chtName')}}</p>
                        @endif
						<div class="form-group">
							<label for="">中文姓名</label>
							<input type="text" class="form-control" name="chtName" value="{{$chtName}}">
						</div>

						@if($errors->has('engName'))
                            <p class="text-danger">{{$errors->first('engName')}}</p>
                        @endif
						<div class="form-group">
							<label for="">英文姓名</label>
							<input type="text" class="form-control" name="engName" value="{{$engName}}">
						</div>

						<div class="form-group">
							<label for="stuLevel">身分</label>
							<select name="stuLevel" id="stuLevel" class="form-control">
								<option value="1">博士班</option>
								<option value="2">碩士班</option>
								<option value="3">學士班</option>
							</select>
						</div>

						@if($errors->has('nation'))
                            <p class="text-danger">{{$errors->first('nation')}}</p>
                        @endif
						<div class="form-group">
							<label for="nation">國籍(中文)</label>
							<input type="text" name="nation" class="form-control" value="{{$nation}}">
						</div>


						@if($errors->has('nation'))
                            <p class="text-danger">{{$errors->first('nation')}}</p>
                        @endif
						<div class="form-group">
							<label for="engNation">國籍(英文)</label>
							<input type="text" name="engNation" class="form-control" value="{{$nation}}">
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

						<div class="form-group">
							<label for="status">學籍狀態</label>
							<select name="status" id="status" class="form-control">
								<option value="1">在學中</option>
								<option value="2">休學中</option>
								<option value="3">已畢業</option>
							</select>
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
<script>
	document.getElementById('stuLevel').value = {{$stuLevel}};
	document.getElementById('status').value = {{$status}};
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