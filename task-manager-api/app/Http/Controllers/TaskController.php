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
        $tasks = $request->user()->tasks()->with('project')->orderBy('priority')->get();
        
        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request)
    {
        Gate::authorize('create', Task::class);

        DB::transaction(function () use ($request) {
            $request->user()->tasks()->create($request->validated());
        });

        return response()->json(['message' => 'Task Created Successfully']);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        Gate::authorize('update', $task);
        $task->update($request->validated());
        $task->load('project');

        return new TaskResource($task);
    }

    public function show(Task $task)
    {
        Gate::authorize('view', $task);
        $task->load('project');

        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);

        DB::transaction(function () use ($task) {
            $userId = $task->user_id;
            $deletedPriority = $task->priority;

            $task->delete();

            Task::where('user_id', $userId)
                ->where('priority', '>', $deletedPriority)
                ->decrement('priority');

            return response()->json(['message' => 'Task Deleted Successfully.'], 200);
        });
    }
    
    public function reorder(ReorderTaskRequest $request)
    {
        $taskIds = $request->validated('task_ids');

        $tasks = $request->user()->tasks()
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
