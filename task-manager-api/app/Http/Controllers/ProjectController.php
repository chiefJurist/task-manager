<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    //
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Project::class);
        $projects = $request->user()->projects()->with('tasks')->orderByDesc('created_at')->get();
        
        return ProjectResource::collection($projects);
    }

    public function store(StoreProjectRequest $request)
    {
        $project = $request->user()->projects()->create($request->validated());
        $project->load('tasks');
        
        return new ProjectResource($project);
    }

    public function update(UpdateProjectRequest $request, Project $project) 
    {
        $project->update($request->validated());
        $project->load('tasks');

        return new ProjectResource($project); 
    }

    public function destroy(Request $request, Project $project)
    {
        Gate::authorize('delete', $project);
        $project->delete();
        return response()->json(['message' => 'Project Deleted Successfully']);
    }
}
