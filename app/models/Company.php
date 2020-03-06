<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class CompanyModel extends Eloquent {
    protected $table = 'company';
    protected $primaryKey = 'company_id';
    public $timestamps = false;
    protected $fillable = ['cegnev', 'szamlazasi_cim', 'adoszam', 'kozponti_telefonszam', 'price_scope'];

    public function CompanyUsers()
    {
        return $this->hasMany('UserModel', 'company_id');
    }

    public function CompanyDelivery()
    {
        return $this->hasMany('CompanyDeliveryModel', 'company_id');
    }

}