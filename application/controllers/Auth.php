<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Role_model');
        $this->load->helper('auth_helper');
    }

    public function index()
    {
        if (is_logged_in())
        {
            redirect(get_dashboard_url());
        }
        $this->login();
    }

    public function login()
    {
        if (is_logged_in())
        {
            redirect(get_dashboard_url());
        }

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('password', 'Kata Sandi', 'required|trim');

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('auth/login');
        }
        else
        {
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $user = $this->User_model->login($email, $password);

            if ($user)
            {
                $session_data = array(
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'user_role' => $user->role_name,
                    'user_role_id' => $user->role_id,
                    'user_role_display' => $user->role_display_name,
                    'user_permissions' => $this->Role_model->get_permission_keys($user->role_id),
                    'is_logged_in' => TRUE
                );

                $this->session->set_userdata($session_data);

                redirect(get_dashboard_url());
            }
            else
            {
                $this->session->set_flashdata('error', 'Email atau kata sandi salah');
                $this->load->view('auth/login');
            }
        }
    }

    public function logout()
    {
        $session_data = array(
            'user_id', 'user_name', 'user_email', 'user_role', 'user_role_id',
            'user_role_display', 'user_permissions', 'is_logged_in'
        );
        $this->session->unset_userdata($session_data);
        $this->session->sess_destroy();

        redirect('login');
    }
}
