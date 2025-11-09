<?php

namespace Orcaforge\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BrainsoftController extends Controller
{
    public function index(Request $request)
    {
        $databaseName = env('DB_DATABASE');
        $tables = DB::select("SHOW TABLES");
        $tableKey = 'Tables_in_' . $databaseName;
        $tableNames = array_map(fn($t) => $t->$tableKey, $tables);

        $search = $request->input('search');
        if (!empty($search)) {
            $tableNames = array_filter($tableNames, function ($table) use ($search) {
                return str_contains(strtolower($table), strtolower($search));
            });
        }

        $perPage = 5;
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $perPage;
        $items = array_slice($tableNames, $offset, $perPage);

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            count($tableNames),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('orcaforge::components.brainsoft_database.index', [
            'tableNames' => $paginator,
            'search' => $search,
        ]);
    }

      public function exportSql()
      {
          try {
              $db = env('DB_DATABASE');
              $user = env('DB_USERNAME');
              $pass = env('DB_PASSWORD');
              $host = env('DB_HOST', '127.0.0.1');
              $filename = "backup_{$db}_" . date('Y-m-d_H-i-s') . ".sql";
              $filePath = storage_path("app/{$filename}");

              $command = sprintf(
                  'mysqldump --user=%s --password=%s --host=%s %s > %s',
                  escapeshellarg($user),
                  escapeshellarg($pass),
                  escapeshellarg($host),
                  escapeshellarg($db),
                  escapeshellarg($filePath)
              );

              exec($command, $output, $returnVar);

              if ($returnVar !== 0) {
                  return back()->with('error', 'Gagal mengekspor database. Pastikan mysqldump tersedia di server.');
              }

              return response()->download($filePath)->deleteFileAfterSend(true);

          } catch (\Exception $e) {
              return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
          }
      }


    public function show($table)
    {
        if (!DB::select("SHOW TABLES LIKE '$table'")) {
            abort(404, "Tabel tidak ditemukan");
        }

        $columns = DB::select("SHOW FULL COLUMNS FROM `$table`");
        $types = [
            'INT', 'BIGINT', 'SMALLINT', 'TINYINT', 'DECIMAL', 'FLOAT', 'DOUBLE',
            'CHAR', 'VARCHAR', 'TEXT', 'MEDIUMTEXT', 'LONGTEXT',
            'DATE', 'DATETIME', 'TIME', 'TIMESTAMP', 'YEAR',
            'BOOLEAN', 'ENUM', 'SET', 'BLOB', 'JSON'
        ];

        return view('orcaforge::components.brainsoft_database.detail', compact('table', 'columns', 'types'));
    }

    public function updateTable(Request $request, $table)
    {
        $columns = $request->input('columns');

        try {
            foreach ($columns as $col) {
                $old = $col['old_name'];
                $name = $col['name'];
                $type = strtoupper($col['type']);
                $length = trim($col['length']) ? "({$col['length']})" : '';
                $null = $col['null'] === 'YES' ? 'NULL' : 'NOT NULL';
                $default = trim($col['default']) !== '' ? "DEFAULT '{$col['default']}'" : '';
                $extra = isset($col['auto_increment']) ? 'AUTO_INCREMENT' : '';

                $sql = "ALTER TABLE `$table` CHANGE `$old` `$name` $type$length $null $default $extra";
                DB::statement($sql);
            }

            return back()->with('success', 'Semua perubahan berhasil disimpan!');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function addColumn(Request $request, $table)
    {
        $name = $request->input('name');
        $type = strtoupper($request->input('type'));
        $length = trim($request->input('length')) ? "({$request->input('length')})" : '';
        $null = $request->input('null') === 'YES' ? 'NULL' : 'NOT NULL';
        $default = trim($request->input('default')) !== '' ? "DEFAULT '{$request->input('default')}'" : '';
        $extra = $request->has('auto_increment') ? 'AUTO_INCREMENT' : '';

        try {
            $sql = "ALTER TABLE `$table` ADD `$name` $type$length $null $default $extra";
            DB::statement($sql);

            return back()->with('success', "Kolom '$name' berhasil ditambahkan!");
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function deleteColumn($table, $column)
    {
        try {
            DB::statement("ALTER TABLE `$table` DROP COLUMN `$column`");
            return back()->with('success', "Kolom '$column' berhasil dihapus!");
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function addTable(Request $request)
      {
          $request->validate([
              'name' => 'required|string|max:255',
              'columns' => 'required|integer|min:1|max:50',
          ]);

          $table = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($request->name));

          try {
              $sql = "CREATE TABLE `$table` (id INT AUTO_INCREMENT PRIMARY KEY)";
              DB::statement($sql);

              session()->flash('new_table', $table);
              return back()->with('success', "Tabel '$table' berhasil dibuat dengan 1 kolom (id).");
          } catch (\Throwable $e) {
              return back()->with('error', $e->getMessage());
          }
      }


    public function createFillableFromTable(Request $request)
{
    $table = $request->input('table');
    $modelName = Str::studly($table);
    $path = app_path("Models/{$modelName}.php");

    try {
        $columns = DB::select("SHOW FULL COLUMNS FROM `$table`");
        $fillable = collect($columns)
            ->pluck('Field')
            ->reject(fn($col) => $col === 'id' || str_contains($col, 'created_at') || str_contains($col, 'updated_at'))
            ->values()
            ->toArray();
        $fillableString = "['" . implode("', '", $fillable) . "']";
        if (!File::exists($path)) {
            File::put($path, <<<PHP
            <?php

            namespace App\Models;

            use Illuminate\Database\Eloquent\Factories\HasFactory;
            use Illuminate\Database\Eloquent\Model;

            class {$modelName} extends Model
            {
                use HasFactory;

                protected \$table = '{$table}';
                protected \$fillable = {$fillableString};
            }

            PHP);
                    } else {
                        // Update model yang sudah ada (replace guarded atau fillable)
                        $content = File::get($path);

                        if (Str::contains($content, 'protected $fillable')) {
                            $content = preg_replace(
                                "/protected\s+\\\$fillable\s+=\s+\[[^\]]*\];/m",
                                "protected \$fillable = {$fillableString};",
                                $content
                            );
                        } elseif (Str::contains($content, 'protected $guarded')) {
                            $content = preg_replace(
                                "/protected\s+\\\$guarded\s+=\s+\[[^\]]*\];/m",
                                "protected \$fillable = {$fillableString};",
                                $content
                            );
                        } else {
                            $content = str_replace(
                                'use HasFactory;',
                                "use HasFactory;\n\n    protected \$fillable = {$fillableString};",
                                $content
                            );
                        }

                        File::put($path, $content);
                    }

                    return response()->json(['success' => "Model {$modelName} berhasil dibuat atau diperbarui dengan fillable."]);

                } catch (\Throwable $e) {
                    return response()->json(['error' => $e->getMessage()], 500);
                }
            }

        public function createModelFromTable(Request $request)
        {
            $table = $request->input('table');
            $modelName = \Illuminate\Support\Str::studly($table);
            $path = app_path("Models/{$modelName}.php");

            if (\Illuminate\Support\Facades\File::exists($path)) {
                return response()->json(['error' => "Model {$modelName} sudah ada."], 400);
            }

            $content = <<<PHP
        <?php

        namespace App\Models;

        use Illuminate\Database\Eloquent\Factories\HasFactory;
        use Illuminate\Database\Eloquent\Model;

        class {$modelName} extends Model
        {
            use HasFactory;

            protected \$table = '{$table}';
            protected \$guarded = [];
        }

        PHP;

            \Illuminate\Support\Facades\File::put($path, $content);

            return response()->json(['success' => "Model {$modelName} berhasil dibuat."]);
        }




    public function deleteTable($table)
      {
          try {
              DB::statement("DROP TABLE `$table`");

              $modelName = Str::studly($table);
              $modelPath = app_path("Models/{$modelName}.php");

              if (File::exists($modelPath)) {
                  File::delete($modelPath);
              }

              return back()->with('success', "Tabel '$table' dan model '{$modelName}' berhasil dihapus!");
          } catch (\Throwable $e) {
              return back()->with('error', $e->getMessage());
          }
      }


}
