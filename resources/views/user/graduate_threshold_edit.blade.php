@extends('../layouts/master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">英檢畢業門檻資料修改</h1>
	</div>
</div>
<div class="rows">
	<form action="{{url('graduate_threshold',$id)}}" method="post">
	{{ method_field('PATCH') }}
		{{ csrf_field() }}
		@include("../layouts/select_edit")
		<div class="form-group">
			<label for="testName">語言測驗名稱</label>
			<input type="text" name="testName" class="form-control"
				value="{{$testName}}"></input>
		</div>
		<div class="form-group">
			<label for="testGrade">等級或分數</label>
			<input type="text" name="testGrade" class="form-control"
				value="{{$testGrade}}">
		</div>
		<div class="form-group">
			<label for="comments">備註</label>
			<textarea type="text" class="form-control" name="comments">{{$comments}}</textarea>
		</div>

		<button class="btn btn-success">修改</button>
	</form>
</div>
@endsection