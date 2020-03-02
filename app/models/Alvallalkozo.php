<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class AlvallalkozoModel extends Eloquent {
    protected $table = 'alvallalkozo';
    protected $primaryKey = 'alvallalkozo_id';
    public $timestamps = false;
    protected $fillable = ['cegnev', 'token', 'email', 'vezeteknev', 'keresztnev', 'telefon', 'megnevezes', 'adoszam', 'bankszamlaszam', 'megjegyzes'];

}