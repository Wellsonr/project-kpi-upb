<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment_model extends CI_Model {

    protected $table = 'task_comments';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_by_task($task_id)
    {
        $this->db->select('c.*, u.name as user_name, u.role_id, r.display_name as user_role');
        $this->db->from($this->table . ' c');
        $this->db->join('users u', 'u.id = c.user_id');
        $this->db->join('roles r', 'r.id = u.role_id', 'left');
        $this->db->where('c.task_id', $task_id);
        $this->db->order_by('c.created_at', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_by_id($id)
    {
        $this->db->select('c.*, u.name as user_name');
        $this->db->from($this->table . ' c');
        $this->db->join('users u', 'u.id = c.user_id');
        $this->db->where('c.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function insert($task_id, $user_id, $comment)
    {
        $data = array(
            'task_id' => $task_id,
            'user_id' => $user_id,
            'comment' => $comment
        );

        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $comment)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, array('comment' => $comment));
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    public function count_by_task($task_id)
    {
        $this->db->where('task_id', $task_id);
        return $this->db->count_all_results($this->table);
    }

    public function get_recent($user_id = NULL, $limit = 10)
    {
        $this->db->select('c.*, t.title as task_title, u.name as user_name');
        $this->db->from($this->table . ' c');
        $this->db->join('tasks t', 't.id = c.task_id');
        $this->db->join('users u', 'u.id = c.user_id');

        if ($user_id !== NULL)
        {
            $this->db->where('c.user_id', $user_id);
        }

        $this->db->order_by('c.created_at', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }
}
