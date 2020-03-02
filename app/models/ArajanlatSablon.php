<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class ArajanlatSablonModel extends Eloquent {
    protected $table = 'arajanlat_sablonok';
    protected $primaryKey = 'sablon_id';
    public $timestamps = false;
    protected $fillable = ['megnevezes', 'sablon'];

}