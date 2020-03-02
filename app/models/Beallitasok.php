<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class BeallitasokModel extends Eloquent {
    protected $table = 'beallitasok';
    protected $primaryKey = 'beallitas_id';
    public $timestamps = false;
    protected $fillable = ['beallitas_key', 'beallitas_value'];
}
