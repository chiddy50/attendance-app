<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\Record;
use App\Http\Resources\Employee as EmployeeResource;
use Illuminate\Support\Facades\DB;


class EmployeeController extends Controller
{
    public function index(){
        $employees = Employee::paginate(15);
        return EmployeeResource::collection($employees);
    }

    public function show(){
        return Employee::find(1)->records;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function single($id)
    {
        $employee = Employee::findOrFail($id);

        // Return single employee as a resource
        return new EmployeeResource($employee);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $employee = new Employee;
        $employee->firstname = $request->input('firstname');
        $employee->lastname = $request->input('lastname');
        $employee->username = $request->input('username');
        $employee->email = $request->input('email');
        $employee->designation = $request->input('designation');
        $employee->team_id = $request->input('team');

        if ($employee->save()) {
            return new EmployeeResource($employee);
        }
    }

    public function search(Request $request){
        $search = $request->input('search');
        if (empty($search)) {
            return response()->json([]);
        }
        // $query = Employee::where('firstname', 'LIKE', "%$search%");
        $employees = DB::table('employees')
        ->where('firstname','LIKE','%'.$search.'%')
        ->orWhere('lastname','LIKE','%'.$search.'%')
        ->get();
 
        // dd($query->toSql(), $query->getBindings());
        return EmployeeResource::collection($employees);
    }

    public function monthRecords(Request $request)
    {
        $employee_id = $request->input('employee_id');
        $month_id = $request->input('month_id');
 
        $records = DB::table('records')
        ->where('employee_id', $employee_id)
        ->where('month_id', $month_id)->get();
        
        // dd($records);
        return response()->json(['records' => $records]);

         
    }

    public function dateRecord(Request $request)
    {
        $employee_id = $request->input('employee_id');
        $date = $request->input('date');

        $record = DB::table('records')
        ->where('employee_id', $employee_id)
        ->where('date', $date)->get();

        return response()->json($record);
    }

    
}
