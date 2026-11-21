<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cobrancas extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->model('cobrancas_model');
        $this->data['menuCobrancas'] = 'financeiro';
    }

    public function index()
    {
        $this->cobrancas();
    }

    public function adicionar()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'aCobranca')) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(403)
                ->set_output(json_encode(['result' => false, 'message' => 'Você não tem permissão para adicionar cobrança!']));
        }

        $id            = (int) $this->input->post('id');
        $tipo          = $this->input->post('tipo');          // 'os' ou 'venda'
        $formaPagamento = $this->input->post('forma_pagamento');
        $descricao     = $this->input->post('descricao');
        $vencimentoRaw = $this->input->post('vencimento');   // dd/mm/aaaa

        if (! $id || ! $tipo || ! $formaPagamento || ! $vencimentoRaw) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['result' => false, 'message' => 'Preencha todos os campos obrigatórios.']));
        }

        // Converter vencimento para Y-m-d
        try {
            $vencimento = DateTime::createFromFormat('d/m/Y', $vencimentoRaw)->format('Y-m-d');
        } catch (Exception $e) {
            $vencimento = date('Y-m-d');
        }

        // Buscar dados do cliente e valor total conforme tipo
        $this->load->model('Os_model');
        $this->load->model('vendas_model');

        if ($tipo === 'os') {
            $registro = $this->Os_model->getOs($id);
            $clienteId = $registro ? $registro->clientes_id : null;
            $valorTotalData = $this->os_model->valorTotalOS($id);
            $total = ($valorTotalData['totalServico'] + $valorTotalData['totalProdutos']) - $valorTotalData['valor_desconto'];
        } else {
            $registro = $this->vendas_model->getVenda($id);
            $clienteId = $registro ? $registro->clientes_id : null;
            $total = $registro ? $registro->valorTotal : 0;
        }

        $data = [
            'charge_id'         => null,
            'payment_gateway'   => 'Manual',
            'payment_url'       => null,
            'barcode'           => '',
            'link'              => '',
            'pdf'               => null,
            'status'            => 'Pendente',
            'total'             => $total > 0 ? $total : 0,
            'expire_at'         => $vencimento,
            'message'           => $descricao ?: ($tipo === 'os' ? 'OS #' . $id : 'Venda #' . $id),
            'payment'           => $formaPagamento,
            'os_id'             => $tipo === 'os'    ? $id : null,
            'vendas_id'         => $tipo === 'venda' ? $id : null,
            'clientes_id'       => $clienteId,
        ];

        try {
            $this->db->insert('cobrancas', $data);

            if ($this->db->affected_rows() > 0) {
                log_info('Cobrança manual registrada. Tipo: ' . $tipo . ' ID: ' . $id);
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['result' => true, 'message' => 'Cobrança registrada com sucesso!']));
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['result' => false, 'message' => 'Erro ao salvar cobrança no banco de dados.']));
        } catch (Exception $e) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['result' => false, 'message' => $e->getMessage()]));
        }
    }

    public function cobrancas()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'vCobranca')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar cobrancas.');
            redirect(base_url());
        }

        $this->load->library('pagination');
        $this->load->config('payment_gateways');

        $this->data['configuration']['base_url'] = site_url('cobrancas/cobrancas/');
        $this->data['configuration']['total_rows'] = $this->cobrancas_model->count('cobrancas');

        $this->pagination->initialize($this->data['configuration']);

        $this->data['results'] = $this->cobrancas_model->get('cobrancas', '*', '', $this->data['configuration']['per_page'], $this->uri->segment(3));

        $this->data['view'] = 'cobrancas/cobrancas';

        return $this->layout();
    }

    public function excluir()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'dCobranca')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir cobranças');
            redirect(site_url('cobrancas/cobrancas/'));
        }
        try {
            $this->cobrancas_model->cancelarPagamento($this->input->post('excluir_id'));

            if ($this->cobrancas_model->delete('cobrancas', 'idCobranca', $this->input->post('excluir_id')) == true) {
                log_info('Removeu uma cobrança. ID' . $this->input->post('excluir_id'));
                $this->session->set_flashdata('success', 'Cobrança excluida com sucesso!');
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro</p></div>';
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
        }
        redirect($_SERVER['HTTP_REFERER'] ?? site_url('cobrancas/cobrancas/'));
    }

    public function atualizar()
    {
        if (! $this->uri->segment(3) || ! is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eCobranca')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para atualizar cobrança.');
            redirect(base_url());
        }
        try {
            $this->load->model('cobrancas_model');
            $this->cobrancas_model->atualizarStatus($this->uri->segment(3));
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
        }
        redirect($_SERVER['HTTP_REFERER'] ?? site_url('cobrancas/cobrancas/'));
    }

    public function confirmarPagamento()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eCobranca')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para confirmar pagamento da cobrança.');
            redirect(base_url());
        }
        try {
            $this->load->model('cobrancas_model');
            $this->cobrancas_model->confirmarPagamento($this->input->post('confirma_id'));
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
        }
        redirect($_SERVER['HTTP_REFERER'] ?? site_url('cobrancas/cobrancas/'));
    }

    public function cancelar()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eCobranca')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para cancelar cobrança.');
            redirect(base_url());
        }
        try {
            $this->load->model('cobrancas_model');
            $this->cobrancas_model->cancelarPagamento($this->input->post('cancela_id'));
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
        }
        redirect($_SERVER['HTTP_REFERER'] ?? site_url('cobrancas/cobrancas/'));
    }

    public function visualizar()
    {
        if (! $this->uri->segment(3) || ! is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('cobrancas');
        }

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'vCobranca')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar cobranças.');
            redirect(base_url());
        }
        $this->load->model('cobrancas_model');
        $this->load->config('payment_gateways');

        $this->data['result'] = $this->cobrancas_model->getById($this->uri->segment(3));
        if ($this->data['result'] == null) {
            $this->session->set_flashdata('error', 'Cobrança não encontrada.');
            redirect(site_url('cobrancas/'));
        }

        $this->data['view'] = 'cobrancas/visualizarCobranca';

        return $this->layout();
    }

    public function enviarEmail()
    {
        if (! $this->uri->segment(3) || ! is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('cobrancas');
        }

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'vCobranca')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar cobranças.');
            redirect(base_url());
        }

        $this->load->model('cobrancas_model');
        $this->cobrancas_model->enviarEmail($this->uri->segment(3));
        $this->session->set_flashdata('success', 'Email adicionado na fila.');

        redirect($_SERVER['HTTP_REFERER'] ?? site_url('cobrancas/cobrancas/'));
    }
}
