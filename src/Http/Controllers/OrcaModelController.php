<?php

namespace Orcaforge\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class OrcaModelController extends Controller
{
    /**
     * Tampilkan daftar model yang ada di app/Models
     */
    public function index(Request $request)
    {
        $path = app_path('Models');
        $models = [];

        if (File::exists($path)) {
            $files = File::files($path);
            foreach ($files as $file) {
                if ($file->getExtension() === 'php') {
                    $modelName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                    $models[] = $modelName;
                }
            }
        }

        // fitur pencarian
        $search = $request->input('search');
        if ($search) {
            $models = array_filter($models, fn($m) => Str::contains(strtolower($m), strtolower($search)));
        }

        return view('orcaforge::components.orca_model.index', compact('models', 'search'));
    }

    /**
     * Hapus model dari folder Models
     */
    public function destroy($model)
    {
        $path = app_path("Models/{$model}.php");

        if (!File::exists($path)) {
            return back()->with('error', "Model {$model} tidak ditemukan.");
        }

        try {
            File::delete($path);
            return back()->with('success', "Model {$model} berhasil dihapus!");
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
