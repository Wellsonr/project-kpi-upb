<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Google_calendar_model extends CI_Model {

    protected $tokens_table = 'user_google_tokens';

    const OAUTH_AUTH_URL = 'https://accounts.google.com/o/oauth2/v2/auth';
    const OAUTH_TOKEN_URL = 'https://oauth2.googleapis.com/token';
    const OAUTH_REVOKE_URL = 'https://oauth2.googleapis.com/revoke';
    const CALENDAR_NAME = 'Task Tracker';

    public function __construct()
    {
        parent::__construct();
        $this->config->load('google');
    }

    public function get_auth_url($state)
    {
        $params = array(
            'client_id' => $this->config->item('google_client_id'),
            'redirect_uri' => base_url('profile/google_callback'),
            'response_type' => 'code',
            'scope' => 'https://www.googleapis.com/auth/calendar',
            'access_type' => 'offline',
            'prompt' => 'consent',
            'state' => $state
        );

        return self::OAUTH_AUTH_URL . '?' . http_build_query($params);
    }

    public function exchange_code_for_token($code)
    {
        return $this->post_form(self::OAUTH_TOKEN_URL, array(
            'code' => $code,
            'client_id' => $this->config->item('google_client_id'),
            'client_secret' => $this->config->item('google_client_secret'),
            'redirect_uri' => base_url('profile/google_callback'),
            'grant_type' => 'authorization_code'
        ));
    }

    public function get_or_create_calendar($access_token)
    {
        $list = $this->get_json('https://www.googleapis.com/calendar/v3/users/me/calendarList', $access_token);

        if ($list && isset($list['items']))
        {
            foreach ($list['items'] as $calendar)
            {
                if ($calendar['summary'] === self::CALENDAR_NAME)
                {
                    return $calendar['id'];
                }
            }
        }

        $created = $this->post_json('https://www.googleapis.com/calendar/v3/calendars', array('summary' => self::CALENDAR_NAME), $access_token);

        return $created ? $created['id'] : FALSE;
    }

    public function get_valid_access_token($user_id)
    {
        $tokens = $this->get_tokens($user_id);
        return $tokens ? $this->get_valid_access_token_for($tokens) : FALSE;
    }

    public function get_valid_access_token_for($tokens)
    {
        if (strtotime($tokens->token_expires_at) > time() + 60)
        {
            return $tokens->access_token;
        }

        $refreshed = $this->post_form(self::OAUTH_TOKEN_URL, array(
            'refresh_token' => $tokens->refresh_token,
            'client_id' => $this->config->item('google_client_id'),
            'client_secret' => $this->config->item('google_client_secret'),
            'grant_type' => 'refresh_token'
        ));

        if (!$refreshed || !isset($refreshed['access_token']))
        {
            $this->delete_tokens($tokens->user_id);
            return FALSE;
        }

        $this->db->where('user_id', $tokens->user_id);
        $this->db->update($this->tokens_table, array(
            'access_token' => $refreshed['access_token'],
            'token_expires_at' => date('Y-m-d H:i:s', time() + $refreshed['expires_in'])
        ));

        return $refreshed['access_token'];
    }

    public function save_tokens($user_id, $access_token, $refresh_token, $expires_in, $calendar_id)
    {
        $data = array(
            'access_token' => $access_token,
            'refresh_token' => $refresh_token,
            'token_expires_at' => date('Y-m-d H:i:s', time() + $expires_in),
            'google_calendar_id' => $calendar_id
        );

        if ($this->get_tokens($user_id))
        {
            $this->db->where('user_id', $user_id);
            return $this->db->update($this->tokens_table, $data);
        }

        $data['user_id'] = $user_id;
        return $this->db->insert($this->tokens_table, $data);
    }

    public function get_tokens($user_id)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->get($this->tokens_table)->row();
    }

    public function delete_tokens($user_id)
    {
        $tokens = $this->get_tokens($user_id);

        if ($tokens)
        {
            $this->post_form(self::OAUTH_REVOKE_URL, array('token' => $tokens->refresh_token));
        }

        $this->db->where('user_id', $user_id);
        return $this->db->delete($this->tokens_table);
    }

    protected function post_form($url, $fields)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error || $http_code < 200 || $http_code >= 300)
        {
            log_message('error', "Google OAuth POST to {$url} failed (HTTP {$http_code}): " . ($error ?: $response));
            return FALSE;
        }

        return json_decode($response, TRUE);
    }

    protected function request_json($method, $url, $payload, $access_token)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $headers = array('Authorization: Bearer ' . $access_token);

        if ($payload !== NULL)
        {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            $headers[] = 'Content-Type: application/json';
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error || $http_code < 200 || $http_code >= 300)
        {
            log_message('error', "Google API {$method} to {$url} failed (HTTP {$http_code}): " . ($error ?: $response));
            return FALSE;
        }

        return $response ? json_decode($response, TRUE) : TRUE;
    }

    protected function get_json($url, $access_token)
    {
        return $this->request_json('GET', $url, NULL, $access_token);
    }

    protected function post_json($url, $payload, $access_token)
    {
        return $this->request_json('POST', $url, $payload, $access_token);
    }

    protected function patch_json($url, $payload, $access_token)
    {
        return $this->request_json('PATCH', $url, $payload, $access_token);
    }

    protected function delete_json($url, $access_token)
    {
        return $this->request_json('DELETE', $url, NULL, $access_token);
    }

    private function color_id_for_priority($priority)
    {
        $map = array('high' => '11', 'medium' => '5', 'low' => '10');
        return isset($map[$priority]) ? $map[$priority] : '5';
    }

    private function build_event_payload($task)
    {
        $priority_label = array('high' => 'Tinggi', 'medium' => 'Sedang', 'low' => 'Rendah');
        $priority_text = isset($priority_label[$task->priority]) ? $priority_label[$task->priority] : $task->priority;

        $description = "Proyek: " . ($task->project_title ? $task->project_title : '-') . "\n";
        $description .= "Prioritas: {$priority_text}\n";
        $description .= "Deskripsi: " . ($task->description ? $task->description : '-') . "\n\n";
        $description .= "Dibuat otomatis oleh Task Tracker.\n";
        $description .= "Lihat detail: " . base_url('tasks/detail/' . $task->id);

        return array(
            'summary' => "[{$priority_text}] {$task->title}",
            'description' => $description,
            'start' => array(
                'dateTime' => $task->deadline . 'T17:00:00+07:00',
                'timeZone' => 'Asia/Jakarta'
            ),
            'end' => array(
                'dateTime' => $task->deadline . 'T18:00:00+07:00',
                'timeZone' => 'Asia/Jakarta'
            ),
            'colorId' => $this->color_id_for_priority($task->priority)
        );
    }

    private function create_event($task)
    {
        $tokens = $this->get_tokens($task->assigned_to);
        if (!$tokens) { return; }

        $access_token = $this->get_valid_access_token_for($tokens);
        if (!$access_token) { return; }

        $url = "https://www.googleapis.com/calendar/v3/calendars/" . $tokens->google_calendar_id . "/events";
        $result = $this->post_json($url, $this->build_event_payload($task), $access_token);

        if ($result && isset($result['id']))
        {
            $this->db->insert('task_calendar_events', array(
                'task_id' => $task->id,
                'user_id' => $task->assigned_to,
                'google_event_id' => $result['id']
            ));
        }
    }

    private function update_event($task, $google_event_id)
    {
        $tokens = $this->get_tokens($task->assigned_to);
        if (!$tokens) { return; }

        $access_token = $this->get_valid_access_token_for($tokens);
        if (!$access_token) { return; }

        $url = "https://www.googleapis.com/calendar/v3/calendars/" . $tokens->google_calendar_id . "/events";
        $this->patch_json($url, $this->build_event_payload($task), $access_token);
    }

    private function delete_event($user_id, $google_event_id)
    {
        $tokens = $this->get_tokens($user_id);
        if (!$tokens) { return; }

        $access_token = $this->get_valid_access_token_for($tokens);
        if (!$access_token) { return; }

        $url = "https://www.googleapis.com/calendar/v3/calendars/" . $tokens->google_calendar_id . "/events";
        $this->delete_json($url, $access_token);
    }

    public function sync_task_event($task, $deleted = FALSE)
    {
        try
        {
            $this->db->where('task_id', $task->id);
            $mapping = $this->db->get('task_calendar_events')->row();

            if ($deleted || $task->status === 'done')
            {
                if ($mapping)
                {
                    $this->delete_event($mapping->user_id, $mapping->google_event_id);
                    $this->db->where('task_id', $task->id);
                    $this->db->delete('task_calendar_events');
                }
                return;
            }

            if (!$mapping)
            {
                $this->create_event($task);
                return;
            }

            if ($mapping->user_id != $task->assigned_to)
            {
                $this->delete_event($mapping->user_id, $mapping->google_event_id);
                $this->db->where('task_id', $task->id);
                $this->db->delete('task_calendar_events');
                $this->create_event($task);
                return;
            }

            $this->update_event($task, $mapping->google_event_id);
        }
        catch (\Throwable $e)
        {
            log_message('error', 'Google Calendar sync failed for task ' . $task->id . ': ' . $e->getMessage());
        }
    }
}
