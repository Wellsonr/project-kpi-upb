<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_model extends CI_Model {

    protected $table = 'notifications';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_by_user($user_id, $limit = 20, $offset = 0)
    {
        $this->db->select('n.*, t.title as task_title, p.title as project_title');
        $this->db->from($this->table . ' n');
        $this->db->join('tasks t', 't.id = n.task_id', 'left');
        $this->db->join('projects p', 'p.id = t.project_id', 'left');
        $this->db->where('n.user_id', $user_id);
        $this->db->order_by('n.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_unread($user_id, $limit = 10)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('is_read', 0);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function unread_count($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('is_read', 0);
        return $this->db->count_all_results($this->table);
    }

    public function create($user_id, $message, $type = 'task_assigned', $task_id = NULL)
    {
        $data = array(
            'user_id' => $user_id,
            'message' => $message,
            'type' => $type,
            'task_id' => $task_id,
            'is_read' => 0
        );

        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function mark_as_read($notification_id, $user_id)
    {
        $this->db->where('id', $notification_id);
        $this->db->where('user_id', $user_id);
        return $this->db->update($this->table, array('is_read' => 1));
    }

    public function mark_all_read($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('is_read', 0);
        return $this->db->update($this->table, array('is_read' => 1));
    }

    public function delete($notification_id, $user_id)
    {
        $this->db->where('id', $notification_id);
        $this->db->where('user_id', $user_id);
        return $this->db->delete($this->table);
    }

    public function get_by_id($id)
    {
        $query = $this->db->get_where($this->table, array('id' => $id));
        return $query->row();
    }

    public function notify_task_assigned($task_id, $assigned_to_id)
    {
        $this->load->model('Task_model');
        $task = $this->Task_model->get_by_id($task_id);

        $message = "Tugas baru ditugaskan: {$task->title}";
        if ($task->project_title)
        {
            $message .= " di proyek: {$task->project_title}";
        }

        return $this->create($assigned_to_id, $message, 'task_assigned', $task_id);
    }

    public function notify_deadline_reminder($task_id)
    {
        $this->load->model('Task_model');
        $task = $this->Task_model->get_by_id($task_id);

        $days_left = days_remaining($task->deadline);

        if ($days_left == 0)
        {
            $message = "Tenggat HARI INI: {$task->title}";
        }
        elseif ($days_left == 1)
        {
            $message = "Tenggat BESOK: {$task->title}";
        }
        else
        {
            $message = "Tenggat dalam {$days_left} hari: {$task->title}";
        }

        return $this->create($task->assigned_to, $message, 'deadline_reminder', $task_id);
    }

    public function notify_new_comment($task_id, $commenter_id, $comment)
    {
        $this->load->model('Task_model');
        $task = $this->Task_model->get_by_id($task_id);
        $this->load->model('User_model');
        $commenter = $this->User_model->get_by_id($commenter_id);

        if ($task->assigned_to != $commenter_id)
        {
            $message = "{$commenter->name} berkomentar pada tugas: {$task->title}";
            return $this->create($task->assigned_to, $message, 'new_comment', $task_id);
        }

        return NULL;
    }

    public function notify_status_update($task_id, $new_status)
    {
        $this->load->model('Task_model');
        $task = $this->Task_model->get_by_id($task_id);

        $status_labels = array(
            'pending' => 'Menunggu',
            'on_progress' => 'Sedang Dikerjakan',
            'done' => 'Selesai'
        );
        $status_text = isset($status_labels[$new_status]) ? $status_labels[$new_status] : $new_status;
        $message = "Status tugas diperbarui menjadi '{$status_text}': {$task->title}";

        return $this->create($task->assigned_to, $message, 'status_update', $task_id);
    }

    public function get_recent($user_id, $limit = 5)
    {
        $this->db->select('n.*, t.title as task_title, p.title as project_title');
        $this->db->from($this->table . ' n');
        $this->db->join('tasks t', 't.id = n.task_id', 'left');
        $this->db->join('projects p', 'p.id = t.project_id', 'left');
        $this->db->where('n.user_id', $user_id);
        $this->db->order_by('n.created_at', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }
}
