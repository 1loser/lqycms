<?php
namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Taglist extends Model
{
	protected $table = 'taglist';
    public $timestamps = false;
	protected $guarded = []; //$guarded包含你不想被赋值的字段数组。
	
}