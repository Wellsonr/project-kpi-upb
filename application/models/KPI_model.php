<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KPI_model extends CI_Model {

    protected $kpis_table = 'kpis';
    protected $tasks_table = 'tasks';
    protected $users_table = 'users';
    protected $settings_table = 'kpi_settings';
    protected $targets_table = 'role_kpi_targets';

    protected $weights = array(
        'completion' => 30,
        'ontime' => 40,
        'quality' => 30
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Task_model');
        $this->load->model('User_model');

        $this->load_settings();
    }

    public function get_non_admin_role_names()
    {
        $this->db->select('name');
        $this->db->where('name !=', 'admin');
        $roles = $this->db->get('roles')->result();
        return array_map(function($r) { return $r->name; }, $roles);
    }

    public function get_role_target_by_name($role_name)
    {
        $this->db->select('t.target_performance_score');
        $this->db->from($this->targets_table . ' t');
        $this->db->join('roles r', 'r.id = t.role_id');
        $this->db->where('r.name', $role_name);
        $row = $this->db->get()->row();
        return $row ? (float) $row->target_performance_score : 80.00;
    }

    public function get_all_role_targets()
    {
        $rows = $this->db->get($this->targets_table)->result();
        $targets = array();
        foreach ($rows as $row)
        {
            $targets[$row->role_id] = (float) $row->target_performance_score;
        }
        return $targets;
    }

    public function set_role_target($role_id, $target)
    {
        $this->db->where('role_id', $role_id);
        $exists = $this->db->get($this->targets_table)->row();

        if ($exists)
        {
            $this->db->where('role_id', $role_id);
            return $this->db->update($this->targets_table, array('target_performance_score' => $target));
        }

        return $this->db->insert($this->targets_table, array('role_id' => $role_id, 'target_performance_score' => $target));
    }

    private function load_settings()
    {
        $settings = $this->db->get($this->settings_table)->result();
        foreach ($settings as $setting)
        {
            if (strpos($setting->setting_key, '_weight') !== FALSE)
            {
                $key = str_replace('_weight', '', $setting->setting_key);
                $this->weights[$key] = (int) $setting->setting_value;
            }
        }
    }

    public function calculate_user_kpi($user_id, $period_start, $period_end)
    {
        $period_start = date('Y-m-d', strtotime($period_start));
        $period_end = date('Y-m-d', strtotime($period_end));

        $as_of = $this->db->escape(min($period_end, date('Y-m-d')));

        $this->db->select('
            COUNT(*) as tasks_assigned,
            SUM(CASE WHEN status = "done" THEN 1 ELSE 0 END) as tasks_done,
            SUM(CASE WHEN status = "done" AND DATE(completed_at) <= deadline THEN 1 ELSE 0 END) as tasks_on_time,
            SUM(CASE WHEN status = "done" AND DATE(completed_at) > deadline THEN 1 ELSE 0 END) as tasks_late,
            SUM(CASE WHEN deadline < ' . $as_of . ' AND status != "done" THEN 1 ELSE 0 END) as tasks_overdue,
            SUM(revision_count) as tasks_revised,
            AVG(CASE WHEN quality_score IS NOT NULL THEN quality_score ELSE NULL END) as quality_avg
        ');
        $this->db->from($this->tasks_table);
        $this->db->where('assigned_to', $user_id);
        $this->db->where('deadline >=', $period_start);
        $this->db->where('deadline <=', $period_end);

        $result = $this->db->get()->row();

        if (!$result || $result->tasks_assigned == 0)
        {
            return $this->empty_kpi_result();
        }

        $completion_rate = ($result->tasks_done / $result->tasks_assigned) * 100;
        $ontime_rate = $result->tasks_done > 0 ? ($result->tasks_on_time / $result->tasks_done) * 100 : 0;
        $quality_avg = $result->quality_avg ? ($result->quality_avg / 5) * 100 : 0;

        $performance_score = $this->calculate_performance_score(
            $completion_rate,
            $ontime_rate,
            $quality_avg
        );

        return array(
            'user_id' => $user_id,
            'period_start' => $period_start,
            'period_end' => $period_end,
            'tasks_assigned' => (int) $result->tasks_assigned,
            'tasks_done' => (int) $result->tasks_done,
            'tasks_on_time' => (int) $result->tasks_on_time,
            'tasks_overdue' => (int) $result->tasks_overdue,
            'tasks_revised' => (int) $result->tasks_revised,
            'completion_rate' => round($completion_rate, 2),
            'ontime_rate' => round($ontime_rate, 2),
            'quality_avg' => round($result->quality_avg, 2),
            'performance_score' => round($performance_score, 2)
        );
    }

    public function calculate_role_kpi($role_name, $period_start, $period_end)
    {
        $period_start = date('Y-m-d', strtotime($period_start));
        $period_end = date('Y-m-d', strtotime($period_end));

        $this->db->select('
            COUNT(t.id) as tasks_assigned,
            SUM(CASE WHEN t.status = "done" THEN 1 ELSE 0 END) as tasks_done,
            SUM(CASE WHEN t.status = "done" AND DATE(t.completed_at) <= t.deadline THEN 1 ELSE 0 END) as tasks_on_time,
            AVG(CASE WHEN t.quality_score IS NOT NULL THEN t.quality_score ELSE NULL END) as quality_avg
        ');
        $this->db->from($this->tasks_table . ' t');
        $this->db->join($this->users_table . ' u', 'u.id = t.assigned_to');
        $this->db->join('roles r', 'r.id = u.role_id');
        $this->db->where('r.name', $role_name);
        $this->db->where('t.deadline >=', $period_start);
        $this->db->where('t.deadline <=', $period_end);

        $result = $this->db->get()->row();

        if (!$result || $result->tasks_assigned == 0)
        {
            return null;
        }

        $completion_rate = ($result->tasks_done / $result->tasks_assigned) * 100;
        $ontime_rate = $result->tasks_done > 0 ? ($result->tasks_on_time / $result->tasks_done) * 100 : 0;
        $quality_avg = $result->quality_avg ? ($result->quality_avg / 5) * 100 : 0;
        $performance_score = $this->calculate_performance_score($completion_rate, $ontime_rate, $quality_avg);

        return array(
            'role' => $role_name,
            'tasks_assigned' => (int) $result->tasks_assigned,
            'tasks_done' => (int) $result->tasks_done,
            'tasks_on_time' => (int) $result->tasks_on_time,
            'completion_rate' => round($completion_rate, 2),
            'ontime_rate' => round($ontime_rate, 2),
            'quality_avg' => round($result->quality_avg, 2),
            'performance_score' => round($performance_score, 2)
        );
    }

    private function calculate_performance_score($completion_rate, $ontime_rate, $quality_avg)
    {
        $score = (
            ($completion_rate * $this->weights['completion'] / 100) +
            ($ontime_rate * $this->weights['ontime'] / 100) +
            ($quality_avg * $this->weights['quality'] / 100)
        );

        return min(100, max(0, $score));
    }

    public function get_top_performers($limit = 5, $period_start = null, $period_end = null)
    {
        if ($period_start === null)
        {
            $period_start = date('Y-m-d', strtotime('monday this week'));
        }
        if ($period_end === null)
        {
            $period_end = date('Y-m-d', strtotime('sunday this week'));
        }

        $performers = array();
        $users = $this->User_model->get_all(1);

        foreach ($users as $user)
        {
            $kpi = $this->calculate_user_kpi($user->id, $period_start, $period_end);
            if ($kpi['tasks_assigned'] > 0)
            {
                $kpi['user_name'] = $user->name;
                $kpi['role_name'] = $user->role_name;
                $kpi['role_display'] = $user->role_display_name;
                $performers[] = $kpi;
            }
        }

        usort($performers, function($a, $b) {
            return $b['performance_score'] <=> $a['performance_score'];
        });

        return array_slice($performers, 0, $limit);
    }

    public function get_bottom_performers($limit = 5, $period_start = null, $period_end = null)
    {
        if ($period_start === null)
        {
            $period_start = date('Y-m-d', strtotime('monday this week'));
        }
        if ($period_end === null)
        {
            $period_end = date('Y-m-d', strtotime('sunday this week'));
        }

        $performers = array();
        $users = $this->User_model->get_all(1);

        foreach ($users as $user)
        {
            $kpi = $this->calculate_user_kpi($user->id, $period_start, $period_end);
            if ($kpi['tasks_assigned'] > 0)
            {
                $kpi['user_name'] = $user->name;
                $kpi['role_name'] = $user->role_name;
                $kpi['role_display'] = $user->role_display_name;
                $performers[] = $kpi;
            }
        }

        usort($performers, function($a, $b) {
            return $a['performance_score'] <=> $b['performance_score'];
        });

        return array_slice($performers, 0, $limit);
    }

    public function get_team_kpi_summary($period_start = null, $period_end = null)
    {
        if ($period_start === null)
        {
            $period_start = date('Y-m-d', strtotime('monday this week'));
        }
        if ($period_end === null)
        {
            $period_end = date('Y-m-d', strtotime('sunday this week'));
        }

        $team_kpi = array(
            'period_start' => $period_start,
            'period_end' => $period_end,
            'total_users' => 0,
            'active_users' => 0,
            'total_tasks' => 0,
            'total_done' => 0,
            'total_on_time' => 0,
            'avg_completion_rate' => 0,
            'avg_ontime_rate' => 0,
            'avg_performance_score' => 0,
            'roles' => array()
        );

        $roles = $this->get_non_admin_role_names();

        foreach ($roles as $role)
        {
            $role_kpi = $this->calculate_role_kpi($role, $period_start, $period_end);
            if ($role_kpi)
            {
                $team_kpi['roles'][$role] = $role_kpi;
                $team_kpi['total_tasks'] += $role_kpi['tasks_assigned'];
                $team_kpi['total_done'] += $role_kpi['tasks_done'];
                $team_kpi['total_on_time'] += $role_kpi['tasks_on_time'];
            }
        }

        if (count($team_kpi['roles']) > 0)
        {
            $total_completion = 0;
            $total_ontime = 0;
            $total_score = 0;

            foreach ($team_kpi['roles'] as $role)
            {
                $total_completion += $role['completion_rate'];
                $total_ontime += $role['ontime_rate'];
                $total_score += $role['performance_score'];
            }

            $team_kpi['avg_completion_rate'] = round($total_completion / count($team_kpi['roles']), 2);
            $team_kpi['avg_ontime_rate'] = round($total_ontime / count($team_kpi['roles']), 2);
            $team_kpi['avg_performance_score'] = round($total_score / count($team_kpi['roles']), 2);
        }

        $all_users = $this->User_model->get_all(1);
        $team_kpi['total_users'] = count($all_users);

        return $team_kpi;
    }

    public function save_kpi_snapshot($user_id, $kpi_data, $period_type = 'weekly')
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('period_type', $period_type);
        $this->db->where('period_start', $kpi_data['period_start']);

        $existing = $this->db->get($this->kpis_table)->row();

        $data = array(
            'user_id' => $user_id,
            'period_type' => $period_type,
            'period_start' => $kpi_data['period_start'],
            'period_end' => $kpi_data['period_end'],
            'tasks_assigned' => $kpi_data['tasks_assigned'],
            'tasks_done' => $kpi_data['tasks_done'],
            'tasks_on_time' => $kpi_data['tasks_on_time'],
            'tasks_overdue' => $kpi_data['tasks_overdue'],
            'tasks_revised' => $kpi_data['tasks_revised'],
            'completion_rate' => $kpi_data['completion_rate'],
            'ontime_rate' => $kpi_data['ontime_rate'],
            'quality_avg' => $kpi_data['quality_avg'],
            'performance_score' => $kpi_data['performance_score']
        );

        if ($existing)
        {
            $this->db->where('id', $existing->id);
            return $this->db->update($this->kpis_table, $data);
        }
        else
        {
            return $this->db->insert($this->kpis_table, $data);
        }
    }

    public function get_kpi_history($user_id, $limit = 12)
    {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('period_start', 'DESC');
        $this->db->limit($limit);
        return $this->db->get($this->kpis_table)->result();
    }

    public function get_performance_trend($user_id, $period_type, $current_period_start, $current_score)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('period_type', $period_type);
        $this->db->where('period_start <', $current_period_start);
        $this->db->order_by('period_start', 'DESC');
        $this->db->limit(1);
        $previous = $this->db->get($this->kpis_table)->row();

        if (!$previous)
        {
            return array('direction' => 'flat', 'delta' => null);
        }

        $delta = round($current_score - $previous->performance_score, 2);
        $direction = $delta > 0.5 ? 'up' : ($delta < -0.5 ? 'down' : 'flat');

        return array('direction' => $direction, 'delta' => $delta);
    }

    public function get_kpi_history_chart($user_id, $limit = 12)
    {
        $history = $this->get_kpi_history($user_id, $limit);

        $labels = array();
        $performance_scores = array();
        $completion_rates = array();
        $ontime_rates = array();

        $history = array_reverse($history);

        foreach ($history as $record)
        {
            $labels[] = date('M d', strtotime($record->period_start));
            $performance_scores[] = (float) $record->performance_score;
            $completion_rates[] = (float) $record->completion_rate;
            $ontime_rates[] = (float) $record->ontime_rate;
        }

        return array(
            'labels' => $labels,
            'performance_scores' => $performance_scores,
            'completion_rates' => $completion_rates,
            'ontime_rates' => $ontime_rates
        );
    }

    public function get_user_current_kpi($user_id)
    {
        $period_start = date('Y-m-d', strtotime('monday this week'));
        $period_end = date('Y-m-d', strtotime('sunday this week'));

        $this->db->where('user_id', $user_id);
        $this->db->where('period_type', 'weekly');
        $this->db->where('period_start', $period_start);
        $snapshot = $this->db->get($this->kpis_table)->row();

        if ($snapshot)
        {
            return $snapshot;
        }

        return (object) $this->calculate_user_kpi($user_id, $period_start, $period_end);
    }

    public function generate_kpi_report($period_type = 'weekly', $period_count = 4)
    {
        $report = array(
            'periods' => array(),
            'summary' => array()
        );

        $periods = array();
        for ($i = $period_count - 1; $i >= 0; $i--)
        {
            if ($period_type == 'weekly')
            {
                $start = date('Y-m-d', strtotime('monday this week -' . $i . ' weeks'));
                $end = date('Y-m-d', strtotime('sunday this week -' . $i . ' weeks'));
                $label = date('M d', strtotime($start)) . ' - ' . date('M d', strtotime($end));
            }
            else
            {
                $start = date('Y-m-01', strtotime("-$i months"));
                $end = date('Y-m-t', strtotime("-$i months"));
                $label = date('M Y', strtotime($start));
            }

            $periods[] = array(
                'start' => $start,
                'end' => $end,
                'label' => $label
            );
        }

        $users = $this->User_model->get_all(1);

        foreach ($periods as $period)
        {
            $period_data = array(
                'label' => $period['label'],
                'start' => $period['start'],
                'end' => $period['end'],
                'users' => array()
            );

            foreach ($users as $user)
            {
                $kpi = $this->calculate_user_kpi($user->id, $period['start'], $period['end']);
                $kpi['user_name'] = $user->name;
                $kpi['role_name'] = $user->role_name;
                $period_data['users'][] = $kpi;
            }

            $report['periods'][] = $period_data;
        }

        return $report;
    }

    public function get_user_ranking($user_id, $period_start = null, $period_end = null)
    {
        if ($period_start === null)
        {
            $period_start = date('Y-m-d', strtotime('monday this week'));
        }
        if ($period_end === null)
        {
            $period_end = date('Y-m-d', strtotime('sunday this week'));
        }

        $top_performers = $this->get_top_performers(100, $period_start, $period_end);

        foreach ($top_performers as $index => $performer)
        {
            if ($performer['user_id'] == $user_id)
            {
                return array(
                    'rank' => $index + 1,
                    'total' => count($top_performers),
                    'percentile' => round((1 - $index / count($top_performers)) * 100, 1)
                );
            }
        }

        return null;
    }

    private function empty_kpi_result()
    {
        return array(
            'user_id' => 0,
            'period_start' => date('Y-m-d'),
            'period_end' => date('Y-m-d'),
            'tasks_assigned' => 0,
            'tasks_done' => 0,
            'tasks_on_time' => 0,
            'tasks_overdue' => 0,
            'tasks_revised' => 0,
            'completion_rate' => 0,
            'ontime_rate' => 0,
            'quality_avg' => 0,
            'performance_score' => 0
        );
    }

    public function recalculate_period_kpis($period_start, $period_end, $period_type = 'weekly')
    {
        $users = $this->User_model->get_all(1);
        $results = array('success' => 0, 'failed' => 0);

        foreach ($users as $user)
        {
            $kpi = $this->calculate_user_kpi($user->id, $period_start, $period_end);

            if ($this->save_kpi_snapshot($user->id, $kpi, $period_type))
            {
                $results['success']++;
            }
            else
            {
                $results['failed']++;
            }
        }

        return $results;
    }

    public function get_settings()
    {
        $settings = array();
        $result = $this->db->get($this->settings_table)->result();

        foreach ($result as $row)
        {
            $settings[$row->setting_key] = $row->setting_value;
        }

        return $settings;
    }

    public function update_setting($key, $value)
    {
        $this->db->where('setting_key', $key);
        $exists = $this->db->get($this->settings_table)->row();

        $data = array(
            'setting_key' => $key,
            'setting_value' => $value
        );

        if ($exists)
        {
            $this->db->where('setting_key', $key);
            return $this->db->update($this->settings_table, $data);
        }
        else
        {
            return $this->db->insert($this->settings_table, $data);
        }
    }
}
