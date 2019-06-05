@extends('../layouts/master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<a href="{{URL('foreign_stu')}}" style="color: black">
			<h1 class="page-header">修讀正式學位之外國學生</h1>
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
									<td id="foreign_stu.college" class="text-nowrap" onclick="sort(id)">一級單位
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="foreign_stu.dept" class="text-nowrap" onclick="sort(id)">系所部門
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="stuID" class="text-nowrap" onclick="sort(id)">學號
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="chtName" class="text-nowrap" onclick="sort(id)">中文姓名
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="engName" class="text-nowrap" onclick="sort(id)">英文姓名
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="stuLevel" class="text-nowrap" onclick="sort(id)">身分
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="nation" class="text-nowrap" onclick="sort(id)">國籍(中文)
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="engNation" class="text-nowrap" onclick="sort(id)">國籍(英文)
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="startDate" class="text-nowrap" onclick="sort(id)">開始時間
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="endDate" class="text-nowrap" onclick="sort(id)">結束時間
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="" class="text-nowrap" onclick="sort(id)">學籍狀態
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="comments" class="text-nowrap" onclick="sort(id)">備註
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td class="text-nowrap">管理</td>
								</tr>
							</thead>
							<tbody>
								@foreach($foreignStu as $data)
								<tr>
									<td class="text-nowrap">{{$data->chtCollege}}</td>
									<td style="max-width: 90px">{{$data->chtDept}}</td>
									<td class="text-nowrap">{{$data->stuID}}</td>
									<td>{{$data->chtName}}</td>
									<td>{{$data->engName}}</td>
									<td class="text-nowrap">@if ($data->stuLevel==1)
										博士班
										@elseif ($data->stuLevel==2)
										碩士班
										@else
										學士班
										@endif
									</td>
									<td class="text-nowrap">{{$data->nation}}</td>
									<td>{{$data->engNation}}</td>
									<td class="text-nowrap">{{$data->startDate}}</td>
									<td class="text-nowrap">{{$data->endDate}}</td>
									<td>
										@if($data->status == 1)
										在學中
										@elseif($data->status == 2)
										休學中
										@elseif($data->status == 3)
										已畢業
										@endif
									</td>
									<td>{{$data->comments}}</td>
									<td class="text-nowrap">
										@can('permission',$data)
										<a href="{{url('foreign_stu',$data->id)}}" class="glyphicon glyphicon-pencil btn 
											btn-success btn-xs"></a>
										<form action="{{url('foreign_stu',$data->id)}}" method="post" style="display: inline;">
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
						{{ $foreignStu->links() }}
					</div>

					<!--insert page-->

					@if(count($errors)>0)
						<div class="tab-pane fade in col-md-12 active " id="insert" style="margin-top: 10px">
					@else
						<div class="tab-pane fade in col-md-12 " id="insert" style="margin-top: 10px">
					@endif
						<form action="{{url('foreign_stu')}}" method="post">
							{{ csrf_field() }}
							@include('../layouts/select')

							@if($errors->has('stuID'))
                                <p class="text-danger">{{$errors->first('stuID')}}</p>
                            @endif
							<div class="form-group">
								<label for="stuID">學號</label>
								<input type="text" name="stuID" class="form-control" value="{{old('stuID')}}">
							</div>

							@if($errors->has('chtName'))
                                <p class="text-danger">{{$errors->first('chtName')}}</p>
                            @endif
							<div class="form-group">
								<label for="">中文姓名</label>
								<input type="text" class="form-control" name="chtName" value="{{old('chtName')}}">
							</div>

							@if($errors->has('engName'))
                                <p class="text-danger">{{$errors->first('engName')}}</p>
                            @endif
							<div class="form-group">
								<label for="">英文姓名</label>
								<input type="text" class="form-control" name="engName" value="{{old('engName')}}">
							</div>

							<div class="form-group">
								<label for="stuLevel">身分</label>
								<select name="stuLevel" id="stuLevel_option" class="form-control">
									<option value="1">博士班</option>
									<option value="2">碩士班</option>
									<option value="3">學士班</option>
								</select>
							</div>

							@if($errors->has('nation'))
                                <p class="text-danger">{{$errors->first('nation')}}</p>
                            @endif
							<div class="form-group">
								<label for="nation">國籍(中文)</label>
								<input type="text" name="nation" class="form-control" value="{{old('nation')}}">
							</div>


							@if($errors->has('nation'))
                                <p class="text-danger">{{$errors->first('nation')}}</p>
                            @endif
							<div class="form-group">
								<label for="engNation">國籍(英文)</label>
								<input type="text" name="engNation" class="form-control" value="{{old('nation')}}">
							</div>

							@if($errors->has('startDate')||$errors->has('endDate'))
                                <p class="text-danger col-md-6">{{ $errors->first('startDate')}}</p>
                                <p class="text-danger col-md-6">{{ $errors->first('endDate')}}</p>
                            @endif
							<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
								<label for="startDate">開始時間</label>
								<input type="text" name="startDate" class="form-control" value="{{old('startDate')}}" id="insert_startDate">
							</div>
							<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
								<label for="endDate">結束時間</label>
								<input type="text" name="endDate" class="form-control" value="{{old('endDate')}}" id="insert_endDate">
							</div>

							@if($errors->has('status'))
                                <p class="text-danger">{{$errors->first('status')}}</p>
                            @endif
							<div class="form-group">
								<label for="comments">學籍狀態</label>
								<select name="status" id="status_option" class="form-control">
									<option value="1">在學中</option>
									<option value="2">休學中</option>
									<option value="3">已畢業</option>
								</select>
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
						<form action="{{url('foreign_stu/search')}}">
							@include('../layouts/select_search')
							<div class="form-group">
								<label for="stuID">學號</label>
								<input type="text" name="stuID" class="form-control">
							</div>
							<div class="form-group">
								<label for="">中文姓名</label>
								<input type="text" class="form-control" name="chtName" />
							</div>
							<div class="form-group">
								<label for="">英文姓名</label>
								<input type="text" class="form-control" name="engName" />
							</div>
							<div class="form-group">
								<label for="stuLevel">身分</label>
								<select name="stuLevel" class="form-control">
									<option value=""></option>
									<option value="1">博士班</option>
									<option value="2">碩士班</option>
									<option value="3">學士班</option>
								</select>
							</div>
							<div class="form-group">
								<label for="nation">國籍(中文)</label>
								<input type="text" name="nation" class="form-control">
							</div>
							<div class="form-group">
								<label for="engNation">國籍(英文)</label>
								<input type="text" name="engNation" class="form-control">
							</div>
							<div class="form-group" style="margin-bottom: 0px">
								<label >日期</label>
							</div>
							<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
								<label for="startDate">從</label>
								<input type="text" name="startDate" class="form-control" id="search_startDate">
							</div>
							<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
								<label for="endDate">到</label>
								<input type="text" name="endDate" class="form-control" id="search_endDate">
							</div>
							<div class="form-group">
								<label for="comments">學籍狀態</label>
								<select name="status" class="form-control">
									<option value=""></option>
									<option value="1">在學中</option>
									<option value="2">休學中</option>
									<option value="3">已畢業</option>
								</select>
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
						<form action="{{url('foreign_stu/upload')}}" method="post" enctype="multipart/form-data">
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
							<button class="btn btn-primary" style="margin: 2px"
								onclick="checkFile(event)">上傳</button>
							<a class="btn btn-success" href="{{url('foreign_stu/example')}}">範例檔案</a>
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
						<a class="btn btn-success" href="{{url('foreign_stu/download')}}">下載所有資料</a>
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
		document.getElementById('stuLevel_option').value ={{old('stuLevel')}};
		document.getElementById('status_option').value ={{old('status')}};
	</script>
@endif
@endsection