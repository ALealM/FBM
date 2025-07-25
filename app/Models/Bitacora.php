<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class Bitacora extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'bitacora';
    public $timestamps = false;
    protected $fillable = [
        'id_usuario',
        'fecha',
        'accion'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    
    public static function creaRegistro($accion) {
            return Bitacora::create([
                'id_usuario' => \Auth::User()->id,
                'fecha' => date("Y-m-d H:i:s",strtotime ( '-5 hour' , strtotime ( date("Y-m-d H:i:s")) )),
                'accion' => $accion
            ]);
    }
    
    public function usuario(){
        return $this->belongsTo('App\User','id_usuario','id')->first();
    }
    
}
