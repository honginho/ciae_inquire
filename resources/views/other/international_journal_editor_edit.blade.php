@extends('../layouts/master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">擔任國籍組織編輯資料修改</h1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">	
		<div class="panel panel-default">
			<div class="panel-body">
				<form action="{{url('international_journal_editor',$id)}}" method="post">
				{{ method_field('PATCH') }}
					{{ csrf_field() }}
					@include("../layouts/select_edit")

					@if($errors->has('name'))
                        <p class="text-danger">{{$errors->first('name')}}</p>
                        @endif
						<div class="form-group">
							<label for="name">期刊編輯者姓名</label>
							<input type="text" class="form-control" name="name" value="{{$name}}">
						</div>

						@if($errors->has('journalName'))
                            <p class="text-danger">{{$errors->first('journalName')}}</p>
                        @endif
						<div class="form-group">
							<label for="journalName">期刊名稱</label>
							<input type="text" name="journalName" class="form-control" value="{{$journalName}}">
						</div>
						
						@if($errors->has('startDate')||$errors->has('endDate'))
                            <p class="text-danger col-md-6">{{ $errors->first('startDate')}}</p>                      
                            <p class="text-danger col-md-6">{{ $errors->first('endDate')}}</p>
                        @endif
						<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
							<label for="startDate">開始擔任時間</label>
							<input type="text" name="startDate" class="form-control" value="{{$startDate}}" id="edit_startDate">
						</div>
						<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
							<label for="endDate">結束擔任時間</label>
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


