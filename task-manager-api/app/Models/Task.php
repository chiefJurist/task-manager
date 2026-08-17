<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id','project_id', 'name', 'priority', 'status'])]
class Task extends Model
{
    use HasFactory;

    protected function cast() : array {
        return [
            'priority' => 'integer',
            'status' => TaskStatus::cases()
        ];
    }
    
    protected static function booted(): void
    {
        static::creating(function (Task $task) {
            if (is_null($task->priority)) {
                $task->priority = static::where('user_id', $task->user_id)
                ->lockForUpdate()
                ->max('priority') + 1;
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
