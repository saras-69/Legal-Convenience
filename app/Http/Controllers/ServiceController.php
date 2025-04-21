<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $services = Service::where('lsp_id', $user->id)->get();
        
        return view('lsp.services.index', compact('services'));
    }

    public function create()
    {
        return view('lsp.services.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:15',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        Service::create([
            'lsp_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'duration_minutes' => $request->duration_minutes,
            'is_active' => true,
        ]);

        return redirect()->route('lsp.services.index')->with('success', 'Service created successfully!');
    }

    public function edit(Service $service)
    {
        $this->authorize('update', $service);
        
        return view('lsp.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $this->authorize('update', $service);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:15',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $service->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'duration_minutes' => $request->duration_minutes,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('lsp.services.index')->with('success', 'Service updated successfully!');
    }

    public function destroy(Service $service)
    {
        $this->authorize('delete', $service);
        
        $service->delete();
        
        return redirect()->route('lsp.services.index')->with('success', 'Service deleted successfully!');
    }

    public function search(Request $request)
    {
        $query = Service::query()->where('is_active', true);
        
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $services = $query->with('lsp.lspProfile')->get();
        
        return view('services.search', compact('services'));
    }

    public function show(Service $service)
    {
        $service->load('lsp.lspProfile', 'lsp.receivedReviews');
        
        return view('services.show', compact('service'));
    }
}