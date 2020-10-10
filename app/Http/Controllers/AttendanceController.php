<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function bulkCreate(Request $request){
        // sleep(5);
        $attendance = $request->input('attendance');
        $date = $request->input('date');
        $month = $request->input('month');
        $created = 0;
        $updated = 0;
        $updatedRecords = [];
        $createdRecords = [];

        foreach($attendance as $employeeId => $present) {
            $exists = DB::table('records')
                ->where('employee_id', $employeeId)
                ->where('date', $date)
                ->exists();

            if ($exists) {
                $record = DB::table('records')
                ->where('employee_id', $employeeId)
                ->where('date', $date)->get();
                $recordId = $record[0]->id;
                if ($present === "Present") {
                    $present = true;
                 }else{
                    $present = false;
                 }
                $existingRecord = Record::findOrFail($recordId);
                // dd($existingRecord);
                $existingRecord->present = $present;
                if ($existingRecord->save()) {
                    $updatedRecords[] = Employee::find($employeeId);
                    $updated += 1;
                }
            }else{
                $record = new Record;
                $record->date = $request->input('date');
                $record->month_id = $request->input('month');
                if ($present === "Present") {
                   $present = true;
                }else{
                   $present = false;
                }
                $record->present = $present;
                $record->employee_id = $employeeId;
                if ($record->save()) {
                    $createdRecords[] = Employee::find($employeeId);
                    $created += 1;
                }
            }
        }
        

        // if ($record->save()) {
            return response()->json(['created' => [$created, $createdRecords], 'updated' => [$updated, $updatedRecords]]);
        // }
    }

    public function create(Request $request)
    {
        $present = $request->input('present');
        $employee_id = $request->input('employee_id');
        $date = $request->input('date');
        $month = $request->input('month');

        if (empty($present)) {
            return response()->json(['error' =>  true, 'message' => 'Please the present value']);
        }


        $exists = DB::table('records')
        ->where('employee_id', $employee_id)
        ->where('date', $date)->exists();

        if ($exists) {
            $record = DB::table('records')
                ->where('employee_id', $employee_id)
                ->where('date', $date)->get();
            $recordId = $record[0]->id;
            if ($present === "Present") {
                $present = true;
            }else{
                $present = false;
            }
            $existingRecord = Record::findOrFail($recordId);
            $existingRecord->present = $present;
            if ($existingRecord->save()) {
                return response()->json(['error' => false, 'message' => 'Successfully updated']);
            }
        }else{
            $record = new Record;
            $record->date = $date;
            $record->month_id = $month;
            if ($present === "Present") {
                $present = true;
            }else{
                $present = false;
            }
            $record->present = $present;
            $record->employee_id = $employee_id;
            if ($record->save()) {
                return response()->json(['error' => false, 'message' => 'Successfully created']);
            }
        }

        // dd([$present, $employee_id, $date]);
    }

    protected function updateRecord($employeeId, $present, $date){
        
    }

    protected function createRecord($employeeId, $present, $date, $request){
        
    }
}
