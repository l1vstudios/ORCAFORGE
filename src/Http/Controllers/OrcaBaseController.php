<?php

namespace Orcaforge\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class OrcaBaseController extends Controller
{
    public function index(Request $request)
    {
        $path = app_path('Http/Controllers');
        $controllers = [];

        if (File::exists($path)) {
            $files = File::allFiles($path);
            foreach ($files as $file) {
                $name = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                if ($name !== 'Controller') {
                    $controllers[] = $name;
                }
            }
        }

        $search = $request->input('search');
        if ($search) {
            $controllers = array_filter($controllers, fn($c) => Str::contains(strtolower($c), strtolower($search)));
        }

        sort($controllers);

        return view('orcaforge::components.orca_controller.index', compact('controllers', 'search'));
    }

    public function destroy($controller)
    {
        $path = app_path("Http/Controllers/{$controller}.php");

        if (!File::exists($path)) {
            return back()->with('error', "Controller {$controller} tidak ditemukan.");
        }

        try {
            File::delete($path);
            return back()->with('success', "Controller {$controller} berhasil dihapus!");
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
