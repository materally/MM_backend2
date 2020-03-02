<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class ArajanlatToUgyfelModel extends Eloquent {
    protected $table = 'arajanlat_to_ugyfel';
    protected $primaryKey = 'arajanlat_to_ugyfel_id';
    public $timestamps = false;
    protected $fillable = ['admin_user_id', 'company_id', 'user_id', 'arajanlat_id', 'email', 'targy', 'tartalom', 'datum', 'token', 'pdf', 'azonosito'];

    public function User()
    {
        return $this->belongsTo('UserModel', 'user_id');
    }

    public function Arajanlat()
    {
        return $this->belongsTo('ArajanlatModel', 'arajanlat_id');
    }

    public function Arjegyzek()
    {
        return $this->hasMany('ArajanlatToUgyfelArjegyzekModel', 'arajanlat_to_ugyfel_id');
    }

    public function Admin()
    {
        return $this->belongsTo('UserModel', 'admin_user_id');
    }

}