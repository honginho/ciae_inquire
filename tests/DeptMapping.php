<?php

class DeptMapping{

	public static function old2new($oid){
		$connect = mysqli_connect("127.0.0.1","root",
			"","ciae_inquire");
		mysqli_query($connect,"set names UTF8");
		$result = mysqli_query($connect,"select * from college_data where oldID = $oid");
		$array = mysqli_fetch_row($result);
		if(!$array){
			return null;
		}
		return $array;
	}

	public static function findByCollege($college){
		$connect = mysqli_connect("127.0.0.1","root",
			"","ciae_inquire");
		mysqli_query($connect,"set names UTF8");
		$result = mysqli_query($connect,"select * from college_data where college = $college AND dept = 0");
		$array = mysqli_fetch_row($result);
		if(!$array){
			echo "select * from college_data where college = $college AND dept = 0";
			return null;
		}
		return $array;
	}
}
?>