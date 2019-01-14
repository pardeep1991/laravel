<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    protected $table = 'tags';
    protected $fillable=[ 'url', 'title', 'description', 'category', 'review', 'ip', 'ipregion', 'datasource','tags'];
}
