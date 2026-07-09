<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    protected $table = 'users';

    public function __construct()
    {
        parent::__construct();
    }

    public function login($email, $password)
    {
        $this->db->select('u.*, r.name as role_name, r.display_name as role_display_name');
        $this->db->from($this->table . ' u');
        $this->db->join('roles r', 'r.id = u.role_id');
        $this->db->where('u.email', $email);
        $this->db->where('u.is_active', 1);
        $query = $this->db->get();

        if ($query->num_rows() == 1)
        {
            $user = $query->row();
            if (password_verify($password, $user->password))
            {
                return $user;
            }
        }
        return FALSE;
    }

    public function get_by_id($id)
    {
        $this->db->select('u.*, r.name as role_name, r.display_name as role_display_name');
        $this->db->from($this->table . ' u');
        $this->db->join('roles r', 'r.id = u.role_id');
        $this->db->where('u.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_all($is_active = NULL)
    {
        $this->db->select('u.*, r.name as role_name, r.display_name as role_display_name');
        $this->db->from($this->table . ' u');
        $this->db->join('roles r', 'r.id = u.role_id');

        if ($is_active !== NULL)
        {
            $this->db->where('u.is_active', $is_active);
        }

        $this->db->order_by('u.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_by_role($role_name)
    {
        $this->db->select('u.*, r.name as role_name, r.display_name as role_display_name');
        $this->db->from($this->table . ' u');
        $this->db->join('roles r', 'r.id = u.role_id');
        $this->db->where('r.name', $role_name);
        $this->db->where('u.is_active', 1);
        $this->db->order_by('u.name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_by_role_id($role_id)
    {
        $this->db->select('u.*, r.name as role_name, r.display_name as role_display_name');
        $this->db->from($this->table . ' u');
        $this->db->join('roles r', 'r.id = u.role_id');
        $this->db->where('u.role_id', $role_id);
        $this->db->where('u.is_active', 1);
        $this->db->order_by('u.name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function insert($data)
    {
        if (isset($data['password']))
        {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        if (isset($data['password']) && !empty($data['password']))
        {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        elseif (isset($data['password']))
        {
            unset($data['password']);
        }

        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, array('is_active' => 0));
    }

    public function toggle_active($id)
    {
        $user = $this->get_by_id($id);
        if ($user)
        {
            $this->db->where('id', $id);
            return $this->db->update($this->table, array('is_active' => !$user->is_active));
        }
        return FALSE;
    }

    public function email_exists($email, $exclude_id = NULL)
    {
        $this->db->where('email', $email);
        if ($exclude_id !== NULL)
        {
            $this->db->where('id !=', $exclude_id);
        }
        $query = $this->db->get($this->table);
        return $query->num_rows() > 0;
    }

    public function get_all_roles()
    {
        $query = $this->db->get('roles');
        return $query->result();
    }
}
