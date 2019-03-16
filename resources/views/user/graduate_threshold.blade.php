@extends('../layouts/master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<a href="{{url('graduate_threshold')}}" style="color: black">
			<h1 class="page-header">英檢畢業門檻</h1>
		</a>
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
									<td id="graduate_threshold.college" onclick="sort(id)">單位</td>
									<td id="graduate_threshold.dept" onclick="sort(id)">系所部門</td>
									<td id="testName" onclick="sort(id)">語言測驗名稱</td>
									<td id="testGrade" onclick="sort(id)">等級或分數</td>
									<td id="comments" onclick="sort(id)">備註</td>
									<td>管理</td>
								</tr>
								</thead>
								<tbody>
								@foreach ($graduateThreshold as $data)
								<tr id="{{$data->id}}">
									<td class="text-nowrap">{{$data->chtCollege}}</td>
									<td class="text-nowrap">{{$data->chtDept}}</td>
									<td class="text-nowrap">{{$data->testName}}</td>
									<td>{{$data->testGrade}}</td>
									<td>{{$data->comments}}</td>
									<td class="text-nowrap">
										@can('permission',$data)
										<a href="{{url('graduate_threshold',$data->id)}}"
											class="glyphicon glyphicon-pencil	
											btn btn-success btn-xs"></a>
										<form action="{{url('graduate_threshold',$data->id)}}"
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
						{{ $graduateThreshold->links()}}
					</div>
					
					<!--insert page-->

					@if(count($errors)>0)
						<div class="tab-pane fade in col-md-12 active " id="insert" 
							style="margin-top: 10px">
					@else
						<div class="tab-pane fade in col-md-12 " id="insert" 
							style="margin-top: 10px">
					@endif
						<form action="{{url('graduate_threshold')}}" method="post">
                        	{{ csrf_field() }}
							@include("../layouts/select")

							@if($errors->has('testName'))
                                <p class="text-danger">{{$errors->first('testName')}}</p>
                            @endif
							<div class="form-group">
								<label for="testName">語言測驗名稱</label>
								<input type="text" name="testName" class="form-control" value="{{old('testName')}}">
							</div>

							@if($errors->has('testGrade'))
                                <p class="text-danger">{{$errors->first('testGrade')}}</p>
                            @endif
							<div class="form-group">
								<label for="testGrade">等級或分數</label>
								<input type="text" name="testGrade" class="form-control" value="{{old('testGrade')}}">
							</div>

							@if($errors->has('comments'))
                                <p class="text-danger">{{$errors->first('comments')}}</p>
                            @endif
							<div class="form-group">
								<label for="comments">備註</label>
								<textarea type="text" class="form-control" name="comments">{{old('comments')}}</textarea>
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
						<form action="{{url('graduate_threshold/search')}}" method="get">
							@include("../layouts/select_search")
							<div class="form-group">
								<label for="testName">語言測驗名稱</label>
								<input type="text" name="testName" class="form-control"></input>
							</div>
							<div class="form-group">
								<label for="testGrade">等級或分數</label>
								<input type="text" name="testGrade" class="form-control">
							</div>
							<div class="form-group">
								<label for="comments">備註</label>
								<textarea type="text" class="form-control" name="comments"></textarea>
							</div>
							<button class="btn btn-success">搜尋</button>
						</form>
					</div>

					<div class="tab-pane fade in col-md-12" id="upload" style="margin-top: 10px;">
						<form action="{{url('graduate_threshold/upload')}}" method="post" enctype="multipart/form-data">
                        	{{ csrf_field() }}
                        	<div id="file_error"></div>
                        	@if(count($errors->upload)>0)
                        		@if($errors->upload->has('format'))
                        			<p class="text-danger">
										{{$errors->upload->first('format')}}
                        			</p>
                        		@elseif($errors->upload->has('permission'))
                        			<p class="text-danger">
                        				{{$errors->upload->first('permission')}}
                        			</p>
                        		@elseif($errors->upload->has('number'))
                        			<p class="text-danger">
                        				{{$errors->upload->first('number')}}
                        			</p>
                        		@else
                        			<p class="text-danger">
                        				欄位內容格式錯誤或必填欄位未填
                        			</p>
                        		@endif
                        	@endif
							<input type="file" id="file" class="" style="margin: 2px" name="file">
							<button class="btn btn-primary" style="margin: 2px"
								onclick="checkFile(event)">上傳</button>
							<a class="btn btn-success" href="{{url('graduate_threshold/example')}}">範例檔案</a>
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

@endsection