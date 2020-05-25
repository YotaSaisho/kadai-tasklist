<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User; // 追加

class Task extends Model
{
    protected $fillable = ['title','status','content', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
