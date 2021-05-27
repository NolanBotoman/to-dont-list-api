<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body',
        'done',
        'user_id',
    ];

    protected $table = 'dontdotasks';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
