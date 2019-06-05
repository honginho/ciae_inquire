@extends('../layouts/master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<a href="{{ URL('prof_speech_lecture') }}" style="color: black">
			<h1 class="page-header">本校教師境內外演講、研習或講學活動</h1>
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
									<td id="prof_speech_lecture.college" class="text-nowrap" onclick="sort(id)">一級單位
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="prof_speech_lecture.dept" class="text-nowrap" onclick="sort(id)">系所部門
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="name" class="text-nowrap" onclick="sort(id)">姓名
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="profLevel" class="text-nowrap" onclick="sort(id)">身分
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="lecture" class="text-nowrap" onclick="sort(id)">演講、研習活動或講學
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="isForeign" class="text-nowrap" onclick="sort(id)">境（內）外
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="place" class="text-nowrap" onclick="sort(id)">地點
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="startDate" class="text-nowrap" onclick="sort(id)">開始時間
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="endDate" class="text-nowrap" onclick="sort(id)">結束時間
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="comments" class="text-nowrap" onclick="sort(id)">備註
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td class="text-nowrap">管理</td>
								</tr>
							</thead>
							<tbody>
								@foreach ($Pspeechlecture as $data)
								<tr>
									<td class='text-nowrap'>{{ $data->chtCollege }}</td>
									<td>{{ $data->chtDept }}</td>
									<td class='text-nowrap'>{{ $data->name }}</td>
									<td>
										@if($data->profLevel==1)
										教授
										@elseif($data->profLevel==2)
										副教授
										@elseif($data->profLevel==3)
										助理教授
										@elseif($data->profLevel==4)
										博士後研究員
										@elseif($data->profLevel==5)
										研究生
										@endif
									</td>
									<td>{{ $data->lecture }}</td>
									<td>
										@if($data->isForeign==0)
										境內
										@elseif($data->isForeign==1)
										境外
										@endif
									</td>
									<td>{{ $data->place }}</td>
									<td class='text-nowrap'>{{ $data->startDate }}</td>
									<td class='text-nowrap'>{{ $data->endDate }}</td>
									<td>{{ $data->comments }}</td>
									<td class="text-nowrap">
										@can('permission',$data)
										<a href="{{ url('prof_speech_lecture',$data->id) }}"
											class="glyphicon glyphicon-pencil btn btn-success btn-xs"></a>
										<form action="{{ url('prof_speech_lecture',$data->id) }}" method="post" style="display: inline;">
											{{ method_field('DELETE') }}
											{{ csrf_field() }}
											<button class="glyphicon glyphicon-trash btn btn-danger btn-xs"
												onclick="clickDel(event)"></button>
										</form>
										@endcan
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						{{ $Pspeechlecture->links() }}
					</div>

					<!--insert page-->
					@if(count($errors)>0)
						<div class="tab-pane fade in col-md-12 active" id="insert" style="margin-top: 10px">
					@else
						<div class="tab-pane fade in col-md-12 " id="insert" style="margin-top: 10px">
					@endif
						<form action="{{ url('prof_speech_lecture') }}" method="post">
							{{ csrf_field() }}
							@include('../layouts/select')
							@if($errors->has('name'))
                                <p class="text-danger">{{ $errors->first('name') }}</p>
                            @endif
							<div class="form-group">
								<label for="">姓名</label>
								<input type="text" class="form-control" name="name" value="{{ old('name') }}">
							</div>

							<div class="form-group">
								<label for="profLevel">身分</label>
								<select name="profLevel" id="profLevel_option" class="form-control">
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
								<textarea name="lecture" id="lecture" cols="30" rows="3" class="form-control">{{ old('lecture') }}</textarea>
							</div>

							<div class="form-group">
								<label for="isForeign">境（內）外</label>
								<select name="isForeign" id="isForeign_option" class="form-control">
									<option value="0">境內</option>
									<option value="1">境外</option>
								</select>
							</div>

							@if($errors->has('place'))
                                <p class="text-danger">{{ $errors->first('place') }}</p>
                            @endif
							<div class="form-group">
								<label for="place">地點</label>
								<textarea name="place" id="place" cols="30" rows="3" class="form-control">{{ old('place') }}</textarea>
							</div>

							@if($errors->has('startDate')||$errors->has('endDate'))
                                <p class="text-danger col-md-6">{{ $errors->first('startDate') }}</p>
                                <p class="text-danger col-md-6">{{ $errors->first('endDate') }}</p>
                            @endif

							<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
								<label for="startDate">開始時間</label>
								<input type="text" name="startDate" class="form-control" value="{{ old('startDate') }}" id="insert_startDate">
							</div>
							<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
								<label for="endDate">結束時間</label>
								<input type="text" name="endDate" class="form-control" value="{{ old('endDate') }}" id="insert_endDate">
							</div>

							@if($errors->has('comments'))
                                <p class="text-danger">{{ $errors->first('comments') }}</p>
                            @endif
							<div class="form-group">
								<label for="comments">備註</label>
								<textarea name="comments" id="comments" cols="30" rows="3" class="form-control">{{ old('comments') }}</textarea>
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
						<form action="{{ url('prof_speech_lecture/search') }}">
							@include('../layouts/select_search')
							<div class="form-group">
								<label for="name">姓名</label>
								<input type="text" class="form-control" name="name" />
							</div>
							<div class="form-group">
								<label for="profLevel">身分</label>
								<select name="profLevel" class="form-control">
									<option value=""></option>
									<option value="1">教授</option>
									<option value="2">副教授</option>
									<option value="3">助理教授</option>
									<option value="4">博士後研究員</option>
									{{-- <option value="5">研究生</option> --}}
								</select>
							</div>
							<div class="form-group">
								<label for="lecture">演講、研習活動或講學</label>
								<textarea name="lecture" id="lecture" cols="30" rows="3" class="form-control"></textarea>
							</div>
							<div class="form-group">
								<label for="isForeign">境（內）外</label>
								<select name="isForeign" class="form-control">
									<option value=""></option>
									<option value="0">境內</option>
									<option value="1">境外</option>
								</select>
							</div>
							<div class="form-group">
								<label for="place">地點</label>
								<textarea name="place" id="place" cols="30" rows="3" class="form-control"></textarea>
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
						<form action="{{ url('prof_speech_lecture/upload') }}" method="post" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div id="file_error"></div>
							@if(count($errors->upload)>0)
							<div class="alert alert-danger alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
								<strong>
									@foreach($errors->upload->all() as $errors)
									{{ $errors }}<br>
									@endforeach
								</strong>
							</div>
							@endif
							<input type="file" name="file" id="file" style="margin: 2px">
							<button class="btn btn-primary" style="margin: 2px"
								onclick="checkFile(event)">上傳</button>
							<a class="btn btn-success" href="{{ url('prof_speech_lecture/example') }}">範例檔案</a>
							<a class="btn btn-success" href="{{ url('example') }}">系所對照表</a>
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
						<a class="btn btn-success" href="{{url('prof_speech_lecture/download')}}">下載所有資料</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$("#insert_startDate").datepicker({
		format: 'yyyy-mm-dd',
		setDate: "{{ old('startDate') }}",
	})
	$("#insert_endDate").datepicker({
		format: 'yyyy-mm-dd',
		setDate: "{{ old('endDate') }}",
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
		document.getElementById('profLevel_option').value = {{ old('profLevel') }};
		document.getElementById('isForeign_option').value = {{ old('isForeign') }};
	</script>
@endif
@endsection