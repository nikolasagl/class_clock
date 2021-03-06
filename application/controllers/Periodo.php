<?php
/**
 *
 */
class Periodo extends CI_Controller
{
    /**
     * Exibe todos os periodos cadastrados no banco de dados.
     * @author Denny Azevedo
     * @since 2017/10/25
     */
    function index()
    {
        $this->load->helper('date');
        $periodos = Periodo_model::withTrashed()->get();
        $this->load->template('periodos/periodos',compact('periodos'),'periodos/js_periodos');
    }

    function cadastrar()
    {
        $this->load->template('periodos/cadastrar',[],'periodos/js_periodos');
    }

    /**
     * Salva um novo periodo no banco de dados.
     * @author Denny Azevedo
     * @since 2017/10/25
     */
    public function salvar()
    {
        $this->form_validation->set_rules('nome','nome',array('required','max_length[6]','is_unique[periodo.nome]','trim','strtolower'));
        $this->form_validation->set_message('is_unique','O ano e o semestre informados já estão cadastrados.');
        $this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
        if ($this->form_validation->run())
        {
            try
            {
                DB::transaction(function()
                {
                    $periodo = new Periodo_model();
                    $periodo->nome = $this->input->post('nome');
                    $periodo->ativo = "0";
                    $periodo->save();
                });
                $this->session->set_flashdata('success','Período cadastrado com sucesso');
            }
            catch (Exception $e)
            {
                $this->session->set_flashdata('danger','Problemas ao cadastrar o período, tente novamente!');
            }
            redirect("Periodo");
        }
        else
        {
            $this->cadastrar();
        }
    }

    /**
     * Formulário para alterar os dados do período
     * @author Denny Azevedo
     * @since 2017/10/25
     */
    function editar($id)
    {
        $periodo = Periodo_model::withTrashed()->findOrFail($id);

        if($periodo->deletado_em != null){
            $this->session->set_flashdata('danger', 'É necessário que o período esteja ativado para ser editado');
            redirect('Periodo');
        }

        $this->load->template('periodos/editar',compact('periodo','id'),'periodos/js_periodos');
    }

    /**
     * Edita os dados do período no banco de dados.
     * @author Denny Azevedo
     * @since 2017/10/26
     */
    public function atualizar($id)
    {
        $periodo = Periodo_model::withTrashed()->findOrFail($id);
        if(ucwords($periodo->nome) != $this->input->post('nome'))
        {
            $this->form_validation->set_rules('nome','nome',array('required','max_length[6]','is_unique[periodo.nome]','trim','strtolower'));
        }
        else
        {
            $this->form_validation->set_rules('nome','nome',array('required','max_length[6]','trim','strtolower'));
        }
        $this->form_validation->set_message('is_unique', 'O ano e o semestre informados já estão cadastrados.');
        $this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
        if ($this->form_validation->run())
        {
            try
            {
                $periodo->nome = $this->input->post('nome');
                $periodo->save();
                $this->session->set_flashdata('success','Período atualizado com sucesso');
            }
            catch (Exception $e)
            {
                $this->session->set_flashdata('danger','Problemas ao atualizar os dados do período, tente novamente!');
            }
            redirect('Periodo');
        }
        else
        {
            $this->editar($id);
        }
    }

    /**
     * Desativa um período cadastrado no banco de dados.
     * @author Denny Azevedo
     * @param ID do período
     */
    function deletar($id)
    {
        try
        {
            $periodo = Periodo_model::findOrFail($id);
            if($periodo->ativo =="1")
            {
                $this->session->set_flashdata('danger','Período atual não pode ser desativado');
            }   
            else
            {
                $periodo->delete();
                $this->session->set_flashdata('success','Período desativado com sucesso');
            }
        }
        catch (Exception $e)
        {
            $this->session->set_flashdata('danger','Erro ao desativar um período, tente novamente!');
        }
        redirect("Periodo");
    }

    /**
     * Ativa o período
     * @author Denny Azevedo
     * @since 2017/10/26
     * @param ID do período
     */
    function ativar($id)
    {
        try
        {
            $ativo = Periodo_model::where('deletado_em', null)->first();
            if(!$ativo){
                $ativo->delete();                
            }
            $periodo = Periodo_model::withTrashed()->findOrFail($id);
            $periodo->restore();
            $this->session->set_flashdata('success','Período ativado com sucesso');
        }
        catch (Exception $e)
        {
            $this->session->set_flashdata('danger','Erro ao ativar o período. Tente novamente!');
        }
        redirect("Periodo");
    }



    function importCsv() {
        $csv_array = CSVImporter::fromForm('csvfile');
        //var_dump($csv_array);
        
        if ($csv_array) {
        // Faz a interação no array para poder gravar os dados na tabela 'disciplinas'
            foreach ($csv_array as $row) {
                try {
                    if(count($row) > 1){
                        $this->session->set_flashdata('danger', 'CSV fora do padrão de Períodos');
                    }else{
                    $periodo = new Periodo_model();
                    $periodo->nome = $row[0];
                                        
                    $periodo->save();
                    $this->session->set_flashdata('success','Período cadastrado com sucesso');
                    }    
                } catch (Exception $ignored){}
                    
            }
                redirect("Periodo");
        }
    }

    function download()
    {
        $this->load->helper('download');
        force_download("periodo.csv", file_get_contents(base_url("uploads/periodo.csv")));
    }
    
    /**
     * Seta o Período atual ("ativo")
     * @author Denny Azevedo
     * @since 2017/12/02
     * @param ID do período
     */
    function setPeriodoAtual($id)
    {
        $periodo = Periodo_model::withTrashed()->findOrFail($id);

        if($periodo->deletado_em == null){
            $stored_pocedure = 'CALL ativa_periodo(?)';
            $result = $this->db->query($stored_pocedure, array('id'=>$id));
            $this->session->set_flashdata('success', 'periodo ' . $periodo->nome . ' definido como atual');
        }else{
            $this->session->set_flashdata('danger', 'O período está desativado e não pode ser definido como atual');
        }

        redirect("Periodo");
    }
}

?>
