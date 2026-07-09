<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_model extends CI_Model {

    protected $table = 'roles';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function get_all_permissions()
    {
        $query = $this->db->get('permissions');
        return $query->result();
    }

    public function get_permission_keys($role_id)
    {
        $this->db->select('p.key');
        $this->db->from('role_permissions rp');
        $this->db->join('permissions p', 'p.id = rp.permission_id');
        $this->db->where('rp.role_id', $role_id);
        $query = $this->db->get();

        $keys = array();
        foreach ($query->result() as $row)
        {
            $keys[] = $row->key;
        }
        return $keys;
    }

    public function get_permission_ids($role_id)
    {
        $this->db->select('permission_id');
        $this->db->from('role_permissions');
        $this->db->where('role_id', $role_id);
        $query = $this->db->get();

        $ids = array();
        foreach ($query->result() as $row)
        {
            $ids[] = (int) $row->permission_id;
        }
        return $ids;
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function save_permissions($role_id, array $permission_ids)
    {
        $this->db->where('role_id', $role_id);
        $this->db->delete('role_permissions');

        if (empty($permission_ids))
        {
            return TRUE;
        }

        $rows = array();
        foreach ($permission_ids as $permission_id)
        {
            $rows[] = array('role_id' => $role_id, 'permission_id' => (int) $permission_id);
        }
        return $this->db->insert_batch('role_permissions', $rows);
    }

    public function user_count($role_id)
    {
        $this->db->where('role_id', $role_id);
        return $this->db->count_all_results('users');
    }

    public function delete($id)
    {
        if ($this->user_count($id) > 0)
        {
            return FALSE;
        }
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}
