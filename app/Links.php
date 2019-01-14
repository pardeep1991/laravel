<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Links extends Model
{
    protected $table = 'links';
    protected $fillable=[ 'url', 'title', 'description', 'category', 'review', 'ip', 'ipregion', 'datasource','tag','url_type','url_level1','url_level2','url_level3'];
}
