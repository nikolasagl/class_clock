<?php
/**
*  Regras de negócio sobre disciplinas.
*  @since 2017/11/13
*  @author Gilberto Pagani Sevilio
*/
class ConsultaDocente extends CI_Controller {
    function index () {
        if (in_array(1, $this->session->userdata()["usuario_logado"]["tipos"]) || in_array(3, $this->session->userdata()["usuario_logado"]["tipos"])) {
            $preferencias = Docente_preferencia_model::where("periodo_id", Periodo_model::periodoAtivo()->id)->get();
            $this->load->template('consultaDocente/consultaDocente',compact('preferencias'),'consultaDocente/js_consultaDocente');
        } else {
            $user = $this->session->userdata();
            // $data = array(
            //     "curso" => Pessoa_model::join('docente', 'pessoa.id', '=', 'docente.pessoa_id')
            //     ->join('curso', 'docente.id', '=', 'curso.docente_id')
            //     ->where('pessoa.id', '=', $user["usuario_logado"]["id"])
            //     ->select('curso.id')
            //     ->first(),
            // );
            //
            // if ($data['curso']) {

                // $curso_docente = array(
                //     "curso" =>  Docente_preferencia_model//::join('turno', 'docente_preferencia.turno_id', '=', 'turno.turno_id')
                //     // ->where('docente_preferencia.curso_id', '=', $data["curso"]["id"])
                //     ::where('docente_preferencia.curso_id', '=', Docente_model::where("pessoa_id",  $user["usuario_logado"]["id"])->first()->cursos[0]->id)
                //     ->select('*')
                //     ->get(),
                // );

                $curso_docente = Docente_model::where("pessoa_id",  $user["usuario_logado"]["id"])->first()->cursos[0]->id;

                $preferencias = Docente_preferencia_model::where("curso_id", $curso_docente)
                  ->where("periodo_id", Periodo_model::periodoAtivo()->id)->get();
                //$curso_docente['curso'];
                $this->load->template('consultaDocente/consultaDocente',compact('preferencias'),'consultaDocente/js_consultaDocente');
            // } else {
            //     redirect('authError');
            // }
        }
    }
}
