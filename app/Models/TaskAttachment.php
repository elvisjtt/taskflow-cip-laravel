<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskAttachment extends Model
{
    protected $fillable = [
        'filename',
        'original_name',
        'mime_type',
        'size',
        'path',
        'task_id',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
    
}
