<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Project_model');
        $this->load->model('Task_model');
        $this->load->model('Notification_model');
        $this->load->model('KPI_model');
        $this->load->helper('auth_helper');
        require_login();
    }

    public function index()
    {
        redirect(get_dashboard_url());
    }

    public function admin()
    {
        require_permission('view_all_tasks');

        $data['page_title'] = 'Dashboard Admin';

        $data['total_projects'] = $this->Project_model->count_active();
        $data['task_stats'] = $this->Task_model->get_stats();
        $data['projects'] = $this->Project_model->with_stats(5);
        $data['deadline_alerts'] = $this->Task_model->get_deadline_alerts(NULL, 3);
        $data['overdue_tasks'] = $this->Task_model->get_overdue();
        $data['total_users'] = count($this->User_model->get_all(1));

        $data['top_performers'] = $this->KPI_model->get_top_performers(5);
        $data['team_summary'] = $this->KPI_model->get_team_kpi_summary();

        $this->load->view('layouts/header', $data);
        $this->load->view('dashboard/admin', $data);
        $this->load->view('layouts/footer');
    }

    public function user()
    {
        $user_id = get_user_id();

        $data['page_title'] = 'Dashboard Saya';

        $data['task_stats'] = $this->Task_model->get_stats($user_id);

        $data['today_tasks'] = $this->Task_model->get_today_tasks($user_id);

        $data['week_tasks'] = $this->Task_model->get_week_tasks($user_id);

        $data['deadline_alerts'] = $this->Task_model->get_deadline_alerts($user_id, 3);

        $data['overdue_tasks'] = $this->Task_model->get_overdue($user_id);

        $this->load->view('layouts/header', $data);
        $this->load->view('dashboard/user', $data);
        $this->load->view('layouts/footer');
    }

    public function notification_count()
    {
        $count = $this->Notification_model->unread_count(get_user_id());
        echo json_encode(array('count' => $count));
    }

    public function notifications()
    {
        $notifications = $this->Notification_model->get_recent(get_user_id(), 10);
        echo json_encode($notifications);
    }
}
