<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Month as MonthResource;
use App\Month;
use Illuminate\Support\Facades\DB;

class MonthController extends Controller
{
    public function index(){
        $months = Month::paginate(15);
        return MonthResource::collection($months);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->input('name');
        $exists = DB::table('months')
        ->where('name', $name)->exists();

        if ($exists) {
            return response()->json(['error' => true, 'message' => 'This month exists already']);
        }

        $month = new Month;
        $month->name = $name;
        
        if ($month->save()) {
            return new MonthResource($month);
        }
    }
}
