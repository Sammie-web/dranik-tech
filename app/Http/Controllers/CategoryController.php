<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use App\Models\Service;

class CategoryController extends Controller
{
    public function __construct()
    {
        // Public pages do not require auth
        $this->middleware('auth')->except(['index', 'show']);
    }

    private function ensureAdmin()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403);
        }
    }

    public function index()
    {
        $categories = ServiceCategory::active()
            ->withCount('services')
            ->ordered()
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function show(ServiceCategory $category)
    {
        $services = Service::active()
            ->where('category_id', $category->id)
            ->with(['provider', 'category', 'reviews'])
            ->paginate(12);

        return view('categories.show', compact('category', 'services'));
    }

    // Admin pages
    public function adminIndex()
    {
        $this->ensureAdmin();
        $categories = ServiceCategory::latest()->paginate(15);
        return view('admin.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        ServiceCategory::create($data);
        return back()->with('success', 'Category created');
    }

    public function update(Request $request, ServiceCategory $category)
    {
        $this->ensureAdmin();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $category->update($data);
        return back()->with('success', 'Category updated');
    }

    public function destroy(ServiceCategory $category)
    {
        $this->ensureAdmin();
        $category->delete();
        return back()->with('success', 'Category deleted');
    }

    public function assignServiceCategory(Request $request, Service $service)
    {
        $this->ensureAdmin();
        $data = $request->validate([
            'category_id' => 'nullable|exists:service_categories,id',
        ]);
        $service->update(['category_id' => $data['category_id'] ?? null]);
        return back()->with('success', 'Service category updated');
    }
}
