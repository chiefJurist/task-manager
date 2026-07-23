<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
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
        $projects = $request->user()->projects()->orderBy('name')->get();
        
        return ProjectResource::collection($projects);
    }

    public function store(StoreProjectRequest $request, Project $project)
    {
        Gate::authorize('create', Project::class);
        $project = $request->user()->projects()->create($request->validated());
        
        return new ProjectResource($project);
    }
}
