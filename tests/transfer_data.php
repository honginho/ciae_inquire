<?php
require_once("DeptMapping.php");

echo "begin transfer data\n";

$old_connect = mysqli_connect("127.0.0.1","root","","ciae");
$new_connect = mysqli_connect("127.0.0.1","root","","ciae_inquire");

mysqli_query($old_connect,"set names UTF8");
mysqli_query($new_connect,"set names UTF8");



echo "insert college_data\n";

mysqli_query($new_connect,"CREATE TABLE `college_data` (
  `college` int(11) NOT NULL,
  `dept` int(11) NOT NULL,
  `chtCollege` varchar(50) NOT NULL,
  `oldID` int(11) NOT NULL,
  `chtDept` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;");

mysqli_query($new_connect,"INSERT INTO `college_data` (`college`, `dept`, `chtCollege`, `oldID`, `chtDept`) VALUES
(1, 0, '文學院', 95, '文學院'),
(1, 10, '文學院', 1, '中文系/中文所'),
(1, 15, '文學院', 2, '外文系/外文所/英語教學研究所'),
(1, 20, '文學院', 3, '歷史系/歷史所'),
(1, 25, '文學院', 4, '哲學系/哲學所'),
(1, 30, '文學院', 5, '語言學研究所'),
(1, 40, '文學院', 6, '台灣文學研究所'),
(2, 0, '理學院', 92, '理學院'),
(2, 10, '理學院', 7, '數學系/數學所/應數所'),
(2, 20, '理學院', 9, '物理系/物理所'),
(2, 35, '理學院', 8, '地球與環境科學系(含地震學碩士班、地震學博士班、應用地球物理與環境科學碩士班)'),
(2, 50, '理學院', 11, '生科系/生科所'),
(2, 60, '理學院', 10, '化生系/化生所'),
(3, 0, '社科院', 82, '社科院'),
(3, 10, '社科院', 12, '社福系/社福所'),
(3, 15, '社科院', 13, '心理系/心理所'),
(3, 20, '社科院', 14, '勞工系/勞工所'),
(3, 30, '社科院', 15, '政治系/政治所'),
(3, 35, '社科院', 17, '傳播系/電傳所'),
(3, 41, '社科院', 16, '戰略所'),
(4, 0, '工學院', 94, '工學院'),
(4, 10, '工學院', 18, '資工系/資工所'),
(4, 15, '工學院', 19, '電機系/電機所'),
(4, 20, '工學院', 20, '機械系/機械所'),
(4, 25, '工學院', 21, '化工系/化工所'),
(4, 30, '工學院', 22, '通訊系/通訊所'),
(4, 41, '工學院', 23, '光機電整合工程研究所'),
(5, 0, '管理學院', 96, '管理學院'),
(5, 10, '管理學院', 24, '經濟系/經濟所/國經所'),
(5, 15, '管理學院', 25, '財金系/財金所'),
(5, 20, '管理學院', 26, '企業管理學系(學士班.碩士班.行銷管理碩士班.博士班)'),
(5, 26, '管理學院', 27, '會資系/會資所'),
(5, 30, '管理學院', 28, '資管系/資管所'),
(6, 0, '法學院', 97, '法學院'),
(6, 10, '法學院', 29, '法律系/法律所'),
(6, 30, '法學院', 30, '財經法律學系'),
(7, 0, '教育學院', 98, '教育學院'),
(7, 40, '教育學院', 31, '成教系/成教所/高齡所'),
(7, 45, '教育學院', 33, '犯防系/犯防所'),
(7, 50, '教育學院', 32, '教育所/教育所'),
(7, 54, '教育學院', 34, '課程所/師培中心'),
(7, 90, '教育學院', 35, '運動競技學系(運動與休閒教育碩士班)'),
(8, 0, '校長室', 0, '校長室'),
(9, 0, '副校長室', 0, '副校長室'),
(10, 0, '秘書室', 0, '秘書室'),
(11, 0, '教務處', 0, '教務處'),
(11, 1, '教務處', 72, '教務處教學組'),
(11, 2, '教務處', 102, '教務處招生組'),
(12, 0, '學務處', 0, '學務處'),
(13, 0, '總務處', 0, '總務處'),
(14, 0, '研發處', 0, '研發處'),
(14, 1, '研發處', 36, '創新育成中心'),
(14, 2, '研發處', 37, '先進工具機研究中心'),
(14, 3, '研發處', 38, '電信研究中心'),
(14, 4, '研發處', 39, '認知科學中心'),
(14, 5, '研發處', 40, '自動化研究中心'),
(14, 6, '研發處', 41, '網際網路中心'),
(14, 7, '研發處', 42, '行銷策略與創意研究中心'),
(14, 8, '研發處', 43, '產業發展與預測研究中心'),
(14, 9, '研發處', 44, '醫療資訊管理研究中心'),
(14, 10, '研發處', 45, '製商整合中心'),
(14, 11, '研發處', 46, '台灣自旋科技研究中心'),
(14, 12, '研發處', 47, '公共政策管理研究中心'),
(14, 13, '研發處', 48, '晶片系統研究中心'),
(14, 14, '研發處', 49, '心理健康推廣中心'),
(14, 15, '研發處', 50, '創業家培育中心'),
(14, 16, '研發處', 51, '精密模具研究中心'),
(14, 17, '研發處', 52, '數位媒體製作研究中心'),
(14, 18, '研發處', 53, '金融創新與債券研究中心'),
(14, 19, '研發處', 54, '奈米設計與原型中心'),
(14, 20, '研發處', 55, '犯罪研究中心'),
(14, 21, '研發處', 56, '民意及市場調查中心'),
(14, 22, '研發處', 57, '財經法律研究中心'),
(14, 23, '研發處', 58, '精緻電能應用研究中心'),
(14, 24, '研發處', 59, '高齡教育研究中心'),
(14, 25, '研發處', 60, '奈米生物檢測科技中心'),
(14, 26, '研發處', 61, '系統生物學與組織工程研究中心'),
(14, 27, '研發處', 62, '法律e化與互動教學研究中心'),
(14, 28, '研發處', 63, '人類對位性基因體學研究中心'),
(14, 29, '研發處', 64, '人文與社會科學研究中心'),
(14, 30, '研發處', 65, '貴重儀器中心'),
(14, 31, '研發處', 66, '體育運動研究發展中心'),
(14, 32, '研發處', 67, '尖端製程與軋鍛技術研發中心'),
(14, 33, '研發處', 68, '台商運籌研究中心'),
(14, 34, '研發處', 69, '國家安全研究中心'),
(14, 35, '研發處', 70, '後殖民研究中心'),
(26, 0, '前瞻製造系統頂尖研究中心', 101, '前瞻製造系統頂尖研究中心'),
(15, 0, '圖書館', 0, '圖書館'),
(16, 0, '清江學習中心', 0, '清江學習中心'),
(17, 0, '電算中心', 0, '電算中心'),
(18, 0, '體育中心', 0, '體育中心'),
(19, 0, '輔導中心', 0, '輔導中心'),
(20, 0, '環安中心', 0, '環安中心'),
(21, 0, '人事室', 0, '人事室'),
(22, 0, '會計室', 0, '會計室'),
(23, 0, '國際處', 104, '國際處'),
(24, 0, '通識教育中心', 0, '通識教育中心'),
(25, 0, '語言中心', 0, '語言中心');");

mysqli_query($new_connect,"ALTER TABLE `college_data` ADD PRIMARY KEY (`college`,`dept`);");

mysqli_query($new_connect,"CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(16) NOT NULL UNIQUE KEY,
  `password` varchar(60) NOT NULL,
  `college` int(11) NOT NULL,
  `dept` int(11) NOT NULL,
  `contactPeople` varchar(20) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `permission` int(11) NOT NULL DEFAULT '4',
  `remember_token` varchar(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf32;");

echo "end\n";

echo "transfer test_prefixudata to user\n";

$result = mysqli_query($old_connect,"select * from test_prefixudata");

while($array = mysqli_fetch_row($result)){
	if($array[1] == "admin"){
		$password =  password_hash($array[2],PASSWORD_BCRYPT);
		$sql = "insert into 
			user(username,password,college,dept,contactPeople,email,permission) 
			values('$array[1]','$password',23,0,'$array[3]','$array[4]',0)";
		if(!mysqli_query($new_connect,$sql))
			echo $sql."\n";
	}else{
		$password =  password_hash($array[2],PASSWORD_BCRYPT);
		$sql = "insert into 
			user(username,password,college,dept,contactPeople,email,permission) 
			values('$array[1]','$password',23,0,'$array[3]','$array[4]',1)";
		if(!mysqli_query($new_connect,$sql))
			echo $sql."\n";
	}
}

echo "end\n";

echo "transfer test_prefixschooldept to user\n";

$result = mysqli_query($old_connect,"select * from test_prefixschooldept");

while($array = mysqli_fetch_array($result)){
	$newData = null;
	if($array['did'] != 0)
		$newData = DeptMapping::old2new($array['did']);
	if(!$newData)
		$newData = DeptMapping::findByCollege($array['DeptClass']);

	$password =  password_hash($array['PassWd'],PASSWORD_BCRYPT);

	if($newData[1]!=0){
		$sql = "insert into user values(0,'$array[UserID]','$password'
			,$newData[0],$newData[1],'$array[ContactPeople]','$array[ContactPhone]'
			,'$array[ContactEmail]',3,'')";
		if(!mysqli_query($new_connect,$sql))
			echo $sql."\n";
	}else{
		$sql = "insert into user values(0,'$array[UserID]','$password'
			,$newData[0],$newData[1],'$array[ContactPeople]','$array[ContactPhone]'
			,'$array[ContactEmail]',2,'')";
		if(!mysqli_query($new_connect,$sql))
			echo $sql."\n";
	}
	
}

echo "end\n";

echo "transfer test_prefixschooldeptclass to user\n";

$result = mysqli_query($old_connect,"select * from test_prefixschooldeptclass");
while($array = mysqli_fetch_array($result)){
	$password = password_hash($array['PassWd'],PASSWORD_BCRYPT);
	$sql = "insert into user	
		values(0,'$array[UserID]','$password',$array[cid],0
		,'$array[ContactPeople]','$array[ContactPhone]','$array[ContactEmail]',2,'')";
	if(!mysqli_query($new_connect,$sql))
		echo $sql."\n";
}

echo "end\n";

echo "transfer test_prefixdeptpartnerschool to partner_school\n";

mysqli_query($new_connect,"
	CREATE TABLE `partner_school` (
	  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  `college` int(11) NOT NULL,
	  `dept` int(11) NOT NULL,
	  `nation` varchar(20) NOT NULL,
	  `chtName` varchar(50),
	  `engName` varchar(80),
	  `startDate` date NOT NULL,
	  `endDate` date NOT NULL,
	  `comments` varchar(500)
	) ENGINE=InnoDB DEFAULT CHARSET=utf32;");

$result = mysqli_query($old_connect,"select * from test_prefixdeptpartnerschool");
while($array = mysqli_fetch_array($result)){
	$newData = null;
	if($array['DeptID'] != 0)
		$newData = DeptMapping::old2new($array['DeptID']);
	if(!$newData)
		$newData = DeptMapping::findByCollege($array['DeptClass']);

	$sql = "insert into partner_school 
	values(0,$newData[0],$newData[1],'$array[Nation]','$array[ChtName]','$array[EngName]'
	,'$array[StartDate]','$array[EndDate]','$array[Memo]')";
	if(!mysqli_query($new_connect,$sql))
		echo $sql."\n";
}


echo "end\n";

echo "transfer test_prefixexchangeproject to cooperation_proj\n";

mysqli_query($new_connect,"
	CREATE TABLE `cooperation_proj` (
	  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  `college` int(11) NOT NULL,
	  `dept` int(11) NOT NULL,
	  `name` varchar(10) NOT NULL,
	  `projName` varchar(200) NOT NULL,
	  `projOrg` varchar(200) NOT NULL,
	  `projContent` varchar(200),
	  `startDate` date NOT NULL,
	  `endDate` date NOT NULL,
	  `comments` varchar(500)
	) ENGINE=InnoDB DEFAULT CHARSET=utf32;");

$result = mysqli_query($old_connect,"select * from test_prefixexchangeproject");
while($array = mysqli_fetch_array($result)){

	$newData = null;
	if($array['DeptID'] != 0)
		$newData = DeptMapping::old2new($array['DeptID']);
	if(!$newData)
		$newData = DeptMapping::findByCollege($array['DeptClass']);

	$sql = "insert into cooperation_proj 
		values(0,$newData[0],$newData[1],'$array[ChtName]','$array[ProjName]','$array[PorjOrg]'
		,'$array[ProjectContent]','$array[StartDate]','$array[EndDate]','$array[Memo]')";
	mysqli_query($new_connect,$sql);
}

echo "end\n";

echo "transfer test_prefixgradlevel to graduate_threshold\n";

mysqli_query($new_connect,"
	CREATE TABLE `graduate_threshold` (
	  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  `college` int(11) NOT NULL,
	  `dept` int(11) NOT NULL,
	  `testName` varchar(200) NOT NULL,
	  `testGrade` varchar(200) NOT NULL,
	  `comments` varchar(500)
	) ENGINE=InnoDB DEFAULT CHARSET=utf32;");

$result = mysqli_query($old_connect,"select * from test_prefixgradlevel");

while($array = mysqli_fetch_array($result)){
	$newData = null;
	if($array['DeptID'] != 0)
		$newData = DeptMapping::old2new($array['DeptID']);
	if(!$newData)
		$newData = DeptMapping::findByCollege($array['DeptClass']);

	$sql = "insert into graduate_threshold 
		values(0,$newData[0],$newData[1],'$array[ExamName]','$array[ExamLevel]','$array[Memo]')";

	if(!mysqli_query($new_connect,$sql))
		echo $sql."\n";
}

echo "end\n";

echo "transfer test_prefixinlactivity to internationalize_activity\n";

mysqli_query($new_connect,"
	CREATE TABLE `internationalize_activity` (
	  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  `college` int(11) NOT NULL,
	  `dept` int(11) NOT NULL,
	  `activityName` varchar(200) NOT NULL,
	  `place` varchar(200) NOT NULL,
	  `host` varchar(200) NOT NULL,
	  `guest` varchar(200) NOT NULL,
	  `startDate` date NOT NULL,
	  `endDate` date NOT NULL,
	  `comments` varchar(500)
	) ENGINE=InnoDB DEFAULT CHARSET=utf32;");

$result = mysqli_query($old_connect,"select * from test_prefixinlactivity");

while($array = mysqli_fetch_array($result)){
	$newData = null;
	if($array['DeptID'] != 0)
		$newData = DeptMapping::old2new($array['DeptID']);
	if(!$newData)
		$newData = DeptMapping::findByCollege($array['DeptClass']);

	$array['Host'] = mysqli_escape_string($new_connect,$array['Host']);
	$array['Guest'] = mysqli_escape_string($new_connect,$array['Guest']);

	$sql = "insert into internationalize_activity 
		values(0,$newData[0],$newData[1],'$array[Character]','$array[Place]','$array[Host]'
		,'$array[Guest]','$array[StartDate]','$array[EndDate]','$array[Memo]')";

	if(!mysqli_query($new_connect,$sql))
		echo $sql."\n";
}

echo "end\n";

echo "transfer test_prefixinldegree to transnational_degree\n";

mysqli_query($new_connect,"
	CREATE TABLE `transnational_degree` (
	  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  `college` int(11) NOT NULL,
	  `dept` int(11) NOT NULL,
	  `nation` varchar(20) NOT NULL,
	  `chtName` varchar(200) NOT NULL,
	  `engName` varchar(200) NOT NULL,
	  `stuLevel` int(11) NOT NULL,
	  `year` varchar(200) NOT NULL,
	  `classMode` varchar(200) NOT NULL,
	  `degreeMode` varchar(200) NOT NULL,
	  `comments` varchar(500)
	) ENGINE=InnoDB DEFAULT CHARSET=utf32;");

$result = mysqli_query($old_connect,"select * from test_prefixinldegree");

while($array = mysqli_fetch_array($result)){
$newData = null;
	if($array['DeptID'] != 0)
		$newData = DeptMapping::old2new($array['DeptID']);
	if(!$newData)
		$newData = DeptMapping::findByCollege($array['DeptClass']);

	if($array['PHD']=="o"){
		$sql = "insert into transnational_degree
		values(0,$newData[0],$newData[1],'$array[Nation]','$array[ChtName]','$array[EngName]'
		,1,'','$array[ClassMode]','$array[DegreeMode]','$array[Memo]')";
		if(!mysqli_query($new_connect,$sql))
		echo $sql."\n";
	}
	if($array['Master']=="o"){
		$sql = "insert into transnational_degree
		values(0,$newData[0],$newData[1],'$array[Nation]','$array[ChtName]','$array[EngName]'
		,2,'','$array[ClassMode]','$array[DegreeMode]','$array[Memo]')";
		if(!mysqli_query($new_connect,$sql))
		echo $sql."\n";
	}

	if($array['Bachelor']=="o"){
		$sql = "insert into transnational_degree
		values(0,$newData[0],$newData[1],'$array[Nation]','$array[ChtName]','$array[EngName]'
		,3,'','$array[ClassMode]','$array[DegreeMode]','$array[Memo]')";
		if(!mysqli_query($new_connect,$sql))
		echo $sql."\n";
	}

	// $sql = "insert into transnational_degree
	// 	values(0,$newData[0],$newData[1],'$array[Nation]','$array[ChtName]','$array[EngName]'
	// 	,'$array[Bachelor]','$array[Master]','$array[PHD]','$array[ClassMode]','$array[DegreeMode]','$array[Memo]')";

	if(!mysqli_query($new_connect,$sql))
		echo $sql."\n";
}

echo "end\n";

echo "transfer test_prefixlangclass to foreign_language_class\n";

mysqli_query($new_connect,"
	CREATE TABLE `foreign_language_class` (
	  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  `college` int(11) NOT NULL,
	  `dept` int(11) NOT NULL,
	  `year` varchar(5) NOT NULL,
	  `semester` int(1) NOT NULL,
	  `chtName` varchar(50) NOT NULL,
	  `engName` varchar(200) NOT NULL,
	  `teacher` varchar(20) NOT NULL,
	  `language` varchar(20) NOT NULL,
	  `totalCount` int(11) NOT NULL,
	  `nationalCount` int(11) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf32;");

$result = mysqli_query($old_connect,"select * from test_prefixlangclass");

while($array = mysqli_fetch_array($result)){
	$newData = null;
	if($array['DeptID'] != 0)
		$newData = DeptMapping::old2new($array['DeptID']);
	if(!$newData)
		$newData = DeptMapping::findByCollege($array['DeptClass']);

	$sql = "insert into foreign_language_class
		values(0,$newData[0],$newData[1],'$array[SchoolYear]','$array[Session]','$array[ChtName]','$array[EngName]'
		,'$array[Professor]','$array[Lang]',$array[TotCount],$array[NatCount])";

	if(!mysqli_query($new_connect,$sql))
		echo $sql."\n";
}

echo "end\n";

echo "transfer test_prefixprofinresearch to foreign_prof_vist\n";

mysqli_query($new_connect,"
	CREATE TABLE `foreign_prof_vist` (
	  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	  `college` int(11) NOT NULL,
	  `dept` int(11) NOT NULL,
	  `name` varchar(50) NOT NULL,
	  `profLevel` int(11) NOT NULL,
	  `nation` varchar(20) NOT NULL,
	  `startDate` date NOT NULL,
	  `endDate` date NOT NULL,
	  `comments` varchar(500)
	) ENGINE=InnoDB DEFAULT CHARSET=utf32;");

$result = mysqli_query($old_connect,"select * from test_prefixprofinresearch");

while($array = mysqli_fetch_array($result)){
	$newData = null;
	if($array['DeptID'] != 0)
		$newData = DeptMapping::old2new($array['DeptID']);
	if(!$newData)
		$newData = DeptMapping::findByCollege($array['DeptClass']);

	$array['ChtName'] = mysqli_escape_string($new_connect,$array['ChtName']);
	$array['Memo'] = mysqli_escape_string($new_connect,$array['Memo']);

	$sql = "insert into foreign_prof_vist
		values(0,$newData[0],$newData[1],'$array[ChtName]',$array[ProfLevel]
		,'$array[Nation]','$array[StartDate]','$array[EndDate]','$array[Memo]')";

	if(!mysqli_query($new_connect,$sql))
		echo $sql."\n";
}

echo "end\n";

echo "transfer test_prefixprofoutconference to prof_attend_conference\n";

mysqli_query($new_connect,"
	CREATE TABLE `prof_attend_conference` (
	  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  `college` int(11) NOT NULL,
	  `dept` int(11) NOT NULL,
	  `name` varchar(20) NOT NULL,
	  `profLevel` int(11) NOT NULL,
	  `nation` varchar(20) NOT NULL,
	  `confName` varchar(200) NOT NULL,
	  `startDate` date NOT NULL,
	  `endDate` date NOT NULL,
	  `comments` varchar(500)
	) ENGINE=InnoDB DEFAULT CHARSET=utf32;");

$result = mysqli_query($old_connect,"select * from test_prefixprofoutconference");

while($array = mysqli_fetch_array($result)){
	$newData = null;
	if($array['DeptID'] != 0)
		$newData = DeptMapping::old2new($array['DeptID']);
	if(!$newData)
		$newData = DeptMapping::findByCollege($array['DeptClass']);
		

	$confName = mysqli_escape_string($new_connect,$array['ConfName']);

	$sql = "insert into prof_attend_conference
		values(0,$newData[0],$newData[1],'$array[ChtName]',$array[ProfLevel]
		,'$array[Nation]','$confName','$array[StartDate]','$array[EndDate]','$array[Memo]')";

	if(!mysqli_query($new_connect,$sql)){
		
		echo $sql."\n";
	}
}

echo "end\n";

echo "transfer test_prefixprofoutexchange to prof_exchange\n";

mysqli_query($new_connect,"
	CREATE TABLE `prof_exchange` (
	  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  `college` int(11) NOT NULL,
	  `dept` int(11) NOT NULL,
	  `name` varchar(20) NOT NULL,
	  `profLevel` int(11) NOT NULL,
	  `nation` varchar(20) NOT NULL,
	  `startDate` date NOT NULL,
	  `endDate` date NOT NULL,
	  `comments` varchar(500)
	) ENGINE=InnoDB DEFAULT CHARSET=utf32;");

$result = mysqli_query($old_connect,"select * from test_prefixprofoutexchange");

while($array = mysqli_fetch_array($result)){
	$newData = null;
	if($array['DeptID'] != 0)
		$newData = DeptMapping::old2new($array['DeptID']);
	if(!$newData)
		$newData = DeptMapping::findByCollege($array['DeptClass']);
		
	$sql = "insert into prof_exchange
		values(0,$newData[0],$newData[1],'$array[ChtName]',$array[ProfLevel]
		,'$array[Nation]','$array[StartDate]','$array[EndDate]','$array[Memo]')";

	if(!mysqli_query($new_connect,$sql)){
		
		echo $sql."\n";
	}
}

echo "end\n";

echo "transfer test_prefixprofoutresearch to prof_foreign_research\n";

mysqli_query($new_connect,"
	CREATE TABLE `prof_foreign_research` (
	  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  `college` int(11) NOT NULL,
	  `dept` int(11) NOT NULL,
	  `name` varchar(20) NOT NULL,
	  `profLevel` int(11) NOT NULL,
	  `nation` varchar(200) NOT NULL,
	  `startDate` date NOT NULL,
	  `endDate` date NOT NULL,
	  `comments` varchar(500)
	) ENGINE=InnoDB DEFAULT CHARSET=utf32;");

$result = mysqli_query($old_connect,"select * from test_prefixprofoutresearch");

while($array = mysqli_fetch_array($result)){
	$newData = null;
	if($array['DeptID'] != 0)
		$newData = DeptMapping::old2new($array['DeptID']);
	if(!$newData)
		$newData = DeptMapping::findByCollege($array['DeptClass']);
		
	$sql = "insert into prof_foreign_research
		values(0,$newData[0],$newData[1],'$array[ChtName]',$array[ProfLevel]
		,'$array[Nation]','$array[StartDate]','$array[EndDate]','$array[Memo]')";

	if(!mysqli_query($new_connect,$sql)){
		
		echo $sql."\n";
	}
}

echo "end\n";

echo "transfer test_prefixstudinresearch to short_term_foreign_stu\n";

mysqli_query($new_connect,"
	CREATE TABLE `short_term_foreign_stu` (
	  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  `college` int(11) NOT NULL,
	  `dept` int(11) NOT NULL,
	  `name` varchar(50) NOT NULL,
	  `stuLevel` int(11) NOT NULL,
	  `nation` varchar(50) NOT NULL,
	  `startDate` date NOT NULL,
	  `endDate` date NOT NULL,
	  `comments` varchar(500)
	) ENGINE=InnoDB DEFAULT CHARSET=utf32;");

$result = mysqli_query($old_connect,"select * from test_prefixstudinresearch");

while($array = mysqli_fetch_array($result)){
	$newData = null;
	if($array['DeptID'] != 0)
		$newData = DeptMapping::old2new($array['DeptID']);
	if(!$newData)
		$newData = DeptMapping::findByCollege($array['DeptClass']);
		
	$sql = "insert into short_term_foreign_stu
		values(0,$newData[0],$newData[1],'$array[ChtName]',$array[StudLevel]
		,'$array[Nation]','$array[StartDate]','$array[EndDate]','$array[Memo]')";

	if(!mysqli_query($new_connect,$sql)){
		
		echo $sql."\n";
	}
}

echo "end\n";

echo "transfer test_prefixstudinstudy to foreign_stu\n<br>";

mysqli_query($new_connect,"
	CREATE TABLE `foreign_stu` (
	  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  `college` int(11) NOT NULL,
	  `dept` int(11) NOT NULL,
	  `chtName` varchar(50) NOT NULL,
	  `engName` varchar(50) NOT NULL,
	  `stuID` varchar(15) NOT NULL,
	  `stuLevel` int(11) NOT NULL,
	  `nation` varchar(50) NOT NULL,
	  `engNation` varchar(50) NOT NULL,
	  `startDate` date NOT NULL,
	  `endDate` date NOT NULL,
	  `status` int(11) NOT NULL,
	  `comments` varchar(500)
	) ENGINE=InnoDB DEFAULT CHARSET=utf32;");

$result = mysqli_query($old_connect,"select * from test_prefixstudinstudy");

while($array = mysqli_fetch_array($result)){
	$newData = null;
	if($array['DeptID'] != 0)
		$newData = DeptMapping::old2new($array['DeptID']);
	if(!$newData)
		$newData = DeptMapping::findByCollege($array['DeptClass']);
		
	$array['EngName'] = mysqli_escape_string($new_connect,$array['EngName']);
	if($array['EngName']==""){
		$temp = [];
		$i = 0;
		$string = $array['ChtName'];
		while($i<strlen($string)){
			if(ctype_alpha($string[$i]))
				break;
			$i++;
		}
		$array['ChtName'] = substr($string,0,$i);
		$array['EngName'] = substr($string,$i,strlen($string));
	}
	if($array['EngNation']==""){
		$temp = [];
		$i = 0;
		$string = $array['ChtNation'];
		while($i<strlen($string)){
			if(ctype_alpha($string[$i]))
				break;
			$i++;
		}
		$array['ChtNation'] = substr($string,0,$i);
		$array['EngNation'] = substr($string,$i,strlen($string));
	}

	if($array["StudState"]==0)
		$array['StudState'] = 2;

	$sql = "insert into foreign_stu
		values(0,$newData[0],$newData[1],'$array[ChtName]','$array[EngName]'
		,'$array[ID]',$array[StudLevel],'$array[ChtNation]','$array[EngNation]'
		,'$array[StartDate]','$array[EndDate]',$array[StudState],'$array[Memo]')";

	if(!mysqli_query($new_connect,$sql)){
		
		echo $sql."\n<br>";
	}
}

echo "end\n<br>";

echo "transfer test_prefixstudoutconference to stu_attend_conf\n";

mysqli_query($new_connect,"
	CREATE TABLE `stu_attend_conf` (
	  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  `college` int(11) NOT NULL,
	  `dept` int(11) NOT NULL,
	  `name` varchar(20) NOT NULL,
	  `stuLevel` int(11) NOT NULL,
	  `nation` varchar(20) NOT NULL,
	  `confName` varchar(200) NOT NULL,
	  `startDate` date NOT NULL,
	  `endDate` date NOT NULL,
	  `comments` varchar(500)
	) ENGINE=InnoDB DEFAULT CHARSET=utf32;");

$result = mysqli_query($old_connect,"select * from test_prefixstudoutconference");

while($array = mysqli_fetch_array($result)){
	$newData = null;
	if($array['DeptID'] != 0)
		$newData = DeptMapping::old2new($array['DeptID']);
	if(!$newData)
		$newData = DeptMapping::findByCollege($array['DeptClass']);
		
	$array['ConfName'] = mysqli_escape_string($new_connect,$array['ConfName']);

	$sql = "insert into stu_attend_conf
		values(0,$newData[0],$newData[1],'$array[ChtName]',$array[StudLevel],'$array[Nation]'
		,'$array[ConfName]','$array[StartDate]','$array[EndDate]','$array[Memo]')";

	if(!mysqli_query($new_connect,$sql)){
		
		echo $sql."\n";
	}
}

echo "end\n";

echo "transfer test_prefixstudoutexchange to stu_to_partner_school\n";

mysqli_query($new_connect,"
	CREATE TABLE `stu_to_partner_school` (
	  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  `college` int(11) NOT NULL,
	  `dept` int(11) NOT NULL,
	  `name` varchar(20) NOT NULL,
	  `stuLevel` int(11) NOT NULL,
	  `nation` varchar(200) NOT NULL,
	  `startDate` date NOT NULL,
	  `endDate` date NOT NULL,
	  `comments` varchar(500)
	) ENGINE=InnoDB DEFAULT CHARSET=utf32;");

$result = mysqli_query($old_connect,"select * from test_prefixstudoutexchange");

while($array = mysqli_fetch_array($result)){
	$newData = null;
	if($array['DeptID'] != 0)
		$newData = DeptMapping::old2new($array['DeptID']);
	if(!$newData)
		$newData = DeptMapping::findByCollege($array['DeptClass']);
		

	$sql = "insert into stu_to_partner_school
		values(0,$newData[0],$newData[1],'$array[ChtName]',$array[StudLevel]
		,'$array[Nation]','$array[StartDate]','$array[EndDate]','$array[Memo]')";

	if(!mysqli_query($new_connect,$sql)){
		
		echo $sql."\n";
	}
}

echo "end\n";

echo "transfer test_prefixstudoutresearch to stu_foreign_research\n";

mysqli_query($new_connect,"
	CREATE TABLE `stu_foreign_research` (
	  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  `college` int(11) NOT NULL,
	  `dept` int(11) NOT NULL,
	  `name` varchar(20) NOT NULL,
	  `stuLevel` int(11) NOT NULL,
	  `nation` varchar(50) NOT NULL,
	  `startDate` date NOT NULL,
	  `endDate` date NOT NULL,
	  `comments` varchar(500)
	) ENGINE=InnoDB DEFAULT CHARSET=utf32;");

$result = mysqli_query($old_connect,"select * from test_prefixstudoutresearch");

while($array = mysqli_fetch_array($result)){
	$newData = null;
	if($array['DeptID'] != 0)
		$newData = DeptMapping::old2new($array['DeptID']);
	if(!$newData)
		$newData = DeptMapping::findByCollege($array['DeptClass']);
		

	$sql = "insert into stu_foreign_research
		values(0,$newData[0],$newData[1],'$array[ChtName]',$array[StudLevel]
		,'$array[Nation]','$array[StartDate]','$array[EndDate]','$array[Memo]')";

	if(!mysqli_query($new_connect,$sql)){
		
		echo $sql."\n";
	}
}

echo "end\n";

echo "transfer test_prefixstudinexchange to stu_from_partner_school\n";

mysqli_query($new_connect,"
	CREATE TABLE `stu_from_partner_school` (
	  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  `college` int(11) NOT NULL,
	  `dept` int(11) NOT NULL,
	  `name` varchar(20) NOT NULL,
	  `stuLevel` int(11) NOT NULL,
	  `nation` varchar(50) NOT NULL,
	  `startDate` date NOT NULL,
	  `endDate` date NOT NULL,
	  `comments` varchar(500)
	) ENGINE=InnoDB DEFAULT CHARSET=utf32;");

$result = mysqli_query($old_connect,"select * from test_prefixstudinexchange");

while($array = mysqli_fetch_array($result)){
	$newData = null;
	if($array['DeptID'] != 0)
		$newData = DeptMapping::old2new($array['DeptID']);
	if(!$newData)
		$newData = DeptMapping::findByCollege($array['DeptClass']);
		

	$sql = "insert into stu_from_partner_school
		values(0,$newData[0],$newData[1],'$array[ChtName]',$array[StudLevel]
		,'$array[Nation]','$array[StartDate]','$array[EndDate]','$array[Memo]')";

	if(!mysqli_query($new_connect,$sql)){
		
		echo $sql."\n";
	}
}

echo "end\n";

echo "transfer test_prefixinlorg to attend_international_organization\n";

mysqli_query($new_connect,"
	CREATE TABLE `attend_international_organization` (
	  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  `college` int(11) NOT NULL,
	  `dept` int(11) NOT NULL,
	  `name` varchar(20) NOT NULL,
	  `organization` varchar(200) NOT NULL,
	  `startDate` date NOT NULL,
	  `endDate` date NOT NULL,
	  `comments` varchar(500)
	) ENGINE=InnoDB DEFAULT CHARSET=utf32;");

$result = mysqli_query($old_connect,"select * from test_prefixinlorg");

while($array = mysqli_fetch_array($result)){
	$newData = null;
	// if($array['Enable']!="Y")
	// 	continue;
	if($array['DeptID'] != 0)
		$newData = DeptMapping::old2new($array['DeptID']);
	if(!$newData)
		$newData = DeptMapping::findByCollege($array['DeptClass']);
		

	$sql = "insert into attend_international_organization
		values(0,$newData[0],$newData[1],'$array[ChtName]',
		'$array[OrgName]','$array[StartDate]','$array[EndDate]','$array[Memo]')";

	if(!mysqli_query($new_connect,$sql)){
		
		echo $sql."\n";
	}
}

echo "end";

mysqli_query($new_connect,"
	CREATE TABLE `international_journal_editor` (
	  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  `college` int(11) NOT NULL,
	  `dept` int(11) NOT NULL,
	  `name` varchar(20) NOT NULL,
	  `journalName` varchar(200) NOT NULL,
	  `startDate` date NOT NULL,
	  `endDate` date NOT NULL,
	  `comments` varchar(500)
	) ENGINE=InnoDB DEFAULT CHARSET=utf32;");

mysqli_query($new_connect,"
	CREATE TABLE `foreign_prof_exchange` (
	  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  `college` int(11) NOT NULL,
	  `dept` int(11) NOT NULL,
	  `name` varchar(20) NOT NULL,
	  `profLevel` int(11) NOT NULL,
	  `nation` varchar(20) NOT NULL,
	  `startDate` date NOT NULL,
	  `endDate` date NOT NULL,
	  `comments` varchar(500)
	) ENGINE=InnoDB DEFAULT CHARSET=utf32;");

$result = mysqli_query($new_connect,"show tables");
while($array = mysqli_fetch_row($result)){
	$sql = "ALTER TABLE ".$array[0]." ADD deleted_at datetime";
	echo $sql;
	mysqli_query($new_connect,$sql);
}


?>