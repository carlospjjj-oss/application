<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Clientes extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('clientes_model');
        $this->data['menuClientes'] = 'clientes';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'vCliente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar clientes.');
            redirect(base_url());
        }

        $pesquisa = $this->input->get('pesquisa');

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = site_url('clientes/gerenciar/');
        $this->data['configuration']['total_rows'] = $this->clientes_model->count('clientes');
        if ($pesquisa) {
            $this->data['configuration']['suffix'] = "?pesquisa={$pesquisa}";
            $this->data['configuration']['first_url'] = base_url("index.php/clientes")."\?pesquisa={$pesquisa}";
        }

        $this->pagination->initialize($this->data['configuration']);

        $this->data['results'] = $this->clientes_model->get('clientes', '*', $pesquisa, $this->data['configuration']['per_page'], $this->uri->segment(3));

        $this->data['view'] = 'clientes/clientes';

        return $this->layout();
    }

    public function adicionar()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'aCliente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar clientes.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        $senhaCliente = $this->input->post('senha') ? $this->input->post('senha') : preg_replace('/[^\p{L}\p{N}\s]/', '', set_value('documento'));

        $cpf_cnpj = preg_replace('/[^\p{L}\p{N}\s]/', '', set_value('documento'));

        if (strlen($cpf_cnpj) == 11) {
            $pessoa_fisica = true;
        } else {
            $pessoa_fisica = false;
        }

        if ($this->form_validation->run('clientes') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $email = set_value('email');
            if ($email && $this->clientes_model->emailExists($email)) {
                $this->data['custom_error'] = '<div class="form_error"><p>Este e-mail já está sendo utilizado por outro cliente.</p></div>';
            } else {
                $data = [
                'nomeCliente' => set_value('nomeCliente'),
                'contato' => set_value('contato'),
                'pessoa_fisica' => $pessoa_fisica,
                'documento' => set_value('documento'),
                'telefone' => set_value('telefone'),
                'celular' => set_value('celular'),
                'email' => set_value('email'),
                'senha' => password_hash($senhaCliente, PASSWORD_DEFAULT),
                'rua' => set_value('rua'),
                'numero' => set_value('numero'),
                'complemento' => set_value('complemento'),
                'bairro' => set_value('bairro'),
                'cidade' => set_value('cidade'),
                'estado' => set_value('estado'),
                'cep' => set_value('cep'),
                'dataCadastro' => date('Y-m-d'),
                'fornecedor' => $this->input->post('fornecedor') ? 1 : 0,
                'terceiro' => $this->input->post('terceiro') ? 1 : 0,
                'tipo_imovel' => $this->input->post('tipo_imovel'),
                'concessionaria_energia' => $this->input->post('concessionaria_energia'),
                'numero_uc' => $this->input->post('numero_uc'),
                'veiculo_marca_modelo' => $this->input->post('veiculo_marca_modelo'),
                'veiculo_tipo_conector' => $this->input->post('veiculo_tipo_conector'),
                'carregador_atual' => $this->input->post('carregador_atual'),
            ];

                if ($this->clientes_model->add('clientes', $data) == true) {
                    $this->session->set_flashdata('success', 'Cliente adicionado com sucesso!');
                    log_info('Adicionou um cliente.');
                    redirect(site_url('clientes/'));
                } else {
                    $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
                }
            }
        }

        $this->data['view'] = 'clientes/adicionarCliente';

        return $this->layout();
    }

    public function editar()
    {
        if (! $this->uri->segment(3) || ! is_numeric($this->uri->segment(3)) || ! $this->clientes_model->getById($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Cliente não encontrado ou parâmetro inválido.');
            redirect('clientes/gerenciar');
        }

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eCliente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar clientes.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('clientes') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            
            $email = $this->input->post('email');
            $idCliente = $this->input->post('idClientes');
            if ($email && $this->clientes_model->emailExists($email, $idCliente)) {
                $this->data['custom_error'] = '<div class="form_error"><p>Este e-mail já está sendo utilizado por outro cliente.</p></div>';
            } else {
                $senha = $this->input->post('senha');
                if ($senha != null) {
                    $senha = password_hash($senha, PASSWORD_DEFAULT);

                    $data = [
                        'nomeCliente' => $this->input->post('nomeCliente'),
                        'contato' => $this->input->post('contato'),
                        'documento' => $this->input->post('documento'),
                        'telefone' => $this->input->post('telefone'),
                        'celular' => $this->input->post('celular'),
                        'email' => $this->input->post('email'),
                        'senha' => $senha,
                        'rua' => $this->input->post('rua'),
                        'numero' => $this->input->post('numero'),
                        'complemento' => $this->input->post('complemento'),
                        'bairro' => $this->input->post('bairro'),
                        'cidade' => $this->input->post('cidade'),
                        'estado' => $this->input->post('estado'),
                        'cep' => $this->input->post('cep'),
                        'fornecedor' => (set_value('fornecedor') == true ? 1 : 0),
                        'terceiro' => (set_value('terceiro') == true ? 1 : 0),
                        'tipo_imovel' => $this->input->post('tipo_imovel'),
                        'concessionaria_energia' => $this->input->post('concessionaria_energia'),
                        'numero_uc' => $this->input->post('numero_uc'),
                        'veiculo_marca_modelo' => $this->input->post('veiculo_marca_modelo'),
                        'veiculo_tipo_conector' => $this->input->post('veiculo_tipo_conector'),
                        'carregador_atual' => $this->input->post('carregador_atual'),
                    ];
                } else {
                    $data = [
                        'nomeCliente' => $this->input->post('nomeCliente'),
                        'contato' => $this->input->post('contato'),
                        'documento' => $this->input->post('documento'),
                        'telefone' => $this->input->post('telefone'),
                        'celular' => $this->input->post('celular'),
                        'email' => $this->input->post('email'),
                        'rua' => $this->input->post('rua'),
                        'numero' => $this->input->post('numero'),
                        'complemento' => $this->input->post('complemento'),
                        'bairro' => $this->input->post('bairro'),
                        'cidade' => $this->input->post('cidade'),
                        'estado' => $this->input->post('estado'),
                        'cep' => $this->input->post('cep'),
                        'fornecedor' => (set_value('fornecedor') == true ? 1 : 0),
                        'terceiro' => (set_value('terceiro') == true ? 1 : 0),
                        'tipo_imovel' => $this->input->post('tipo_imovel'),
                        'concessionaria_energia' => $this->input->post('concessionaria_energia'),
                        'numero_uc' => $this->input->post('numero_uc'),
                        'veiculo_marca_modelo' => $this->input->post('veiculo_marca_modelo'),
                        'veiculo_tipo_conector' => $this->input->post('veiculo_tipo_conector'),
                        'carregador_atual' => $this->input->post('carregador_atual'),
                    ];
                }

                if ($this->clientes_model->edit('clientes', $data, 'idClientes', $this->input->post('idClientes')) == true) {
                    $this->session->set_flashdata('success', 'Cliente editado com sucesso!');
                    log_info('Alterou um cliente. ID' . $this->input->post('idClientes'));
                    redirect(site_url('clientes/editar/') . $this->input->post('idClientes'));
                } else {
                    $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro</p></div>';
                }
            }
        }

        $this->data['result'] = $this->clientes_model->getById($this->uri->segment(3));
        $this->data['view'] = 'clientes/editarCliente';

        return $this->layout();
    }

    public function visualizar()
    {
        if (! $this->uri->segment(3) || ! is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'vCliente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar clientes.');
            redirect(base_url());
        }

        $this->data['custom_error'] = '';
        $this->data['result'] = $this->clientes_model->getById($this->uri->segment(3));
        $this->data['results'] = $this->clientes_model->getOsByCliente($this->uri->segment(3));
        $this->data['result_vendas'] = $this->clientes_model->getAllVendasByClient($this->uri->segment(3));
        $this->data['view'] = 'clientes/visualizar';

        return $this->layout();
    }

    public function importar()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'aCliente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para importar clientes.');
            redirect(base_url());
        }
        $this->data['view'] = 'clientes/importarClientes';
        return $this->layout();
    }

    public function modeloPlanilha()
    {
        require_once APPPATH . 'third_party/SimpleXLSXGen.php';
        $rows = [
            ['Nome', 'Contato', 'CPF/CNPJ', 'Telefone', 'Celular', 'Email', 'CEP', 'Rua', 'Número', 'Complemento', 'Bairro', 'Cidade', 'Estado', 'Tipo (Cliente / Fornecedor / Terceiro)'],
            ['João Silva', 'Maria Silva', '123.456.789-00', '(11) 99999-0001', '(11) 99999-0002', 'joao@email.com', '01310-100', 'Av. Paulista', '1000', 'Apto 42', 'Bela Vista', 'São Paulo', 'SP', 'Cliente'],
            ['Empresa ABC Ltda', 'Carlos', '12.345.678/0001-90', '(11) 3333-0001', '', 'contato@abc.com', '', '', '', '', '', 'São Paulo', 'SP', 'Fornecedor'],
            ['Técnico Externo', '', '987.654.321-00', '(11) 98888-0001', '', '', '', '', '', '', '', '', '', 'Terceiro'],
        ];
        $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($rows);
        $xlsx->downloadAs('modelo_importacao_clientes.xlsx');
    }

    public function importarPlanilha()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'aCliente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para importar clientes.');
            redirect(base_url());
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['planilha'])) {
            require_once APPPATH . 'third_party/SimpleXLSX.php';
            $file = $_FILES['planilha']['tmp_name'];
            if ($xlsx = \Shuchkin\SimpleXLSX::parse($file)) {
                $rows = $xlsx->rows();
                $importados = 0;
                $erros = 0;
                foreach ($rows as $i => $row) {
                    if ($i === 0) continue; // pular cabeçalho
                    if (empty($row[0])) continue; // nome obrigatório

                    $documento = isset($row[2]) ? $row[2] : '';
                    $cpf_cnpj  = preg_replace('/[^\d]/', '', $documento);
                    $pessoa_fisica = strlen($cpf_cnpj) == 11;
                    $senha = $cpf_cnpj ? password_hash($cpf_cnpj, PASSWORD_DEFAULT) : password_hash('voltcore', PASSWORD_DEFAULT);

                    $tipoStr = strtolower(isset($row[13]) ? trim($row[13]) : 'cliente');
                    $fornecedor = ($tipoStr === 'fornecedor') ? 1 : 0;
                    $terceiro   = ($tipoStr === 'terceiro')   ? 1 : 0;

                    $email = isset($row[5]) ? trim($row[5]) : '';
                    if ($email && $this->clientes_model->emailExists($email)) {
                        $erros++;
                        continue;
                    }

                    $data = [
                        'nomeCliente'  => $row[0],
                        'contato'      => isset($row[1]) ? $row[1] : '',
                        'documento'    => $documento,
                        'pessoa_fisica'=> $pessoa_fisica,
                        'telefone'     => isset($row[3]) ? $row[3] : '',
                        'celular'      => isset($row[4]) ? $row[4] : '',
                        'email'        => $email,
                        'senha'        => $senha,
                        'cep'          => isset($row[6]) ? $row[6] : '',
                        'rua'          => isset($row[7]) ? $row[7] : '',
                        'numero'       => isset($row[8]) ? $row[8] : '',
                        'complemento'  => isset($row[9]) ? $row[9] : '',
                        'bairro'       => isset($row[10]) ? $row[10] : '',
                        'cidade'       => isset($row[11]) ? $row[11] : '',
                        'estado'       => isset($row[12]) ? $row[12] : '',
                        'fornecedor'   => $fornecedor,
                        'terceiro'     => $terceiro,
                        'dataCadastro' => date('Y-m-d'),
                    ];

                    if ($this->clientes_model->add('clientes', $data)) {
                        $importados++;
                    } else {
                        $erros++;
                    }
                }
                $this->session->set_flashdata('success', "Importação concluída: $importados cliente(s) importado(s)" . ($erros ? ", $erros erro(s) (e-mail duplicado ou dados inválidos)." : "."));
            } else {
                $this->session->set_flashdata('error', 'Erro ao ler a planilha. Verifique o formato.');
            }
            redirect(site_url('clientes'));
        }

        $this->data['view'] = 'clientes/importarClientes';
        return $this->layout();
    }

    public function excluir()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'dCliente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir clientes.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir cliente.');
            redirect(site_url('clientes/gerenciar/'));
        }

        $os = $this->clientes_model->getAllOsByClient($id);
        if ($os != null) {
            $this->clientes_model->removeClientOs($os);
        }

        // excluindo Vendas vinculadas ao cliente
        $vendas = $this->clientes_model->getAllVendasByClient($id);
        if ($vendas != null) {
            $this->clientes_model->removeClientVendas($vendas);
        }

        $this->clientes_model->delete('clientes', 'idClientes', $id);
        log_info('Removeu um cliente. ID' . $id);

        $this->session->set_flashdata('success', 'Cliente excluido com sucesso!');
        redirect(site_url('clientes/gerenciar/'));
    }
}
