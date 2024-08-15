<?php

namespace App\Http\Controllers;

use App\Http\Requests\Schedule\ScheduleStoreRequest;
use App\Http\Resources\Schedule\ScheduleCollection;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::orderBy('id','desc')->paginate(15);
        return new ScheduleCollection($schedules);
    }

    public function store(ScheduleStoreRequest $request)
    {

        $schedule = new Schedule();
        $schedule->time = $request->time;
        $schedule->type = $request->type;
        $schedule->save();
        return response()->json(['message'=>'Schedule Created Successfully'],200);
    }
    public function update(Request $request, Schedule $schedule)
    {
        $schedule = Schedule::where('id',$request->id)->first();
        $schedule->time = $request->time;
        $schedule->type = $request->type;
        $schedule->save();
        return response()->json(['message'=>'Schedule Updated Successfully'],200);
    }

    public function destroy(Request $request, $id)
    {

        $schedule = Schedule::where('id',$id)->delete();

        return response()->json(['message'=>'Schedule Deleted Successfully'],200);
    }
    public function search($query)
    {
        return new ScheduleCollection(Schedule::Where('type', 'like', "%$query%")
            ->paginate(10));
    }
}
