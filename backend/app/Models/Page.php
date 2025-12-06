<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'Pages';
    protected $primary_key = 'id';
    protected $fillable = ['page_name'];
}
