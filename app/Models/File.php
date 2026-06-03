<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'path', 'parent_id', 'size'];

    public function children()
    {
        return $this->hasMany(File::class, 'parent_id');
    }
}