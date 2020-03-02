<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class ArjegyzekModel extends Eloquent {
    protected $table = 'arjegyzek';
    protected $primaryKey = 'ar_id';
    public $timestamps = false;
    protected $fillable = ['megnevezes', 'mennyiseg_egysege', 'mennyiseg', 'alapanyag_netto_bekereules_ar', 'nyomtatas_netto_bekerules_ar', 'egyeb_koltseg', 'bekerules_netto_ar', 'megjegyzes', 'eladasi_netto_vip_ar', 'eladasi_netto_nagyker_ar', 'eladasi_netto_kisker_ar'];

}
