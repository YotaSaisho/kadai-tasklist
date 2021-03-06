<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;    

use Illuminate\Support\Facades\Auth; // 追加

use App\User; // 追加

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
        return view('welcome', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;

        return view('tasks.create', [
            'task' => $task,
        ]);
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
            'title' => 'required|max:191',   // 追加
            'status' => 'required|max:10',   // 追加
            'content' => 'required|max:191',
        ]);

        $request->user()->tasks()->create([
            'title' => $request->title,    // 追加
            'status' => $request->status,    // 追加
            'content' => $request->content,
        ]);

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);
        $user_id = $task->user_id;
        if (Auth::id() == $user_id){
            return view('tasks.show', [
                'task' => $task,
            ]);
        }
        else{
            return redirect('/');
        }
    }
    


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::find($id);
        $user_id = $task->user_id;
        if (Auth::id() == $user_id){
            return view('tasks.edit', [
                'task' => $task,
            ]);
        }
        else{
            return redirect('/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $this->validate($request, [
            'title' => 'required|max:191',   // 追加
            'status' => 'required|max:10',   // 追加
            'content' => 'required|max:191',
        ]);

        
        $task = Task::find($id);
        $user_id = $task->user_id;
        if (Auth::id() == $user_id){
            $task->title = $request->title;    // 追加
            $task->status = $request->status;    // 追加
            $task->content = $request->content;
            $task->save();

            return redirect('/');
        }
        else{
            return redirect('/');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        $user_id = $task->user_id;
        if (Auth::id() == $user_id){
            $task->delete();
            return redirect('/');
        }
        else{
            return redirect('/');
        }
    }
}
