<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if (!$this->input->is_cli_request())
        {
            show_404();
        }

        $this->load->model('KPI_model');
        $this->load->model('Task_model');
        $this->load->model('Notification_model');
    }

    public function run()
    {
        $this->send_deadline_reminders();
        $this->auto_recalculate_kpi();
    }

    private function send_deadline_reminders()
    {
        $tasks = $this->Task_model->get_deadline_alerts(NULL, 1);

        foreach ($tasks as $task)
        {
            $this->Notification_model->notify_deadline_reminder($task->id);
        }

        echo count($tasks) . " deadline reminder(s) sent\n";
    }

    private function auto_recalculate_kpi()
    {
        $settings = $this->KPI_model->get_settings();

        if (empty($settings['auto_calculate']))
        {
            echo "auto_calculate disabled, skipping KPI recalculation\n";
            return;
        }

        $period_start = date('Y-m-d', strtotime('monday this week'));
        $period_end = date('Y-m-d', strtotime('sunday this week'));

        $results = $this->KPI_model->recalculate_period_kpis($period_start, $period_end, 'weekly');

        echo "KPI recalculated: {$results['success']} success, {$results['failed']} failed\n";
    }
}
