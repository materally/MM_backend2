<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserModel extends Eloquent {
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = false;
    
    protected $hidden = ['password'];

    protected $fillable = ['email', 'password', 'token', 'scope', 'company_id', 'vezeteknev', 'keresztnev', 'telefonszam'];

    public function ClientCompany()
    {
        return $this->belongsTo('CompanyModel', 'company_id');
    }

}