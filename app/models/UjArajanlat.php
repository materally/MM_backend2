<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class UjArajanlatModel extends Eloquent {
    protected $table = 'uj_arajanlat';
    protected $primaryKey = 'uj_arajanlat_id';
    public $timestamps = false;
    protected $fillable = ['admin_user_id', 'company_id', 'user_id', 'felado_email', 'felado_telefon', 'felado_nev', 'cimzett_email', 'cimzett_telefonszam', 'cimzett_nev', 'megnevezes', 'tartalom', 'datum', 'token', 'pdf', 'azonosito'];

    public function Arjegyzek()
    {
        return $this->hasMany('UjArajanlatArjegyzekModel', 'uj_arajanlat_id');
    }

    public function User()
    {
        return $this->belongsTo('UserModel', 'user_id');
    }

    public function Admin()
    {
        return $this->belongsTo('UserModel', 'admin_user_id');
    }

    public function Company()
    {
        return $this->belongsTo('CompanyModel', 'company_id');
    }

}