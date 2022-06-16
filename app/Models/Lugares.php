<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lugares extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'lugares';
    public $timestamps = false;
    protected $softDelete = true;

    protected $fillable = [
        'sala_id','fila','posicao'];

    public function Salas()
    {
    return $this->belongsTo(Salas::class,'sala_id','id');
    }

    public function Bilhetes()
    {
    return $this->hasMany(Bilhetes::class,'lugar_id','id');
    }


}
