<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

class Visita_tecnica extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('visita_tecnica_model');
        $this->data['menuVisitaTecnica'] = 'VisitaTecnica';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'vCliente')) {
            $this->session->set_flashdata('error', 'Sem permissão para visualizar visitas.');
            redirect(base_url());
        }
        $this->load->library('pagination');
        $this->data['configuration']['base_url'] = site_url('visita_tecnica/gerenciar/');
        $this->data['configuration']['total_rows'] = $this->visita_tecnica_model->count('visita_tecnica');
        $this->pagination->initialize($this->data['configuration']);
        $this->data['results'] = $this->visita_tecnica_model->getAll(
            $this->data['configuration']['per_page'],
            $this->uri->segment(3)
        );
        $this->data['view'] = 'visita_tecnica/index';
        return $this->layout();
    }

    public function adicionar()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'aCliente')) {
            $this->session->set_flashdata('error', 'Sem permissão para adicionar visitas.');
            redirect(base_url());
        }
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->input->post()) {
            $data_visita = $this->input->post('data_visita');
            if ($data_visita) {
                $partes = explode('/', $data_visita);
                $data_visita = $partes[2] . '-' . $partes[1] . '-' . $partes[0];
            }
            $data = [
                'clientes_id'  => $this->input->post('clientes_id'),
                'usuarios_id'  => $this->session->userdata('id_admin'),
                'data_visita'  => $data_visita,
                'hora_visita'  => $this->input->post('hora_visita') ?: null,
                'status'       => $this->input->post('status'),
                'tipo_servico' => $this->input->post('tipo_servico'),
                'endereco'     => $this->input->post('endereco'),
                'observacoes'  => $this->input->post('observacoes'),
                'resultado'    => $this->input->post('resultado'),
            ];
            if ($this->visita_tecnica_model->add('visita_tecnica', $data)) {
                $this->session->set_flashdata('success', 'Visita técnica agendada com sucesso!');
                log_info('Agendou visita tecnica');
                redirect(site_url('visita_tecnica/gerenciar/'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro ao salvar.</p></div>';
            }
        }
        $this->data['view'] = 'visita_tecnica/adicionar';
        return $this->layout();
    }

    public function editar()
    {
        $id = $this->uri->segment(3);
        if (! $id || ! is_numeric($id)) {
            $this->session->set_flashdata('error', 'Visita não encontrada.');
            redirect('visita_tecnica/gerenciar');
        }
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->input->post()) {
            $data_visita = $this->input->post('data_visita');
            if ($data_visita) {
                $partes = explode('/', $data_visita);
                $data_visita = $partes[2] . '-' . $partes[1] . '-' . $partes[0];
            }
            $data = [
                'clientes_id'  => $this->input->post('clientes_id'),
                'data_visita'  => $data_visita,
                'hora_visita'  => $this->input->post('hora_visita') ?: null,
                'status'       => $this->input->post('status'),
                'tipo_servico' => $this->input->post('tipo_servico'),
                'endereco'     => $this->input->post('endereco'),
                'observacoes'  => $this->input->post('observacoes'),
                'resultado'    => $this->input->post('resultado'),
            ];
            if ($this->visita_tecnica_model->edit('visita_tecnica', $data, 'id', $id)) {
                $this->session->set_flashdata('success', 'Visita atualizada com sucesso!');
                log_info('Editou visita tecnica ID: ' . $id);
                redirect(site_url('visita_tecnica/gerenciar/'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro ao salvar.</p></div>';
            }
        }
        $this->data['result'] = $this->visita_tecnica_model->getById($id);
        $this->data['view'] = 'visita_tecnica/editar';
        return $this->layout();
    }

    public function excluir()
    {
        $id = $this->input->post('id');
        if (! $id || ! is_numeric($id)) {
            $this->session->set_flashdata('error', 'Erro ao excluir visita.');
            redirect(site_url('visita_tecnica/gerenciar/'));
        }
        $this->db->where('id', $id)->delete('visita_tecnica');
        $this->session->set_flashdata('success', 'Visita excluída com sucesso!');
        log_info('Removeu visita tecnica ID: ' . $id);
        redirect(site_url('visita_tecnica/gerenciar/'));
    }
}
