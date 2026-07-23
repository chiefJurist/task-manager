<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReorderTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    //
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Task::class);
        $projectId = $request->query('project_id');
        $projectId = $projectId !== null ? (int) $projectId : null;

        $tasks = $request->user()->tasks()->where('project_id', $projectId)->orderBy('name')->get();
        
        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request, Task $task)
    {
        Gate::authorize('create', Task::class);
        $projectId = $request->validated('project_id');

        $nextPriority = $request->user()->tasks()
            ->where('project_id', $projectId)
            ->max('priority') + 1;

        $task = $request->user()->tasks()->create([
            ...$request->validated(),
            'priority' => $nextPriority,
        ]);

        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        Gate::authorize('update', $task);
        $task->update($request->validated());

        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);

        DB::transaction(function () use ($task) {
            $userId = $task->user_id;
            $projectId = $task->project_id;
            $deletedPriority = $task->priority;

            $task->delete();

            Task::where('user_id', $userId)
                ->where('project_id', $projectId)
                ->where('priority', '>', $deletedPriority)
                ->decrement('priority');

            return response()->json(['message' => 'Task deleted successfully.'], 200);
        });
    }
    
    public function reorder(ReorderTaskRequest $request)
    {
        $taskIds = $request->validated('task_ids');
        $projectId = $request->validated('project_id');

        $tasks = $request->user()->tasks()
            ->where('project_id', $projectId)
            ->whereIn('id', $taskIds)
            ->get()
            ->keyBy('id');

        if ($tasks->count() !== count($taskIds)) {
            abort(422, 'Invalid tasks list.');
        };

        DB::transaction(function () use ($taskIds) {
            foreach ($taskIds as $index => $taskId) {
                Task::where('id', $taskId)->update(['priority' => $index + 1]);
            }
        });

        $ordered = collect($taskIds)->map(fn ($id) => $tasks[$id]);

        return TaskResource::collection($ordered);
    }
}
