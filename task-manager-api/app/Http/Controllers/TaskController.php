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

        $task = DB::transaction(function () use ($request) {
            return $request->user()->tasks()->create($request->validated());
        });

        $task->load('project');

        return new TaskResource($task);
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
        });

        return response()->json(['message' => 'Task Deleted Successfully.']);
    }
    
    public function reorder(ReorderTaskRequest $request)
    {
        $validated = $request->validated();
        $tasks = $validated['tasks'];

        DB::transaction(function () use ($tasks) {
            foreach ($tasks as $item) {
                Task::where('id', $item['id'])->update(['priority' => null]);
            }

            foreach ($tasks as $item) {
                Task::where('id', $item['id'])->update(['priority' => $item['priority']]);
            }
        });

        $tasks = $request->user()->tasks()->with('project')->orderBy('priority')->get();
        return TaskResource::collection($tasks);
    }
}
