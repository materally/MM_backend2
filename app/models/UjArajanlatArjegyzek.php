<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class UjArajanlatArjegyzekModel extends Eloquent {
    protected $table = 'uj_arajanlat_arjegyzek';
    protected $primaryKey = 'uj_arajanlat_arjegyzek_id';
    public $timestamps = false;
    protected $fillable = ['uj_arajanlat_id', 'ar_id', 'megnevezes', 'mennyiseg', 'mennyiseg_egysege', 'netto_egysegar', 'megjegyzes'];

}