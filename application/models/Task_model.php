<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task_model extends CI_Model {

    protected $table = 'tasks';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all($filters = array(), $limit = NULL, $offset = 0)
    {
        $this->db->select('t.*, p.title as project_title, p.type as project_type,
                           ua.name as assigned_to_name, ua.role_id as assigned_to_role_id,
                           uc.name as created_by_name, r.name as assigned_role_name,
                           r.display_name as assigned_role_display');
        $this->db->from($this->table . ' t');
        $this->db->join('projects p', 'p.id = t.project_id', 'left');
        $this->db->join('users ua', 'ua.id = t.assigned_to', 'left');
        $this->db->join('users uc', 'uc.id = t.created_by', 'left');
        $this->db->join('roles r', 'r.id = ua.role_id', 'left');

        if (isset($filters['status']))
        {
            if (is_array($filters['status']))
            {
                $this->db->where_in('t.status', $filters['status']);
            }
            else
            {
                $this->db->where('t.status', $filters['status']);
            }
        }

        if (isset($filters['project_id']))
        {
            $this->db->where('t.project_id', $filters['project_id']);
        }

        if (isset($filters['assigned_to']))
        {
            $this->db->where('t.assigned_to', $filters['assigned_to']);
        }

        if (isset($filters['priority']))
        {
            $this->db->where('t.priority', $filters['priority']);
        }

        if (isset($filters['search']))
        {
            $this->db->group_start();
            $this->db->like('t.title', $filters['search']);
            $this->db->or_like('t.description', $filters['search']);
            $this->db->group_end();
        }

        $this->db->order_by('t.deadline', 'ASC');
        $this->db->order_by('t.created_at', 'DESC');

        if ($limit !== NULL)
        {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get();
        return $query->result();
    }

    public function get_by_id($id)
    {
        $this->db->select('t.*, p.title as project_title, p.type as project_type,
                           ua.name as assigned_to_name, ua.email as assigned_to_email, ua.role_id as assigned_to_role_id,
                           uc.name as created_by_name, r.name as assigned_role_name, r.display_name as assigned_role_display');
        $this->db->from($this->table . ' t');
        $this->db->join('projects p', 'p.id = t.project_id', 'left');
        $this->db->join('users ua', 'ua.id = t.assigned_to', 'left');
        $this->db->join('users uc', 'uc.id = t.created_by', 'left');
        $this->db->join('roles r', 'r.id = ua.role_id', 'left');
        $this->db->where('t.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_by_user($user_id, $status = NULL)
    {
        $filters = array('assigned_to' => $user_id);
        if ($status !== NULL)
        {
            $filters['status'] = $status;
        }
        return $this->get_all($filters);
    }

    public function get_by_project($project_id)
    {
        return $this->get_all(array('project_id' => $project_id));
    }

    public function get_stats($user_id = NULL)
    {
        $this->db->select('status, COUNT(*) as count');
        $this->db->from($this->table);

        if ($user_id !== NULL)
        {
            $this->db->where('assigned_to', $user_id);
        }

        $this->db->group_by('status');
        $query = $this->db->get();

        $stats = array(
            'pending' => 0,
            'on_progress' => 0,
            'in_review' => 0,
            'done' => 0,
            'total' => 0
        );

        foreach ($query->result() as $row)
        {
            $stats[$row->status] = (int) $row->count;
            $stats['total'] += (int) $row->count;
        }

        return $stats;
    }

    public function get_active_task_counts()
    {
        $this->db->select('assigned_to, COUNT(*) as count');
        $this->db->where('status !=', 'done');
        $this->db->group_by('assigned_to');
        $query = $this->db->get($this->table);

        $counts = array();
        foreach ($query->result() as $row)
        {
            $counts[$row->assigned_to] = (int) $row->count;
        }
        return $counts;
    }

    public function get_deadline_alerts($user_id = NULL, $days = 3)
    {
        $this->db->select('t.*, ua.name as assigned_to_name, p.title as project_title');
        $this->db->from($this->table . ' t');
        $this->db->join('users ua', 'ua.id = t.assigned_to', 'left');
        $this->db->join('projects p', 'p.id = t.project_id', 'left');
        $this->db->where('t.status !=', 'done');
        $this->db->where('t.deadline <=', date('Y-m-d', strtotime("+$days days")));
        $this->db->where('t.deadline >=', date('Y-m-d'));
        $this->db->order_by('t.deadline', 'ASC');

        if ($user_id !== NULL)
        {
            $this->db->where('t.assigned_to', $user_id);
        }

        $query = $this->db->get();
        return $query->result();
    }

    public function get_overdue($user_id = NULL)
    {
        $this->db->select('t.*, ua.name as assigned_to_name, p.title as project_title');
        $this->db->from($this->table . ' t');
        $this->db->join('users ua', 'ua.id = t.assigned_to', 'left');
        $this->db->join('projects p', 'p.id = t.project_id', 'left');
        $this->db->where('t.status !=', 'done');
        $this->db->where('t.deadline <', date('Y-m-d'));

        if ($user_id !== NULL)
        {
            $this->db->where('t.assigned_to', $user_id);
        }

        $query = $this->db->get();
        return $query->result();
    }

    public function get_today_tasks($user_id)
    {
        $this->db->select('t.*, p.title as project_title');
        $this->db->from($this->table . ' t');
        $this->db->join('projects p', 'p.id = t.project_id', 'left');
        $this->db->where('t.assigned_to', $user_id);
        $this->db->where('t.status !=', 'done');
        $this->db->where('t.deadline <=', date('Y-m-d', strtotime('+1 day')));
        $this->db->order_by('t.priority', 'DESC');
        $this->db->order_by('t.deadline', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_week_tasks($user_id)
    {
        $start = date('Y-m-d');
        $end = date('Y-m-d', strtotime('+7 days'));

        $this->db->select('t.*, p.title as project_title');
        $this->db->from($this->table . ' t');
        $this->db->join('projects p', 'p.id = t.project_id', 'left');
        $this->db->where('t.assigned_to', $user_id);
        $this->db->where('t.status !=', 'done');
        $this->db->where('t.deadline >=', $start);
        $this->db->where('t.deadline <=', $end);
        $this->db->order_by('t.deadline', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function insert($data)
    {
        $data['created_by'] = get_user_id();
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function update_status($id, $status)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, array('status' => $status));
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    public function count_by_status($status, $user_id = NULL)
    {
        $this->db->where('status', $status);
        if ($user_id !== NULL)
        {
            $this->db->where('assigned_to', $user_id);
        }
        return $this->db->count_all_results($this->table);
    }

    public function get_with_tags($task_id)
    {
        $task = $this->get_by_id($task_id);
        if ($task)
        {
            $task->tags = $this->get_task_tags($task_id);
        }
        return $task;
    }

    public function get_task_tags($task_id)
    {
        $this->db->select('tg.*');
        $this->db->from('tags tg');
        $this->db->join('task_tags tt', 'tt.tag_id = tg.id');
        $this->db->where('tt.task_id', $task_id);
        $query = $this->db->get();
        return $query->result();
    }
}
