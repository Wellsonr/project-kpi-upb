<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Role_model');
        $this->load->helper('auth_helper');
        require_login();
        require_permission('manage_users');
    }

    public function index()
    {
        $data['page_title'] = 'Manajemen Pengguna';
        $data['users'] = $this->User_model->get_all();
        $data['roles'] = $this->User_model->get_all_roles();

        $admin_role_ids = array();
        foreach ($data['roles'] as $role)
        {
            if (in_array('manage_roles', $this->Role_model->get_permission_keys($role->id)))
            {
                $admin_role_ids[] = (int) $role->id;
            }
        }
        $data['admin_role_ids'] = $admin_role_ids;

        $this->load->view('layouts/header', $data);
        $this->load->view('users/index', $data);
        $this->load->view('layouts/footer');
    }

    public function create()
    {
        $data['page_title'] = 'Tambah Pengguna';
        $data['roles'] = $this->User_model->get_all_roles();

        $this->form_validation->set_rules('name', 'Nama', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Kata Sandi', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Konfirmasi Kata Sandi', 'required|matches[password]');
        $this->form_validation->set_rules('role_id', 'Peran', 'required|integer');

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('layouts/header', $data);
            $this->load->view('users/create', $data);
            $this->load->view('layouts/footer');
        }
        else
        {
            $user_data = array(
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'password' => $this->input->post('password'),
                'role_id' => $this->input->post('role_id'),
                'is_active' => 1
            );

            $user_id = $this->User_model->insert($user_data);

            if ($user_id)
            {
                $this->session->set_flashdata('success', 'Pengguna berhasil dibuat!');
                redirect('users');
            }
            else
            {
                $this->session->set_flashdata('error', 'Gagal membuat pengguna.');
                redirect('users/create');
            }
        }
    }

    public function edit($id)
    {
        $data['page_title'] = 'Ubah Pengguna';
        $data['user'] = $this->User_model->get_by_id($id);
        $data['roles'] = $this->User_model->get_all_roles();

        if (!$data['user'])
        {
            show_404();
        }

        if ($id == get_user_id())
        {
            $this->session->set_flashdata('error', 'Anda tidak dapat mengubah akun Anda sendiri di sini.');
            redirect('users');
        }

        $this->form_validation->set_rules('name', 'Nama', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_email_check[' . $id . ']');
        $this->form_validation->set_rules('role_id', 'Peran', 'required|integer');

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('layouts/header', $data);
            $this->load->view('users/edit', $data);
            $this->load->view('layouts/footer');
        }
        else
        {
            $user_data = array(
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'role_id' => $this->input->post('role_id')
            );

            if ($this->input->post('password'))
            {
                $user_data['password'] = $this->input->post('password');
            }

            if ($this->User_model->update($id, $user_data))
            {
                $this->session->set_flashdata('success', 'Pengguna berhasil diperbarui!');
                redirect('users');
            }
            else
            {
                $this->session->set_flashdata('error', 'Gagal memperbarui pengguna.');
                redirect('users/edit/' . $id);
            }
        }
    }

    public function toggle($id)
    {
        if ($id == get_user_id())
        {
            $this->session->set_flashdata('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
            redirect('users');
        }

        $user = $this->User_model->get_by_id($id);
        if ($user && $this->User_model->toggle_active($id))
        {
            $status = $user->is_active ? 'dinonaktifkan' : 'diaktifkan';
            $this->session->set_flashdata('success', 'Pengguna berhasil ' . $status . '!');
        }
        else
        {
            $this->session->set_flashdata('error', 'Gagal mengubah status pengguna.');
        }

        redirect('users');
    }

    public function delete($id)
    {
        if ($id == get_user_id())
        {
            $this->session->set_flashdata('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            redirect('users');
        }

        if ($this->User_model->delete($id))
        {
            $this->session->set_flashdata('success', 'Pengguna berhasil dihapus!');
        }
        else
        {
            $this->session->set_flashdata('error', 'Gagal menghapus pengguna.');
        }

        redirect('users');
    }

    public function email_check($email, $user_id)
    {
        if ($this->User_model->email_exists($email, $user_id))
        {
            $this->form_validation->set_message('email_check', '{field} sudah digunakan.');
            return FALSE;
        }
        return TRUE;
    }
}
