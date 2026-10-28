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
                'clientes_id'         => $this->input->post('clientes_id'),
                'usuarios_id'         => $this->input->post('usuarios_id'),
                'data_visita'         => date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $this->input->post('data_visita')))),
                'tipo_instalacao'     => $this->input->post('tipo_instalacao'),
                'potencia_disponivel' => $this->input->post('potencia_disponivel'),
                'distancia_quadro'    => $this->input->post('distancia_quadro'),
                'observacoes'         => $this->input->post('observacoes'),
                'data_cadastro'       => date('Y-m-d H:i:s'),
            ];

            if ($this->visita_tecnica_model->add('visita_tecnica', $data)) {
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
                'clientes_id' => $this->input->post('clientes_id'),
                'usuarios_id' => $this->input->post('usuarios_id'),
                'data_visita' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('data_visita')))),
                'hora_visita' => $this->input->post('hora_visita'),
                'status'      => $this->input->post('status'),
                'tipo_servico'=> $this->input->post('tipo_servico'),
                'endereco'    => $this->input->post('endereco'),
                'observacoes' => $this->input->post('observacoes'),
                'resultado'   => $this->input->post('resultado'),
            ];

            if ($this->visita_tecnica_model->edit('visita_tecnica', $data, 'id', $id)) {
                $this->session->set_flashdata('success', 'Visita técnica atualizada com sucesso!');
                redirect(site_url('visita_tecnica/editar/') . $id);
            } else {
                $this->data['custom_error'] = '<div class="alert alert-danger">Ocorreu um erro ao atualizar.</div>';
            }
        }

        $this->data['midias']         = $this->visita_tecnica_model->getMidias($id);
        $this->data['usuariosAtivos']  = $this->visita_tecnica_model->getUsuariosAtivos();
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

    // Retorna novo token CSRF para requisições AJAX
    public function getCsrfToken()
    {
        $this->output->set_content_type('application/json');
        echo json_encode([
            'csrf_hash' => $this->security->get_csrf_hash()
        ]);
    }

    // Upload de foto ou vídeo (BUG 2 corrigido)
    public function uploadMidia()
    {
        $this->output->set_content_type('application/json');

        $visitaId = (int) $this->input->post('visita_tecnica_id');
        if (!$visitaId) {
            echo json_encode(['success' => false, 'message' => 'ID da visita inválido.']);
            return;
        }

        $uploadDir = FCPATH . 'assets/visita_midia/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $file = $_FILES['midia'] ?? null;
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'Nenhum arquivo recebido ou erro no upload.']);
            return;
        }

        $ext       = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $fotoExts  = ['jpg', 'jpeg', 'png'];
        $videoExts = ['mp4', 'mov', 'webm'];

        if (in_array($ext, $fotoExts)) {
            $tipo = 'foto';
        } elseif (in_array($ext, $videoExts)) {
            $tipo = 'video';
        } else {
            echo json_encode(['success' => false, 'message' => 'Tipo de arquivo não permitido.']);
            return;
        }

        // Verifica limites usando countMidiasPorTipo do model
        $totalTipo = $this->visita_tecnica_model->countMidiasPorTipo($visitaId, $tipo);
        if ($tipo === 'foto'  && $totalTipo >= 6) {
            echo json_encode(['success' => false, 'message' => 'Limite de 6 fotos atingido.']);
            return;
        }
        if ($tipo === 'video' && $totalTipo >= 3) {
            echo json_encode(['success' => false, 'message' => 'Limite de 3 vídeos atingido.']);
            return;
        }

        $nomeArquivo = md5(uniqid(rand(), true)) . '.' . $ext;
        if (!move_uploaded_file($file['tmp_name'], $uploadDir . $nomeArquivo)) {
            echo json_encode(['success' => false, 'message' => 'Falha ao salvar o arquivo no servidor.']);
            return;
        }

        $insertId = $this->visita_tecnica_model->addMidia([
            'visita_tecnica_id' => $visitaId,
            'arquivo'           => $nomeArquivo,
            'tipo'              => $tipo,
            'criado_em'         => date('Y-m-d H:i:s'),
        ]);

        echo json_encode([
            'success'   => true,
            'id'        => $insertId,
            'arquivo'   => $nomeArquivo,
            'tipo'      => $tipo,
            'csrf_hash' => $this->security->get_csrf_hash(),
        ]);
    }

    // Exclui mídia por ID
    public function excluirMidia()
    {
        $this->output->set_content_type('application/json');

        $id = (int) $this->input->post('id');
        if (!$id) {
            echo json_encode(['success' => false]);
            return;
        }

        $midia = $this->visita_tecnica_model->getMidiaById($id);
        if ($midia) {
            $arquivo = FCPATH . 'assets/visita_midia/' . $midia->arquivo;
            if (file_exists($arquivo)) {
                unlink($arquivo);
            }
            $this->visita_tecnica_model->deleteMidia($id);
        }

        echo json_encode(['success' => true]);
    }
}
