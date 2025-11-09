<?php

namespace Orcaforge\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrcaMenu extends Model
{
    use HasFactory;

    protected $table = 'orca_menu';

    protected $fillable = [
        'nama_menu',
        'reference_pages',
        'reference_controller',
        'reference_model',
    ];

    protected $casts = [
        'reference_pages' => 'array',
        'reference_controller' => 'array',
        'reference_model' => 'array',
    ];
}
