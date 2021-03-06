<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;
use App\User;

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
        // userIdのチェックを追加:2021/06/15
        if (\Auth::id() === $task->user_id) {
            return view('tasks.show', ['task' => $task,]);
        }
        
        return redirect('/');
        
    }

    
    public function edit($id)
    {
        
        $task = Task::findOrFail($id);
        
        // userIdのチェックを追加:2021/06/15
        if (\Auth::id() === $task->user_id) {
            return view('tasks.edit', ['task' => $task,]);
        } 
        
        return redirect('/');
    }

 
    public function update(Request $request, $id)
    {
        
        $request->validate([
            
            'content' => 'required|max:255',
            'status' => 'required|max:10',
                
        ]);
        
        $task = Task::findOrFail($id);
        
        // userIdのチェックを追加
        if (\Auth::id() === $task->user_id) {
            $task->content = $request->content;
            $task->status = $request->status;
            $task->save();
        } 
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
        
        // userIdのチェックを追加:2021/06/15
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        } 

        return redirect('/');
    }
}
