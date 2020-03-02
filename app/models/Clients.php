<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class ClientsModel extends Eloquent {
    protected $table = 'clients';
    protected $primaryKey = 'client_id';
    public $timestamps = false;
    protected $fillable = ['cegnev', 'szekhely', 'szamlazasi_cim', 'kapcs_nev', 'kapcs_telefon', 'kapcs_email', 'kamra_gps_1', 'kamra_gps_2', 'kamra_cim', 'utolso_karbantartas', 'kovetkezo_karbantartas'];

    public function ClientMaintenances()
    {
        return $this->hasMany('Maintenance', 'client_id');
    }

}