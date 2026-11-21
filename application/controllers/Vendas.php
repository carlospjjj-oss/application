<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Vendas extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->model('vendas_model');
        $this->data['menuVendas'] = 'Vendas';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar vendas.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $where_array = [];

        $pesquisa = $this->input->get('pesquisa');
        $status = $this->input->get('status');
        $de = $this->input->get('data');
        $ate = $this->input->get('data2');

        if ($pesquisa) {
            $where_array['pesquisa'] = $pesquisa;
        }
        if ($status) {
            $where_array['status'] = $status;
        }
        if ($de) {
            $where_array['de'] = $de;
        }
        if ($ate) {
            $where_array['ate'] = $ate;
        }

        $this->data['configuration']['base_url'] = site_url('vendas/gerenciar/');
        $this->data['configuration']['total_rows'] = $this->vendas_model->count('vendas');
        
        if (count($where_array) > 0) {
            $this->data['configuration']['suffix'] = "?pesquisa={$pesquisa}&status={$status}&data={$de}&data2={$ate}";
            $this->data['configuration']['first_url'] = base_url("index.php/vendas/gerenciar")."?pesquisa={$pesquisa}&status={$status}&data={$de}&data2={$ate}";
        }

        $this->pagination->initialize($this->data['configuration']);

        $this->data['results'] = $this->vendas_model->get('vendas', '*', $where_array, $this->data['configuration']['per_page'], $this->uri->segment(3));

        foreach ($this->data['results'] as $key => $venda) {
            $this->data['results'][$key]->totalProdutos = $this->vendas_model->getTotalVendas($venda->idVendas);
        }

        $this->data['view'] = 'vendas/vendas';

        return $this->layout();
    }

    public function adicionar()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'aVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar Vendas.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('vendas') == false) {
            $this->data['custom_error'] = (validation_errors() ? true : false);
        } else {
            $dataVenda = $this->input->post('dataVenda');

            try {
                $dataVenda = explode('/', $dataVenda);
                $dataVenda = $dataVenda[2] . '-' . $dataVenda[1] . '-' . $dataVenda[0];
            } catch (Exception $e) {
                $dataVenda = date('Y-m-d');
            }

            $data = [
                'dataVenda' => $dataVenda,
                'observacoes' => $this->input->post('observacoes'),
                'observacoes_cliente' => $this->input->post('observacoes_cliente'),
                'clientes_id' => $this->input->post('clientes_id'),
                'usuarios_id' => $this->input->post('usuarios_id'),
                'vendedor' => $this->input->post('tecnico'),
                'faturado' => 0,
                'status' => $this->input->post('status'),
                'garantia' => $this->input->post('garantia')
            ];

            $id = $this->vendas_model->add('vendas', $data, true);

            if (is_numeric($id)) {
                $this->session->set_flashdata('success', 'Venda iniciada com sucesso, adicione os produtos.');
                log_info('Adicionou uma venda. ID: ' . $id);
                redirect(site_url('vendas/editar/') . $id);
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['view'] = 'vendas/adicionarVenda';

        return $this->layout();
    }

    public function editar()
    {
        if (! $this->uri->segment(3) || ! is_numeric($this->uri->segment(3)) || ! $this->vendas_model->getById($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Venda não encontrada ou parâmetro inválido.');
            redirect('vendas/gerenciar');
        }

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar vendas');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        $idVendaAtual = $this->uri->segment(3);
        $vendaAntiga = $this->vendas_model->getById($idVendaAtual);
        $statusAntigo = $vendaAntiga ? $vendaAntiga->status : null;

        $this->data['editavel'] = $this->vendas_model->isEditable($idVendaAtual);
        if (! $this->data['editavel']) {
            if ($vendaAntiga && $vendaAntiga->convertida) {
                $this->session->set_flashdata('error', 'Essa Venda já foi convertida em Ordem de Serviço #' . $vendaAntiga->os_id . ' e não pode mais ser editada.');
            } else {
                $this->session->set_flashdata('error', 'Essa Venda já tem seu status Faturada e não pode ser alterado e nem suas informações atualizadas. Por favor abrir uma nova Venda.');
            }

            redirect(site_url('vendas'));
        }

        if ($this->form_validation->run('vendas') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $dataVenda = $this->input->post('dataVenda');

            try {
                $dataVenda = explode('/', $dataVenda);
                $dataVenda = $dataVenda[2] . '-' . $dataVenda[1] . '-' . $dataVenda[0];
            } catch (Exception $e) {
                $dataVenda = date('Y/m/d');
            }

            $data = [
                'dataVenda' => $dataVenda,
                'observacoes' => $this->input->post('observacoes'),
                'observacoes_cliente' => $this->input->post('observacoes_cliente'),
                'usuarios_id' => $this->input->post('usuarios_id'),
                'clientes_id' => $this->input->post('clientes_id'),
                'vendedor' => $this->input->post('tecnico'),
                'status' => $this->input->post('status'),
                'garantia' => $this->input->post('garantia')
            ];

            if ($this->vendas_model->edit('vendas', $data, 'idVendas', $this->input->post('idVendas')) == true) {
                $novoStatus = $this->input->post('status');
                if ($novoStatus === 'Aprovado' && $statusAntigo !== 'Aprovado') {
                    $novoOsId = $this->vendas_model->converterParaOs($this->input->post('idVendas'));
                    if ($novoOsId) {
                        log_info('Venda convertida em OS. Venda ID: ' . $this->input->post('idVendas') . ' -> OS ID: ' . $novoOsId);
                        $this->session->set_flashdata('success', 'Venda aprovada e convertida em Ordem de Serviço #' . $novoOsId . ' com sucesso!');
                        redirect(site_url('os/editar/') . $novoOsId);
                    }
                }
                $this->session->set_flashdata('success', 'Venda editada com sucesso!');
                log_info('Alterou uma venda. ID: ' . $this->input->post('idVendas'));
                redirect(site_url('vendas/editar/') . $this->input->post('idVendas'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro</p></div>';
            }
        }

        $this->data['result'] = $this->vendas_model->getById($this->uri->segment(3));
        $this->data['produtos'] = $this->vendas_model->getProdutos($this->uri->segment(3));
        $this->data['servicos'] = $this->vendas_model->getServicos($this->uri->segment(3));
        if (empty($this->data['result']->observacoes) && !empty($this->data['result']->clientes_id)) {
            $this->load->model('visita_tecnica_model');
            $ultimaVisita = $this->visita_tecnica_model->getUltimoResultado($this->data['result']->clientes_id);
            if ($ultimaVisita && !empty($ultimaVisita->resultado)) {
                $this->data['result']->observacoes = $ultimaVisita->resultado;
            }
        }
        $this->data['view'] = 'vendas/editarVenda';

        return $this->layout();
    }

    public function visualizar()
    {
        if (! $this->uri->segment(3) || ! is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'vVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar vendas.');
            redirect(base_url());
        }

        $this->data['custom_error'] = '';
        $this->load->model('mapos_model');
        $this->data['result'] = $this->vendas_model->getById($this->uri->segment(3));
        $this->data['produtos'] = $this->vendas_model->getProdutos($this->uri->segment(3));
        $this->data['emitente'] = $this->mapos_model->getEmitente();
        $this->data['qrCode'] = $this->vendas_model->getQrCode(
            $this->uri->segment(3),
            $this->data['configuration']['pix_key'],
            $this->data['emitente']
        );
        $this->data['chaveFormatada'] = $this->formatarChave($this->data['configuration']['pix_key']);
        $this->data['modalGerarPagamento'] = $this->load->view(
            'cobrancas/modalGerarPagamento',
            [
                'id' => $this->uri->segment(3),
                'tipo' => 'venda',
            ],
            true
        );

        $clienteId = $this->data['result']->clientes_id;
        $this->load->model('clientes_model');
        $cliente = $this->clientes_model->getById($clienteId);

        $zapnumber = preg_replace('/[^0-9]/', '', $cliente->telefone ?? '');
        $this->data['zapnumber'] = $zapnumber;
        $this->data['view'] = 'vendas/visualizarVenda';

        return $this->layout();
    }

    public function imprimir()
    {
        if (! $this->uri->segment(3) || ! is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'vVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar vendas.');
            redirect(base_url());
        }

        $this->data['custom_error'] = '';
        $this->load->model('mapos_model');
        $this->data['result'] = $this->vendas_model->getById($this->uri->segment(3));
        $this->data['produtos'] = $this->vendas_model->getProdutos($this->uri->segment(3));
        $this->data['emitente'] = $this->mapos_model->getEmitente();
        $this->data['qrCode'] = $this->vendas_model->getQrCode(
            $this->uri->segment(3),
            $this->data['configuration']['pix_key'],
            $this->data['emitente']
        );
        $this->data['chaveFormatada'] = $this->formatarChave($this->data['configuration']['pix_key']);

        $this->load->view('vendas/imprimirVenda', $this->data);
    }

    public function imprimirTermica()
    {
        if (! $this->uri->segment(3) || ! is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'vVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar vendas.');
            redirect(base_url());
        }

        $this->data['custom_error'] = '';
        $this->load->model('mapos_model');
        $this->data['result'] = $this->vendas_model->getById($this->uri->segment(3));
        $this->data['produtos'] = $this->vendas_model->getProdutos($this->uri->segment(3));
        $this->data['emitente'] = $this->mapos_model->getEmitente();
        $this->data['qrCode'] = $this->vendas_model->getQrCode(
            $this->uri->segment(3),
            $this->data['configuration']['pix_key'],
            $this->data['emitente']
        );
        
        $this->data['chaveFormatada'] = $this->formatarChave($this->data['configuration']['pix_key']);

        $this->load->view('vendas/imprimirVendaTermica', $this->data);
    }

    public function imprimirVendaOrcamento()
    {
        if (! $this->uri->segment(3) || ! is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'vVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar vendas.');
            redirect(base_url());
        }

        $this->data['custom_error'] = '';
        $this->load->model('mapos_model');
        $this->data['result'] = $this->vendas_model->getById($this->uri->segment(3));
        $this->data['produtos'] = $this->vendas_model->getProdutos($this->uri->segment(3));
        $this->data['emitente'] = $this->mapos_model->getEmitente();
        $this->data['qrCode'] = $this->vendas_model->getQrCode(
            $this->uri->segment(3),
            $this->data['configuration']['pix_key'],
            $this->data['emitente']
        );
        
        $this->data['chaveFormatada'] = $this->formatarChave($this->data['configuration']['pix_key']);
        $this->load->view('vendas/imprimirVendaOrcamento', $this->data);
    }

    public function excluir()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'dVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir vendas');
            redirect(base_url());
        }

        $this->load->model('vendas_model');

        $id = $this->input->post('id');

        $editavel = $this->vendas_model->isEditable($id);
        if (! $editavel) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir. Venda já faturada');
            redirect(site_url('vendas/gerenciar/'));
        }

        $venda = $this->vendas_model->getByIdCobrancas($id);
        if ($venda == null) {
            $venda = $this->vendas_model->getById($id);
            if ($venda == null) {
                $this->session->set_flashdata('error', 'Erro ao tentar excluir venda.');
                redirect(site_url('vendas/gerenciar/'));
            }
        }

        if (isset($venda->idCobranca) != null) {
            if ($venda->status == 'canceled') {
                $this->vendas_model->delete('cobrancas', 'vendas_id', $id);
            } else {
                $this->session->set_flashdata('error', 'Existe uma cobrança associada a esta venda, deve cancelar e/ou excluir a cobrança primeiro!');
                redirect(site_url('vendas/gerenciar/'));
            }
        }

        $this->vendas_model->delete('itens_de_vendas', 'vendas_id', $id);
        $this->vendas_model->delete('vendas', 'idVendas', $id);
        if ((int) $venda->faturado === 1) {
            $this->vendas_model->delete('lancamentos', 'descricao', "Fatura de Venda - #${id}");
        }

        log_info('Removeu uma venda. ID: ' . $id);

        $this->session->set_flashdata('success', 'Venda excluída com sucesso!');
        redirect(site_url('vendas/gerenciar/'));
    }

    public function autoCompleteProduto()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->vendas_model->autoCompleteProduto($q);
        }
    }

    public function autoCompleteCliente()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->vendas_model->autoCompleteCliente($q);
        }
    }

    public function autoCompleteUsuario()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->vendas_model->autoCompleteUsuario($q);
        }
    }

    public function autoCompleteVendedor()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->vendas_model->autoCompleteVendedor($q);
        }
    }

    public function getUltimoResultadoVisita()
    {
        $clientes_id = $this->input->get('clientes_id');
        if (!$clientes_id || !is_numeric($clientes_id)) {
            echo json_encode(['resultado' => null]);
            return;
        }
        $this->load->model('visita_tecnica_model');
        $visita = $this->visita_tecnica_model->getUltimoResultado($clientes_id);
        echo json_encode(['resultado' => $visita ? $visita->resultado : null]);
    }

    public function adicionarProduto()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar vendas.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('quantidade', 'Quantidade', 'trim|required');
        $this->form_validation->set_rules('idProduto', 'Produto', 'trim|required');
        $this->form_validation->set_rules('idVendasProduto', 'Vendas', 'trim|required');

        $idVenda = $this->input->post('idVendasProduto');
        $editavel = $this->vendas_model->isEditable($idVenda);
        if (!$editavel) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(422)
                ->set_output(json_encode(['result' => false, 'messages' => '<br /><br /> <strong>Motivo:</strong> Venda já faturada']));
        }

        if ($this->form_validation->run() == false) {
            echo json_encode(['result' => false]);
        } else {
            $preco = $this->input->post('preco');
            $quantidade = $this->input->post('quantidade');
            $margem = (float)$this->input->post('margem') ?: 0;
            $subtotal = $preco * $quantidade * (1 + $margem / 100);
            $produto = $this->input->post('idProduto');
            $data = [
                'quantidade' => $quantidade,
                'subTotal' => $subtotal,
                'produtos_id' => $produto,
                'preco' => $preco,
                'margem' => $margem,
                'vendas_id' => $idVenda,
                'margem' => $margem,
            ];

            if ($this->vendas_model->add('itens_de_vendas', $data) == true) {
                $this->load->model('produtos_model');

                if ($this->data['configuration']['control_estoque']) {
                    $this->produtos_model->updateEstoque($produto, $quantidade, '-');
                }

                // Atualiza o desconto da venda
                $this->db->set('desconto', 0.00);
                $this->db->set('valor_desconto', 0.00);
                $this->db->set('tipo_desconto', null);
                $this->db->where('idVendas', $idVenda);
                $this->db->update('vendas');

                // Registra a ação nos logs com o ID da venda
                log_info('Adicionou produto à venda com ID: ' . $idVenda . ' | Preço: ' . $preco . ' | Qtd: ' . $quantidade . ' | Margem: ' . $margem . '% | SubTotal: ' . number_format($subtotal, 2, '.', ''));

                echo json_encode(['result' => true]);
            } else {
                echo json_encode(['result' => false]);
            }
        }
    }

    public function excluirProduto()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar Vendas.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('idProduto', 'Produto', 'trim|required');
        $this->form_validation->set_rules('idVendas', 'Venda', 'trim|required');
        $this->form_validation->set_rules('quantidade', 'Quantidade', 'trim|required');
        $this->form_validation->set_rules('produto', 'Produto', 'trim|required');

        if ($this->form_validation->run() == false) {
            echo json_encode(['result' => false, 'messages' => 'Dados inválidos']);
            return;
        }

        $idProduto = $this->input->post('idProduto');
        $idVendas = $this->input->post('idVendas');
        $quantidade = $this->input->post('quantidade');
        $produto = $this->input->post('produto');

        $editavel = $this->vendas_model->isEditable($idVendas);
        if (!$editavel) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(422)
                ->set_output(json_encode(['result' => false, 'messages' => '<br /><br /> <strong>Motivo:</strong> Venda já faturada']));
        }

        $this->db->trans_start();

        $this->vendas_model->delete('itens_de_vendas', 'idItens', $idProduto);

        if ($this->data['configuration']['control_estoque']) {
            $this->load->model('produtos_model');
            $this->produtos_model->updateEstoque($produto, $quantidade, '+');
        }

        $this->db->set('desconto', 0.00);
        $this->db->set('valor_desconto', 0.00);
        $this->db->set('tipo_desconto', null);
        $this->db->where('idVendas', $idVendas);
        $this->db->update('vendas');

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode(['result' => false, 'messages' => 'Erro ao excluir o produto']);
        } else {
            $this->db->trans_complete();
            log_info('Removeu produto da venda. ID da Venda: ' . $idVendas . ', ID do Produto: ' . $idProduto);
            echo json_encode(['result' => true, 'messages' => 'Produto removido com sucesso']);
        }
    }

    public function atualizarMargem()
    {
        $idItem = (int)$this->input->post('idItem');
        $margem = (float)$this->input->post('margem');
        $subTotal = (float)$this->input->post('subTotal');

        if (!$idItem) {
            echo json_encode(['result' => false]);
            return;
        }

        $this->db->where('idItens', $idItem);
        $this->db->update('itens_de_vendas', ['margem' => $margem, 'subTotal' => $subTotal]);

        log_info('Atualizou margem inline | Item: ' . $idItem . ' | Margem: ' . $margem . '% | SubTotal: ' . number_format($subTotal, 2, '.', ''));

        echo json_encode(['result' => true]);
    }

    public function adicionarServico()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar Vendas.');
            redirect(base_url());
        }

        $idServico = $this->input->post('idServico');
        $preco = floatval(str_replace(',', '.', $this->input->post('precoServico')));
        $quantidade = floatval($this->input->post('quantidadeServico'));
        $margem = floatval(str_replace(',', '.', $this->input->post('margemServico') ?? '0'));
        $idVendas = $this->input->post('idVendasServico');

        if (!$idServico || !$preco || !$quantidade || !$idVendas) {
            echo json_encode(['result' => false, 'messages' => 'Dados inválidos']);
            return;
        }

        $editavel = $this->vendas_model->isEditable($idVendas);
        if (!$editavel) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(422)
                ->set_output(json_encode(['result' => false, 'messages' => '<br /><br /> <strong>Motivo:</strong> Venda já faturada']));
        }

        $subTotal = $preco * $quantidade * (1 + $margem / 100);

        $data = [
            'servicos_id' => $idServico,
            'vendas_id'   => $idVendas,
            'preco'       => $preco,
            'quantidade'  => $quantidade,
            'margem'      => $margem,
            'subTotal'    => $subTotal,
        ];

        if ($this->vendas_model->add('servicos_vendas', $data)) {
            log_info('Adicionou serviço à venda com ID: ' . $idVendas . ' | Preço: ' . $preco . ' | Qtd: ' . $quantidade . ' | Margem: ' . $margem . '% | SubTotal: ' . number_format($subTotal, 2, '.', ''));
            echo json_encode(['result' => true]);
        } else {
            echo json_encode(['result' => false, 'messages' => 'Erro ao adicionar serviço']);
        }
    }

    public function atualizarMargemServico()
    {
        $idItem = (int)$this->input->post('idItem');
        $margem = (float)$this->input->post('margem');
        $subTotal = (float)$this->input->post('subTotal');

        if (!$idItem) {
            echo json_encode(['result' => false]);
            return;
        }

        $data = [
            'margem'   => $margem,
            'subTotal' => $subTotal,
        ];

        $this->vendas_model->edit('servicos_vendas', $data, 'idServicos_venda', $idItem);
        log_info('Atualizou margem serviço inline | Item: ' . $idItem . ' | Margem: ' . $margem . '% | SubTotal: ' . number_format($subTotal, 2, '.', ''));

        echo json_encode(['result' => true]);
    }

    public function excluirServico()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar Vendas.');
            redirect(base_url());
        }

        $idServicos_venda = $this->input->post('idServicos_venda');

        if (!$idServicos_venda) {
            echo json_encode(['result' => false, 'messages' => 'Dados inválidos']);
            return;
        }

        if ($this->vendas_model->delete('servicos_vendas', 'idServicos_venda', $idServicos_venda)) {
            log_info('Removeu serviço da venda. ID: ' . $idServicos_venda);
            echo json_encode(['result' => true, 'messages' => 'Serviço removido com sucesso']);
        } else {
            echo json_encode(['result' => false, 'messages' => 'Erro ao excluir serviço']);
        }
    }

    public function adicionarDesconto()
    {
        if ($this->input->post('desconto') == '') {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['messages' => 'Campo desconto vazio']));
        } else {
            $idVendas = $this->input->post('idVendas');
            $data = [
                'desconto' => $this->input->post('desconto'),
                'tipo_desconto' => $this->input->post('tipoDesconto'),
                'valor_desconto' => $this->input->post('resultado'),
            ];
            $editavel = $this->vendas_model->isEditable($idVendas);
            if (! $editavel) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['result' => false, 'messages', 'Desconto não pode ser adiciona. Venda não ja Faturada/Cancelada']));
            }
            if ($this->vendas_model->edit('vendas', $data, 'idVendas', $idVendas) == true) {
                log_info('Adicionou um desconto na Venda. ID: ' . $idVendas);

                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(201)
                    ->set_output(json_encode(['result' => true, 'messages' => 'Desconto adicionado com sucesso!']));
            } else {
                log_info('Ocorreu um erro ao tentar adiciona desconto a Venda: ' . $idVendas);

                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['result' => false, 'messages', 'Ocorreu um erro ao tentar adiciona desconto a Venda.']));
            }
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode(['result' => false, 'messages', 'Ocorreu um erro ao tentar adiciona desconto a OS.']));
    }

    public function adicionarDescontoServico()
    {
        $idVendas = $this->input->post('idVendas');
        $desconto = floatval(str_replace(',', '.', $this->input->post('desconto')));
        $tipoDesconto = $this->input->post('tipoDesconto');
        $valorDesconto = floatval($this->input->post('valor_desconto'));

        if (!$idVendas || $desconto <= 0) {
            echo json_encode(['result' => false, 'messages' => 'Dados inválidos']);
            return;
        }

        $editavel = $this->vendas_model->isEditable($idVendas);
        if (!$editavel) {
            echo json_encode(['result' => false, 'messages' => 'Venda já faturada']);
            return;
        }

        $data = [
            'desconto_servico'       => $desconto,
            'tipo_desconto_servico'  => $tipoDesconto,
            'valor_desconto_servico' => $valorDesconto,
        ];

        if ($this->vendas_model->edit('vendas', $data, 'idVendas', $idVendas)) {
            log_info('Adicionou desconto em serviços da Venda. ID: ' . $idVendas . ' | Desconto: ' . $desconto . ' | Tipo: ' . $tipoDesconto . ' | Total: ' . number_format($valorDesconto, 2, '.', ''));
            echo json_encode(['result' => true]);
        } else {
            echo json_encode(['result' => false, 'messages' => 'Erro ao salvar desconto']);
        }
    }

    public function salvarCondicoesPagamento()
    {
        $idVendas = $this->input->post('idVendas');
        if (!$idVendas) {
            echo json_encode(['result' => false, 'messages' => 'ID inválido']);
            return;
        }

        $data = [
            'cond_aceite'           => floatval(str_replace(',', '.', $this->input->post('cond_aceite'))),
            'cond_aceite_pct'       => floatval($this->input->post('cond_aceite_pct')),
            'cond_entrega_serv'     => floatval(str_replace(',', '.', $this->input->post('cond_entrega_serv'))),
            'cond_entrega_serv_pct' => floatval($this->input->post('cond_entrega_serv_pct')),
            'cond_pix'              => floatval(str_replace(',', '.', $this->input->post('cond_pix'))),
            'cond_pix_pct'          => floatval($this->input->post('cond_pix_pct')),
            'cond_parcelado'        => floatval(str_replace(',', '.', $this->input->post('cond_parcelado'))),
            'cond_parcelas'         => intval($this->input->post('cond_parcelas')),
        ];

        if ($this->vendas_model->edit('vendas', $data, 'idVendas', $idVendas)) {
            log_info('Condições de pagamento salvas na Venda ID: ' . $idVendas);
            echo json_encode(['result' => true]);
        } else {
            echo json_encode(['result' => false, 'messages' => 'Erro ao salvar']);
        }
    }

    public function faturar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar Vendas');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('receita') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $venda_id = $this->input->post('vendas_id');
            $vencimento = $this->input->post('vencimento');
            $recebimento = $this->input->post('recebimento');

            try {
                $vencimento = explode('/', $vencimento);
                $vencimento = $vencimento[2] . '-' . $vencimento[1] . '-' . $vencimento[0];

                if ($recebimento != null) {
                    $recebimento = explode('/', $recebimento);
                    $recebimento = $recebimento[2] . '-' . $recebimento[1] . '-' . $recebimento[0];
                }
            } catch (Exception $e) {
                $vencimento = date('Y-m-d');
            }

            $vendas = $this->vendas_model->getById($venda_id);

            // Derivar forma de pagamento automaticamente pelas condições da venda
            $formaPgto = '';
            if (!empty($vendas->cond_parcelas) && $vendas->cond_parcelas > 1) {
                $formaPgto = 'Cartão de Crédito ' . intval($vendas->cond_parcelas) . 'x';
            } elseif (!empty($vendas->cond_pix) && $vendas->cond_pix > 0) {
                $formaPgto = 'Pix';
            } elseif (!empty($vendas->cond_aceite) && $vendas->cond_aceite > 0) {
                $formaPgto = 'Dinheiro';
            } elseif (!empty($vendas->cond_entrega_serv) && $vendas->cond_entrega_serv > 0) {
                $formaPgto = 'Dinheiro';
            }

            $valorTotal = getAmount($this->input->post('valor'));
            $tipoDesconto = $vendas->tipo_desconto;
            $valorDesconto = $vendas->desconto;

            if ($tipoDesconto == 'percentual') {
                $valorDesconto = $valorTotal * ($valorDesconto / 100);
            } elseif ($tipoDesconto == 'real') {
                $valorDesconto = $valorDesconto;
            } else {
                $valorDesconto = 0;
            }

            $valorDesconto = min($valorTotal, $valorDesconto);
            $valorComDesconto = $valorTotal - $valorDesconto;

            $data = [
                'vendas_id' => $venda_id,
                'descricao' => set_value('descricao'),
                'valor' => $valorTotal,
                'desconto' => $vendas->desconto,
                'tipo_desconto' => 'real',
                'valor_desconto' => $valorComDesconto,
                'clientes_id' => $this->input->post('clientes_id'),
                'data_vencimento' => $vencimento,
                'data_pagamento' => $recebimento,
                'baixado' => $this->input->post('recebido') == 1 ? true : false,
                'cliente_fornecedor' => set_value('cliente'),
                'forma_pgto' => $formaPgto,
                'tipo' => 'receita',
                'usuarios_id' => $this->session->userdata('id_admin'),
            ];

            $this->db->trans_start();

            $this->db->insert('lancamentos', $data);
            $idLancamentos = $this->db->insert_id();

            if ($idLancamentos) {
                $this->db->set('faturado', 1);
                $this->db->set('valorTotal', $valorTotal);
                $this->db->set('desconto', $vendas->desconto);
                $this->db->set('valor_desconto', $valorComDesconto);
                $this->db->set('lancamentos_id', $idLancamentos);
                $this->db->set('status', 'Faturado');
                $this->db->where('idVendas', $venda_id);
                $this->db->update('vendas');

                log_info('Faturou a venda com ID.' . $venda_id);

                $this->db->trans_complete();

                if ($this->db->trans_status() === false) {
                    $this->session->set_flashdata('error', 'Ocorreu um erro ao tentar faturar venda.');
                    $json = ['result' => false];
                } else {
                    $this->session->set_flashdata('success', 'Venda faturada com sucesso!');
                    $json = ['result' => true];
                }
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Ocorreu um erro ao tentar faturar venda.');
                $json = ['result' => false];
            }

            echo json_encode($json);
            exit();
        }

        $this->session->set_flashdata('error', 'Ocorreu um erro ao tentar faturar venda.');
        $json = ['result' => false];
        echo json_encode($json);
    }

    public function validarCPF($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1+$/', $cpf)) {
            return false;
        }
        $soma1 = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma1 += $cpf[$i] * (10 - $i);
        }
        $resto1 = $soma1 % 11;
        $dv1 = ($resto1 < 2) ? 0 : 11 - $resto1;
        if ($dv1 != $cpf[9]) {
            return false;
        }
        $soma2 = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma2 += $cpf[$i] * (11 - $i);
        }
        $resto2 = $soma2 % 11;
        $dv2 = ($resto2 < 2) ? 0 : 11 - $resto2;
    
        return $dv2 == $cpf[10];
    }
    
    public function validarCNPJ($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        if (strlen($cnpj) !== 14 || preg_match('/^(\d)\1+$/', $cnpj)) {
            return false;
        }
        $soma1 = 0;
        for ($i = 0, $pos = 5; $i < 12; $i++, $pos--) {
            $pos = ($pos < 2) ? 9 : $pos;
            $soma1 += $cnpj[$i] * $pos;
        }
        $dv1 = ($soma1 % 11 < 2) ? 0 : 11 - ($soma1 % 11);
        if ($dv1 != $cnpj[12]) {
            return false;
        }
        $soma2 = 0;
        for ($i = 0, $pos = 6; $i < 13; $i++, $pos--) {
            $pos = ($pos < 2) ? 9 : $pos;
            $soma2 += $cnpj[$i] * $pos;
        }
        $dv2 = ($soma2 % 11 < 2) ? 0 : 11 - ($soma2 % 11);
    
        return $dv2 == $cnpj[13];
    }
    
    public function formatarChave($chave)
    {
        if ($this->validarCPF($chave)) {
            return substr($chave, 0, 3) . '.' . substr($chave, 3, 3) . '.' . substr($chave, 6, 3) . '-' . substr($chave, 9);
        } elseif ($this->validarCNPJ($chave)) {
            return substr($chave, 0, 2) . '.' . substr($chave, 2, 3) . '.' . substr($chave, 5, 3) . '/' . substr($chave, 8, 4) . '-' . substr($chave, 12);
        } elseif (strlen($chave) === 11) {
            return '(' . substr($chave, 0, 2) . ') ' . substr($chave, 2, 5) . '-' . substr($chave, 7);
        }
        return $chave;
    }

    public function visualizarVenda($id)
    {
        $venda = $this->Vendas_model->getById($id);
        $produtos = $this->Vendas_model->getProdutos($id);
        $total = $this->Vendas_model->getTotalVendas($id);

        $data['venda'] = $venda;
        $data['produtos'] = $produtos;
        $data['total'] = $total;

        $this->load->view('vendas/vendas', $data);
    }

    public function getVendaJson($id = null)
    {
        $id = (int)($id ?: $this->input->get('id'));

        $json = function($data) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        };

        if (! $id) {
            return $json(['result' => false, 'message' => 'ID inválido']);
        }

        // Busca só o essencial — sem colunas que podem não existir
        $venda = $this->db->select('vendas.idVendas, clientes.nomeCliente')
            ->from('vendas')
            ->join('clientes', 'clientes.idClientes = vendas.clientes_id')
            ->where('vendas.idVendas', $id)
            ->get()->row();

        if (! $venda) {
            return $json(['result' => false, 'message' => 'Venda não encontrada']);
        }

        // Busca dados completos da venda para derivar forma de pagamento
        $v = $this->db->get_where('vendas', ['idVendas' => $id])->row();

        // Monta texto de forma de pagamento com valores
        $forma_texto = '';
        if ($v) {
            $partes = [];
            if (!empty($v->cond_pix) && $v->cond_pix > 0)
                $partes[] = 'PIX R$ ' . number_format($v->cond_pix, 2, ',', '.');
            if (!empty($v->cond_aceite) && $v->cond_aceite > 0)
                $partes[] = 'No Aceite R$ ' . number_format($v->cond_aceite, 2, ',', '.');
            if (!empty($v->cond_entrega_serv) && $v->cond_entrega_serv > 0)
                $partes[] = 'Na Entrega R$ ' . number_format($v->cond_entrega_serv, 2, ',', '.');
            if (!empty($v->cond_parcelas) && $v->cond_parcelas > 1) {
                $val_parc = !empty($v->cond_parcelado) && $v->cond_parcelado > 0 ? floatval($v->cond_parcelado) : 0;
                // Fallback: total da venda menos valores já cobertos por outras condições
                if ($val_parc == 0 && !empty($v->total) && $v->total > 0) {
                    $ja_coberto = floatval($v->cond_pix ?? 0) + floatval($v->cond_aceite ?? 0) + floatval($v->cond_entrega_serv ?? 0);
                    $val_parc = floatval($v->total) - $ja_coberto;
                    if ($val_parc < 0) $val_parc = 0;
                }
                $por_parcela = $v->cond_parcelas > 0 && $val_parc > 0 ? $val_parc / intval($v->cond_parcelas) : 0;
                $txt = 'Cartão ' . intval($v->cond_parcelas) . 'x';
                if ($por_parcela > 0) $txt .= ' de R$ ' . number_format($por_parcela, 2, ',', '.');
                elseif ($val_parc > 0) $txt .= ' R$ ' . number_format($val_parc, 2, ',', '.');
                $partes[] = $txt;
            }
            $forma_texto = implode(' + ', $partes);
        }

        return $json([
            'result'          => true,
            'cliente'         => $venda->nomeCliente,
            'formas'          => [],
            'forma_pagamento' => $forma_texto,
            'descricao'       => 'Venda #' . $id,
        ]);
    }

}
