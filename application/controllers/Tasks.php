<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Task_model');
        $this->load->model('Project_model');
        $this->load->model('User_model');
        $this->load->model('Tag_model');
        $this->load->model('Comment_model');
        $this->load->model('File_model');
        $this->load->model('Notification_model');
        $this->load->model('Task_activity_model');
        $this->load->model('Google_calendar_model');
        $this->load->helper('auth_helper');
        require_login();
    }

    public function index()
    {
        $data['page_title'] = 'Tugas';

        $filters = array();
        $status_filter = $this->input->get('status');
        $project_filter = $this->input->get('project_id');
        $search_filter = $this->input->get('search');

        if ($status_filter)
        {
            $filters['status'] = $status_filter;
        }

        if ($project_filter)
        {
            $filters['project_id'] = $project_filter;
        }

        if ($search_filter)
        {
            $filters['search'] = $search_filter;
        }

        if (!has_permission('view_all_tasks'))
        {
            $filters['assigned_to'] = get_user_id();
            $data['tasks'] = $this->Task_model->get_all($filters);
        }
        else
        {
            $data['tasks'] = $this->Task_model->get_all($filters);
            $data['users'] = $this->User_model->get_all(1);
            $data['projects'] = $this->Project_model->get_all();
        }

        $data['projects'] = $this->Project_model->get_all();
        $data['all_tags'] = $this->Tag_model->get_all();
        $data['filters'] = $filters;

        $this->load->view('layouts/header', $data);
        $this->load->view('tasks/index', $data);
        $this->load->view('layouts/footer');
    }

    public function create()
    {
        require_permission('manage_tasks');

        $data['page_title'] = 'Buat Tugas';
        $data['projects'] = $this->Project_model->get_all('active');
        $data['users'] = $this->User_model->get_all(1);
        $data['tags'] = $this->Tag_model->get_all();
        $data['selected_project'] = $this->input->get('project_id');
        $data['active_task_counts'] = $this->Task_model->get_active_task_counts();

        $this->form_validation->set_rules('title', 'Judul', 'required|trim|max_length[200]');
        $this->form_validation->set_rules('project_id', 'Proyek', 'required|integer');
        $this->form_validation->set_rules('assigned_to', 'Ditugaskan Kepada', 'required|integer');
        $this->form_validation->set_rules('deadline', 'Tenggat', 'required');
        $this->form_validation->set_rules('priority', 'Prioritas', 'required|in_list[low,medium,high]');

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('layouts/header', $data);
            $this->load->view('tasks/create', $data);
            $this->load->view('layouts/footer');
        }
        else
        {
            $task_data = array(
                'project_id' => $this->input->post('project_id'),
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'assigned_to' => $this->input->post('assigned_to'),
                'deadline' => date('Y-m-d', strtotime($this->input->post('deadline'))),
                'priority' => $this->input->post('priority'),
                'status' => 'pending'
            );

            $task_id = $this->Task_model->insert($task_data);

            if ($task_id)
            {
                $tags = $this->input->post('tags');
                if ($tags)
                {
                    $this->Tag_model->attach_to_task($task_id, $tags);
                }

                $this->Notification_model->notify_task_assigned($task_id, $this->input->post('assigned_to'));
                $this->Task_activity_model->log($task_id, get_user_id(), 'created', 'Tugas dibuat');
                $this->Google_calendar_model->sync_task_event($this->Task_model->get_by_id($task_id));

                $this->session->set_flashdata('success', 'Tugas berhasil dibuat!');
                redirect('tasks/detail/' . $task_id);
            }
            else
            {
                $this->session->set_flashdata('error', 'Gagal membuat tugas.');
                redirect('tasks/create');
            }
        }
    }

    public function detail($id)
    {
        $data['page_title'] = 'Detail Tugas';
        $task = $this->Task_model->get_with_tags($id);

        if (!$task)
        {
            show_404();
        }

        if (!has_permission('view_all_tasks') && $task->assigned_to != get_user_id())
        {
            show_error('Akses Ditolak', 403, 'Dilarang');
        }

        $data['task'] = $task;
        $data['comments'] = $this->Comment_model->get_by_task($id);
        $data['files'] = $this->File_model->get_by_task($id);
        $data['all_tags'] = $this->Tag_model->get_all();
        $data['users'] = $this->User_model->get_all(1);
        $data['activities'] = $this->Task_activity_model->get_by_task($id);

        $this->load->view('layouts/header', $data);
        $this->load->view('tasks/detail', $data);
        $this->load->view('layouts/footer');
    }

    public function edit($id)
    {
        require_permission('manage_tasks');

        $data['page_title'] = 'Ubah Tugas';
        $data['task'] = $this->Task_model->get_with_tags($id);
        $data['projects'] = $this->Project_model->get_all('active');
        $data['users'] = $this->User_model->get_all(1);
        $data['tags'] = $this->Tag_model->get_all();
        $data['active_task_counts'] = $this->Task_model->get_active_task_counts();

        if (!$data['task'])
        {
            show_404();
        }

        $this->form_validation->set_rules('title', 'Judul', 'required|trim|max_length[200]');
        $this->form_validation->set_rules('project_id', 'Proyek', 'required|integer');
        $this->form_validation->set_rules('assigned_to', 'Ditugaskan Kepada', 'required|integer');
        $this->form_validation->set_rules('deadline', 'Tenggat', 'required');
        $this->form_validation->set_rules('priority', 'Prioritas', 'required|in_list[low,medium,high]');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[pending,on_progress,in_review,done]');

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('layouts/header', $data);
            $this->load->view('tasks/edit', $data);
            $this->load->view('layouts/footer');
        }
        else
        {
            $task_data = array(
                'project_id' => $this->input->post('project_id'),
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'assigned_to' => $this->input->post('assigned_to'),
                'deadline' => date('Y-m-d', strtotime($this->input->post('deadline'))),
                'priority' => $this->input->post('priority'),
                'status' => $this->input->post('status')
            );

            if ($this->Task_model->update($id, $task_data))
            {
                $tags = $this->input->post('tags');
                $this->Tag_model->attach_to_task($id, $tags ? $tags : array());
                $this->Task_activity_model->log($id, get_user_id(), 'edited', 'Detail tugas diperbarui');
                $this->Google_calendar_model->sync_task_event($this->Task_model->get_by_id($id));

                $this->session->set_flashdata('success', 'Tugas berhasil diperbarui!');
                redirect('tasks/detail/' . $id);
            }
            else
            {
                $this->session->set_flashdata('error', 'Gagal memperbarui tugas.');
                redirect('tasks/edit/' . $id);
            }
        }
    }

    public function duplicate($id)
    {
        if (!has_permission('manage_tasks'))
        {
            echo json_encode(array('success' => FALSE, 'message' => 'Akses ditolak'));
            return;
        }

        $task = $this->Task_model->get_by_id($id);

        if (!$task)
        {
            echo json_encode(array('success' => FALSE, 'message' => 'Tugas tidak ditemukan'));
            return;
        }

        $offset = ($task->project_type === 'monthly') ? '+1 month' : '+7 days';

        $new_task_data = array(
            'project_id' => $task->project_id,
            'title' => $task->title,
            'description' => $task->description,
            'assigned_to' => $task->assigned_to,
            'deadline' => date('Y-m-d', strtotime($offset, strtotime($task->deadline))),
            'priority' => $task->priority,
            'status' => 'pending'
        );

        $new_task_id = $this->Task_model->insert($new_task_data);

        if (!$new_task_id)
        {
            echo json_encode(array('success' => FALSE, 'message' => 'Gagal menduplikasi tugas'));
            return;
        }

        $tags = $this->Task_model->get_task_tags($id);
        if ($tags)
        {
            $this->Tag_model->attach_to_task($new_task_id, array_map(function($t) { return $t->id; }, $tags));
        }

        $this->Notification_model->notify_task_assigned($new_task_id, $task->assigned_to);

        echo json_encode(array('success' => TRUE, 'task_id' => $new_task_id));
    }

    public function update_status($id)
    {
        $task = $this->Task_model->get_by_id($id);

        if (!$task)
        {
            echo json_encode(array('success' => FALSE, 'message' => 'Tugas tidak ditemukan'));
            return;
        }

        $current_user_id = get_user_id();

        $can_update = has_permission('manage_tasks') ||
                      $task->assigned_to == $current_user_id;

        if (!$can_update)
        {
            echo json_encode(array('success' => FALSE, 'message' => 'Akses ditolak. Anda hanya dapat mengubah status tugas yang ditugaskan kepada Anda.'));
            return;
        }

        $status = $this->input->post('status');

        if (!in_array($status, array('pending', 'on_progress', 'in_review', 'done')))
        {
            echo json_encode(array('success' => FALSE, 'message' => 'Status tidak valid'));
            return;
        }

        if ($status === 'done' && !has_permission('manage_tasks'))
        {
            echo json_encode(array('success' => FALSE, 'message' => 'Hanya manajer yang dapat menyetujui tugas menjadi selesai. Ajukan ke "Menunggu Review" terlebih dahulu.'));
            return;
        }

        if ($this->Task_model->update_status($id, $status))
        {
            $this->Task_activity_model->log($id, get_user_id(), 'status_change', "Status diubah dari '{$task->status}' menjadi '{$status}'");

            $this->Notification_model->notify_status_update($id, $status);
            $this->Google_calendar_model->sync_task_event($this->Task_model->get_by_id($id));

            echo json_encode(array('success' => TRUE, 'status' => $status));
        }
        else
        {
            echo json_encode(array('success' => FALSE, 'message' => 'Gagal memperbarui status'));
        }
    }

    public function delete($id)
    {
        require_permission('manage_tasks');

        $task = $this->Task_model->get_by_id($id);

        if (!$task)
        {
            show_404();
        }

        $this->Google_calendar_model->sync_task_event($task, TRUE);

        if ($this->Task_model->delete($id))
        {
            $this->session->set_flashdata('success', 'Tugas berhasil dihapus!');
        }
        else
        {
            $this->session->set_flashdata('error', 'Gagal menghapus tugas.');
        }

        redirect('tasks');
    }

    public function add_comment($task_id)
    {
        $task = $this->Task_model->get_by_id($task_id);

        if (!$task)
        {
            echo json_encode(array('success' => FALSE, 'message' => 'Tugas tidak ditemukan'));
            return;
        }

        if (!has_permission('manage_tasks') && $task->assigned_to != get_user_id())
        {
            echo json_encode(array('success' => FALSE, 'message' => 'Akses ditolak'));
            return;
        }

        $this->form_validation->set_rules('comment', 'Komentar', 'required|trim');

        if ($this->form_validation->run() == FALSE)
        {
            echo json_encode(array('success' => FALSE, 'message' => validation_errors()));
            return;
        }

        $comment = $this->input->post('comment');
        $comment_id = $this->Comment_model->insert($task_id, get_user_id(), $comment);

        if ($comment_id)
        {
            $this->Task_activity_model->log($task_id, get_user_id(), 'comment', 'Menambahkan komentar');

            $this->Notification_model->notify_new_comment($task_id, get_user_id(), $comment);

            echo json_encode(array('success' => TRUE, 'comment_id' => $comment_id));
        }
        else
        {
            echo json_encode(array('success' => FALSE, 'message' => 'Gagal menambah komentar'));
        }
    }

    public function upload_file($task_id)
    {
        $task = $this->Task_model->get_by_id($task_id);

        if (!$task)
        {
            $this->session->set_flashdata('error', 'Tugas tidak ditemukan');
            redirect('tasks');
            return;
        }

        if (!has_permission('manage_tasks') && $task->assigned_to != get_user_id())
        {
            show_error('Akses Ditolak', 403, 'Dilarang');
            return;
        }

        if ($this->File_model->upload($task_id))
        {
            $this->Task_activity_model->log($task_id, get_user_id(), 'file_upload', 'Mengunggah file');
            $this->session->set_flashdata('success', 'File berhasil diunggah!');
        }
        else
        {
            $error = $this->upload->display_errors('', '');
            $this->session->set_flashdata('error', 'Gagal mengunggah: ' . $error);
        }

        redirect('tasks/detail/' . $task_id);
    }

    public function delete_file($file_id)
    {
        $file = $this->File_model->get_by_id($file_id);

        if (!$file)
        {
            echo json_encode(array('success' => FALSE, 'message' => 'File tidak ditemukan'));
            return;
        }

        $task = $this->Task_model->get_by_id($file->task_id);

        if (!has_permission('moderate_comments') && $task->assigned_to != get_user_id())
        {
            echo json_encode(array('success' => FALSE, 'message' => 'Akses ditolak'));
            return;
        }

        if ($this->File_model->delete($file_id))
        {
            echo json_encode(array('success' => TRUE));
        }
        else
        {
            echo json_encode(array('success' => FALSE, 'message' => 'Gagal menghapus file'));
        }
    }

    public function delete_comment($comment_id)
    {
        $comment = $this->Comment_model->get_by_id($comment_id);

        if (!$comment)
        {
            echo json_encode(array('success' => FALSE, 'message' => 'Komentar tidak ditemukan'));
            return;
        }

        if (!has_permission('moderate_comments') && $comment->user_id != get_user_id())
        {
            echo json_encode(array('success' => FALSE, 'message' => 'Akses ditolak'));
            return;
        }

        if ($this->Comment_model->delete($comment_id))
        {
            echo json_encode(array('success' => TRUE));
        }
        else
        {
            echo json_encode(array('success' => FALSE, 'message' => 'Gagal menghapus komentar'));
        }
    }
}
