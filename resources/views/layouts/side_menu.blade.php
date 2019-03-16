<div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        
                        <li>
                            <a href="#"><i class="fa fa-address-card-o fa-fw" aria-hidden="true"></i> 基本資料<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{{URL('/user')}}">單位資料修改</a>
                                </li>
                                <!-- <li>
                                    <a href="{{URL('/graduate_threshold')}}">英檢畢業門檻</a>
                                </li>
                                <li>
                                    <a href="{{URL('/foreign_language_class')}}">全外語授課之課程</a>
                                </li> -->
                                @can('superUser')
                                <li>
                                    <a href="{{URL('/manage')}}">帳號管理</a>
                                </li>
                                @endcan
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-briefcase fa-fw" aria-hidden="true"></i> 教師、研究員專區<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{{URL('/prof_attend_conference')}}">本校教師赴國外出席國際會議</a>
                                </li>
                                <li>
                                    <a href="{{URL('/prof_exchange')}}">本校教師赴國外交換</a>
                                </li>
                                <li>
                                    <a href="{{URL('/prof_foreign_research')}}">本校教師赴國外研究</a>
                                </li>
                                <li>
                                    <a href="{{URL('/foreign_prof_vist')}}">外籍學者蒞校訪問</a>
                                </li>
                                <li>
                                    <a href="{{URL('/foreign_prof_exchange')}}">外籍學者來校交換</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#"><i class="fa fa-graduation-cap fa-fw"></i> 學生專區<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li><a href="{{ URL('/stu_attend_conf') }}">本校學生赴國外出席國際會議</a></li>
                                <li><a href="{{URL('/stu_to_partner_school')}}">本校學生出國赴姊妹校參加交換計畫</a></li>
                                <li><a href="{{URL('/stu_foreign_research')}}">本校學生其他出國研修情形</a></li>
                                <li><a href="{{URL('/stu_from_partner_school')}}">姊妹校學生至本校參加交換計畫</a></li>
                                <li><a href="{{URL('/short_term_foreign_stu')}}">外籍學生至本校短期交流訪問或實習</a></li>
                                <li><a href="{{URL('/foreign_stu')}}">修讀正式學位之外國學生</a></li>
                            </ul>
                        </li>
                            <!-- /.nav-second-level -->

                        <li>
                            <a href="#"><i class="fa fa-wrench fa-fw"></i> 其他國際交流活動<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{{URL('/transnational_degree')}}">跨國學位</a>
                                </li>
                                <li>
                                    <a href="{{URL('/partner_school')}}">姊妹校締約情形</a>
                                </li>
                                <li>
                                    <a href="{{URL('/cooperation_proj')}}">國際合作交流計畫</a>
                                </li>
                                <li>
                                    <a href="{{URL('/internationalize_activity')}}">國際化活動</a>
                                </li>
                                <li>
                                    <a href="{{URL('/international_journal_editor')}}">擔任國際期刊編輯</a>
                                </li>
                                <li>
                                    <a href="{{URL('/attend_international_organization')}}">參與國際組織</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->