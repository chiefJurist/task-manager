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
use League\Uri\Exceptions\OffsetOutOfBounds;

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
        $task = DB::transaction(function () use ($request) {
            $user = User::whereKey($request->user()->id)
            ->lockForUpdate()
            ->firstOrFail();

            return $user->tasks()->create($request->validated());
        });

        $task->load('project');

        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());
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

        DB::transaction(function () use ($tasks, $request) {
            $maxPriority = Task::where('user_id', $request->user()->id)->max('priority');
            $offset = $maxPriority + 1;

            foreach ($tasks as $item) {
                Task::where('user_id', $request->user()->id)
                ->where('id', $item['id'])
                ->update(['priority' => $item['priority'] + $offset]);
            }

            foreach ($tasks as $item) {
                Task::where('user_id', $request->user()->id)
                ->where('id', $item['id'])
                ->update(['priority' => $item['priority']]);
            }
        });

        return response()->json(['message' => 'Reordered Successfully']);
    }
}
