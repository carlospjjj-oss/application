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
        $this->db->where('id', $id);
        return $this->db->get('visita_tecnica')->row();
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
