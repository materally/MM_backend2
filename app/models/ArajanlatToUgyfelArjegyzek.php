<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class ArajanlatToUgyfelArjegyzekModel extends Eloquent {
    protected $table = 'ato_arjegyzek';
    protected $primaryKey = 'ato_arjegyzek_id';
    public $timestamps = false;
    protected $fillable = ['arajanlat_to_ugyfel_id', 'ar_id', 'megnevezes', 'mennyiseg', 'mennyiseg_egysege', 'netto_egysegar', 'megjegyzes'];
}