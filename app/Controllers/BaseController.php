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
    protected $helpers = ['form', 'html', 'security'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->session = service('session');
        
        // Set secure headers
        $this->response->setHeader('X-Frame-Options', 'SAMEORIGIN');
        $this->response->setHeader('X-Content-Type-Options', 'nosniff');
        $this->response->setHeader('X-XSS-Protection', '1; mode=block');
        $this->response->setHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }
    
    /**
     * Validate session security
     */
    protected function validateSession()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        
        // Check if user agent changed (potential session hijacking)
        $currentUserAgent = $this->request->getUserAgent();
        $sessionUserAgent = $this->session->get('user_agent');
        
        if ($sessionUserAgent && $sessionUserAgent !== (string)$currentUserAgent) {
            $this->session->destroy();
            return redirect()->to('/login')->with('error', 'Sesi tidak valid. Silakan login kembali.');
        }
        
        // Check session timeout (8 hours)
        $loginTime = $this->session->get('login_time');
        if ($loginTime && (time() - $loginTime) > 28800) {
            $this->session->destroy();
            return redirect()->to('/login')->with('error', 'Sesi telah berakhir. Silakan login kembali.');
        }
        
        return null;
    }
    
    /**
     * Sanitize output to prevent XSS attacks
     */
    protected function sanitizeOutput($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitizeOutput'], $data);
        }
        
        if (is_string($data)) {
            return esc($data);
        }
        
        return $data;
    }
    
    /**
     * Validate and sanitize input
     */
    protected function validateAndSanitizeInput(array $rules, array $data = null)
    {
        $data = $data ?? $this->request->getPost();
        
        // Sanitize input data
        $sanitizedData = [];
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $sanitizedData[$key] = trim(strip_tags($value));
            } else {
                $sanitizedData[$key] = $value;
            }
        }
        
        // Validate
        if (!$this->validate($rules, $sanitizedData)) {
            return false;
        }
        
        return $sanitizedData;
    }
}
