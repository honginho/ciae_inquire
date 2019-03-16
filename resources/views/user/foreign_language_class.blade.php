@extends('../layouts/master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">全外語授課之課程</h1>
	</div>
</div>
<div class="row">	
	<div class="col-md-12">	
		<div class="panel panel-default">
			<div class="panel-body">
			<ul class="nav nav-tabs">
               @if(count($errors)>0)
	                <li><a href="#show" data-toggle="tab">檢視</a>
	                </li>
	                <li class="active"><a href="#insert" data-toggle="tab">新增</a>
	                </li>
				@else
	                <li class="active"><a href="#show" data-toggle="tab">檢視</a>
	                </li>
	                <li><a href="#insert" data-toggle="tab">新增</a>
	                </li>
	            @endif    
                <li><a href="#search" data-toggle="tab">進階搜尋</a>
                </li>
                <li><a href="#upload" data-toggle="tab">批次上傳</a>
                </li>
            </ul>
            	<div class="tab-content">
					@if(count($errors)>0)
						<div class="tab-pane fade in table-responsive" id="show" 
							style="margin-top: 10px">
					@else
						<div class="tab-pane fade in active table-responsive" id="show" 
							style="margin-top: 10px">
					@endif
						@if(session('success'))
				        <div class="alert alert-success alert-dismissible" role="alert">
				            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				                <span aria-hidden="true">&times;</span>
				            </button>
				            <strong> {{ session('success') }}</strong>
				        </div>
			        	@endif
						<table width="100%" class="table table-striped table-bordered table-hover">
							<thead>	
								<tr>
									<td id="foreign_language_class.college"
										onclick="sort(id)">單位</td>
									<td id="foreign_language_class.dept"
										onclick="sort(id)">系所部門</td>
									<td id="year" onclick="sort(id)">學年</td>
									<td id="semester" onclick="sort(id)">學期</td>
									<td id="chtName" onclick="sort(id)">中文名稱</td>
									<td id="engName" onclick="sort(id)">英文名稱</td>
									<td id="teacher" onclick="sort(id)">教師</td>
									<td id="language" onclick="sort(id)">授課語言</td>
									<td id="totalCount" onclick="sort(id)">修課總人數</td>
									<td id="nationalCount" onclick="sort(id)">國際生人數</td>
									<td>管理</td>
								</tr>
								</thead>
								<tbody>
								@foreach($foreignLanguageClass as $data)
								<tr id="{{$data->id}}">
									<td class="text-nowrap">{{$data->chtCollege}}</td>
									<td class="text-nowrap">{{$data->chtDept}}</td>
									<td>{{$data->year}}</td>
									<td>{{$data->semester}}</td>
									<td class="text-nowrap">{{$data->chtName}}</td>
									<td>{{$data->engName}}</td>
									<td class="text-nowrap">{{$data->teacher}}</td>
									<td class="text-nowrap">{{$data->language}}</td>
									<td>{{$data->totalCount}}</td>
									<td>{{$data->nationalCount}}</td>
									<td class="text-nowrap">
										@can('permission',$data)
										<a href="{{url('foreign_language_class',$data->id)}}"
									class="glyphicon glyphicon-pencil btn btn-success btn-xs"></a>
										<form action="{{url('foreign_language_class',$data->id)}}"
											method="post" style="display: inline;">
											{{ method_field('DELETE') }}
                        					{{ csrf_field() }}
											<button class="glyphicon glyphicon-trash	
												btn btn-danger btn-xs"
												onclick="clickDel(event)"></button>
										</form>
										@endcan
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						{{$foreignLanguageClass->links()}}
					</div>

					<!--insert page-->

					@if(count($errors)>0)
						<div class="tab-pane fade in col-md-12 active " id="insert" 
							style="margin-top: 10px">
					@else
						<div class="tab-pane fade in col-md-12 " id="insert" 
							style="margin-top: 10px">
					@endif
						<form action="{{url('/foreign_language_class')}}" method="post">
                        	{{ csrf_field() }}
							@include('../layouts/select')

							@if($errors->has('year'))
                                <p class="text-danger">{{$errors->first('year')}}</p>
                            @endif
							<div class="form-group">
								<label for="">學年</label>
								<input type="number" name="year" class="form-control" value="{{old('year')}}" >
							</div>

							<div class="form-group">
								<label for="semester">學期</label>
								<select name="semester" id="semester_option" class="form-control">
									<option value="1">上學期</option>
									<option value="2">下學期</option>
								</select>
							</div>

							@if($errors->has('chtName'))
                                <p class="text-danger">{{$errors->first('chtName')}}</p>
                            @endif
							<div class="form-group">
								<label for="">課程中文名稱</label>
								<input type="text"  name="chtName" class="form-control" value="{{old('chtName')}}"></input>
							</div>

							@if($errors->has('engName'))
                                <p class="text-danger">{{$errors->first('engName')}}</p>
                            @endif
							<div class="form-group">
								<label for="engName">課程英文名稱</label>
								<input type="text" name="engName" class="form-control" value="{{old('engName')}}">
							</div>

							@if($errors->has('teacher'))
                                <p class="text-danger">{{$errors->first('teacher')}}</p>
                            @endif
							<div class="form-group">
								<label for="teacher">授課教師</label>
								<input type="text" name="teacher" class="form-control" value="{{old('teacher')}}">
							</div>

							@if($errors->has('language'))
                                <p class="text-danger">{{$errors->first('language')}}</p>
                            @endif
							<div class="form-group">
								<label for="language">授課語言</label>
								<input type="text" name="language" class="form-control" value="{{old('language')}}">
							</div>

							@if($errors->has('totalCount'))
                                <p class="text-danger">{{$errors->first('totalCount')}}</p>
                            @endif
							<div class="form-group">
								<label for="totalCount">總人數</label>
								<input type="number" name="totalCount" class="form-control" value="{{old('totalCount')}}">
							</div>

							@if($errors->has('nationalCount'))
                                <p class="text-danger">{{$errors->first('nationalCount')}}</p>
                            @endif
							<div class="form-group">
								<label for="nationalCount">外籍生人數</label>
								<input type="number" name="nationalCount" class="form-control" value="{{old('nationalCount')}}">
							</div>
							<button class="btn btn-success">新增</button>
						</form>
					</div>

					<div class="tab-pane fade in col-md-12" id="search" style="margin-top: 10px;">
						<div class="alert alert-success alert-dismissible" role="alert">
				            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				                <span aria-hidden="true">&times;</span>
				            </button>
				            <strong>不加入搜尋條件之選項留空即可</strong>
				        </div>
						<form action="{{url('foreign_language_class/search')}}" method="get">
							@include('../layouts/select_search')
							<div class="form-group">
								<label for="">學年</label>
								<input type="number" name="year" class="form-control">
							</div>
							<div class="form-group">
								<label for="semester">學期</label>
								<select name="semester" class="form-control">
									<option ></option>
									<option value="1">上學期</option>
									<option value="2">下學期</option>
								</select>
							</div>
							<div class="form-group">
								<label for="">課程中文名稱</label>
								<input type="text"  name="chtName" class="form-control"></input>
							</div>
							<div class="form-group">
								<label for="engName">課程英文名稱</label>
								<input type="text" name="engName" class="form-control">
							</div>
							<div class="form-group">
								<label for="teacher">授課教師</label>
								<input type="text" name="teacher" class="form-control">
							</div>
							<div class="form-group">
								<label for="totalCount">總人數</label>
								<input type="number" name="totalCount" class="form-control">
							</div>							
							<div class="form-group">
								<label for="nationalCount">外籍生人數</label>
								<input type="number" name="nationalCount" class="form-control">
							</div>
							<button class="btn btn-success">搜尋</button>
						</form>
					</div>

					<div class="tab-pane fade in col-md-12" id="upload" style="margin-top: 10px;">
						<form action="{{url('foreign_language_class/upload')}}" method="post" enctype="multipart/form-data" >
							{{ csrf_field() }}
							<input type="file" name="file" class="" style="margin: 2px">
							<button class="btn btn-primary" style="margin: 2px"
							   onclick="checkFile(event)" >上傳</button>
							<a class="btn btn-success" href="{{url('foreign_language_class/example')}}">範例檔案</a>
							<a class="btn btn-success" href="{{url('example')}}">系所對照表</a>
						</form>
						
						<div class="alert alert-warning" style="margin-top:10px">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<strong>上傳注意事項</strong>
							<ul>
								<li>請下載範例檔案填寫</li>	
								<li>請將系所欄位依照系所對照表之代號填入</li>
								<li>其餘欄位若有限制請參照該欄位括弧中選項填入</li>
							</ul>
						</div>

					</div>

				</div>
			</div>
		</div>
	</div>
</div>
@if(count($errors)>0)
	<script>
		document.getElementById('semester_option').value = {{old('semester')}};
	</script>
@endif
@endsection