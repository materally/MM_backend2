<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class ArajanlatModel extends Eloquent {
    protected $table = 'arajanlat_keresek';
    protected $primaryKey = 'arajanlat_id';
    public $timestamps = false;
    protected $fillable = ['company_id', 'user_id', 'megnevezes', 'tartalom', 'gyartasi_ido', 'datum'];

    public function UserData()
    {
        return $this->belongsTo('UserModel', 'user_id');
    }

    public function CompanyData()
    {
        return $this->belongsTo('CompanyModel', 'company_id');
    }

    public function Comments()
    {
        return $this->hasMany('ArajanlatMegjegyzesModel', 'arajanlat_id')->orderBy('megjegyzes_id', 'desc');
    }

    public function ToUgyfel()
    {
        return $this->hasOne('ArajanlatToUgyfelModel', 'arajanlat_id');
    }
}