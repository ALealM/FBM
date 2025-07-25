<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class Medidas extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'medidas';
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    
    public static function creaRegistro($data) {
            return Periodos::create([
                'periodo' => $data['periodo'],
                'dias' => $data['dias'],
            ]);
    }
    
    public function getPeriodoDiasAttribute() { 
        return $this->attributes['periodo'] . ' - ' . $this->attributes['dias'];
    }
    
    public function usuario(){
        return $this->belongsTo('App\User','id_usuario','id')->first();
    }
    
}
