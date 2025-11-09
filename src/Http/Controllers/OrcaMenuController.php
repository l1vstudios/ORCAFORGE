<?php

namespace Orcaforge\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Orcaforge\Models\OrcaMenu;

class OrcaMenuController extends Controller
{
    public function index()
    {
        $menus = OrcaMenu::latest()->paginate(10);
        return view('orcaforge::components.orca_menu.index', compact('menus'));
    }

    public function create()
    {
        $tables = DB::select('SHOW TABLES');
        return view('orcaforge::components.orca_menu.create', compact('tables'));
    }

public function store(Request $request)
{
    try {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'tabel' => 'required|string'
        ]);
        $formStructure = json_decode($request->form_structure, true);
        $menuName   = Str::studly($request->nama_menu);
        $lowerName  = strtolower($request->nama_menu);
        $table      = $request->tabel;
        $modelPath       = app_path("Models/{$menuName}.php");
        $controllerName  = "Orca{$menuName}Controller";
        $controllerPath  = app_path("Http/Controllers/{$controllerName}.php");
        $viewDir         = resource_path("views/components/orca_{$lowerName}");
        $routePath       = base_path('routes/web.php');
        $columns = DB::select("SHOW FULL COLUMNS FROM `$table`");
        if (empty($columns)) {
            return back()->with('error', "Tabel '{$table}' tidak ditemukan atau kosong.");
        }
        $fillable = collect($columns)
            ->pluck('Field')
            ->reject(fn($f) => in_array($f, ['id', 'created_at', 'updated_at']))
            ->values();

        $fillableString = "['" . implode("', '", $fillable->toArray()) . "']";
        /*
        |--------------------------------------------------------------------------
        | ORCA MODEL
        |--------------------------------------------------------------------------
        */
        $modelTemplate = <<<PHP
        <?php
        namespace App\Models;
        use Illuminate\Database\Eloquent\Factories\HasFactory;
        use Illuminate\Database\Eloquent\Model;
        class {$menuName} extends Model
        {
            use HasFactory;

            protected \$table = '{$table}';
            protected \$fillable = {$fillableString};
        }
        PHP;
        File::put($modelPath, trim($modelTemplate));

        /*
        |--------------------------------------------------------------------------
        | ORCA CONTROLLER
        |--------------------------------------------------------------------------
        */
        $controllerTemplate = <<<PHP
        <?php
        namespace App\Http\Controllers;
        use App\Models\\{$menuName};
        use Illuminate\Http\Request;
        use Illuminate\Support\Facades\\Storage;
        use Illuminate\\Support\\Facades\\Artisan;
        use Illuminate\\Support\\Str;
        use Maatwebsite\\Excel\\Facades\\Excel;
        use App\\Exports\\GenericExport;
        class {$controllerName} extends Controller
        {
            public function index(Request \$request)
            {
                \$query = {$menuName}::query();
                if (\$request->search) {
                    foreach ((new {$menuName})->getFillable() as \$col) {
                        \$query->orWhere(\$col, 'like', '%'.\$request->search.'%');
                    }
                }
                \$data = \$query->paginate(10);
                return view('orcaforge::components.orca_{$lowerName}.index', compact('data'));
            }

            public function create()
            {
                return view('orcaforge::components.orca_{$lowerName}.create');
            }
            public function store(Request \$request)
            {
                if (!file_exists(public_path('storage'))) {
                    Artisan::call('storage:link');
                }
                \$data = \$request->all();
                foreach ((new {$menuName})->getFillable() as \$field) {
                    if (\$request->hasFile(\$field)) {
                        \$path = \$request->file(\$field)->store('uploads/{$lowerName}', 'public');
                        \$data[\$field] = \$path;
                    }
                }

                {$menuName}::create(\$data);
                return redirect()->route('orca_{$lowerName}.index')->with('success', '{$menuName} berhasil ditambahkan!');
            }
            public function edit(\$id)
            {
                \$item = {$menuName}::findOrFail(\$id);
                return view('orcaforge::components.orca_{$lowerName}.edit', compact('item'));
            }
            public function update(Request \$request, \$id)
            {
                \$item = {$menuName}::findOrFail(\$id);
                \$data = \$request->all();

                foreach ((new {$menuName})->getFillable() as \$field) {
                    if (\$request->hasFile(\$field)) {
                        if (\$item[\$field] && Storage::disk('public')->exists(\$item[\$field])) {
                            Storage::disk('public')->delete(\$item[\$field]);
                        }
                        \$path = \$request->file(\$field)->store('uploads/{$lowerName}', 'public');
                        \$data[\$field] = \$path;
                    }
                }

                \$item->update(\$data);
                return redirect()->route('orca_{$lowerName}.index')->with('success', '{$menuName} berhasil diperbarui!');
            }

            public function destroy(\$id)
            {
                \$item = {$menuName}::findOrFail(\$id);
                foreach ((new {$menuName})->getFillable() as \$field) {
                    if (\$item[\$field] && Storage::disk('public')->exists(\$item[\$field])) {
                        Storage::disk('public')->delete(\$item[\$field]);
                    }
                }
                \$item->delete();
                return back()->with('success', '{$menuName} berhasil dihapus!');
            }

            public function export()
            {
                \$data = {$menuName}::all();
                return Excel::download(new GenericExport(\$data), '{$lowerName}.xlsx');
            }
        }
        PHP;
        File::put($controllerPath, trim($controllerTemplate));
        /*
        |--------------------------------------------------------------------------
        | ORCA VIEWS PREVIEW
        |--------------------------------------------------------------------------
        */
        File::makeDirectory($viewDir, 0755, true, true);
        $theadCols = '';
        $tbodyCols = '';
        foreach ($fillable as $f) {
            $theadCols .= "<th class='px-4 py-2 text-left capitalize'>{$f}</th>\n";
            $isFile = collect($formStructure)->firstWhere('name', $f)['type'] === 'file';
              if ($isFile) {
                $tbodyCols .= <<<BLADE
                <td class="px-4 py-2 text-left">
                    @if(\$item->{$f})
                        @php \$url = asset('storage/' . \$item->{$f}); @endphp
                        <a href="{{ \$url }}" target="_blank"
                          class="inline-flex items-center gap-1 text-sm font-medium text-white bg-black border border-gray-700 rounded-md px-3 py-1.5 hover:bg-gray-800 transition-all overflow-hidden relative">
                            <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.1)_0px,_rgba(255,255,255,0.1)_2px,_transparent_2px,_transparent_6px)]"></span>
                            <span class="relative z-10 flex items-center gap-1">
                                <i data-lucide="external-link" class="w-4 h-4"></i> Lihat File
                            </span>
                        </a>
                    @else
                        <span class="text-gray-400 italic">Tidak ada</span>
                    @endif
                </td>
                BLADE;
              } else {
                  $tbodyCols .= "<td class='px-4 py-2 text-gray-800'>{{ \$item->{$f} }}</td>\n";
              }
        }
        $indexView = <<<BLADE
              @extends('orcaforge::layouts.app')
              @section('title', '{$menuName} - ORCAFORGE')
              @section('content')
              <div class="space-y-6">
                  @include('orcaforge::components.header')
                  <div class="bg-white rounded-xl shadow-md p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between text-black gap-4">
                      <div class="flex items-center gap-3">
                          <div class="relative bg-black text-white p-2 rounded-lg flex items-center justify-center overflow-hidden border border-gray-700 shadow-sm">
                              <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.1)_0px,_rgba(255,255,255,0.1)_2px,_transparent_2px,_transparent_6px)]"></span>
                              <i data-lucide="list" class="w-5 h-5 relative z-10 text-white"></i>
                          </div>
                          <div>
                              <h3 class="text-lg font-semibold text-gray-900">Daftar {$menuName}</h3>
                              <p class="text-sm text-gray-500">Semua data dari tabel <strong>{$table}</strong></p>
                          </div>
                      </div>
                      <div class="flex gap-2">
                          <a href="{{ route('orca_{$lowerName}.create') }}"
                              class="relative inline-flex items-center justify-center gap-2 text-sm text-white font-medium w-28 h-9 rounded-lg border border-gray-700 bg-black hover:bg-gray-800 transition overflow-hidden">
                              <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                              <span class="relative z-10 flex items-center gap-2"><i data-lucide="plus" class="w-4 h-4"></i> Tambah</span>
                          </a>
                          <a href="{{ url()->current() }}"
                              class="relative inline-flex items-center justify-center gap-2 text-sm text-white font-medium w-28 h-9 rounded-lg border border-gray-700 bg-black hover:bg-gray-800 transition overflow-hidden">
                              <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                              <span class="relative z-10 flex items-center gap-2"><i data-lucide="refresh-cw" class="w-4 h-4"></i> Refresh</span>
                          </a>
                          <a href="{{ route('orca_{$lowerName}.export') }}"
                              class="relative inline-flex items-center justify-center gap-2 text-sm text-white font-medium w-28 h-9 rounded-lg border border-gray-700 bg-black hover:bg-gray-800 transition overflow-hidden">
                              <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                              <span class="relative z-10 flex items-center gap-2"><i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Excel</span>
                          </a>
                      </div>
                  </div>
                  <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 flex flex-wrap gap-3 items-end">
                      <form method="GET" action="{{ url()->current() }}" class="flex flex-wrap gap-3 items-end">
                          <div class="flex flex-col">
                              <label class="text-xs text-gray-500 mb-1">Cari</label>
                              <input type="text" name="search" value="{{ request('search') }}"
                                  placeholder="Ketik kata kunci..."
                                  class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-60">
                          </div>
                          <div class="flex flex-col">
                              <label class="text-xs text-gray-500 mb-1 invisible">Cari</label>
                              <button type="submit"
                                  class="relative inline-flex items-center justify-center gap-2 text-sm text-white font-medium w-28 h-9 rounded-lg border border-gray-700 bg-black hover:bg-gray-800 overflow-hidden transition">
                                  <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                                  <span class="relative z-10 flex items-center gap-2"><i data-lucide="search" class="w-4 h-4"></i> Filter</span>
                              </button>
                          </div>
                      </form>
                  </div>
                  <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 overflow-x-auto">
                      <table class="min-w-full text-sm border-collapse">
                          <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 uppercase text-xs">
                              <tr>
                                  <th class="px-4 py-2 w-10">#</th>
                                  {$theadCols}
                                  <th class="px-4 py-2 text-center w-48">Aksi</th>
                              </tr>
                          </thead>
                          <tbody>
                              @forelse(\$data as \$index => \$item)
                                  <tr class="border-b hover:bg-gray-50 transition">
                                      <td class="px-4 py-2 text-gray-500">{{ \$data->firstItem() + \$index }}</td>
                                      {$tbodyCols}
                                      <td class="px-4 py-2 text-center">
                                          <div class="flex justify-center gap-2">
                                              <a href="{{ route('orca_{$lowerName}.edit', \$item->id) }}"
                                                  class="relative inline-flex items-center justify-center gap-1 text-white text-xs font-medium rounded-md border border-gray-700 bg-black hover:bg-gray-800 w-24 h-9 overflow-hidden">
                                                  <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                                                  <span class="relative z-10 flex items-center gap-1 justify-center">
                                                      <i data-lucide="edit-3" class="w-4 h-4"></i> Edit
                                                  </span>
                                              </a>
                                              <form action="{{ route('orca_{$lowerName}.destroy', \$item->id) }}" method="POST" class="delete-form" data-name="{{ \$item->id }}">
                                                  @csrf
                                                  @method('DELETE')
                                                  <button type="button"
                                                      class="relative inline-flex items-center justify-center gap-1 text-white text-xs font-medium rounded-md border border-gray-700 bg-black hover:bg-gray-800 w-24 h-9 delete-btn overflow-hidden">
                                                      <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                                                      <span class="relative z-10 flex items-center gap-1 justify-center">
                                                          <i data-lucide="trash" class="w-4 h-4"></i> Hapus
                                                      </span>
                                                  </button>
                                              </form>
                                          </div>
                                      </td>
                                  </tr>
                              @empty
                                  <tr><td colspan="100%" class="text-center text-gray-500 py-4 italic">Belum ada data.</td></tr>
                              @endforelse
                          </tbody>
                      </table>
                      @if(\$data->hasPages())
                      <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-3">
                          <div class="text-sm text-gray-600">
                              Menampilkan <span class="font-semibold">{{ \$data->firstItem() }}</span>–<span class="font-semibold">{{ \$data->lastItem() }}</span> dari <span class="font-semibold">{{ \$data->total() }}</span> entri
                          </div>
                          <div class="flex items-center gap-2">
                              {{ \$data->onEachSide(1)->links('pagination::tailwind') }}
                          </div>
                      </div>
                      @endif
                  </div>
              </div>
              <script src="https://unpkg.com/lucide@latest"></script>
              <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
              <script>
              lucide.createIcons();
              document.querySelectorAll('.delete-btn').forEach(btn => {
                  btn.addEventListener('click', function() {
                      const form = this.closest('.delete-form');
                      const name = form.dataset.name;
                      Swal.fire({
                          title: 'Hapus Data?',
                          html: `Data <b>\${name}</b> akan dihapus permanen.`,
                          icon: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: '#ef4444',
                          cancelButtonColor: '#6b7280',
                          confirmButtonText: 'Ya, Hapus',
                          cancelButtonText: 'Batal',
                      }).then(result => {
                          if (result.isConfirmed) form.submit();
                      });
                  });
              });
              </script>
              @endsection
              BLADE;
        File::put("$viewDir/index.blade.php", trim($indexView));

        /*
        |--------------------------------------------------------------------------
        | CREATE & EDIT ORCA
        |--------------------------------------------------------------------------
        */
        $formInputs = '';
        foreach ($columns as $col) {
            $field = $col->Field;
            if (in_array($field, ['id', 'created_at', 'updated_at'])) continue;

            $selectedType = collect($formStructure)->firstWhere('name', $field)['type'] ?? 'text';
            $label = ucwords(str_replace('_', ' ', $field));

            switch ($selectedType) {
                case 'textarea':
                    $inputHtml = "<textarea name='{$field}' class='w-full border rounded-lg px-3 py-2'>{{ old('{$field}') }}</textarea>";
                    break;
                case 'number':
                    $inputHtml = "<input type='number' name='{$field}' value='{{ old('{$field}') }}' class='w-full border rounded-lg px-3 py-2'>";
                    break;
                case 'date':
                    $inputHtml = "<input type='date' name='{$field}' value='{{ old('{$field}') }}' class='w-full border rounded-lg px-3 py-2'>";
                    break;
                case 'file':
                    $inputHtml = "<input type='file' name='{$field}' class='w-full border rounded-lg px-3 py-2'>";
                    break;
                default:
                    $inputHtml = "<input type='text' name='{$field}' value='{{ old('{$field}') }}' class='w-full border rounded-lg px-3 py-2'>";
            }

            $formInputs .= "
            <div class='mb-4'>
                <label class='block text-sm font-medium text-gray-700 mb-1'>{$label}</label>
                {$inputHtml}
            </div>";
        }

        $hasFileInput = collect($formStructure)->contains(fn($f) => $f['type'] === 'file');
        $enctypeAttr = $hasFileInput ? "enctype=\"multipart/form-data\"" : "";

        $createView = <<<BLADE
            @extends('orcaforge::layouts.app')
            @section('title', 'Tambah {$menuName}')
            @section('content')
            <div class="space-y-6">
                @include('orcaforge::components.header')
                <div class="bg-white border rounded-xl shadow-sm p-6 max-w-2xl mx-auto">
                    <h3 class="text-lg font-semibold mb-4">Tambah {$menuName}</h3>
                    <form action="{{ route('orca_{$lowerName}.store') }}" method="POST" {$enctypeAttr}>
                        @csrf
                        {$formInputs}
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('orca_{$lowerName}.index') }}"
                                class="relative inline-flex items-center gap-2 text-sm text-white font-medium px-4 py-2 rounded-lg border border-gray-700 bg-black hover:bg-gray-800 transition overflow-hidden">
                                <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                                <span class="relative z-10 flex items-center gap-2">
                                    <i data-lucide="x" class="w-4 h-4"></i> Batal
                                </span>
                            </a>
                            <button type="submit"
                                class="relative inline-flex items-center gap-2 text-sm text-white font-medium px-4 py-2 rounded-lg border border-gray-700 bg-black hover:bg-gray-800 transition overflow-hidden">
                                <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                                <span class="relative z-10 flex items-center gap-2">
                                    <i data-lucide="save" class="w-4 h-4"></i> Simpan
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <script src="https://unpkg.com/lucide@latest"></script>
            <script>lucide.createIcons();</script>
            @endsection
            BLADE;
            File::put("$viewDir/create.blade.php", trim($createView));
            $editView = str_replace(
                "action=\"{{ route('orca_{$lowerName}.store') }}\"",
                "action=\"{{ route('orca_{$lowerName}.update', \$item->id) }}\" method=\"POST\" enctype=\"multipart/form-data\"",
                $createView
            );
            $editView = str_replace("@csrf", "@csrf\n@method('PUT')", $editView);
            File::put("$viewDir/edit.blade.php", trim($editView));
            /*
            |--------------------------------------------------------------------------
            | ORCA ROUTE
            |--------------------------------------------------------------------------
            */
            $importLine = "use App\\Http\\Controllers\\{$controllerName};";
            $routeLine  = <<<PHP
            Route::resource('orca_{$lowerName}', {$controllerName}::class);
            Route::get('orca_{$lowerName}/export', [{$controllerName}::class, 'export'])->name('orca_{$lowerName}.export');
            PHP;

            $webContent = File::get($routePath);
            if (!str_contains($webContent, $importLine)) {
                $webContent = preg_replace('/(<\?php\s+)/', "$1\n{$importLine}\n", $webContent);
            }
            if (!str_contains($webContent, $routeLine)) {
                $webContent .= "\n{$routeLine}\n";
                File::put($routePath, $webContent);
            }

            OrcaMenu::create([
                'nama_menu' => $menuName,
                'reference_pages' => ['index', 'create', 'edit'],
                'reference_controller' => [$controllerName],
                'reference_model' => [$menuName],
            ]);

            return back()->with('success', "✅ Menu <b>{$menuName}</b> berhasil dibuat dengan preview gambar & PDF (50x50) tanpa ubah desain!");
        } catch (\Throwable $e) {
            return back()->with('error', '❌ Terjadi kesalahan!')
                        ->with('exception_message', $e->getMessage());
        }
    }
    public function getTableColumns(Request $request)
    {
        $table = $request->tabel;
        $columns = DB::select("SHOW FULL COLUMNS FROM `$table`");

        $fields = collect($columns)->map(function ($col) {
            return [
                'name' => $col->Field,
                'type' => 'text', // default
                'comment' => $col->Comment ?? '',
            ];
        });
        return response()->json($fields);
    }
    public function destroy($id)
    {
        $menu = OrcaMenu::findOrFail($id);
        $menuName = $menu->nama_menu;
        $lowerName = strtolower($menuName);

        $viewDir = resource_path("views/components/orca_{$lowerName}");
        if (File::isDirectory($viewDir)) {
            File::deleteDirectory($viewDir);
        }
        $modelPath = app_path("Models/{$menuName}.php");
        $controllerPath = app_path("Http/Controllers/Orca{$menuName}Controller.php");
        if (File::exists($modelPath)) File::delete($modelPath);
        if (File::exists($controllerPath)) File::delete($controllerPath);

        $routePath = base_path('routes/web.php');
        $importLine = "use App\\Http\\Controllers\\Orca{$menuName}Controller;";
        $routeLine1 = "Route::resource('orca_{$lowerName}', Orca{$menuName}Controller::class);";
        $routeLine2 = "Route::get('orca_{$lowerName}/export', [Orca{$menuName}Controller::class, 'export'])->name('orca_{$lowerName}.export');";
        $webContent = File::get($routePath);
        $webContent = str_replace([$importLine, $routeLine1, $routeLine2], '', $webContent);
        File::put($routePath, $webContent);
        $storageDir = storage_path("app/public/uploads/{$lowerName}");
        if (File::isDirectory($storageDir)) {
            File::deleteDirectory($storageDir);
        }
        $menu->delete();
        return redirect()->route('orca_menu.index')
            ->with('success', "🧹 Menu <b>{$menuName}</b> dan semua file terkait berhasil dihapus, termasuk folder upload & route export!");
    }

}
