<?php

namespace App\Http\Controllers\other;

use App\CollegeData;
use App\HoldInternationalConference;
use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Validator;

class HoldInternationalConferenceController extends Controller
{
    public function index(Request $request) {
        $sortBy  = 'id';
        $orderBy = "desc";
        $user    = Auth::user();

        if ($request->sortBy != null) {
            $sortBy = $request->sortBy;
        }

        if ($request->orderBy != null) {
            $orderBy = $request->orderBy;
        }

        $holdinternationalconference = HoldInternationalConference::join('college_data', function ($join) {
            $join->on('hold_international_conference.college', 'college_data.college');
            $join->on('hold_international_conference.dept', 'college_data.dept');
        });

        if ($user->permission == 2) {
            $holdinternationalconference = $holdinternationalconference->where('hold_international_conference.college', $user->college);
        } else if ($user->permission == 3) {
            $holdinternationalconference = $holdinternationalconference->where('hold_international_conference.college', $user->college)->where('hold_international_conference.dept', $user->dept);
        }

        $holdinternationalconference = $holdinternationalconference->orderBy($sortBy, $orderBy)->paginate(20);
        $holdinternationalconference->appends($request->except('page'));
        $data = compact('holdinternationalconference', 'user');
        return view('other/hold_international_conference', $data);
    }

    public function insert(Request $request) {
        $rules = [
            'academicYear'     => 'required|max:11',
            'college'          => 'required|max:11',
            'dept'             => 'required|max:11',
            'holdWayId'        => 'required|max:11',
            'holdWay'          => 'required|max:20',
            'confName'         => 'required|max:200',
            'confHoldNationId' => 'required|max:11',
            'startDate'        => 'required',
            'endDate'          => 'required',
            'comments'         => 'max:500',
        ];

        $message = [
            'required' => '必須填寫:attribute欄位',
            'max'      => ':attribute欄位的輸入長度不能大於:max',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($request->startDate > $request->endDate) {
            $validator->errors()->add('endDate', '開始時間必須在結束時間前');
            return redirect('hold_international_conference')->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            return redirect('hold_international_conference')->withErrors($validator)->withInput();
        }

        HoldInternationalConference::create($request->all());
        return redirect('hold_international_conference')->with('success', '新增成功');
    }

    public function search(Request $request) {
        $sortBy  = 'id';
        $orderBy = "desc";
        $user    = Auth::user();

        if ($request->sortBy != null) {
            $sortBy = $request->sortBy;
        }

        if ($request->orderBy != null) {
            $orderBy = $request->orderBy;
        }

        $holdinternationalconference = HoldInternationalConference::join('college_data', function ($join) {
            $join->on('hold_international_conference.college', 'college_data.college');
            $join->on('hold_international_conference.dept', 'college_data.dept');
        });

        if ($request->academicYear != "") {
            $holdinternationalconference = $holdinternationalconference
                ->where('academicYear', "like", "%$request->academicYear%");
        }

        if ($request->college != 0) {
            $holdinternationalconference = $holdinternationalconference
                ->where('hold_international_conference.college', $request->college);
        }

        if ($request->dept != 0) {
            $holdinternationalconference = $holdinternationalconference
                ->where('hold_international_conference.dept', $request->dept);
        }

        if ($request->holdWayId != "") {
            $holdinternationalconference = $holdinternationalconference
                ->where('holdWayId', "like", "%$request->holdWayId%");
        }

        if ($request->holdWay != "") {
            $holdinternationalconference = $holdinternationalconference
                ->where('holdWay', "like", "%$request->holdWay%");
        }

        if ($request->confName != "") {
            $holdinternationalconference = $holdinternationalconference
                ->where('confName', "like", "%$request->confName%");
        }

        if ($request->confHoldNationId != "") {
            $holdinternationalconference = $holdinternationalconference
                ->where('confHoldNationId', "like", "%$request->confHoldNationId%");
        }

        if ($request->startDate != "") {
            $holdinternationalconference = $holdinternationalconference
                ->where('startDate', '>=', "$request->startDate");
        }

        if ($request->endDate != "") {
            $holdinternationalconference = $holdinternationalconference
                ->where('endDate', '<=', "$request->endDate");
        }

        if ($request->comments != "") {
            $holdinternationalconference = $holdinternationalconference
                ->where('comments', "like", "%$request->comments%");
        }

        if ($user->permission == 2) {
            $holdinternationalconference = $holdinternationalconference->where('hold_international_conference.college', $user->college);
        } else if ($user->permission == 3) {
            $holdinternationalconference = $holdinternationalconference->where('hold_international_conference.college', $user->college)->where('hold_international_conference.dept', $user->dept);
        }

        $holdinternationalconference = $holdinternationalconference->orderBy($sortBy, $orderBy)->paginate(20);
        $holdinternationalconference->appends($request->except('page'));
        $data = compact('holdinternationalconference', 'user');
        return view('other/hold_international_conference', $data);
    }

    public function edit($id) {
        $holdinternationalconference = HoldInternationalConference::find($id);
        if (Gate::allows('permission', $holdinternationalconference)) {
            return view('other/hold_international_conference_edit', $holdinternationalconference);
        }

        return redirect('hold_international_conference');
    }

    public function update($id, Request $request) {
        $holdinternationalconference = HoldInternationalConference::find($id);
        if (!Gate::allows('permission', $holdinternationalconference)) {
            return redirect('hold_international_conference');
        }

        $rules = [
            'academicYear'     => 'required|max:11',
            'college'          => 'required|max:11',
            'dept'             => 'required|max:11',
            'holdWayId'        => 'required|max:11',
            'holdWay'          => 'required|max:20',
            'confName'         => 'required|max:200',
            'confHoldNationId' => 'required|max:11',
            'startDate'        => 'required',
            'endDate'          => 'required',
            'comments'         => 'max:500',
        ];

        $message = [
            'required' => '必須填寫:attribute欄位',
            'max'      => ':attribute欄位的輸入長度不能大於:max',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($request->startDate > $request->endDate) {
            $validator->errors()->add('endDate', '開始時間必須在結束時間前');
            return redirect("hold_international_conference/$id")->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            return redirect("hold_international_conference/$id")->withErrors($validator)->withInput();
        }

        $holdinternationalconference->update($request->all());
        return redirect('hold_international_conference')->with('success', '更新成功');
    }

    public function delete($id) {
        $holdinternationalconference = HoldInternationalConference::find($id);
        if (!Gate::allows('permission', $holdinternationalconference)) {
            return redirect('hold_international_conference');
        }

        $holdinternationalconference->delete();
        return redirect('hold_international_conference');
    }

    public function upload(Request $request) {
        Excel::load($request->file('file'), function ($reader) {
            $array    = $reader->toArray();
            $newArray = [];

            foreach ($array as $arrayKey => $item) {
                if ($this->isAllNull($item)) {
                    continue;
                }

                $errorLine = $arrayKey + 2;
                $rules     = [
                    '學年度'        => 'required|max:11',
                    '所屬一級單位'     => 'required|max:11',
                    '所屬系所部門'     => 'required|max:11',
                    '舉辦方式代碼'     => 'required|max:11',
                    '舉辦方式'       => 'required|max:20',
                    '會議名稱'       => 'required|max:200',
                    '會議舉行國家地區代碼' => 'required|max:11',
                    '開始時間'       => 'required|date',
                    '結束時間'       => 'required|date',
                    '備註'         => 'max:500',
                ];
                $message = [
                    'required' => "必須填寫 :attribute 欄位, 第 $errorLine 行",
                    'max'      => ':attribute 欄位的輸入長度不能大於:max' . ", 第 $errorLine 行",
                    'date'     => ':attribute 欄位時間格式錯誤,  應為 xxxx/xx/xx' . ",  第 $errorLine 行",
                ];
                $validator = Validator::make($item, $rules, $message);

                foreach ($item as $key => $value) {
                    switch ($key) {
                        case '學年度':
                            $item['academicYear'] = $value;
                            unset($item[$key]);
                            break;
                        case '所屬一級單位':
                            $item['college'] = $value;
                            unset($item[$key]);
                            break;
                        case '所屬系所部門':
                            $item['dept'] = $value;
                            unset($item[$key]);
                            break;
                        case '舉辦方式代碼':
                            $item['holdWayId'] = $value;
                            unset($item[$key]);
                            break;
                        case '舉辦方式':
                            $item['holdWay'] = $value;
                            unset($item[$key]);
                            break;
                        case '會議名稱':
                            $item['confName'] = $value;
                            unset($item[$key]);
                            break;
                        case '會議舉行國家地區代碼':
                            $item['confHoldNationId'] = $value;
                            unset($item[$key]);
                            break;
                        case '開始時間':
                            $item['startDate'] = $value;
                            unset($item[$key]);
                            break;
                        case '結束時間':
                            $item['endDate'] = $value;
                            unset($item[$key]);
                            break;
                        case '備註':
                            $item['comments'] = $value;
                            unset($item[$key]);
                            break;
                        default:
                            unset($item[$key]);
                            break;
                    }
                }

                if ($item['startDate'] > $item['endDate']) {
                    $validator->errors()->add('date', '開始時間必須在結束時間前' . ", 第 $errorLine 行");
                }
                if (CollegeData::where('college', $item['college'])->where('dept', $item['dept'])->first() == null) {
                    $validator->errors()->add('number', '系所代碼錯誤' . ", 第 $errorLine 行");
                }
                if (!Gate::allows('permission', (object) $item)) {
                    $validator->errors()->add('permission', '無法新增未有權限之系所部門' . ", 第 $errorLine 行");
                }
                if (count($validator->errors()) > 0) {
                    return redirect('hold_international_conference')->withErrors($validator, "upload");
                }
                array_push($newArray, $item);
            }
            HoldInternationalConference::insert($newArray);
        });
        return redirect('hold_international_conference');
    }

    public function example(Request $request) {
        return response()->download(public_path() . '/Excel_example/other/hold_international_conference.xlsx', "辦理國際及兩岸學術研討會.xlsx");
    }

    public function download(Request $request) {
        $sortBy  = 'id';
        $orderBy = "desc";
        $user    = Auth::user();

        if ($request->sortBy != null) {
            $sortBy = $request->sortBy;
        }

        if ($request->orderBy != null) {
            $orderBy = $request->orderBy;
        }

        $holdinternationalconference = HoldInternationalConference::join('college_data', function ($join) {
            $join->on('hold_international_conference.college', 'college_data.college');
            $join->on('hold_international_conference.dept', 'college_data.dept');
        });

        if ($user->permission == 2) {
            $holdinternationalconference = $holdinternationalconference->where('hold_international_conference.college', $user->college);
        } else if ($user->permission == 3) {
            $holdinternationalconference = $holdinternationalconference->where('hold_international_conference.college', $user->college)
                ->where('hold_international_conference.dept', $user->dept);
        }

        $holdinternationalconference         = $holdinternationalconference->orderBy($sortBy, $orderBy)->get()->toArray();
        $holdinternationalconference_array[] = array(/*'索引值',*/ '學年度', '一級單位', '系所部門', '舉辦方式代碼', '舉辦方式', '會議名稱', '會議舉行國家/地區代碼', '開始時間', '結束時間', '備註');
        // dd($holdinternationalconference);
        foreach ($holdinternationalconference as $holdinternationalconference_data) {
            $holdinternationalconference_array[] = array(
                // 'id'               => $holdinternationalconference_data['id'],
                'academicYear'     => $holdinternationalconference_data['academicYear'],
                'chtCollege'       => $holdinternationalconference_data['chtCollege'],
                'chtDept'          => $holdinternationalconference_data['chtDept'],
                'holdWayId'        => $holdinternationalconference_data['holdWayId'],
                'holdWay'          => $holdinternationalconference_data['holdWay'],
                'confName'         => $holdinternationalconference_data['confName'],
                'confHoldNationId' => $holdinternationalconference_data['confHoldNationId'],
                'startDate'        => $holdinternationalconference_data['startDate'],
                'endDate'          => $holdinternationalconference_data['endDate'],
                'comments'         => $holdinternationalconference_data['comments'],
            );
        }

        Excel::create('辦理國際及兩岸學術研討會', function ($excel) use ($holdinternationalconference_array) {
            $excel->setTitle('辦理國際及兩岸學術研討會');
            $excel->sheet('表單', function ($sheet) use ($holdinternationalconference_array) {
                // fromArray(5) parameter:
                //   - source               要輸出的array
                //   - nullValue            array資料內null的呈現方式，預設null
                //   - startCell            資料起始位置，預設A1
                //   - strictNullComparison 預設情況下0會視為空白，若要顯示0則需改成false，預設true
                //   - headingGeneration    表頭是否自動產生。預設為true
                // dd($holdinternationalconference_array);
                $sheet->fromArray($holdinternationalconference_array, null, 'A1', false, false);
            });
        })->download('xlsx');
    }

    private function isAllNull($array) {
        foreach ($array as $item) {
            if ($item != null) {
                return false;
            }
        }
        return true;
    }
}
