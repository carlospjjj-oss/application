<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

class Visita_tecnica_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll($perpage = 20, $start = 0)
    {
        $this->db->select('vt.*, c.nomeCliente, u.nome as tecnico');
        $this->db->from('visita_tecnica vt');
        $this->db->join('clientes c', 'c.idClientes = vt.clientes_id', 'left');
        $this->db->join('usuarios u', 'u.idUsuarios = vt.usuarios_id', 'left');
        $this->db->order_by('vt.data_visita', 'desc');
        $this->db->limit($perpage, $start);
        return $this->db->get()->result();
    }

    public function getById($id)
    {
        $this->db->select('visita_tecnica.*, clientes.nomeCliente');
        $this->db->from('visita_tecnica');
        $this->db->join('clientes', 'clientes.idClientes = visita_tecnica.clientes_id', 'left');
        $this->db->where('visita_tecnica.id', $id);
        return $this->db->get()->row();
    }

    public function getUltimoResultado($clientes_id)
    {
        $this->db->select('resultado');
        $this->db->where('clientes_id', $clientes_id);
        $this->db->where('status', 'Realizada');
        $this->db->where('resultado IS NOT NULL', null, false);
        $this->db->order_by('data_visita', 'desc');
        $this->db->limit(1);
        return $this->db->get('visita_tecnica')->row();
    }

    public function getMidias($visita_tecnica_id)
    {
        $this->db->where('visita_tecnica_id', $visita_tecnica_id);
        $this->db->order_by('criado_em', 'asc');
        return $this->db->get('visita_tecnica_midia')->result();
    }

    public function countMidiasPorTipo($visita_tecnica_id, $tipo)
    {
        $this->db->where('visita_tecnica_id', $visita_tecnica_id);
        $this->db->where('tipo', $tipo);
        return $this->db->count_all_results('visita_tecnica_midia');
    }

    public function addMidia($data)
    {
        $this->db->insert('visita_tecnica_midia', $data);
        if ($this->db->affected_rows() == 1) {
            return $this->db->insert_id();
        }
        return false;
    }

    public function getMidiaById($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('visita_tecnica_midia')->row();
    }

    public function deleteMidia($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('visita_tecnica_midia');
    }

    public function getUsuariosAtivos()
    {
        $this->db->select('idUsuarios, nome');
        $this->db->where('situacao', 1);
        $this->db->order_by('nome', 'asc');
        return $this->db->get('usuarios')->result();
    }

    public function add($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->affected_rows() == 1;
    }

    public function edit($table, $data, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->update($table, $data);
        return $this->db->affected_rows() >= 0;
    }

    public function count($table)
    {
        return $this->db->count_all($table);
    }
}
