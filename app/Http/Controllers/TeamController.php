<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use App\Http\Resources\Team as TeamResource;
use App\Team;
use Illuminate\Support\Facades\DB;
use stdClass;

class TeamController extends Controller
{
    public function index(){
        $teams = Team::paginate(15);
        return TeamResource::collection($teams);
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
        $exists = DB::table('teams')
        ->where('name', $name)->exists();

        if ($exists) {
            return response()->json(['error' => true, 'message' => 'This team exists already']);
        }

        $team = new Team;
        $team->name = $name;
        
        if ($team->save()) {
            return new TeamResource($team);
        }
    }

    public function show($id){
        return Team::find($id)->employees;
    }

    public function count(){
        $allTeams = Team::all();
        $resource = TeamResource::collection($allTeams);
        $teams = [];
        foreach ($resource as $key => $team) {
            $count = Employee::where('team_id', $team->id)->count();
            $std = new stdClass();
            $std->id = $team->id;
            $std->name = $team->name;
            $std->population = $count;
            array_push($teams, $std);
            // dd(["id"=> $team->id, "name" => $team->name]);
        }
        // dd($teams);
        return response()->json($teams);
    }
}
