<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $table = 'alumno';
    protected $primaryKey = 'pk_alumno';
    public $timestamps = false;

    protected $fillable = [
        'fk_grupo',
        'nombre',
        'apellido_paterno',
        'carrera',
        'apellido_materno',
        'fecha_nacimiento',
        'telefono',
        'celular',
        'nombre_padre',
        'padre_edad',
        'padre_profesion',
        'nombre_madre',
        'madre_edad',
        'madre_profesion',
        'hermanos_info',
        'tiene_hijos',
        'trabaja',
        'recibe_apoyo_familiar',
        'tiene_beca',
        'tipo_beca',
        'contacto_emergencia_nombre',
        'contacto_emergencia_telefono',
        'contacto_emergencia_celular',
        'correo',
        'direccion',
        'estatus',
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'fk_grupo', 'pk_grupo');
    }

    public function historialMedico()
    {
        return $this->hasOne(HistorialMedico::class, 'fk_alumno', 'pk_alumno');
    }

    public function antecedentesEscolares()
    {
        return $this->hasOne(AntecedentesEscolares::class, 'fk_alumno', 'pk_alumno');
    }

    public function potencialAprendizaje()
    {
        return $this->hasOne(PotencialAprendizaje::class, 'fk_alumno', 'pk_alumno');
    }

    public function sociabilidad()
    {
        return $this->hasOne(Sociabilidad::class, 'fk_alumno', 'pk_alumno');
    }

    public function personalidad()
    {
        return $this->hasOne(Personalidad::class, 'fk_alumno', 'pk_alumno');
    }

    public function informacionExtra()
    {
        return $this->hasOne(InformacionExtra::class, 'fk_alumno', 'pk_alumno');
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido_paterno} {$this->apellido_materno}";
    }

    public function observaciones()
        {
        return $this->hasMany(\App\Models\Observacion::class, 'fk_alumno', 'pk_alumno');
        }

}