<?php

namespace App\Models;
use \Illuminate\Database\Eloquent\Model;

class TodosModel extends Model
{
    protected $table = 'todos';
    protected $fillable = [];
    protected $guarded = [];
}