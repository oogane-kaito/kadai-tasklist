<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task; 

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (\Auth::check()) {
        // 認証済みユーザーを取得
        $user = \Auth::user();
        $tasks =  $user->tasks()->get();
        return view("tasks.index",[
            "tasks" => $tasks,
        ]);
    }else{
        // // トップページへリダイレクトさせる
        return redirect('/');
    }
       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ユーザーが認証されているか確認
        if (!\Auth::check()) {
            // 認証されていない場合、ログインページにリダイレクト
            return redirect("/login")->with('error', 'ログインが必要です。');
        }
        $task = new Task;

        
    
        // メッセージ作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
         // バリデーション
        $request->validate([
            'status' => 'required|max:10',   // 追加
            'content' => 'required|max:255',
        ]);

        // メッセージを作成
        // 認証済みユーザー（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request ->status
        ]);

        // // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
                // ユーザーが認証されているか確認
        if (!\Auth::check()) {
            // 認証されていない場合、ログインページにリダイレクト
            return redirect("/login")->with('error', 'ログインが必要です。');
        }
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);

        if (\Auth::id() !== $task->user_id){
            return redirect("/")->with('error', 'ユーザーが違います');
        }

        // メッセージ詳細ビューでそれを表示
        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!\Auth::check()) {
            // 認証されていない場合、ログインページにリダイレクト
            return redirect("/login")->with('error', 'ログインが必要です。');
        }
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        if (\Auth::id() !== $task->user_id){
            return redirect("/")->with('error', 'ユーザーが違います');
        }


        // メッセージ編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        // バリデーション
        $request->validate([
            'status' => 'required|max:10',   
            'content' => 'required|max:255',
        ]);

        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        if (\Auth::id() === $task->user_id){
            // メッセージを更新
            $task->status = $request->status;
            $task->content = $request->content;
            $task->save();
            // トップページへリダイレクトさせる
            return redirect('/');
        }

        


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        if (\Auth::id() === $task->user_id) {
            $task->delete();
            return redirect('/');
        }else{
            return redirect('/');
        }

    }
}
