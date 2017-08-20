<?php defined('BASEPATH') OR exit('No direct script access allowed');

    /** 
     * Esta classe é um modelo do banco de dados que representa os turnos que a instituição possui
     * @author Lucas Leonel
     * @since 2017/08/19
    */

    class Turno_model extends Model{

        protected $table = 'turno';
        public $timestamps = 'false';

        /**
         * Função responsável para retornar todos os turnos
         * @author Lucas Leonel
         * @since 2017/08/19
        */

        public function getAll() {
            $result = Turno_model::all();
            return $result;
          }

        /**
         * Função responsável para retornar todos os horarios linkados com o turno
         * @author Lucas Leonel
         * @since 2017/08/19
        */

        public function getHorarios(){
            return $this->hasMany('Horario_model.php');
        }
    }

?>