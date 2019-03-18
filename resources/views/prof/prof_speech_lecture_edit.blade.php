@extends('../layouts/master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">本校教師境內外演講、研習或講學活動</h1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<form action="{{ url('prof_speech_lecture',$id) }}" method="post">
					{{ method_field('PATCH') }}
					{{ csrf_field() }}
					@include("../layouts/select_edit")
						@if($errors->has('name'))
                            <p class="text-danger">{{ $errors->first('name') }}</p>
                        @endif
						<div class="form-group">
							<label for="">姓名</label>
							<input type="text" class="form-control" name="name" value="{{ $name }}">
						</div>
						<div class="form-group">
							<label for="profLevel">身分</label>
							<select name="profLevel" id="profLevel" class="form-control">
								<option value="1">教授</option>
								<option value="2">副教授</option>
								<option value="3">助理教授</option>
								<option value="4">博士後研究員</option>
								{{-- <option value="5">研究生</option> --}}
							</select>
						</div>

						@if($errors->has('lecture'))
                            <p class="text-danger">{{ $errors->first('lecture') }}</p>
                        @endif
						<div class="form-group">
							<label for="lecture">演講、研習活動或講學</label>
							<textarea name="lecture" id="lecture" cols="30" rows="3" class="form-control">{{ $lecture }}</textarea>
						</div>

						<div class="form-group">
							<label for="isForeign">境（內）外</label>
							<select name="isForeign" id="isForeign" class="form-control">
								<option value="0">境內</option>
								<option value="1">境外</option>
							</select>
						</div>

						@if($errors->has('place'))
                            <p class="text-danger">{{ $errors->first('place') }}</p>
                        @endif
						<div class="form-group">
							<label for="place">地點</label>
							<textarea name="place" id="place" cols="30" rows="3" class="form-control">{{ $place }}</textarea>
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
	document.getElementById('profLevel').value = {{ $profLevel }};
	document.getElementById('isForeign').value = {{ $isForeign }};
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