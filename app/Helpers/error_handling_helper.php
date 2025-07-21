<?php

/**
 * Error Handling Helper
 * 
 * Provides consistent error handling and fallback content
 * for the classroom management system
 */

if (! function_exists('handle_db_error')) {
    /**
     * Handle database errors with proper logging and user-friendly messages
     *
     * @param Exception $e
     * @param string $context
     * @return string
     */
    function handle_db_error(\Exception $e, string $context = 'database operation'): string
    {
        // Log the actual error for debugging
        log_message('error', "Database error in {$context}: " . $e->getMessage());
        
        if (ENVIRONMENT === 'development') {
            return "Database Error: " . $e->getMessage();
        }
        
        return "Terjadi kesalahan dalam mengakses data. Silakan coba lagi atau hubungi administrator.";
    }
}

if (! function_exists('safe_data_fetch')) {
    /**
     * Safely fetch data with fallback
     *
     * @param callable $fetchFunction
     * @param mixed $fallback
     * @param string $context
     * @return mixed
     */
    function safe_data_fetch(callable $fetchFunction, $fallback = [], string $context = 'data fetch')
    {
        try {
            return $fetchFunction();
        } catch (\Exception $e) {
            log_message('error', "Error in {$context}: " . $e->getMessage());
            return $fallback;
        }
    }
}

if (! function_exists('fallback_content')) {
    /**
     * Provide fallback content for missing data
     *
     * @param string $type
     * @return string
     */
    function fallback_content(string $type = 'general'): string
    {
        $fallbacks = [
            'students' => '<div class="p-4 bg-gray-100 text-center"><p>Belum ada data siswa tersedia.</p></div>',
            'grades' => '<div class="p-4 bg-gray-100 text-center"><p>Belum ada data nilai tersedia.</p></div>',
            'attendance' => '<div class="p-4 bg-gray-100 text-center"><p>Belum ada data absensi tersedia.</p></div>',
            'news' => '<div class="p-4 bg-gray-100 text-center"><p>Belum ada berita tersedia.</p></div>',
            'general' => '<div class="p-4 bg-gray-100 text-center"><p>Data tidak tersedia saat ini.</p></div>'
        ];
        
        return $fallbacks[$type] ?? $fallbacks['general'];
    }
}

if (! function_exists('safe_display')) {
    /**
     * Safely display data with HTML escaping
     *
     * @param mixed $data
     * @param string $default
     * @return string
     */
    function safe_display($data, string $default = '-'): string
    {
        if (empty($data) || is_null($data)) {
            return esc($default);
        }
        
        return esc($data);
    }
}

if (! function_exists('validate_and_sanitize')) {
    /**
     * Validate and sanitize input data
     *
     * @param array $data
     * @param array $rules
     * @return array
     */
    function validate_and_sanitize(array $data, array $rules = []): array
    {
        $validation = \Config\Services::validation();
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            // Basic sanitization
            if (is_string($value)) {
                $sanitized[$key] = trim(strip_tags($value));
            } else {
                $sanitized[$key] = $value;
            }
        }
        
        // Apply validation rules if provided
        if (!empty($rules)) {
            $validation->setRules($rules);
            if (!$validation->run($sanitized)) {
                log_message('warning', 'Validation failed: ' . implode(', ', $validation->getErrors()));
            }
        }
        
        return $sanitized;
    }
}

if (! function_exists('check_permissions')) {
    /**
     * Check user permissions for action
     *
     * @param string $action
     * @param int|null $userId
     * @return bool
     */
    function check_permissions(string $action, ?int $userId = null): bool
    {
        // Basic permission check - can be expanded based on role system
        $session = session();
        
        if (!$session->get('logged_in')) {
            return false;
        }
        
        $userRole = $session->get('role') ?? 'guest';
        
        // Define basic permissions
        $permissions = [
            'admin' => ['create', 'read', 'update', 'delete', 'manage'],
            'guru' => ['read', 'update'],
            'walikelas' => ['read', 'update'],
            'guest' => []
        ];
        
        return in_array($action, $permissions[$userRole] ?? []);
    }
}

if (! function_exists('log_user_activity')) {
    /**
     * Log user activity for audit trail
     *
     * @param string $action
     * @param string $details
     * @param int|null $userId
     * @return void
     */
    function log_user_activity(string $action, string $details = '', ?int $userId = null): void
    {
        $session = session();
        $userId = $userId ?? $session->get('user_id');
        $username = $session->get('username') ?? 'Unknown';
        
        $logMessage = sprintf(
            'User Activity - User: %s (ID: %d) | Action: %s | Details: %s | IP: %s',
            $username,
            $userId ?? 0,
            $action,
            $details,
            \CodeIgniter\HTTP\IncomingRequest::createFromGlobals()->getIPAddress()
        );
        
        log_message('info', $logMessage);
    }
}