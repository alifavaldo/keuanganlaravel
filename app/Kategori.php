<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = "kategori";
    protected $fillable = ["kategori"];
    public function transaksi()
    {
        return $this->hasMany('App\Transaksi');
    }
}
