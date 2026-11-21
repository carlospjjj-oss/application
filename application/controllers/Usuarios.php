<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Usuarios extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'cUsuario')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para configurar os usuários.');
            redirect(base_url());
        }

        $this->load->helper('form');
        $this->load->model('usuarios_model');
        $this->data['menuUsuarios'] = 'Usuários';
        $this->data['menuConfiguracoes'] = 'Configurações';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = base_url() . 'index.php/usuarios/gerenciar/';
        $this->data['configuration']['total_rows'] = $this->usuarios_model->count('usuarios');

        $this->pagination->initialize($this->data['configuration']);

        $this->data['results'] = $this->usuarios_model->get($this->data['configuration']['per_page'], $this->uri->segment(3));

        $this->data['view'] = 'usuarios/usuarios';

        return $this->layout();
    }

    public function adicionar()
    {
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('usuarios') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-danger">' . validation_errors() . '</div>' : false);
        } else {
            $email = $this->input->post('email');
            if ($email && $this->usuarios_model->emailExists($email)) {
                $this->data['custom_error'] = '<div class="alert alert-danger">Este e-mail já está em uso por outro usuário.</div>';
                $this->load->model('permissoes_model');
                $this->data['permissoes'] = $this->permissoes_model->getActive('permissoes', 'permissoes.idPermissao,permissoes.nome');
                $this->data['view'] = 'usuarios/adicionarUsuario';
                return $this->layout();
            }
            $data = [
                'nome' => set_value('nome'),
                'rg' => set_value('rg'),
                'cpf' => set_value('cpf'),
                'cep' => set_value('cep'),
                'rua' => set_value('rua'),
                'numero' => set_value('numero'),
                'bairro' => set_value('bairro'),
                'cidade' => set_value('cidade'),
                'estado' => set_value('estado'),
                'email' => set_value('email'),
                'senha' => password_hash($this->input->post('senha'), PASSWORD_DEFAULT),
                'telefone' => set_value('telefone'),
                'celular' => set_value('celular'),
                'dataExpiracao' => set_value('dataExpiracao'),
                'situacao' => set_value('situacao'),
                'permissoes_id' => $this->input->post('permissoes_id'),
                'dataCadastro' => date('Y-m-d'),
            ];

            if ($this->usuarios_model->add('usuarios', $data) == true) {
                $this->session->set_flashdata('success', 'Usuário cadastrado com sucesso!');
                log_info('Adicionou um usuário.');
                redirect(site_url('usuarios/adicionar/'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->load->model('permissoes_model');
        $this->data['permissoes'] = $this->permissoes_model->getActive('permissoes', 'permissoes.idPermissao,permissoes.nome');
        $this->data['view'] = 'usuarios/adicionarUsuario';

        return $this->layout();
    }

    public function editar()
    {
        if (! $this->uri->segment(3) || ! is_numeric($this->uri->segment(3)) || ! $this->usuarios_model->getById($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Usuário não encontrado ou parâmetro inválido.');
            redirect('usuarios/gerenciar');
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        $this->form_validation->set_rules('nome', 'Nome', 'trim|required');
        $this->form_validation->set_rules('rg', 'RG', 'trim|required');
        $this->form_validation->set_rules('cpf', 'CPF', 'trim|required');
        $this->form_validation->set_rules('cep', 'CEP', 'trim|required');
        $this->form_validation->set_rules('rua', 'Rua', 'trim|required');
        $this->form_validation->set_rules('numero', 'Número', 'trim|required');
        $this->form_validation->set_rules('bairro', 'Bairro', 'trim|required');
        $this->form_validation->set_rules('cidade', 'Cidade', 'trim|required');
        $this->form_validation->set_rules('estado', 'Estado', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('telefone', 'Telefone', 'trim|required');
        $this->form_validation->set_rules('situacao', 'Situação', 'trim|required');
        $this->form_validation->set_rules('permissoes_id', 'Permissão', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $emailEdit = $this->input->post('email');
            $idEdit    = $this->input->post('idUsuarios');
            if ($emailEdit && $this->usuarios_model->emailExists($emailEdit, $idEdit)) {
                $this->data['custom_error'] = '<div class="alert alert-danger">Este e-mail já está em uso por outro usuário.</div>';
                $this->data['result'] = $this->usuarios_model->getById($this->uri->segment(3));
                $this->load->model('permissoes_model');
                $this->data['permissoes'] = $this->permissoes_model->getActive('permissoes', 'permissoes.idPermissao,permissoes.nome');
                $this->data['view'] = 'usuarios/editarUsuario';
                return $this->layout();
            }
            if ($this->input->post('idUsuarios') == 1 && $this->input->post('situacao') == 0) {
                $this->session->set_flashdata('error', 'O usuário super admin não pode ser desativado!');
                redirect(base_url() . 'index.php/usuarios/editar/' . $this->input->post('idUsuarios'));
            }

            $senha = $this->input->post('senha');
            if ($senha != null) {
                $senha = password_hash($senha, PASSWORD_DEFAULT);

                $data = [
                    'nome' => $this->input->post('nome'),
                    'rg' => $this->input->post('rg'),
                    'cpf' => $this->input->post('cpf'),
                    'cep' => $this->input->post('cep'),
                    'rua' => $this->input->post('rua'),
                    'numero' => $this->input->post('numero'),
                    'bairro' => $this->input->post('bairro'),
                    'cidade' => $this->input->post('cidade'),
                    'estado' => $this->input->post('estado'),
                    'email' => $this->input->post('email'),
                    'senha' => $senha,
                    'telefone' => $this->input->post('telefone'),
                    'celular' => $this->input->post('celular'),
                    'dataExpiracao' => set_value('dataExpiracao'),
                    'situacao' => $this->input->post('situacao'),
                    'permissoes_id' => $this->input->post('permissoes_id'),
                ];
            } else {
                $data = [
                    'nome' => $this->input->post('nome'),
                    'rg' => $this->input->post('rg'),
                    'cpf' => $this->input->post('cpf'),
                    'cep' => $this->input->post('cep'),
                    'rua' => $this->input->post('rua'),
                    'numero' => $this->input->post('numero'),
                    'bairro' => $this->input->post('bairro'),
                    'cidade' => $this->input->post('cidade'),
                    'estado' => $this->input->post('estado'),
                    'email' => $this->input->post('email'),
                    'telefone' => $this->input->post('telefone'),
                    'celular' => $this->input->post('celular'),
                    'dataExpiracao' => set_value('dataExpiracao'),
                    'situacao' => $this->input->post('situacao'),
                    'permissoes_id' => $this->input->post('permissoes_id'),
                ];
            }

            if ($this->usuarios_model->edit('usuarios', $data, 'idUsuarios', $this->input->post('idUsuarios')) == true) {
                $this->session->set_flashdata('success', 'Usuário editado com sucesso!');
                log_info('Alterou um usuário. ID: ' . $this->input->post('idUsuarios'));
                redirect(site_url('usuarios/editar/') . $this->input->post('idUsuarios'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro</p></div>';
            }
        }

        $this->data['result'] = $this->usuarios_model->getById($this->uri->segment(3));
        $this->load->model('permissoes_model');
        $this->data['permissoes'] = $this->permissoes_model->getActive('permissoes', 'permissoes.idPermissao,permissoes.nome');

        $this->data['view'] = 'usuarios/editarUsuario';

        return $this->layout();
    }

    public function excluir()
    {
        $id = $this->uri->segment(3);
        $this->usuarios_model->delete('usuarios', 'idUsuarios', $id);

        log_info('Removeu um usuário. ID: ' . $id);

        redirect(site_url('usuarios/gerenciar/'));
    }

    public function importar()
    {
        $this->data['view'] = 'usuarios/importarUsuarios';
        return $this->layout();
    }

    public function modeloPlanilha()
    {
        require_once APPPATH . 'third_party/SimpleXLSXGen.php';
        $rows = [
            ['Nome', 'CPF', 'Email', 'Senha', 'Telefone', 'Celular', 'RG', 'CEP', 'Rua', 'Número', 'Bairro', 'Cidade', 'Estado', 'Situação', 'Expira em'],
            ['João Silva', '123.456.789-00', 'joao@email.com', 'senha123', '(11) 99999-0001', '(11) 99999-0002', '12.345.678', '01310-100', 'Av. Paulista', '1000', 'Bela Vista', 'São Paulo', 'SP', 'Ativo', '2027-12-31'],
        ];
        $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($rows);
        $xlsx->downloadAs('modelo_importacao_usuarios.xlsx');
    }

    public function importarPlanilha()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['planilha'])) {
            require_once APPPATH . 'third_party/SimpleXLSX.php';

            // Busca a primeira permissão disponível como padrão
            $this->load->model('permissoes_model');
            $permissoes = $this->permissoes_model->getActive('permissoes', 'permissoes.idPermissao');
            $permissaoDefault = !empty($permissoes) ? $permissoes[0]->idPermissao : 1;

            $file = $_FILES['planilha']['tmp_name'];
            if ($xlsx = \Shuchkin\SimpleXLSX::parse($file)) {
                $rows = $xlsx->rows();
                $importados = 0;
                $erros = 0;
                $ignorados = 0;
                $limite = 500;
                $processados = 0;
                foreach ($rows as $i => $row) {
                    if ($i === 0) continue; // pular cabeçalho
                    if (empty($row[0]) || empty($row[2]) || empty($row[3])) continue; // nome, email e senha obrigatórios
                    if ($processados >= $limite) { $ignorados++; continue; }
                    // Verificar e-mail duplicado
                    if (!empty($row[2]) && $this->usuarios_model->emailExists(trim($row[2]))) { $erros++; continue; }
                    $processados++;

                    $situacaoStr = strtolower(isset($row[13]) ? trim($row[13]) : 'ativo');
                    $situacao = ($situacaoStr === 'ativo' || $situacaoStr === '1') ? 1 : 0;

                    $dataExpiracao = isset($row[14]) && $row[14] ? $row[14] : date('Y-m-d', strtotime('+1 year'));

                    $data = [
                        'nome'          => $row[0],
                        'cpf'           => isset($row[1]) ? $row[1] : '',
                        'email'         => $row[2],
                        'senha'         => password_hash($row[3], PASSWORD_DEFAULT),
                        'telefone'      => isset($row[4]) ? $row[4] : '',
                        'celular'       => isset($row[5]) ? $row[5] : '',
                        'rg'            => isset($row[6]) ? $row[6] : '',
                        'cep'           => isset($row[7]) ? $row[7] : '',
                        'rua'           => isset($row[8]) ? $row[8] : '',
                        'numero'        => isset($row[9]) ? $row[9] : '',
                        'bairro'        => isset($row[10]) ? $row[10] : '',
                        'cidade'        => isset($row[11]) ? $row[11] : '',
                        'estado'        => isset($row[12]) ? $row[12] : '',
                        'situacao'      => $situacao,
                        'dataExpiracao' => $dataExpiracao,
                        'permissoes_id' => $permissaoDefault,
                        'dataCadastro'  => date('Y-m-d'),
                    ];

                    if ($this->usuarios_model->add('usuarios', $data)) {
                        $importados++;
                    } else {
                        $erros++;
                    }
                }
                $msg = "Importação concluída: $importados usuário(s) importado(s)";
                if ($erros)     $msg .= ", $erros erro(s) (e-mail duplicado ou dados inválidos)";
                if ($ignorados) $msg .= ", $ignorados linha(s) ignorada(s) — limite de $limite registros por importação";
                $this->session->set_flashdata('success', $msg . '.');
            } else {
                $this->session->set_flashdata('error', 'Erro ao ler a planilha. Verifique o formato.');
            }
            redirect(site_url('usuarios'));
        }

        $this->data['view'] = 'usuarios/importarUsuarios';
        return $this->layout();
    }
}
