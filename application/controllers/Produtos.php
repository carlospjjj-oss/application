<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Produtos extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->model('produtos_model');
        $this->data['menuProdutos'] = 'Produtos';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'vProduto')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar produtos.');
            redirect(base_url());
        }

        $pesquisa = $this->input->get('pesquisa');

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = site_url('produtos/gerenciar/');
        $this->data['configuration']['total_rows'] = $this->produtos_model->count('produtos');
        if ($pesquisa) {
            $this->data['configuration']['suffix'] = "?pesquisa={$pesquisa}";
            $this->data['configuration']['first_url'] = base_url("index.php/produtos")."\?pesquisa={$pesquisa}";
        }

        $this->pagination->initialize($this->data['configuration']);

        $this->data['results'] = $this->produtos_model->get('produtos', '*', $pesquisa, $this->data['configuration']['per_page'], $this->uri->segment(3));

        $this->data['view'] = 'produtos/produtos';

        return $this->layout();
    }

    public function adicionar()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'aProduto')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar produtos.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('produtos') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $precoCompra = $this->input->post('precoCompra');
            $precoCompra = str_replace(',', '', $precoCompra);
            $precoVenda = $this->input->post('precoVenda');
            $precoVenda = str_replace(',', '', $precoVenda);
            $data = [
                'codDeBarra' => set_value('codDeBarra'),
                'descricao' => set_value('descricao'),
                'unidade' => set_value('unidade'),
                'precoCompra' => $precoCompra,
                'precoVenda' => $precoVenda,
                'estoque' => set_value('estoque'),
                'estoqueMinimo' => set_value('estoqueMinimo'),
                'saida' => set_value('saida'),
                'entrada' => set_value('entrada'),
                'ncm' => $this->input->post('ncm'),
            ];

            if ($this->produtos_model->add('produtos', $data) == true) {
                $this->session->set_flashdata('success', 'Produto adicionado com sucesso!');
                log_info('Adicionou um produto');
                redirect(site_url('produtos/adicionar/'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';
            }
        }
        $this->data['view'] = 'produtos/adicionarProduto';

        return $this->layout();
    }

    public function editar()
    {
        if (! $this->uri->segment(3) || ! is_numeric($this->uri->segment(3)) || ! $this->produtos_model->getById($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Produto não encontrado ou parâmetro inválido.');
            redirect('produtos/gerenciar');
        }

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eProduto')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar produtos.');
            redirect(base_url());
        }
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('produtos') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $precoCompra = $this->input->post('precoCompra');
            $precoCompra = str_replace(',', '', $precoCompra);
            $precoVenda = $this->input->post('precoVenda');
            $precoVenda = str_replace(',', '', $precoVenda);
            $data = [
                'codDeBarra' => set_value('codDeBarra'),
                'descricao' => $this->input->post('descricao'),
                'unidade' => $this->input->post('unidade'),
                'precoCompra' => $precoCompra,
                'precoVenda' => $precoVenda,
                'estoque' => $this->input->post('estoque'),
                'estoqueMinimo' => $this->input->post('estoqueMinimo'),
                'saida' => set_value('saida'),
                'entrada' => set_value('entrada'),
                'ncm' => $this->input->post('ncm'),
            ];

            if ($this->produtos_model->edit('produtos', $data, 'idProdutos', $this->input->post('idProdutos')) == true) {
                $this->session->set_flashdata('success', 'Produto editado com sucesso!');
                log_info('Alterou um produto. ID: ' . $this->input->post('idProdutos'));
                redirect(site_url('produtos/editar/') . $this->input->post('idProdutos'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>An Error Occured</p></div>';
            }
        }

        $this->data['result'] = $this->produtos_model->getById($this->uri->segment(3));

        $this->data['view'] = 'produtos/editarProduto';

        return $this->layout();
    }

    public function visualizar()
    {
        if (! $this->uri->segment(3) || ! is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'vProduto')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar produtos.');
            redirect(base_url());
        }

        $this->data['result'] = $this->produtos_model->getById($this->uri->segment(3));

        if ($this->data['result'] == null) {
            $this->session->set_flashdata('error', 'Produto não encontrado.');
            redirect(site_url('produtos/editar/') . $this->input->post('idProdutos'));
        }

        $this->data['view'] = 'produtos/visualizarProduto';

        return $this->layout();
    }

    public function excluir()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'dProduto')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir produtos.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir produto.');
            redirect(base_url() . 'index.php/produtos/gerenciar/');
        }

        $this->produtos_model->delete('produtos_os', 'produtos_id', $id);
        $this->produtos_model->delete('itens_de_vendas', 'produtos_id', $id);
        $this->produtos_model->delete('produtos', 'idProdutos', $id);

        log_info('Removeu um produto. ID: ' . $id);

        $this->session->set_flashdata('success', 'Produto excluido com sucesso!');
        redirect(site_url('produtos/gerenciar/'));
    }

    public function atualizar_estoque()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eProduto')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para atualizar estoque de produtos.');
            redirect(base_url());
        }

        $idProduto = $this->input->post('id');
        $novoEstoque = $this->input->post('estoque');
        $estoqueAtual = $this->input->post('estoqueAtual');

        $estoque = $estoqueAtual + $novoEstoque;

        $data = [
            'estoque' => $estoque,
        ];

        if ($this->produtos_model->edit('produtos', $data, 'idProdutos', $idProduto) == true) {
            $this->session->set_flashdata('success', 'Estoque de Produto atualizado com sucesso!');
            log_info('Atualizou estoque de um produto. ID: ' . $idProduto);
            redirect(site_url('produtos/visualizar/') . $idProduto);
        } else {
            $this->data['custom_error'] = '<div class="alert">Ocorreu um erro.</div>';
        }
    }

    public function modeloPlanilha()
    {
        require_once APPPATH . 'third_party/SimpleXLSXGen.php';
        $rows = [
            ['Código de Barra', 'Descrição', 'Unidade', 'Preço Compra', 'Preço Venda', 'Estoque', 'Estoque Mínimo'],
            ['7891234560001', 'Exemplo Produto', 'UN', '10,00', '15,00', '100', '5'],
        ];
        $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($rows);
        $xlsx->downloadAs('modelo_importacao_produtos.xlsx');
    }

    public function importarPlanilha()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aProduto')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para importar produtos.');
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
                    if (empty($row[1])) continue; // sem descrição, pular
                    $precoCompra = str_replace(',', '.', isset($row[3]) ? $row[3] : '0');
                    $precoVenda  = str_replace(',', '.', isset($row[4]) ? $row[4] : '0');
                    $data = [
                        'codDeBarra'    => isset($row[0]) ? $row[0] : '',
                        'descricao'     => $row[1],
                        'unidade'       => isset($row[2]) ? $row[2] : 'UN',
                        'precoCompra'   => floatval($precoCompra),
                        'precoVenda'    => floatval($precoVenda),
                        'estoque'       => isset($row[5]) ? intval($row[5]) : 0,
                        'estoqueMinimo' => isset($row[6]) ? intval($row[6]) : 0,
                        'saida'         => 0,
                        'entrada'       => 0,
                    ];
                    if ($this->produtos_model->add('produtos', $data)) {
                        $importados++;
                    } else {
                        $erros++;
                    }
                }
                $this->session->set_flashdata('success', "Importação concluída: $importados produto(s) importado(s)" . ($erros ? ", $erros erro(s)." : "."));
            } else {
                $this->session->set_flashdata('error', 'Erro ao ler a planilha. Verifique o formato.');
            }
            redirect(site_url('produtos'));
        }

        $this->data['view'] = 'produtos/importarProdutos';
        return $this->layout();
    }
}
