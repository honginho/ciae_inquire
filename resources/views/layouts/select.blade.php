
	<div class="form-group">
		<label for="college">所屬一級單位名稱</label>
		<select name="college" id="college_option" onchange="change(value)" class="form-control">
			<option value="1">文學院</option>
			<option value="2">理學院</option>
			<option value="3">社科院</option>
			<option value="4">工學院</option>
			<option value="5">管理學院</option>
			<option value="6">法學院</option>
			<option value="7">教育學院</option>
			<option value="8">校長室</option>
			<option value="9">副校長室</option>
			<option value="10">秘書室</option>
			<option value="11">教務處</option>
			<option value="12">學務處</option>
			<option value="13">總務處</option>
			<option value="14">研發處</option>
			<option value="15">圖書館</option>
			<option value="16">清江學習中心</option>
			<option value="17">電算中心</option>
			<option value="18">體育中心</option>
			<option value="19">輔導中心</option>
			<option value="20">環安中心</option>
			<option value="21">人事室</option>
			<option value="22">會計室</option>
			<option value="23">國際處</option>
			<option value="24">通識教育中心</option>
			<option value="25">語言中心</option>
			<option value='26'>前瞻製造系統頂尖研究中心</option>
		</select>
	</div>
	<div class="form-group">
		<label for="dept">所屬系所部門</label>
		<select name="dept" id="dept_option" class="form-control" >
			<option value="0">文學院</option>
			<option value="10">中文系/中文所</option>
			<option value="15">外文系/外文所/英語教學研究所</option>
			<option value="20">歷史系/歷史所</option>
			<option value="25">哲學系/哲學所</option>
			<option value="30">語言學研究所</option>
			<option value="40">台灣文學研究所</option>
		</select>
	</div>
	


	<script type="text/javascript">
		
		function change(value){
			var college =[
				""
			,
			
				"<option value='0'>文學院</option>"+
				"<option value='10'>中文系/中文所</option>"+
				"<option value='15'>外文系/外文所/英語教學研究所</option>"+
				"<option value='20'>歷史系/歷史所</option>"+
				"<option value='25'>哲學系/哲學所</option>"+
				"<option value='30'>語言學研究所</option>"+
				"<option value='40'>台灣文學研究所</option>"
			,
					
				"<option value='0'>理學院</option>"+
				"<option value='10'>數學系/數學所/應數所</option>"+
				"<option value='20'>物理系/物理所</option>"+
				"<option value='35'>地球與環境科學系(含地震學碩士班、地震學博士班、應用地球物理與環境科學碩士班)</option>"+
				"<option value='50'>生科系/生科所</option>"+
				"<option value='60'>化生系/化生所</option>"
			
			,
			
				"<option value='0'>社科院</option>"+
				"<option value='10'>社福系/社福所</option>"+
				"<option value='15'>心理系/心理所</option>"+
				"<option value='20'>勞工系/勞工所</option>"+
				"<option value='30'>政治系/政治所</option>"+
				"<option value='35'>傳播系/傳播所</option>"+
				"<option value='41'>戰略所</option>"
			
			,
			
				"<option value='0'>工學院</option>"+
				"<option value='10'>資工系/資工所</option>"+
				"<option value='15'>電機系/電機所</option>"+
				"<option value='20'>機械系/機械所</option>"+
				"<option value='25'>化工系/化工所</option>"+
				"<option value='30'>通訊系/通訊所</option>"+
				"<option value='41'>光電所</option>"
			
			,
			
				"<option value='0'>管理學院</option>"+
				"<option value='10'>經濟系/經濟所/國經所</option>"+
				"<option value='15'>財金系/財金所</option>"+
				"<option value='20'>企業管理學系(學士班.碩士班.行銷管理碩士班.博士班)</option>"+
				"<option value='26'>會資系/會資所</option>"+
				"<option value='30'>資管系/資管所</option>"
			
			,

				"<option value='0'>法學院</option>"+
				"<option value='10'>法律系/法律所</option>"+
				"<option value='30'>財經法律學系</option>"
			
			,

				"<option value='0'>教育學院</option>"+
				"<option value='40'>成教系/成教所/高齡所</option>"+
				"<option value='45'>犯防系/犯防所</option>"+
				"<option value='50'>教育所</option>"+
				"<option value='54'>課程所/師培中心</option>"+
				"<option value='90'>運動競技學系(運動與休閒教育碩士班)</option>"
			
			,

				"<option value='0'>校長室</option>"
			
			,

				"<option value='0'>副校長室</option>"
			
			, 
			
				"<option value='0'>秘書室</option>"
			
			, 
			
				"<option value='0'>教務處</option>"+
				"<option value='1'>教務處教學組</option>"+
				"<option value='2'>教務處招生組</option>"
			
			, 
			
				"<option value='0'>學務處</option>"
		
			, 
			
				"<option value='0'>總務處</option>"
			
			, 
			
				"<option value='0'>研發處</option>"+
				"<option value='1'>創新育成中心</option>"+
				"<option value='2'>先進工具機研究中心</option>"+
				"<option value='3'>電信研究中心</option>"+
				"<option value='4'>認知科學中心</option>"+
				"<option value='5'>自動化研究中心</option>"+
				"<option value='6'>網際網路中心</option>"+
				"<option value='7'>行銷策略與創意研究中心</option>"+
				"<option value='8'>產業發展與預測研究中心</option>"+
				"<option value='9'>醫療資訊管理研究中心</option>"+
				"<option value='10'>製商整合中心</option>"+
				"<option value='11'>台灣自旋科技研究中心</option>"+
				"<option value='12'>公共政策管理研究中心</option>"+
				"<option value='13'>晶片系統研究中心</option>"+
				"<option value='14'>心理健康推廣中心</option>"+
				"<option value='15'>創業家培育中心</option>"+
				"<option value='16'>精密模具研究中心</option>"+
				"<option value='17'>數位媒體製作研究中心</option>"+
				"<option value='18'>金融創新與債券研究中心</option>"+
				"<option value='19'>奈米設計與原型中心</option>"+
				"<option value='20'>犯罪研究中心</option>"+
				"<option value='21'>民意及市場調查中心</option>"+
				"<option value='22'>財經法律研究中心</option>"+
				"<option value='23'>精緻電能應用研究中心</option>"+
				"<option value='24'>高齡教育研究中心</option>"+
				"<option value='25'>奈米生物檢測科技中心</option>"+
				"<option value='26'>系統生物學與組織工程研究中心</option>"+
				"<option value='27'>法律e化與互動教學研究中心</option>"+
				"<option value='28'>人類對位性基因體學研究中心</option>"+
				"<option value='29'>人文與社會科學研究中心</option>"+
				"<option value='30'>貴重儀器中心</option>"+
				"<option value='31'>體育運動研究發展中心</option>"+
				"<option value='32'>尖端製程與軋鍛技術研發中心</option>"+
				"<option value='33'>台商運籌研究中心</option>"+
				"<option value='34'>國家安全研究中心</option>"+
				"<option value='35'>後殖民研究中心</option>"
			
			
			,
			
				"<option value='0'>圖書館</option>"
			
			, 
			
				"<option value='0'>清江學習中心</option>"
			
			, 
			
				"<option value='0'>電算中心</option>"
			
			, 
			
				"<option value='0'>體育中心</option>"
			
			, 
			
				"<option value='0'>輔導中心</option>"
			
			, 
			
				"<option value='0'>環安中心</option>"
			
			, 
			
				"<option value='0'>人事室</option>"
			
			, 
			
				"<option value='0'>會計室</option>"
			
			, 
			
				"<option value='0'>國際處</option>"
			
			, 
			
				"<option value='0'>通識教育中心</option>"
			
			, 
			
				"<option value='0'>語言中心</option>"
			,
				"<option value='0'>前瞻製造系統頂尖研究中心</option>"
			];

			document.getElementById('dept_option').innerHTML = college[value];
		}

		function lock(value){

			var collegeNode = document.createElement("input");
			var nameAttr = document.createAttribute("name");
			nameAttr.value = "college";
			var valueAttr = document.createAttribute("value");
			valueAttr.value = value.college;
			collegeNode.attributes.setNamedItem(nameAttr);
			collegeNode.attributes.setNamedItem(valueAttr);

			document.getElementById('college_option').append(collegeNode);

			document.getElementById('college_option').value = value.college;
			document.getElementById('college_option').attributes.
				setNamedItem(document.createAttribute('disabled'));
			change(value.college);
			if(value.dept != '0'){
				document.getElementById('dept_option').value = value.dept;
				document.getElementById('dept_option').attributes.setNamedItem(document.createAttribute('disabled'));
				
				var deptNode = document.createElement("input");
				var nameAttr = document.createAttribute("name");
				nameAttr.value = "dept";
				var valueAttr = document.createAttribute("value");
				valueAttr.value = value.dept;
				deptNode.attributes.setNamedItem(nameAttr);
				deptNode.attributes.setNamedItem(valueAttr);
				document.getElementById('dept_option').append(deptNode);
			}
		}
		@if(Auth::user()->permission > 1)
		
			lock(
			{
				college:'{{Auth::user()->college}}',
				dept:'{{Auth::user()->dept}}'
			}
			);	
		@endif
		@if(count($errors)>0)
			document.getElementById('college_option').value = {{old('college')}};
			change({{old('college')}});
			document.getElementById('dept_option').value = {{old('dept')}};
		@endif
	</script>
