<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GenericExport;

class OrcaBeritaController extends Controller
{
    public function index(Request $request)
    {
        $query = Berita::query();
        if ($request->search) {
            foreach ((new Berita)->getFillable() as $col) {
                $query->orWhere($col, 'like', '%'.$request->search.'%');
            }
        }
        $data = $query->paginate(10);
        return view('components.orca_berita.index', compact('data'));
    }

    public function create()
    {
        return view('components.orca_berita.create');
    }

    public function store(Request $request)
    {
        if (!file_exists(public_path('storage'))) {
            Artisan::call('storage:link');
        }

        $data = $request->all();
        foreach ((new Berita)->getFillable() as $field) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('uploads/berita', 'public');
                $data[$field] = $path;
            }
        }

        Berita::create($data);
        return redirect()->route('orca_berita.index')->with('success', 'Berita berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $item = Berita::findOrFail($id);
        return view('components.orca_berita.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Berita::findOrFail($id);
        $data = $request->all();

        foreach ((new Berita)->getFillable() as $field) {
            if ($request->hasFile($field)) {
                if ($item[$field] && Storage::disk('public')->exists($item[$field])) {
                    Storage::disk('public')->delete($item[$field]);
                }
                $path = $request->file($field)->store('uploads/berita', 'public');
                $data[$field] = $path;
            }
        }

        $item->update($data);
        return redirect()->route('orca_berita.index')->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $item = Berita::findOrFail($id);
        foreach ((new Berita)->getFillable() as $field) {
            if ($item[$field] && Storage::disk('public')->exists($item[$field])) {
                Storage::disk('public')->delete($item[$field]);
            }
        }
        $item->delete();
        return back()->with('success', 'Berita berhasil dihapus!');
    }

    public function export()
    {
        $data = Berita::all();
        return Excel::download(new GenericExport($data), 'berita.xlsx');
    }
}