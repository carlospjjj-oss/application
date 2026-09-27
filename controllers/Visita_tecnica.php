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
                'usuarios_id'  => $this->input->post('usuarios_id') ?: $this->session->userdata('id_admin'),
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
        $this->data['usuariosAtivos'] = $this->visita_tecnica_model->getUsuariosAtivos();
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
                'usuarios_id'  => $this->input->post('usuarios_id'),
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
        $this->data['midias'] = $this->visita_tecnica_model->getMidias($id);
        $this->data['usuariosAtivos'] = $this->visita_tecnica_model->getUsuariosAtivos();
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

    public function getCsrfToken()
    {
        echo json_encode(['csrf_hash' => $this->security->get_csrf_hash()]);
    }

    public function uploadMidia()
    {
        $visita_tecnica_id = $this->input->post('visita_tecnica_id');
        if (! $visita_tecnica_id || ! is_numeric($visita_tecnica_id)) {
            echo json_encode(['success' => false, 'message' => 'Visita inválida.']);
            return;
        }

        if (empty($_FILES['midia']['name'])) {
            echo json_encode(['success' => false, 'message' => 'Nenhum arquivo enviado.']);
            return;
        }

        $ext = strtolower(pathinfo($_FILES['midia']['name'], PATHINFO_EXTENSION));
        $isFoto = in_array($ext, ['jpg', 'jpeg', 'png']);
        $isVideo = in_array($ext, ['mp4', 'mov', 'webm']);

        if (! $isFoto && ! $isVideo) {
            echo json_encode(['success' => false, 'message' => 'Formato de arquivo não permitido.']);
            return;
        }

        $tipo = $isFoto ? 'foto' : 'video';

        $atual = $this->visita_tecnica_model->countMidiasPorTipo($visita_tecnica_id, $tipo);
        $limite = $isFoto ? 6 : 3;

        if ($atual >= $limite) {
            $label = $isFoto ? 'fotos' : 'vídeos';
            echo json_encode(['success' => false, 'message' => "Limite de {$limite} {$label} já atingido."]);
            return;
        }

        $config['upload_path']   = FCPATH . 'assets/visita_midia/';
        $config['allowed_types'] = 'jpg|jpeg|png|mp4|mov|webm';
        $config['max_size']      = 51200; // 50MB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('midia')) {
            $upload_data = $this->upload->data();
            $novoId = $this->visita_tecnica_model->addMidia([
                'visita_tecnica_id' => $visita_tecnica_id,
                'tipo' => $tipo,
                'arquivo' => $upload_data['file_name'],
            ]);
            if ($novoId) {
                echo json_encode(['success' => true, 'id' => $novoId, 'arquivo' => $upload_data['file_name'], 'tipo' => $tipo, 'csrf_hash' => $this->security->get_csrf_hash()]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao salvar no banco.', 'csrf_hash' => $this->security->get_csrf_hash()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => $this->upload->display_errors('', ''), 'csrf_hash' => $this->security->get_csrf_hash()]);
        }
    }

    public function excluirMidia()
    {
        $id = $this->input->post('id');
        if (! $id || ! is_numeric($id)) {
            echo json_encode(['success' => false]);
            return;
        }
        $midia = $this->visita_tecnica_model->getMidiaById($id);
        if ($midia) {
            $filePath = FCPATH . 'assets/visita_midia/' . $midia->arquivo;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $ok = $this->visita_tecnica_model->deleteMidia($id);
        echo json_encode(['success' => $ok, 'csrf_hash' => $this->security->get_csrf_hash()]);
    }
}
