@extends('../layouts/master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">辦理國際及兩岸學術研討會修改</h1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<form action="{{ url('hold_international_conference',$id) }}" method="post">
					{{ method_field('PATCH') }}
					{{ csrf_field() }}

					@if($errors->has('academicYear'))
                        <p class="text-danger">{{ $errors->first('academicYear') }}</p>
                    @endif
					<div class="form-group">
						<label for="academicYear">學年度</label>
						<input type="text" class="form-control" name="academicYear" value="{{ $academicYear }}">
					</div>

					@include("../layouts/select_edit")

					@if($errors->has('holdWayId'))
                        <p class="text-danger">{{ $errors->first('holdWayId') }}</p>
                    @endif
					<div class="form-group">
						<label for="holdWayId">舉辦方式代碼</label>
						<input type="text" class="form-control" name="holdWayId" value="{{ $holdWayId }}">
					</div>

					@if($errors->has('holdWay'))
                        <p class="text-danger">{{ $errors->first('holdWay') }}</p>
                    @endif
					<div class="form-group">
						<label for="holdWay">舉辦方式</label>
						<textarea name="holdWay" id="holdWay" cols="30" rows="3" class="form-control">{{ $holdWay }}</textarea>
					</div>

					@if($errors->has('confName'))
                        <p class="text-danger">{{ $errors->first('confName') }}</p>
                    @endif
					<div class="form-group">
						<label for="confName">會議名稱</label>
						<textarea name="confName" id="confName" cols="30" rows="3" class="form-control">{{ $confName }}</textarea>
					</div>

					@if($errors->has('confHoldNationId'))
                        <p class="text-danger">{{ $errors->first('confHoldNationId') }}</p>
                    @endif
					<div class="form-group">
						<label for="confHoldNationId">會議舉行國家/地區代碼</label>
						<input type="text" class="form-control" name="confHoldNationId" value="{{ $confHoldNationId }}">
					</div>

					@if($errors->has('startDate')||$errors->has('endDate'))
                        <p class="text-danger col-md-6">{{ $errors->first('startDate') }}</p>
                        <p class="text-danger col-md-6">{{ $errors->first('endDate') }}</p>
                    @endif
					<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
						<label for="startDate">開始時間</label>
						<input type="text" name="startDate" class="form-control" value="{{ $startDate }}" id="edit_startDate">
					</div>
					<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
						<label for="endDate">結束時間</label>
						<input type="text" name="endDate" class="form-control" value="{{ $endDate }}" id="edit_endDate">
					</div>

					@if($errors->has('comments'))
                        <p class="text-danger">{{ $errors->first('comments') }}</p>
                    @endif
					<div class="form-group">
						<label for="comments">備註</label>
						<textarea name="comments" id="comments" cols="30" rows="3" class="form-control">{{ $comments }}</textarea>
					</div>
					<button class="btn btn-success">修改</button>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	$("#edit_startDate").datepicker({
		format: 'yyyy-mm-dd',
		setDate: "{{ $startDate }}",
	})
	$("#edit_endDate").datepicker({
		format: 'yyyy-mm-dd',
		setDate: "{{ $endDate }}",
	})
</script>
@endsection