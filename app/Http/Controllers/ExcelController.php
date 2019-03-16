<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Excel;

class ExcelController extends Controller
{
    // Excel 文件到處功能

    public function export()
    {
        $cellData = http://www.tuicool.com/articles/[
            ['學號','姓名','成績'],
            ['10001','AAAAA','99'],
            ['10002','BBBBB','92'],
            ['10003','CCCCC','95'],
            ['10004','DDDDD','89'],
            ['10005','EEEEE','96'],
        ];
        Excel::create('學生成績',function ($excel) use ($cellData){
            $excel-gt;sheet('score', function ($sheet) use ($cellData){
                $sheet-gt;rows($cellData);
            });
        })-gt;export('xls');
    }
    Excel::create('學生成績',function($excel) use ($cellData){
     $excel-gt;sheet('score', function($sheet) use ($cellData){
         $sheet-gt;rows($cellData);
     });
})-gt;store('xls')-gt;export('xls');

}