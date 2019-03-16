@extends('../layouts/master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">全外語授課之課程資料修改</h1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">	
		<div class="panel panel-default">
			<div class="panel-body">
				<form action="{{url('foreign_language_class',$id)}}" method="post">
				{{ method_field('PATCH') }}
					{{ csrf_field() }}
					@include("../layouts/select_edit")
					<div class="form-group">
						<label for="">學年</label>
						<input type="number" name="year" class="form-control" 
							value="{{$year}}">
					</div>
					<div class="form-group">
						<label for="semester">學期</label>
						<select name="semester" id="semester" class="form-control">
							<option value="1">上學期</option>
							<option value="2">下學期</option>
						</select>
					</div>
					<div class="form-group">
						<label for="">課程中文名稱</label>
						<input type="text"  name="chtName" class="form-control" 
							value="{{$chtName}}"></input>
					</div>
					<div class="form-group">
						<label for="engName">課程英文名稱</label>
						<input type="text" name="engName" class="form-control"
							value="{{$engName}}">
					</div>
					<div class="form-group">
						<label for="teacher">授課教師</label>
						<input type="text" name="teacher" class="form-control"
							value="{{$teacher}}">
					</div>
					<div class="form-group">
						<label for="language">授課語言</label>
						<input type="text" name="language" class="form-control"
							value="{{$language}}">
					</div>
					<div class="form-group">
						<label for="totalCount">總人數</label>
						<input type="number" name="totalCount" class="form-control" 
							value="{{$totalCount}}">
					</div>
					<div class="form-group">
						<label for="nationalCount">外籍生人數</label>
						<input type="number" name="nationalCount" class="form-control" 
							value="{{$nationalCount}}">
					</div>

					<button class="btn btn-success">修改</button>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	document.getElementById('semester').value = {{$semester}};
</script>
@endsection