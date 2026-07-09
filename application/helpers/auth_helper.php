<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('is_logged_in'))
{
    function is_logged_in()
    {
        $CI =& get_instance();
        return $CI->session->userdata('is_logged_in') === TRUE;
    }
}

if (!function_exists('get_user_id'))
{
    function get_user_id()
    {
        $CI =& get_instance();
        return $CI->session->userdata('user_id');
    }
}

if (!function_exists('get_user_name'))
{
    function get_user_name()
    {
        $CI =& get_instance();
        return $CI->session->userdata('user_name');
    }
}

if (!function_exists('get_user_role'))
{
    function get_user_role()
    {
        $CI =& get_instance();
        return $CI->session->userdata('user_role');
    }
}

if (!function_exists('get_user_role_display'))
{
    function get_user_role_display()
    {
        $CI =& get_instance();
        return $CI->session->userdata('user_role_display');
    }
}

if (!function_exists('get_dashboard_url'))
{
    function get_dashboard_url()
    {
        $CI =& get_instance();
        $CI->load->model('Google_calendar_model');

        $google_tokens = $CI->Google_calendar_model->get_tokens(get_user_id());

        if (!$google_tokens)
        {
            return 'profile';
        }

        if (has_permission('view_all_tasks'))
        {
            return 'dashboard/admin';
        }
        return 'dashboard/user';
    }
}

if (!function_exists('require_login'))
{
    function require_login()
    {
        if (!is_logged_in())
        {
            redirect('login');
        }
    }
}

if (!function_exists('has_permission'))
{
    function has_permission($key)
    {
        $CI =& get_instance();
        $permissions = $CI->session->userdata('user_permissions');
        return is_array($permissions) && in_array($key, $permissions);
    }
}

if (!function_exists('require_permission'))
{
    function require_permission($key)
    {
        require_login();
        if (!has_permission($key))
        {
            show_error('Akses Ditolak', 403, 'Dilarang');
        }
    }
}

if (!function_exists('translate_date_id'))
{
    function translate_date_id($formatted)
    {
        static $translations = array(
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu',
            'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April',
            'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus',
            'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember',
            'Aug' => 'Agu', 'Oct' => 'Okt', 'Dec' => 'Des'
        );
        return strtr($formatted, $translations);
    }
}

if (!function_exists('format_date'))
{
    function format_date($date, $format = 'd M Y')
    {
        if (empty($date) || $date == '0000-00-00' || $date == '0000-00-00 00:00:00')
        {
            return '-';
        }
        return translate_date_id(date($format, strtotime($date)));
    }
}

if (!function_exists('format_datetime'))
{
    function format_datetime($datetime, $format = 'd M Y, H:i')
    {
        if (empty($datetime) || $datetime == '0000-00-00' || $datetime == '0000-00-00 00:00:00')
        {
            return '-';
        }
        return translate_date_id(date($format, strtotime($datetime)));
    }
}

if (!function_exists('status_badge'))
{
    function status_badge($status)
    {
        $badges = array(
            'pending' => '<span class="badge bg-secondary">Menunggu</span>',
            'on_progress' => '<span class="badge bg-warning">Dikerjakan</span>',
            'in_review' => '<span class="badge bg-info">Menunggu Review</span>',
            'done' => '<span class="badge bg-success">Selesai</span>',
            'active' => '<span class="badge bg-success">Aktif</span>',
            'completed' => '<span class="badge bg-info">Selesai</span>',
            'archived' => '<span class="badge bg-secondary">Diarsipkan</span>'
        );

        return isset($badges[$status]) ? $badges[$status] : '<span class="badge bg-secondary">' . ucfirst($status) . '</span>';
    }
}

if (!function_exists('priority_badge'))
{
    function priority_badge($priority)
    {
        $badges = array(
            'low' => '<span class="badge bg-success">Rendah</span>',
            'medium' => '<span class="badge bg-info">Sedang</span>',
            'high' => '<span class="badge bg-danger">Tinggi</span>'
        );

        return isset($badges[$priority]) ? $badges[$priority] : '<span class="badge bg-secondary">' . ucfirst($priority) . '</span>';
    }
}

if (!function_exists('days_remaining'))
{
    function days_remaining($deadline)
    {
        if (empty($deadline) || $deadline == '0000-00-00')
        {
            return NULL;
        }

        $deadline_time = strtotime($deadline);
        $now = strtotime(date('Y-m-d'));
        $diff = ($deadline_time - $now) / (60 * 60 * 24);

        return ceil($diff);
    }
}

if (!function_exists('is_deadline_near'))
{
    function is_deadline_near($deadline)
    {
        $days = days_remaining($deadline);
        return $days !== NULL && $days <= 3 && $days >= 0;
    }
}

if (!function_exists('is_deadline_overdue'))
{
    function is_deadline_overdue($deadline)
    {
        $days = days_remaining($deadline);
        return $days !== NULL && $days < 0;
    }
}

if (!function_exists('csrf_field'))
{
    function csrf_field()
    {
        $CI =& get_instance();
        $csrf_token_name = $CI->security->get_csrf_token_name();
        $csrf_hash = $CI->security->get_csrf_hash();
        return '<input type="hidden" name="' . $csrf_token_name . '" value="' . $csrf_hash . '" />';
    }
}

if (!function_exists('csrf_token'))
{
    function csrf_token()
    {
        $CI =& get_instance();
        return $CI->security->get_csrf_token_name();
    }
}

if (!function_exists('csrf_hash'))
{
    function csrf_hash()
    {
        $CI =& get_instance();
        return $CI->security->get_csrf_hash();
    }
}
