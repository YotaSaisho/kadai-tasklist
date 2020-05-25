<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User; // 追加

use Illuminate\Support\Facades\Auth; // 追加

class UsersController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(10);

        return view('users.index', [
            'users' => $users,
        ]);
    }
    public function show($id)
    {   
        $user = User::find($id);
        if (\Auth::check()) {
            if (Auth::id() == $user->id){
                $user = User::find($id);
                $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
    
                $data = [
                    'user' => $user,
                    'tasks' => $tasks,
                ];
    
                $data += $this->counts($user);
    
                return view('users.show', $data);
            }
            else  {
                return redirect('/');
            }
        }
    }
}
