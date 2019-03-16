@extends('../layouts/master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<h1 class="page-header"><strong>個人資料</strong></h1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong> {{ session('success') }}</strong>
        </div>
        @endif
		<div class="panel panel-default">
			<div class="panel-heading"><b>個人資料</b></div>
			<div class="panel-body">
				<div class="row">
                    <div class="col-md-12">

                       
                        <div class="form-group">
                            <label for="username" >使用者名稱</label>
                            <input type="text" class="form-control" name="username" 
                            disabled value="{{Auth::user()->username}}">
                        </div>
                    
                    
                        <div class="form-group">
                            <label for="username" >單位</label>
                            <input type="text" class="form-control" name="username" 
                            disabled value="{{$collegeData->chtCollege}}">
                        </div>

                        <div class="form-group">
                            <label for="username" >系所部門</label>
                            <input type="text" class="form-control" name="username" 
                            disabled value="{{$collegeData->chtDept}}">
                        </div>
                        
                        <!--change page-->

    					<form role="form" action="{{url('/user')}}" method="post">
                        {{ method_field('PATCH') }}
                        {{ csrf_field() }}
                            <input type="text" hidden name="id" value="{{Auth::user()->id}}">

                            @if($errors->has('password'))
                                <p class="text-danger">{{ $errors->first('password') }}</p>
                            @endif
                            <div class="form-group">
                                <label for="username" >密碼</label>
                                <input type="password" class="form-control" name="password">
                                
                            </div>
                            <div class="form-group">
                                <label for="username" >確認密碼</label>
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>

                            @if($errors->has('contactPeople'))
                                <p class="text-danger">{{ $errors->first('contactPeople')}}</p>
                            @endif
                            <div class="form-group">
                                <label for="">聯絡人</label>
                                <input type="text" class="form-control" name="contactPeople"
                                value="{{Auth::user()->contactPeople}}">
                            </div>

                             @if($errors->has('phone'))
                                <p class="text-danger">{{ $errors->first('phone') }}</p>
                            @endif
                            <div class="form-group">
                                <label for="">電話</label>
                                <input type="text" class="form-control" name="phone"
                                value="{{Auth::user()->phone}}">
                            </div>

                             @if($errors->has('email'))
                                <p class="text-danger">{{ $errors->first('email') }}</p>
                            @endif
                            <div class="form-group">
                                <label for="">email</label>
                                <input type="email" class="form-control" name="email"
                                value="{{Auth::user()->email}}">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success">修改</button>
                            </div>
    					</form>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
  
@endsection