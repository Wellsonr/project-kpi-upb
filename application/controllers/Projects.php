<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Project_model');
        $this->load->model('Task_model');
        $this->load->model('User_model');
        $this->load->model('Notification_model');
        $this->load->helper('auth_helper');
        require_login();
        require_permission('manage_projects');
    }

    public function index()
    {
        $data['page_title'] = 'Proyek';
        $data['projects'] = $this->Project_model->with_stats();

        $this->load->view('layouts/header', $data);
        $this->load->view('projects/index', $data);
        $this->load->view('layouts/footer');
    }

    public function create()
    {
        $data['page_title'] = 'Buat Proyek';

        $this->form_validation->set_rules('title', 'Judul', 'required|trim|max_length[200]');
        $this->form_validation->set_rules('type', 'Tipe', 'required|in_list[weekly,monthly]');
        $this->form_validation->set_rules('periode_start', 'Awal Periode', 'required');
        $this->form_validation->set_rules('periode_end', 'Akhir Periode', 'required');
        $this->form_validation->set_rules('deadline', 'Tenggat', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('layouts/header', $data);
            $this->load->view('projects/create', $data);
            $this->load->view('layouts/footer');
        }
        else
        {
            $project_data = array(
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'type' => $this->input->post('type'),
                'periode_start' => date('Y-m-d', strtotime($this->input->post('periode_start'))),
                'periode_end' => date('Y-m-d', strtotime($this->input->post('periode_end'))),
                'deadline' => date('Y-m-d', strtotime($this->input->post('deadline'))),
                'status' => 'active'
            );

            $project_id = $this->Project_model->insert($project_data);

            if ($project_id)
            {
                $this->session->set_flashdata('success', 'Proyek berhasil dibuat!');
                redirect('projects/detail/' . $project_id);
            }
            else
            {
                $this->session->set_flashdata('error', 'Gagal membuat proyek.');
                redirect('projects/create');
            }
        }
    }

    public function detail($id)
    {
        $data['page_title'] = 'Detail Proyek';
        $data['project'] = $this->Project_model->get_by_id($id);
        $data['tasks'] = $this->Task_model->get_by_project($id);
        $data['stats'] = $this->Project_model->get_task_stats($id);
        $data['progress'] = $this->Project_model->calculate_progress($id);

        if (!$data['project'])
        {
            show_404();
        }

        $this->load->view('layouts/header', $data);
        $this->load->view('projects/detail', $data);
        $this->load->view('layouts/footer');
    }

    public function edit($id)
    {
        $data['page_title'] = 'Ubah Proyek';
        $data['project'] = $this->Project_model->get_by_id($id);

        if (!$data['project'])
        {
            show_404();
        }

        $this->form_validation->set_rules('title', 'Judul', 'required|trim|max_length[200]');
        $this->form_validation->set_rules('type', 'Tipe', 'required|in_list[weekly,monthly]');
        $this->form_validation->set_rules('periode_start', 'Awal Periode', 'required');
        $this->form_validation->set_rules('periode_end', 'Akhir Periode', 'required');
        $this->form_validation->set_rules('deadline', 'Tenggat', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[active,completed,archived]');

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('layouts/header', $data);
            $this->load->view('projects/edit', $data);
            $this->load->view('layouts/footer');
        }
        else
        {
            $project_data = array(
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'type' => $this->input->post('type'),
                'periode_start' => date('Y-m-d', strtotime($this->input->post('periode_start'))),
                'periode_end' => date('Y-m-d', strtotime($this->input->post('periode_end'))),
                'deadline' => date('Y-m-d', strtotime($this->input->post('deadline'))),
                'status' => $this->input->post('status')
            );

            if ($this->Project_model->update($id, $project_data))
            {
                $this->session->set_flashdata('success', 'Proyek berhasil diperbarui!');
                redirect('projects/detail/' . $id);
            }
            else
            {
                $this->session->set_flashdata('error', 'Gagal memperbarui proyek.');
                redirect('projects/edit/' . $id);
            }
        }
    }

    public function delete($id)
    {
        $project = $this->Project_model->get_by_id($id);

        if (!$project)
        {
            show_404();
        }

        if ($this->Project_model->delete($id))
        {
            $this->session->set_flashdata('success', 'Proyek berhasil dihapus!');
        }
        else
        {
            $this->session->set_flashdata('error', 'Gagal menghapus proyek.');
        }

        redirect('projects');
    }
}
