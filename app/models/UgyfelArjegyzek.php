<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class UgyfelArjegyzekModel extends Eloquent {
    protected $table = 'ugyfel_arjegyzek';
    protected $primaryKey = 'u_a_id';
    public $timestamps = false;
    protected $fillable = ['ar_id', 'megnevezes', 'mennyiseg_egysege', 'mennyiseg', 'megjegyzes', 'eladasi_ar', 'company_id'];

}
