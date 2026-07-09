<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Google_calendar_model');
        $this->load->model('User_model');
        $this->load->helper('auth_helper');
        require_login();
    }

    public function index()
    {
        $data['page_title'] = 'Profil Saya';
        $data['user'] = $this->User_model->get_by_id(get_user_id());
        $data['google_tokens'] = $this->Google_calendar_model->get_tokens(get_user_id());

        if (!$this->session->flashdata('calendar_prompt_shown') && !$data['google_tokens'])
        {
            $this->session->set_flashdata('info', 'Silakan hubungkan Google Calendar Anda untuk sinkronisasi tugas.');
            $this->session->set_flashdata('calendar_prompt_shown', TRUE);
        }

        $this->load->view('layouts/header', $data);
        $this->load->view('profile/index', $data);
        $this->load->view('layouts/footer');
    }

    public function connect()
    {
        $state = bin2hex(random_bytes(16));
        $this->session->set_userdata('google_oauth_state', $state);

        redirect($this->Google_calendar_model->get_auth_url($state));
    }

    public function google_callback()
    {
        $state = $this->input->get('state');
        $expected_state = $this->session->userdata('google_oauth_state');
        $this->session->unset_userdata('google_oauth_state');

        if (!$state || !is_string($state) || !$expected_state || !hash_equals($expected_state, $state))
        {
            $this->session->set_flashdata('error', 'Permintaan koneksi Google Calendar tidak valid, silakan coba lagi.');
            redirect('profile');
            return;
        }

        $code = $this->input->get('code');

        if (!$code)
        {
            $this->session->set_flashdata('error', 'Koneksi Google Calendar dibatalkan.');
            redirect('profile');
            return;
        }

        $token_response = $this->Google_calendar_model->exchange_code_for_token($code);

        if (!$token_response || !isset($token_response['access_token']))
        {
            $this->session->set_flashdata('error', 'Gagal menghubungkan Google Calendar. Silakan coba lagi.');
            redirect('profile');
            return;
        }

        $calendar_id = $this->Google_calendar_model->get_or_create_calendar($token_response['access_token']);

        if (!$calendar_id)
        {
            $this->session->set_flashdata('error', 'Gagal membuat calendar "Task Tracker" di akun Google.');
            redirect('profile');
            return;
        }

        $this->Google_calendar_model->save_tokens(
            get_user_id(),
            $token_response['access_token'],
            $token_response['refresh_token'],
            $token_response['expires_in'],
            $calendar_id
        );

        $this->session->set_flashdata('success', 'Google Calendar berhasil terhubung!');
        redirect('profile');
    }

    public function disconnect()
    {
        $this->Google_calendar_model->delete_tokens(get_user_id());
        $this->session->set_flashdata('success', 'Google Calendar berhasil diputus.');
        redirect('profile');
    }
}
