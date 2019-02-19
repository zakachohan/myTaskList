<?php

namespace App\Http\Controllers;
use Auth;
use App\Task;
use Illuminate\Http\Request;
use DB;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::where(['user_id' => Auth::user()->id])->get();
        return response()->json([
            'tasks'    => $tasks,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'        => 'required|max:255',
            'description' => 'required',
        ]);

        $task = Task::create([
            'name'        => request('name'),
            'description' => request('description'),
            'status' => request('status'),
            'user_id'     => Auth::user()->id
        ]);

        return response()->json([
            'task'    => $task,
            'message' => 'Success'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $this->validate($request, [
            'name'        => 'required|max:255',
            'description' => 'required',
        ]);

        $task->name = request('name');
        $task->description = request('description');
        $task->status = request('status');
        $task->save();

        return response()->json([
            'message' => 'Task updated successfully!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json([
            'message' => 'Task deleted successfully!'
        ], 200);
    }
    /**
     * Display a listing of the resource of last 1 hour records.
     *
     * @return \Illuminate\Http\Response
     */
    public function history()
    {        
        $latestTasks = DB::select( DB::raw("select 
             (select count(*) 
                    from tasks t 
                    where tasks.updated_at >= t.updated_at and t.status = 0  )  countt

            FROM tasks
            WHERE updated_at > DATE_ADD( NOW(), INTERVAL -2 HOUR )
            and status = 0
            order by updated_at ASC
             ") ); 
         

        return response()->json([
            'latestTasks'    => $latestTasks
        ], 200);
    }
}
