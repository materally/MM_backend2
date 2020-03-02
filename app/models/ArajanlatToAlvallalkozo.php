<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class ArajanlatToAlvallalkozoModel extends Eloquent {
    protected $table = 'arajanlat_to_alvallalkozo';
    protected $primaryKey = 'ar_to_al_id';
    public $timestamps = false;
    protected $fillable = ['arajanlat_id', 'alvallalkozo_id', 'user_id', 'targy', 'email', 'tartalom', 'datum', 'token', 'valasz', 'valasz_datum'];

    public function Alvallalkozo()
    {
        return $this->belongsTo('AlvallalkozoModel', 'alvallalkozo_id');
    }

    public function Admin()
    {
        return $this->belongsTo('UserModel', 'user_id');
    }

    public function Arajanlat()
    {
        return $this->belongsTo('ArajanlatModel', 'arajanlat_id');
    }

}