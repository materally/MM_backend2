<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class ArajanlatMegjegyzesModel extends Eloquent {
    protected $table = 'arajanlat_megjegyzes';
    protected $primaryKey = 'megjegyzes_id';
    public $timestamps = false;
    protected $fillable = ['arajanlat_id', 'user_id', 'name', 'email', 'comment', 'date'];
}