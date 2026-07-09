<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Notification_model');
        $this->load->helper('auth_helper');
        require_login();
    }

    public function index()
    {
        $data['page_title'] = 'Notifikasi';
        $user_id = get_user_id();

        $data['notifications'] = $this->Notification_model->get_by_user($user_id, 50);
        $data['unread_count'] = $this->Notification_model->unread_count($user_id);

        $this->load->view('layouts/header', $data);
        $this->load->view('notifications/index', $data);
        $this->load->view('layouts/footer');
    }

    public function get_recent()
    {
        header('Content-Type: application/json');

        try {
            $user_id = get_user_id();
            $notifications = $this->Notification_model->get_recent($user_id, 5);

            $html = '';
            if (empty($notifications))
            {
                $html = '<li><div class="text-center p-3 text-muted">Tidak ada notifikasi</div></li>';
            }
            else
            {
                foreach ($notifications as $notif)
                {
                    $readClass = $notif->is_read ? '' : 'bg-light';
                    $html .= '<li class="' . $readClass . '">';
                    $html .= '<a class="dropdown-item" href="';
                    if ($notif->task_id)
                    {
                        $html .= site_url('tasks/detail/' . $notif->task_id);
                    }
                    else
                    {
                        $html .= site_url('notifications');
                    }
                    $html .= '" onclick="markAsRead(' . $notif->id . '); return false;">';
                    $html .= '<small class="text-muted">' . format_datetime($notif->created_at) . '</small><br>';
                    $html .= htmlspecialchars($notif->message);
                    if (!$notif->is_read)
                    {
                        $html .= ' <span class="badge bg-primary">Baru</span>';
                    }
                    $html .= '</a></li>';
                }
            }

            echo json_encode(array('html' => $html, 'success' => true));
        } catch (Exception $e) {
            echo json_encode(array('html' => '<li><div class="text-center p-3 text-danger">Error: ' . $e->getMessage() . '</div></li>', 'success' => false));
        }
    }

    public function count()
    {
        header('Content-Type: application/json');
        $count = $this->Notification_model->unread_count(get_user_id());
        echo json_encode(array('count' => $count, 'success' => true));
    }

    public function mark_read($id)
    {
        $result = $this->Notification_model->mark_as_read($id, get_user_id());
        echo json_encode(array('success' => $result));
    }

    public function mark_all_read()
    {
        $result = $this->Notification_model->mark_all_read(get_user_id());
        echo json_encode(array('success' => $result));
    }

    public function delete($id)
    {
        $result = $this->Notification_model->delete($id, get_user_id());

        if ($this->input->is_ajax_request())
        {
            echo json_encode(array('success' => $result));
        }
        else
        {
            if ($result)
            {
                $this->session->set_flashdata('success', 'Notifikasi berhasil dihapus.');
            }
            else
            {
                $this->session->set_flashdata('error', 'Gagal menghapus notifikasi.');
            }
            redirect('notifications');
        }
    }
}
