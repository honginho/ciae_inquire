@extends('../layouts/master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<a href="{{URL('transnational_degree')}}" style="color:black">
			<h1 class="page-header">跨國學位</h1>
		</a>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<ul class="nav nav-tabs">
					@if(count($errors)>0)
						<li><a href="#show" data-toggle="tab">檢視</a></li>
						<li class="active"><a href="#insert" data-toggle="tab">新增</a></li>
						<li><a href="#search" data-toggle="tab">進階搜尋</a></li>
						<li><a href="#upload" data-toggle="tab">批次上傳</a></li>
						<li><a href="#download" data-toggle="tab">資料下載</a></li>
					@elseif(count($errors->upload)>0)
						<li><a href="#show" data-toggle="tab">檢視</a></li>
						<li><a href="#insert" data-toggle="tab">新增</a></li>
						<li><a href="#search" data-toggle="tab">進階搜尋</a></li>
						<li class="active"><a href="#upload" data-toggle="tab">批次上傳</a></li>
						<li><a href="#download" data-toggle="tab">資料下載</a></li>
					@else
						<li class="active"><a href="#show" data-toggle="tab">檢視</a></li>
						<li><a href="#insert" data-toggle="tab">新增</a></li>
						<li><a href="#search" data-toggle="tab">進階搜尋</a></li>
						<li><a href="#upload" data-toggle="tab">批次上傳</a></li>
						<li><a href="#download" data-toggle="tab">資料下載</a></li>
					@endif
				</ul>
				<div class="tab-content">
					@if(count($errors)>0||count($errors->upload)>0)
						<div class="tab-pane fade in table-responsive" id="show" style="margin-top: 10px">
					@else
						<div class="tab-pane fade in active table-responsive" id="show" style="margin-top: 10px">
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
									<td id="transnational_degree.college" class="text-nowrap" onclick="sort(id)">一級單位
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="transnational_degree.dept" class="text-nowrap" onclick="sort(id)">系所部門
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="nation" class="text-nowrap" onclick="sort(id)">國家
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="chtName" class="text-nowrap" onclick="sort(id)">中文校名
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="engName" class="text-nowrap" onclick="sort(id)">英文校名
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="stuLevel" class="text-nowrap" onclick="sort(id)">身分
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="" class="text-nowrap" onclick="sort(id)">修業年限
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="classMode" class="text-nowrap" onclick="sort(id)">授課方式
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="degreeMode" class="text-nowrap" onclick="sort(id)">學位授予方式
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="comments" class="text-nowrap" onclick="sort(id)">備註
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td class="text-nowrap">管理</td>
								</tr>
							</thead>
							<tbody>
								@foreach($transnational as $data)
								<tr>
									<td>{{$data->chtCollege}}</td>
									<td>{{$data->chtDept}}</td>
									<td>{{$data->nation}}</td>
									<td>{{$data->chtName}}</td>
									<td>{{$data->engName}}</td>
									<td>
										@if($data->stuLevel==1)
										博士班
										@elseif($data->stuLevel==2)
										碩士班
										@elseif($data->stuLevel==3)
										學士班
										@endif
									</td>
									<td>{{$data->year}}</td>
									<td>{{$data->classMode}}</td>
									<td>{{$data->degreeMode}}</td>
									<td>{{$data->comments}}</td>
									<td class="text-nowrap">
										@can('permission',$data)
										<a href="{{url('transnational_degree',$data->id)}}" class="glyphicon glyphicon-pencil	
											btn btn-success btn-xs"></a>
										<form action="{{url('transnational_degree',$data->id)}}" method="post" style="display: inline;">
											{{ method_field('DELETE') }}
											{{ csrf_field() }}
											<button class="glyphicon glyphicon-trash
												btn btn-danger btn-xs" onclick="clickDel(event)"></button>
										</form>
										@endcan
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						{{ $transnational->links() }}
					</div>

					<!--insert page-->

					@if(count($errors)>0)
						<div class="tab-pane fade in col-md-12 active " id="insert" style="margin-top: 10px">
					@else
						<div class="tab-pane fade in col-md-12 " id="insert" style="margin-top: 10px">
					@endif
						<form action="{{url('transnational_degree')}}" method="post">
							{{ csrf_field() }}
							@include('../layouts/select')

							@if($errors->has('nation'))
                                <p class="text-danger">{{$errors->first('nation')}}</p>
                            @endif
							<div class="form-group">
								<label for="nation">國家</label>
								<input type="text" name="nation" class="form-control" value="{{old('nation')}}">
							</div>

							@if($errors->has('chtName'))
                                <p class="text-danger">{{$errors->first('chtName')}}</p>
                            @endif
							<div class="form-group">
								<label for="">中文校名</label>
								<input type="text" class="form-control" name="chtName" value="{{old('chtName')}}">
							</div>

							@if($errors->has('engName'))
                                <p class="text-danger">{{$errors->first('engName')}}</p>
                            @endif
							<div class="form-group">
								<label for="">英文校名</label>
								<input type="text" class="form-control" name="engName" value="{{old('engName')}}">
							</div>
							<div class="form-group">
								<label for="stuLevel">授予身分</label>
								<select name="stuLevel" id="stuLevel_option" class="form-control">
									<option value="1">博士班</option>
									<option value="2">碩士班</option>
									<option value="3">學士班</option>
								</select>
							</div>

							@if($errors->has('year'))
                                <p class="text-danger">{{$errors->first('year')}}</p>
                            @endif
							<div class="form-group">
								<label for="year">修業年限</label>
								<textarea name="year" id="year" cols="30" rows="3" class="form-control">{{old('year')}}</textarea>
							</div>

							@if($errors->has('classMode'))
                                <p class="text-danger">{{$errors->first('classMode')}}</p>
                            @endif
							<div class="form-group">
								<label for="classMode">授課方式</label>
								<textarea name="classMode" id="classMode" cols="30" rows="3" class="form-control">{{old('classMode')}}</textarea>
							</div>

							@if($errors->has('degreeMode'))
                                <p class="text-danger">{{$errors->first('degreeMode')}}</p>
                            @endif
							<div class="form-group">
								<label for="degreeMode">學位授予方式</label>
								<textarea name="degreeMode" id="degreeMode" cols="30" rows="3" class="form-control">{{old('degreeMode')}}</textarea>
							</div>

							@if($errors->has('comments'))
                                <p class="text-danger">{{$errors->first('comments')}}</p>
                            @endif
							<div class="form-group">
								<label for="comments">備註</label>
								<textarea name="comments" id="comments" cols="30" rows="3" class="form-control">{{old('comments')}}</textarea>
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
						<form action="{{url('transnational_degree/search')}}">
							@include('../layouts/select_search')
							<div class="form-group">
								<label for="nation">國家</label>
								<input type="text" name="nation" class="form-control">
							</div>
							<div class="form-group">
								<label for="">中文校名</label>
								<input type="text" class="form-control" name="chtName" />
							</div>
							<div class="form-group">
								<label for="">英文校名</label>
								<input type="text" class="form-control" name="engName" />
							</div>
							<div class="form-group">
								<label for="stuLevel">授予身分</label>
								<select name="stuLevel" id="stuLevel_option" class="form-control">
									<option value=""></option>
									<option value="1">博士班</option>
									<option value="2">碩士班</option>
									<option value="3">學士班</option>
								</select>
							</div>
							<div class="form-group">
								<label for="year">修業年限</label>
								<textarea name="year" id="year" cols="30" rows="3" class="form-control"></textarea>
							</div>
							<div class="form-group">
								<label for="teachMode">授課方式</label>
								<textarea name="teachMode" id="teachMode" cols="30" rows="3" class="form-control"></textarea>
							</div>
							<div class="form-group">
								<label for="degreeMode">學位授予方式</label>
								<textarea name="degreeMode" id="degreeMode" cols="30" rows="3" class="form-control"></textarea>
							</div>

							<div class="form-group">
								<label for="comments">備註</label>
								<textarea name="comments" id="comments" cols="30" rows="3" class="form-control"></textarea>
							</div>
							<button class="btn btn-success">搜尋</button>
						</form>
					</div>

					@if(count($errors->upload)>0)
						<div class="tab-pane fade in col-md-12 active" id="upload" style="margin-top: 10px;">
					@else
						<div class="tab-pane fade in col-md-12" id="upload" style="margin-top: 10px;">
					@endif
						<form action="{{url('transnational_degree/upload')}}" method="post" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div id="file_error"></div>
							@if(count($errors->upload)>0)
								<div class="alert alert-danger alert-dismissible" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									<strong>
										@foreach($errors->upload->all() as $errors)
											{{$errors}}<br>
										@endforeach
									</strong>
								</div>
							@endif
							<input type="file" name="file" id="file" style="margin: 2px">
							<button class="btn btn-primary" style="margin: 2px" onclick="checkFile(event)">上傳</button>
							<a class="btn btn-success" href="{{url('transnational_degree/example')}}">範例檔案</a>
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

					<div class="tab-pane fade in col-md-12" id="download" style="margin-top: 10px;">
						<a class="btn btn-success" href="{{url('transnational_degree/download')}}">下載所有資料</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$("#insert_startDate").datepicker({
		format: 'yyyy-mm-dd',
		setDate: "{{old('startDate')}}",
	})
	$("#insert_endDate").datepicker({
		format: 'yyyy-mm-dd',
		setDate: "{{old('endDate')}}",
	})
	$("#search_startDate").datepicker({
		format: 'yyyy-mm-dd',
	})
	$("#search_endDate").datepicker({
		format: 'yyyy-mm-dd',
	})
</script>
@if(count($errors)>0)
	<script>
		document.getElementById('bachelor_option').value ={{old('bachelor')}};
		document.getElementById('master_option').value ={{old('master')}};
		document.getElementById('PHD_option').value ={{old('PHD')}};
	</script>
@endif
@endsection