<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag_model extends CI_Model {

    protected $table = 'tags';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function get_by_id($id)
    {
        $query = $this->db->get_where($this->table, array('id' => $id));
        return $query->row();
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

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    public function get_by_task($task_id)
    {
        $this->db->select('t.*');
        $this->db->from($this->table . ' t');
        $this->db->join('task_tags tt', 'tt.tag_id = t.id');
        $this->db->where('tt.task_id', $task_id);
        $this->db->order_by('t.name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function attach_to_task($task_id, $tag_ids)
    {
        $this->db->where('task_id', $task_id);
        $this->db->delete('task_tags');

        if (!empty($tag_ids))
        {
            if (!is_array($tag_ids))
            {
                $tag_ids = array($tag_ids);
            }

            foreach ($tag_ids as $tag_id)
            {
                $this->db->insert('task_tags', array(
                    'task_id' => $task_id,
                    'tag_id' => $tag_id
                ));
            }
        }
    }

    public function detach_from_task($task_id, $tag_id)
    {
        $this->db->where('task_id', $task_id);
        $this->db->where('tag_id', $tag_id);
        return $this->db->delete('task_tags');
    }

    public function name_exists($name, $exclude_id = NULL)
    {
        $this->db->where('name', $name);
        if ($exclude_id !== NULL)
        {
            $this->db->where('id !=', $exclude_id);
        }
        $query = $this->db->get($this->table);
        return $query->num_rows() > 0;
    }
}
