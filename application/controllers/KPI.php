<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KPI extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('KPI_model');
        $this->load->model('User_model');
        $this->load->model('Task_model');
        $this->load->model('Project_model');
        $this->load->helper('auth_helper');
        require_login();
    }

    public function index()
    {
        require_permission('view_all_kpi');

        $data['page_title'] = 'Dashboard KPI';

        $data['team_summary'] = $this->KPI_model->get_team_kpi_summary();
        $data['top_performers'] = $this->KPI_model->get_top_performers(5);
        $data['bottom_performers'] = $this->KPI_model->get_bottom_performers(3);

        $data['period_start'] = $this->input->get('start', date('Y-m-d', strtotime('monday this week')));
        $data['period_end'] = $this->input->get('end', date('Y-m-d', strtotime('sunday this week')));

        $data['role_comparison'] = $this->get_role_comparison_data($data['period_start'], $data['period_end']);

        $this->load->view('layouts/header', $data);
        $this->load->view('kpi/index', $data);
        $this->load->view('layouts/footer');
    }

    public function user_performance($user_id = NULL)
    {
        if (!has_permission('view_all_kpi'))
        {
            $user_id = get_user_id();
        }

        if ($user_id === NULL)
        {
            $user_id = get_user_id();
        }

        $data['page_title'] = 'Kinerja Pengguna';

        $data['user'] = $this->User_model->get_by_id($user_id);

        if (!$data['user'])
        {
            show_404();
        }

        if (!has_permission('view_all_kpi') && $user_id != get_user_id())
        {
            show_error('Akses Ditolak', 403, 'Dilarang');
        }

        $period_start = date('Y-m-d', strtotime('monday this week'));
        $period_end = date('Y-m-d', strtotime('sunday this week'));
        $data['current_kpi'] = $this->KPI_model->calculate_user_kpi($user_id, $period_start, $period_end);
        $data['trend'] = $this->KPI_model->get_performance_trend($user_id, 'weekly', $period_start, $data['current_kpi']['performance_score']);

        $data['ranking'] = $this->KPI_model->get_user_ranking($user_id, $period_start, $period_end);

        $data['chart_data'] = $this->KPI_model->get_kpi_history_chart($user_id, 12);

        $data['recent_tasks'] = $this->Task_model->get_all(array('assigned_to' => $user_id), 10);

        $team_summary = $this->KPI_model->get_team_kpi_summary($period_start, $period_end);
        $data['team_avg_score'] = $team_summary['avg_performance_score'];

        $this->load->view('layouts/header', $data);
        $this->load->view('kpi/user_performance', $data);
        $this->load->view('layouts/footer');
    }

    public function team_comparison()
    {
        require_permission('view_all_kpi');

        $data['page_title'] = 'Perbandingan Tim';

        $period_start = $this->input->get('start') ?: date('Y-m-d', strtotime('monday this week'));
        $period_end = $this->input->get('end') ?: date('Y-m-d', strtotime('sunday this week'));

        $data['period_start'] = $period_start;
        $data['period_end'] = $period_end;

        $users = $this->User_model->get_all(1);
        $data['users_kpi'] = array();

        foreach ($users as $user)
        {
            $kpi = $this->KPI_model->calculate_user_kpi($user->id, $period_start, $period_end);
            $kpi['user_name'] = $user->name;
            $kpi['role_name'] = $user->role_name;
            $kpi['role_display'] = $user->role_display_name;
            $kpi['avatar'] = $user->avatar;
            $kpi['trend'] = $this->KPI_model->get_performance_trend($user->id, 'weekly', $period_start, $kpi['performance_score']);
            $data['users_kpi'][] = $kpi;
        }

        usort($data['users_kpi'], function($a, $b) {
            return $b['performance_score'] <=> $a['performance_score'];
        });

        $data['role_comparison'] = $this->get_role_comparison_data($period_start, $period_end);

        $this->load->view('layouts/header', $data);
        $this->load->view('kpi/team_comparison', $data);
        $this->load->view('layouts/footer');
    }

    public function report()
    {
        require_permission('view_all_kpi');

        $data['page_title'] = 'Laporan KPI';

        $period_type = $this->input->get('type', 'weekly');
        $period_count = $this->input->get('count', 4);

        $data['report'] = $this->KPI_model->generate_kpi_report($period_type, $period_count);
        $data['period_type'] = $period_type;
        $data['period_count'] = $period_count;

        $this->load->view('layouts/header', $data);
        $this->load->view('kpi/report', $data);
        $this->load->view('layouts/footer');
    }

    public function recalculate()
    {
        require_permission('view_all_kpi');

        $period_start = $this->input->post('period_start', date('Y-m-d', strtotime('monday this week')));
        $period_end = $this->input->post('period_end', date('Y-m-d', strtotime('sunday this week')));
        $period_type = $this->input->post('period_type', 'weekly');

        $results = $this->KPI_model->recalculate_period_kpis($period_start, $period_end, $period_type);

        if ($this->input->is_ajax_request())
        {
            echo json_encode(array(
                'success' => true,
                'results' => $results
            ));
        }
        else
        {
            $this->session->set_flashdata('success', "KPI dihitung ulang: {$results['success']} pengguna diperbarui, {$results['failed']} gagal.");
            redirect('kpi');
        }
    }

    public function export_report()
    {
        require_permission('view_all_kpi');

        $period_start = $this->input->get('start') ?: date('Y-m-d', strtotime('monday this week'));
        $period_end = $this->input->get('end') ?: date('Y-m-d', strtotime('sunday this week'));

        $users = $this->User_model->get_all(1);
        $kpis = array();

        foreach ($users as $user)
        {
            $kpi = $this->KPI_model->calculate_user_kpi($user->id, $period_start, $period_end);
            $kpi['user_name'] = $user->name;
            $kpi['role_display'] = $user->role_display_name;
            $kpis[] = $kpi;
        }

        usort($kpis, function($a, $b) {
            return $b['performance_score'] <=> $a['performance_score'];
        });

        $filename = 'kpi_report_' . date('Y-m-d') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        fputcsv($output, array(
            'Peringkat', 'Pengguna', 'Peran', 'Tugas Ditugaskan', 'Tugas Selesai',
            'Tingkat Penyelesaian', 'Tingkat Ketepatan Waktu', 'Kualitas Rata-rata',
            'Skor Kinerja', 'Tugas Terlambat', 'Tugas Direvisi'
        ));

        foreach ($kpis as $index => $kpi)
        {
            fputcsv($output, array(
                $index + 1,
                $kpi['user_name'],
                $kpi['role_display'],
                $kpi['tasks_assigned'],
                $kpi['tasks_done'],
                $kpi['completion_rate'] . '%',
                $kpi['ontime_rate'] . '%',
                $kpi['quality_avg'],
                $kpi['performance_score'],
                $kpi['tasks_overdue'],
                $kpi['tasks_revised']
            ));
        }

        fclose($output);
        exit;
    }

    public function ajax_summary()
    {
        require_permission('view_all_kpi');

        $period_start = $this->input->get('start') ?: date('Y-m-d', strtotime('monday this week'));
        $period_end = $this->input->get('end') ?: date('Y-m-d', strtotime('sunday this week'));

        $summary = $this->KPI_model->get_team_kpi_summary($period_start, $period_end);

        echo json_encode(array(
            'success' => true,
            'summary' => $summary
        ));
    }

    public function ajax_top_performers()
    {
        require_permission('view_all_kpi');

        $limit = $this->input->get('limit', 5);
        $performers = $this->KPI_model->get_top_performers($limit);

        echo json_encode(array(
            'success' => true,
            'performers' => $performers
        ));
    }

    private function get_role_comparison_data($period_start, $period_end)
    {
        $roles = $this->KPI_model->get_non_admin_role_names();
        $comparison = array();

        foreach ($roles as $role)
        {
            $role_kpi = $this->KPI_model->calculate_role_kpi($role, $period_start, $period_end);
            if ($role_kpi)
            {
                $role_kpi['target'] = $this->KPI_model->get_role_target_by_name($role);
                $comparison[$role] = $role_kpi;
            }
        }

        return $comparison;
    }

    public function settings()
    {
        require_permission('view_all_kpi');

        $data['page_title'] = 'Pengaturan KPI';
        $data['settings'] = $this->KPI_model->get_settings();
        $data['roles'] = $this->User_model->get_all_roles();
        $data['role_targets'] = $this->KPI_model->get_all_role_targets();

        if ($this->input->post())
        {
            $weights = array('completion', 'ontime', 'quality');

            foreach ($weights as $weight)
            {
                $value = $this->input->post($weight . '_weight');
                $this->KPI_model->update_setting($weight . '_weight', $value);
            }

            foreach ($data['roles'] as $role)
            {
                $target = $this->input->post('target_' . $role->id);
                if ($target !== NULL && is_numeric($target))
                {
                    $this->KPI_model->set_role_target($role->id, (float) $target);
                }
            }

            $this->session->set_flashdata('success', 'Pengaturan KPI berhasil diperbarui!');
            redirect('kpi/settings');
        }

        $this->load->view('layouts/header', $data);
        $this->load->view('kpi/settings', $data);
        $this->load->view('layouts/footer');
    }

    public function rate_task()
    {
        $task_id = $this->input->post('task_id');
        $rating = $this->input->post('rating');

        $task = $this->Task_model->get_by_id($task_id);

        if (!$task)
        {
            echo json_encode(array('success' => false, 'message' => 'Tugas tidak ditemukan'));
            return;
        }

        if (!has_permission('view_all_kpi'))
        {
            echo json_encode(array('success' => false, 'message' => 'Akses ditolak'));
            return;
        }

        $settings = $this->KPI_model->get_settings();
        $min = isset($settings['min_quality_score']) ? (int) $settings['min_quality_score'] : 1;
        $max = isset($settings['max_quality_score']) ? (int) $settings['max_quality_score'] : 5;

        if (!is_numeric($rating) || $rating < $min || $rating > $max)
        {
            echo json_encode(array('success' => false, 'message' => "Rating harus antara {$min} dan {$max}"));
            return;
        }

        $this->db->where('id', $task_id);
        $result = $this->db->update('tasks', array('quality_score' => (int) $rating));

        if ($result)
        {
            $this->load->model('Task_activity_model');
            $this->Task_activity_model->log($task_id, get_user_id(), 'rated', "Diberi rating {$rating}");

            echo json_encode(array('success' => true, 'message' => 'Tugas berhasil dinilai'));
        }
        else
        {
            echo json_encode(array('success' => false, 'message' => 'Gagal menilai tugas'));
        }
    }

    public function add_revision()
    {
        $task_id = $this->input->post('task_id');
        $reason = $this->input->post('reason');

        $task = $this->Task_model->get_by_id($task_id);

        if (!$task)
        {
            echo json_encode(array('success' => false, 'message' => 'Tugas tidak ditemukan'));
            return;
        }

        if (!has_permission('view_all_kpi'))
        {
            echo json_encode(array('success' => false, 'message' => 'Akses ditolak'));
            return;
        }

        $revision_data = array(
            'task_id' => $task_id,
            'revised_by' => get_user_id(),
            'revision_reason' => $reason
        );

        $this->db->insert('task_revisions', $revision_data);

        $this->db->where('id', $task_id);
        $this->db->update('tasks', array(
            'revision_count' => $task->revision_count + 1,
            'status' => 'pending',
            'completed_at' => null
        ));

        $this->load->model('Notification_model');
        $this->Notification_model->create(
            $task->assigned_to,
            "Tugas '{$task->title}' perlu direvisi: " . $reason,
            'status_update',
            $task_id
        );

        $this->load->model('Task_activity_model');
        $this->Task_activity_model->log($task_id, get_user_id(), 'revision_requested', "Revisi diminta: {$reason}");

        echo json_encode(array('success' => true, 'message' => 'Revisi diminta'));
    }
}
