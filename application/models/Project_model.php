<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_model extends CI_Model {

    protected $table = 'projects';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all($status = NULL, $limit = NULL, $offset = 0)
    {
        $this->db->select('p.*, u.name as creator_name, COUNT(t.id) as task_count');
        $this->db->from($this->table . ' p');
        $this->db->join('users u', 'u.id = p.created_by', 'left');
        $this->db->join('tasks t', 't.project_id = p.id', 'left');
        $this->db->group_by('p.id');

        if ($status !== NULL)
        {
            $this->db->where('p.status', $status);
        }

        $this->db->order_by('p.created_at', 'DESC');

        if ($limit !== NULL)
        {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get();
        return $query->result();
    }

    public function get_by_id($id)
    {
        $this->db->select('p.*, u.name as creator_name');
        $this->db->from($this->table . ' p');
        $this->db->join('users u', 'u.id = p.created_by', 'left');
        $this->db->where('p.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function with_stats($limit = NULL)
    {
        $projects = $this->get_all(NULL, $limit);

        foreach ($projects as $project)
        {
            $project->stats = $this->get_task_stats($project->id);
            $project->progress = $this->calculate_progress($project->id);
        }

        return $projects;
    }

    public function get_task_stats($project_id)
    {
        $this->db->select('status, COUNT(*) as count');
        $this->db->where('project_id', $project_id);
        $this->db->group_by('status');
        $query = $this->db->get('tasks');

        $stats = array(
            'pending' => 0,
            'on_progress' => 0,
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

    public function calculate_progress($project_id)
    {
        $stats = $this->get_task_stats($project_id);

        if ($stats['total'] == 0)
        {
            return 0;
        }

        return round(($stats['done'] / $stats['total']) * 100);
    }

    public function count_active()
    {
        $this->db->where('status', 'active');
        return $this->db->count_all_results($this->table);
    }

    public function count_all()
    {
        return $this->db->count_all($this->table);
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

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    public function get_by_date_range($start_date, $end_date)
    {
        $this->db->select('p.*, u.name as creator_name');
        $this->db->from($this->table . ' p');
        $this->db->join('users u', 'u.id = p.created_by', 'left');
        $this->db->where('p.periode_start >=', $start_date);
        $this->db->where('p.periode_end <=', $end_date);
        $this->db->order_by('p.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_current_period()
    {
        $today = date('Y-m-d');
        $this->db->select('p.*');
        $this->db->from($this->table . ' p');
        $this->db->where('p.periode_start <=', $today);
        $this->db->where('p.periode_end >=', $today);
        $this->db->where('p.status', 'active');
        $query = $this->db->get();
        return $query->result();
    }
}
