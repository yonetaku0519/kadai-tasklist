<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    
    public function index()
    {
        // $tasks = Task::orderBy('id', 'desc')->paginate(10);
        // return view('tasks.index', ['tasks' => $tasks,]);
        
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            // ユーザの投稿の一覧を作成日時の降順で取得
            // （後のChapterで他ユーザの投稿も取得するように変更しますが、現時点ではこのユーザの投稿のみ取得します）
            $tasks = $user->tasks()->orderBy('id', 'desc')->paginate(10);
            

            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }

        // Welcomeビューでそれらを表示
        return view('welcome', $data);
        
    }

    public function create()
    {
        $task = new Task;
        
        return view('tasks.create',['task' => $task,]);
    }

 
    public function store(Request $request)
    {
        
        $request->validate([
            
            'content' => 'required|max:255',
            'status' => 'required|max:10',
                
        ]);

        
        $task = new Task;
        $task->content = $request->content;
        $task->status = $request->status;
        $task->user_id = \Auth::user()->id;
        $task->save();
        
        return redirect('/');
    
    }


    public function show($id)
    {
        
        $task = Task::findOrFail($id);
        
        return view('tasks.show', ['task' => $task,]);
        
    }

    
    public function edit($id)
    {
        
        $task = Task::findOrFail($id);
        
        return view('tasks.edit', ['task' => $task,]);
        
    }

 
    public function update(Request $request, $id)
    {
        
        $request->validate([
            
            'content' => 'required|max:255',
            'status' => 'required|max:10',
                
        ]);
        
        $task = Task::findOrFail($id);
        
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();

        return redirect('/');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $task = Task::findOrFail($id);
        
        $task->delete();

        return redirect('/');
    }
}
