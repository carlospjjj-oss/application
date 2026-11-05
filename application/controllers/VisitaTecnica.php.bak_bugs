<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VisitaTecnica extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('visita_tecnica_model');
        $this->load->model('clientes_model');
        $this->load->model('usuarios_model');
        $this->data['menuVisitaTecnica'] = 'visitaTecnica';
    }

    public function index()
    {
        $pesquisa = $this->input->get('pesquisa');
        $this->data['results'] = $this->visita_tecnica_model->getAll($pesquisa);
        $this->data['view'] = 'visita_tecnica/index';
        return $this->layout();
    }

    public function adicionar()
    {
        $this->data['custom_error'] = '';

        if ($this->input->post()) {
            $data = [
                'clientes_id'        => $this->input->post('clientes_id'),
                'usuarios_id'        => $this->input->post('usuarios_id'),
                'data_visita'        => date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $this->input->post('data_visita')))),
                'tipo_instalacao'    => $this->input->post('tipo_instalacao'),
                'potencia_disponivel'=> $this->input->post('potencia_disponivel'),
                'distancia_quadro'   => $this->input->post('distancia_quadro'),
                'observacoes'        => $this->input->post('observacoes'),
                'data_cadastro'      => date('Y-m-d H:i:s'),
            ];

            if ($this->visita_tecnica_model->add($data)) {
                $this->session->set_flashdata('success', 'Visita técnica registrada com sucesso!');
                redirect(site_url('visita_tecnica'));
            } else {
                $this->data['custom_error'] = '<div class="alert alert-danger">Ocorreu um erro ao salvar.</div>';
            }
        }

        $this->data['clientes'] = $this->clientes_model->get('clientes');
        $this->data['tecnicos'] = $this->usuarios_model->get('usuarios');
        $this->data['view'] = 'visita_tecnica/adicionar';
        return $this->layout();
    }

    public function editar($id = null)
    {
        $this->data['custom_error'] = '';
        $this->data['result'] = $this->visita_tecnica_model->getById($id);

        if (!$this->data['result']) {
            show_404();
        }

        if ($this->input->post()) {
            $data = [
                'clientes_id'        => $this->input->post('clientes_id'),
                'usuarios_id'        => $this->input->post('usuarios_id'),
                'data_visita'        => date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $this->input->post('data_visita')))),
                'tipo_instalacao'    => $this->input->post('tipo_instalacao'),
                'potencia_disponivel'=> $this->input->post('potencia_disponivel'),
                'distancia_quadro'   => $this->input->post('distancia_quadro'),
                'observacoes'        => $this->input->post('observacoes'),
            ];

            if ($this->visita_tecnica_model->edit($data, $id)) {
                $this->session->set_flashdata('success', 'Visita técnica atualizada com sucesso!');
                redirect(site_url('visita_tecnica/editar/') . $id);
            } else {
                $this->data['custom_error'] = '<div class="alert alert-danger">Ocorreu um erro ao atualizar.</div>';
            }
        }

        $this->data['clientes'] = $this->clientes_model->get('clientes');
        $this->data['tecnicos'] = $this->usuarios_model->get('usuarios');
        $this->data['view'] = 'visita_tecnica/editar';
        return $this->layout();
    }

    public function deletar($id = null)
    {
        if ($this->visita_tecnica_model->delete($id)) {
            $this->session->set_flashdata('success', 'Visita técnica removida.');
        }
        redirect(site_url('visita_tecnica'));
    }

    public function autoCompleteCliente()
    {
        $term = $this->input->get('term');
        $clientes = $this->clientes_model->getAutocomplete($term);
        echo json_encode($clientes);
    }
}
