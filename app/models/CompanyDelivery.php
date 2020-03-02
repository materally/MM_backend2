<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class CompanyDeliveryModel extends Eloquent {
    protected $table = 'company_delivery';
    protected $primaryKey = 'delivery_id';
    public $timestamps = false;
    protected $fillable = ['company_id', 'address'];
}