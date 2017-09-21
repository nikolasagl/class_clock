<?php defined('BASEPATH') OR exit('No direct script access allowed');

    class Pessoa_model extends Model{

        protected $table = 'pessoa';
        protected $guarded = [];

        public function docente(){
            return $this->hasOne(Docente_model::class);
        }
        
        public function tipo(){
            return $this->belongsToMany(Tipo_model::class);
        }

    }

?>