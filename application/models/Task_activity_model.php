<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task_activity_model extends CI_Model {

    protected $table = 'task_activities';

    public function log($task_id, $user_id, $action, $description)
    {
        return $this->db->insert($this->table, array(
            'task_id' => $task_id,
            'user_id' => $user_id,
            'action' => $action,
            'description' => $description
        ));
    }

    public function get_by_task($task_id)
    {
        $this->db->select('a.*, u.name as user_name');
        $this->db->from($this->table . ' a');
        $this->db->join('users u', 'u.id = a.user_id', 'left');
        $this->db->where('a.task_id', $task_id);
        $this->db->order_by('a.created_at', 'DESC');
        return $this->db->get()->result();
    }
}
