<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InfoLalin;
use Illuminate\Http\Request;

class InfoLalinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = InfoLalin::with('author');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('incident_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('incident_date', '<=', $request->end_date);
        }

        $infoLalins = $query->orderByDesc('incident_date')
            ->orderByDesc('start_time')
            ->paginate(15)
            ->appends($request->query());

        return view('admin.info-lalin.index', compact('infoLalins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.info-lalin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lalin_category' => 'required|string|max:50',
            'lalin_category_custom' => 'nullable|string|max:50',
            'tanggal_kejadian' => 'required|date',
            'waktu_kejadian' => 'required|date_format:H:i',
            'lalin_estimated_end' => 'nullable|date_format:H:i',
            'lalin_status' => 'required|in:Masih aktif,Sudah selesai',
            'action' => 'nullable|string',
            'title' => 'required|string|max:60',
            'content' => 'required|string|max:200',
            'lalin_alternative_route' => 'nullable|string|max:255',
            'lalin_location' => 'required|string|max:50',
            'lalin_source' => 'required|string|max:100',
        ]);

        $category = $validated['lalin_category'] === 'Lainnya' ? $validated['lalin_category_custom'] : $validated['lalin_category'];
        if (empty($category)) {
            $category = 'Lainnya';
        }

        $status = ($request->input('action') === 'draft') ? 'Draft' : $validated['lalin_status'];

        InfoLalin::create([
            'category' => $category,
            'incident_date' => $validated['tanggal_kejadian'],
            'start_time' => $validated['waktu_kejadian'],
            'estimated_end_time' => $validated['lalin_estimated_end'] ?? null,
            'status' => $status,
            'title' => $validated['title'],
            'description' => $validated['content'], // from textarea name="content"
            'alternative_route' => $validated['lalin_alternative_route'] ?? null,
            'location' => $validated['lalin_location'],
            'source' => $validated['lalin_source'],
            'author_id' => auth()->id(),
        ]);

        return redirect()->route('admin.info-lalin.index')->with('success', 'Info Lalin berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InfoLalin $infoLalin)
    {
        return view('admin.info-lalin.edit', compact('infoLalin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InfoLalin $infoLalin)
    {
        $validated = $request->validate([
            'lalin_category' => 'required|string|max:50',
            'lalin_category_custom' => 'nullable|string|max:50',
            'tanggal_kejadian' => 'required|date',
            'waktu_kejadian' => 'required|date_format:H:i',
            'lalin_estimated_end' => 'nullable|date_format:H:i',
            'lalin_status' => 'required|in:Masih aktif,Sudah selesai',
            'action' => 'nullable|string',
            'title' => 'required|string|max:60',
            'content' => 'required|string|max:200',
            'lalin_alternative_route' => 'nullable|string|max:255',
            'lalin_location' => 'required|string|max:50',
            'lalin_source' => 'required|string|max:100',
        ]);

        $category = $validated['lalin_category'] === 'Lainnya' ? $validated['lalin_category_custom'] : $validated['lalin_category'];
        if (empty($category)) {
            $category = 'Lainnya';
        }

        $status = ($request->input('action') === 'draft') ? 'Draft' : $validated['lalin_status'];

        $infoLalin->update([
            'category' => $category,
            'incident_date' => $validated['tanggal_kejadian'],
            'start_time' => $validated['waktu_kejadian'],
            'estimated_end_time' => $validated['lalin_estimated_end'] ?? null,
            'status' => $status,
            'title' => $validated['title'],
            'description' => $validated['content'],
            'alternative_route' => $validated['lalin_alternative_route'] ?? null,
            'location' => $validated['lalin_location'],
            'source' => $validated['lalin_source'],
        ]);

        return redirect()->route('admin.info-lalin.index')->with('success', 'Info Lalin berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InfoLalin $infoLalin)
    {
        $infoLalin->delete();
        return redirect()->route('admin.info-lalin.index')->with('success', 'Info Lalin berhasil dihapus.');
    }
}
