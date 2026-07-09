<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Role_model');
        $this->load->helper('auth_helper');
        require_login();
        require_permission('manage_roles');
    }

    public function index()
    {
        $data['page_title'] = 'Peran & Hak Akses';
        $data['roles'] = $this->Role_model->get_all();

        $role_permissions = array();
        foreach ($data['roles'] as $role)
        {
            $role_permissions[$role->id] = $this->Role_model->get_permission_keys($role->id);
        }
        $data['role_permissions'] = $role_permissions;

        $this->load->view('layouts/header', $data);
        $this->load->view('roles/index', $data);
        $this->load->view('layouts/footer');
    }

    public function create()
    {
        $data['page_title'] = 'Tambah Peran';
        $data['all_permissions'] = $this->Role_model->get_all_permissions();

        $this->form_validation->set_rules('name', 'Kunci Peran', 'required|trim|alpha_dash|max_length[50]|is_unique[roles.name]');
        $this->form_validation->set_rules('display_name', 'Nama Tampilan', 'required|trim|max_length[100]');

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('layouts/header', $data);
            $this->load->view('roles/create', $data);
            $this->load->view('layouts/footer');
        }
        else
        {
            $role_id = $this->Role_model->insert(array(
                'name' => $this->input->post('name'),
                'display_name' => $this->input->post('display_name')
            ));

            if ($role_id)
            {
                $permission_ids = $this->input->post('permissions') ? $this->input->post('permissions') : array();
                $valid_ids = array_column($this->Role_model->get_all_permissions(), 'id');
                $permission_ids = array_intersect($permission_ids, $valid_ids);
                $this->Role_model->save_permissions($role_id, $permission_ids);

                $this->session->set_flashdata('success', 'Peran berhasil dibuat!');
                redirect('roles');
            }
            else
            {
                $this->session->set_flashdata('error', 'Gagal membuat peran.');
                redirect('roles/create');
            }
        }
    }

    public function edit($id)
    {
        if ($id == $this->session->userdata('user_role_id'))
        {
            $this->session->set_flashdata('error', 'Anda tidak dapat mengubah peran Anda sendiri.');
            redirect('roles');
            return;
        }

        $data['page_title'] = 'Ubah Peran';
        $data['role'] = $this->Role_model->get_by_id($id);

        if (!$data['role'])
        {
            show_404();
        }

        $data['all_permissions'] = $this->Role_model->get_all_permissions();
        $data['selected_permission_ids'] = $this->Role_model->get_permission_ids($id);

        $this->form_validation->set_rules('display_name', 'Nama Tampilan', 'required|trim|max_length[100]');

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('layouts/header', $data);
            $this->load->view('roles/edit', $data);
            $this->load->view('layouts/footer');
        }
        else
        {
            $this->Role_model->update($id, array('display_name' => $this->input->post('display_name')));

            $permission_ids = $this->input->post('permissions') ? $this->input->post('permissions') : array();
            $valid_ids = array_column($this->Role_model->get_all_permissions(), 'id');
            $permission_ids = array_intersect($permission_ids, $valid_ids);
            $this->Role_model->save_permissions($id, $permission_ids);

            $this->session->set_flashdata('success', 'Peran berhasil diperbarui!');
            redirect('roles');
        }
    }

    public function delete($id)
    {
        if ($id == $this->session->userdata('user_role_id'))
        {
            $this->session->set_flashdata('error', 'Anda tidak dapat menghapus peran Anda sendiri.');
            redirect('roles');
            return;
        }

        if ($this->Role_model->delete($id))
        {
            $this->session->set_flashdata('success', 'Peran berhasil dihapus!');
        }
        else
        {
            $this->session->set_flashdata('error', 'Peran ini tidak dapat dihapus: masih ada pengguna yang menggunakan peran ini.');
        }

        redirect('roles');
    }
}
