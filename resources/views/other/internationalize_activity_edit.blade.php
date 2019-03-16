@extends('../layouts/master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">國際化活動資料修改</h1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">	
		<div class="panel panel-default">
			<div class="panel-body">
				<form action="{{url('internationalize_activity',$id)}}" method="post">
				{{ method_field('PATCH') }}
					{{ csrf_field() }}
					@include("../layouts/select_edit")

					@if($errors->has('activityName'))
                        <p class="text-danger">{{$errors->first('activityName')}}</p>
                    @endif
					<div class="form-group">
						<label for="activityName">活動性質</label>
						<input type="text" class="form-control" name="activityName" value="{{$activityName}}" />
					</div>

					@if($errors->has('place'))
                        <p class="text-danger">{{$errors->first('place')}}</p>
                    @endif
					<div class="form-group">
						<label for="place">活動地點</label>
						<input type="text" class="form-control" name="place" value="{{$place}}" />
					</div>

					@if($errors->has('host'))
                        <p class="text-danger">{{$errors->first('host')}}</p>
                    @endif
					<div class="form-group">
						<label for="host">本校參加人員</label>
						<textarea name="host" id="host" cols="30" rows="3" class="form-control">{{$host}}</textarea>
					</div>

					@if($errors->has('guest'))
                        <p class="text-danger">{{$errors->first('guest')}}</p>
                    @endif
					<div class="form-group">
						<label for="guest">參加之外賓</label>
						<textarea name="guest" id="guest" cols="30" rows="3" class="form-control">{{$guest}}</textarea>
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