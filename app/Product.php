<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
	protected $fillable = ['name','text','alias','images','price', 'date_start','date_end'];

}
