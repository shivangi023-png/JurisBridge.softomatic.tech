<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableLog extends Model
{
    use HasFactory;
    protected $table = 'table_log';
    protected $fillable = [
        'primary_id',
        'table_name',
        'updated_col',
        'old_value',
        'new_value',
        'description'
    ];
}
