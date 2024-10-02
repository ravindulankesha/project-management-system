<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Customer;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('customers')->get();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('projects.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'customers' => 'required|array',
        ]);

        // Create the project
        $project = Project::create($request->only('name', 'description'));

        // Attach customers to the project
        $project->customers()->sync($request->customers);

        return redirect()->route('projects.index');
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $project->update($request->only('name', 'description'));

        return response()->json([
            'message' => 'Project updated successfully!',
            'project' => $project
        ]);
    }
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully!'
        ]);
    }
}
