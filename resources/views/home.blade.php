@extends('./layouts/master')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header"><b>國立中正大學國際化調查系統</b></h1>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="list-group">
			<li class="list-group-item list-group-item-success">
				基本資料
			</li>
			<a href="{{ URL('/user') }}" class="list-group-item">單位資料修改</a>
			<!-- <a href="{{ URL('/graduate_threshold') }}" class="list-group-item">英檢畢業門檻</a>
			<a href="{{ URL('/foreign_language_class') }}" class="list-group-item">全外語授課之課程</a> -->
			@can('superUser')
			<a href="{{ URL('/manage') }}" class="list-group-item">帳號管理</a>
			@endcan
		</div>
	</div>
	<div class="col-md-6">
		<div class="list-group">
			<li class="list-group-item-success list-group-item">
				教師、研究員專區
			</li>
			<a href="{{ URL('/prof_attend_conference') }}" class="list-group-item">本校教師赴國外出席國際會議</a>
			<a href="{{ URL('/prof_exchange') }}" class="list-group-item">本校教師赴國外交換</a>
			<a href="{{ URL('/prof_foreign_research') }}" class="list-group-item">本校教師赴國外研究</a>
			<a href="{{ URL('/foreign_prof_vist') }}" class="list-group-item">境外學者蒞校訪問</a>
			<a href="{{ URL('/foreign_prof_exchange') }}" class="list-group-item">境外學者來校交換</a>
			<a href="{{ URL('/prof_speech_lecture') }}" class="list-group-item">本校教師境內外演講、研習或講學活動</a>
			<a href="{{ URL('/foreign_prof_attend_conference') }}" class="list-group-item">境外學者來校出席國際會議</a>
		</div>
	</div>
	<div class="col-md-6">
		<div class="list-group">
			<li href="#" class="list-group-item-success list-group-item">
				學生專區
			</li>
			<a href="{{ URL('/stu_attend_conf') }}" class="list-group-item">赴國外出席國際會議</a>
			<a href="{{ URL('/stu_to_partner_school') }}" class="list-group-item">本校學生出國赴姊妹校參加交換計畫</a>
			<a href="{{ URL('/stu_foreign_research') }}" class="list-group-item">本校學生其他出國研修情形</a>
			<a href="{{ URL('/stu_from_partner_school') }}" class="list-group-item">姊妹校學生至本校參加交換計畫</a>
			<a href="{{ URL('/short_term_foreign_stu') }}" class="list-group-item">外籍學生至本校短期交流訪問或實習</a>
			<a href="{{ URL('/foreign_stu') }}" class="list-group-item">修讀正式學位之外國學生</a>
		</div>
	</div>
	<div class="col-md-6">
		<div class="list-group">
			<li class="list-group-item-success list-group-item">
				其他國際交流活動
			</li>
			<a href="{{ URL('/transnational_degree') }}" class="list-group-item">跨國學位</a>
			<a href="{{ URL('/partner_school') }}" class="list-group-item">姊妹校締約情形</a>
			<a href="{{ URL('/cooperation_proj') }}" class="list-group-item">國際合作交流計畫</a>
			<a href="{{ URL('/internationalize_activity') }}" class="list-group-item">國際化活動</a>
			<a href="{{ URL('/international_journal_editor') }}" class="list-group-item">擔任國際期刊編輯</a>
			<a href="{{ URL('/attend_international_organization') }}" class="list-group-item">參與國際組織</a>
		</div>
	</div>
</div>
@endsection