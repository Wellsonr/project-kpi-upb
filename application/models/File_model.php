<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File_model extends CI_Model {

    protected $table = 'task_files';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_by_task($task_id)
    {
        $this->db->select('f.*, u.name as uploader_name');
        $this->db->from($this->table . ' f');
        $this->db->join('users u', 'u.id = f.uploaded_by');
        $this->db->where('f.task_id', $task_id);
        $this->db->order_by('f.uploaded_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_by_id($id)
    {
        $this->db->select('f.*, u.name as uploader_name');
        $this->db->from($this->table . ' f');
        $this->db->join('users u', 'u.id = f.uploaded_by');
        $this->db->where('f.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function delete($id)
    {
        $file = $this->get_by_id($id);
        if ($file)
        {
            $file_path = FCPATH . $file->file_path;
            if (file_exists($file_path))
            {
                unlink($file_path);
            }

            $this->db->where('id', $id);
            return $this->db->delete($this->table);
        }
        return FALSE;
    }

    public function count_by_task($task_id)
    {
        $this->db->where('task_id', $task_id);
        return $this->db->count_all_results($this->table);
    }

    public function upload($task_id, $field_name = 'userfile')
    {
        $task_id = (int) $task_id;

        $upload_path = 'uploads/tasks/' . $task_id . '/';
        if (!is_dir(FCPATH . $upload_path))
        {
            mkdir(FCPATH . $upload_path, 0755, TRUE);
        }

        $config['upload_path'] = FCPATH . $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docx|mp4|mov|zip';
        $config['max_size'] = 10240;
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload($field_name))
        {
            $upload_data = $this->upload->data();

            $file_data = array(
                'task_id' => $task_id,
                'file_path' => $upload_path . $upload_data['file_name'],
                'file_name' => $upload_data['orig_name'],
                'file_type' => $upload_data['file_type'],
                'file_size' => $upload_data['file_size'],
                'uploaded_by' => get_user_id()
            );

            return $this->insert($file_data);
        }

        return FALSE;
    }

    public function get_recent($limit = 10)
    {
        $this->db->select('f.*, t.title as task_title, u.name as uploader_name');
        $this->db->from($this->table . ' f');
        $this->db->join('tasks t', 't.id = f.task_id');
        $this->db->join('users u', 'u.id = f.uploaded_by');
        $this->db->order_by('f.uploaded_at', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_extension($file_name)
    {
        return strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    }

    public function is_image($file_name)
    {
        $ext = $this->get_extension($file_name);
        $image_exts = array('jpg', 'jpeg', 'png', 'gif');
        return in_array($ext, $image_exts);
    }

    public function is_video($file_name)
    {
        $ext = $this->get_extension($file_name);
        $video_exts = array('mp4', 'mov', 'avi');
        return in_array($ext, $video_exts);
    }

    public function get_file_icon($file_name)
    {
        $ext = $this->get_extension($file_name);

        $icons = array(
            'pdf' => 'bi-file-earmark-pdf text-danger',
            'doc' => 'bi-file-earmark-word text-primary',
            'docx' => 'bi-file-earmark-word text-primary',
            'jpg' => 'bi-file-earmark-image text-success',
            'jpeg' => 'bi-file-earmark-image text-success',
            'png' => 'bi-file-earmark-image text-success',
            'gif' => 'bi-file-earmark-image text-success',
            'mp4' => 'bi-file-earmark-play text-info',
            'mov' => 'bi-file-earmark-play text-info',
            'zip' => 'bi-file-earmark-zip text-warning'
        );

        return isset($icons[$ext]) ? $icons[$ext] : 'bi-file-earmark text-secondary';
    }
}
