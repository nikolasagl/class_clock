<?php defined('BASEPATH') OR exit('No direct script access allowed');

  /**
   *
   */
class checkUser {
    private $CI;
    private $controller;
    private $method;

    public function __construct(){
        $this->CI           = &get_instance();
        $this->controller   = strtolower($this->CI->router->class);
        $this->method       = strtolower($this->CI->router->method);
    }

    /*
    * Verificar se existe usuário logado e qual seu respectivo nível de acesso
    * @param controllers que não precisam de validação e/ou usuário logado
    */
    public function check($param) {

        $user = $this->CI->session->userdata('usuario_logado');

        if ( !in_array($this->controller, $param) ) {

            if (!isset($user)){
                $message = "Necessário estar logado para acessar " . $class;
                $this->CI->session->set_flashdata('danger', $message);
                redirect('/');
            }

            switch($user['tipo']){
                case 1:
                    $this->setAccessToAdmin();
                    break;
                case 2:
                    $this->setAccessToCRA();
                    break;
                case 3;
                    $this->setAccessToDAE();
                    break;
                case 4;
                    $this->setAccessToDocente();
                    break;
                default:
                    redirect('authError');
                    break;
            }
        }

    }

    /*
    * seta os controllers e métodos que o perfil administrador pode acessar
    */
    private function setAccessToAdmin(){
        $acess =
        [
            'turno'          => ['index', 'cadastrar', 'salvar', 'editar', 'atualizar', 'deletar', 'ativar'],
            'tipo_sala'      => ['index', 'cadastrar', 'salvar', 'editar', 'atualizar', 'deletar', 'ativar'],
            'modalidade'     => ['index', 'cadastrar', 'salvar', 'editar', 'atualizar', 'deletar', 'ativar'],
            'curso'          => ['index', 'cadastrar', 'salvar', 'editar', 'atualizar', 'deletar', 'ativar'],
            'pessoa'         => ['index', 'cadastrar', 'salvar', 'editar', 'atualizar', 'deletar', 'ativar'],
            'area'           => ['index', 'cadastrar', 'salvar', 'editar', 'atualizar', 'deletar', 'ativar'],
            'disciplina'     => ['index', 'cadastrar', 'salvar', 'editar', 'atualizar', 'deletar', 'ativar'],
            'consultaDocente' => ['index', 'cadastrar', 'salvar', 'editar', 'atualizar', 'deletar', 'ativar'],
            'turma'          => ['index', 'cadastrar', 'salvar', 'editar', 'atualizar', 'deletar', 'ativar']
        ];

        $this->hasAccess($acess);
    }

    /*
    * seta os controllers e métodos que o perfil CRA pode acessar
    */
    private function setAccessToCRA(){
        $acess =
        [
            'turno'       => ['index'],
            'tipo_sala'   => ['index'],
            'turma'       => ['index', 'cadastrar', 'salvar', 'editar', 'atualizar', 'deletar', 'ativar'],
            'modalidade'  => ['index']
        ];

        $this->hasAccess($acess);
    }

    /*
    * seta os controllers e métodos que o perfil DAE pode acessar
    */
    private function setAccessToDAE(){
        $acess =
        [
            'turno'       => ['index'],
            'tipo_sala'   => ['index'],
            'modalidade'  => ['index']
        ];

        $this->hasAccess($acess);
    }

    /*
    * seta os controllers e métodos que o perfil docente pode acessar
    */
    private function setAccessToDocente(){
        $acess =
        [
            'turno'       => ['index'],
            'tipo_sala'   => ['index'],
            'consultaDocente' => ['index'],
            'modalidade'  => ['index']
        ];

        $this->hasAccess($acess);
    }

    /*
    * verifica se o usuário pode acessar controller e método requisitado
    */
    private function hasAccess ($acess) {
      /*    if (!key_exists($this->controller, $acess))
            redirect('authError');

        if(!in_array($this->method, $acess[$this->controller]))
            redirect('authError');  */
    }

}


?>