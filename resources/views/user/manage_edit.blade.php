@extends('../layouts/master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">單位資料修改</h1>
	</div>
</div>
<div class="rows">
	<div class="panel panel-default">
		<div class="panel-body">
			<form action="{{url('manage',$id)}}" method="post">
			{{ method_field('PATCH') }}
				{{ csrf_field() }}
				@include("../layouts/select_edit")
				
				
				<div class="form-group">
					<label for="username">使用者名稱</label>
					<input type="text" name="username" class="form-control" value="{{$username}}" disabled>
				</div>
				
				@if($errors->has("password"))
					<p class="text-danger">{{$errors->first('password')}}</p>
				@endif
				<div class="form-group">
					<label for="password">使用者密碼</label>
					<input type="password" name="password" class="form-control">
				</div>

				<div class="form-group">
					<label for="password" >確認密碼</label>
					<input type="password" class="form-control" name="password_confirmation">
				</div>

				
				<div class="form-group">
					<label for="contactPeople">聯絡人</label>
					<input type="text" name="contactPeople" class="form-control" value="{{$contactPeople}}">
				</div>

				
				<div class="form-group">
					<label for="phone">電話</label>
					<input type="text" class="form-control" name="phone"
						value="{{$phone}}"></input>
				</div>

				
				<div class="form-group">
					<label for="email">電子信箱</label>
					<input type="text" class="form-control" name="email"
						value="{{$email}}"></input>
				</div>
				
				<div class="form-group">
					<label for="permission">權限</label>
					<select name="permission" id="permission_option" 
						class="form-control">
						<option value="1">所有權限</option>
						<option value="2">院級權限</option>
						<option value="3">系級權限</option>
						<option value="4" selected>無權限</option>
					</select>
				</div>
				<button class="btn btn-success">修改</button>
			</form>
		</div>
	</div>
</div>
<script>
	document.getElementById('permission_option').value = {{$permission}};
</script>
@endsection