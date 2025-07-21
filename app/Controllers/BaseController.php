<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['error_handling', 'validation', 'cache', 'form', 'url', 'html'];

    /**
     * Session instance
     */
    protected $session;

    /**
     * Current user data
     */
    protected $currentUser = null;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->session = service('session');
        
        // Load current user data if logged in
        $this->loadCurrentUser();
        
        // Set common view data
        $this->setCommonViewData();
    }

    /**
     * Load current user data from session
     */
    protected function loadCurrentUser(): void
    {
        if ($this->session->get('logged_in')) {
            $this->currentUser = [
                'id' => $this->session->get('user_id'),
                'username' => $this->session->get('username'),
                'role' => $this->session->get('role'),
                'nama_lengkap' => $this->session->get('nama_lengkap')
            ];
        }
    }

    /**
     * Set common view data available to all controllers
     */
    protected function setCommonViewData(): void
    {
        $data = [
            'current_user' => $this->currentUser,
            'is_logged_in' => $this->session->get('logged_in') ?? false,
            'school_profile' => cache_school_profile()
        ];

        // Make data available to all views
        $this->response->setVar($data);
    }

    /**
     * Check if user is logged in, redirect if not
     */
    protected function requireLogin(): bool
    {
        if (!$this->session->get('logged_in')) {
            $this->session->setFlashdata('error', 'Anda harus login terlebih dahulu.');
            return redirect()->to('/login');
        }
        return true;
    }

    /**
     * Check if user has required role
     */
    protected function requireRole(array $allowedRoles): bool
    {
        if (!$this->requireLogin()) {
            return false;
        }

        $userRole = $this->session->get('role');
        if (!in_array($userRole, $allowedRoles)) {
            $this->session->setFlashdata('error', 'Anda tidak memiliki akses untuk halaman ini.');
            return redirect()->to('/dashboard');
        }
        return true;
    }

    /**
     * Handle errors consistently
     */
    protected function handleError(\Exception $e, string $context = 'operation', string $redirect = null)
    {
        $message = handle_db_error($e, $context);
        $this->session->setFlashdata('error', $message);
        
        if ($redirect) {
            return redirect()->to($redirect);
        }
        
        return redirect()->back();
    }

    /**
     * Validate CSRF token manually if needed
     */
    protected function validateCSRF(): bool
    {
        if (!$this->request->is('post')) {
            return true;
        }

        $security = service('security');
        $token = $this->request->getPost($security->getTokenName());
        
        if (!$security->validateToken($token)) {
            $this->session->setFlashdata('error', 'Token keamanan tidak valid. Silakan coba lagi.');
            return false;
        }
        
        return true;
    }

    /**
     * Log user activity
     */
    protected function logActivity(string $action, string $details = ''): void
    {
        log_user_activity($action, $details, $this->currentUser['id'] ?? null);
    }

    /**
     * Render view with error handling
     */
    protected function renderView(string $view, array $data = [], array $options = [])
    {
        try {
            return view($view, $data, $options);
        } catch (\Exception $e) {
            log_message('error', "View rendering error for {$view}: " . $e->getMessage());
            
            if (ENVIRONMENT === 'development') {
                throw $e;
            }
            
            // Show generic error view
            return view('errors/html/generic_error', [
                'message' => 'Terjadi kesalahan dalam menampilkan halaman.'
            ]);
        }
    }

    /**
     * JSON response helper with error handling
     */
    protected function jsonResponse(array $data = [], int $status = 200)
    {
        return $this->response
            ->setStatusCode($status)
            ->setJSON($data);
    }

    /**
     * Success JSON response
     */
    protected function successResponse(string $message, array $data = [])
    {
        return $this->jsonResponse([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ]);
    }

    /**
     * Error JSON response
     */
    protected function errorResponse(string $message, array $errors = [], int $status = 400)
    {
        return $this->jsonResponse([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $status);
    }
}
