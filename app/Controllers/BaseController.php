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
    protected $helpers = [];

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
    }

    /**
     * Handle database errors and log them
     *
     * @param \Exception $e
     * @param string $context
     * @return void
     */
    protected function handleDatabaseError(\Exception $e, string $context = '')
    {
        $message = $context ? "$context: {$e->getMessage()}" : $e->getMessage();
        log_message('error', "Database Error - $message", [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
    }

    /**
     * Handle general application errors and log them
     *
     * @param \Exception $e
     * @param string $context
     * @return void
     */
    protected function handleApplicationError(\Exception $e, string $context = '')
    {
        $message = $context ? "$context: {$e->getMessage()}" : $e->getMessage();
        log_message('error', "Application Error - $message", [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
    }

    /**
     * Get fallback data when primary data fails to load
     *
     * @param string $type
     * @return array
     */
    protected function getFallbackData(string $type): array
    {
        switch ($type) {
            case 'berita':
                return [
                    [
                        'id' => 0,
                        'judul' => 'Selamat Datang di Website SDN Grogol Utara 09',
                        'isi' => 'Website resmi SDN Grogol Utara 09. Pantau terus update terbaru dari sekolah kami.',
                        'tanggal' => date('Y-m-d'),
                        'gambar' => ''
                    ]
                ];
            case 'dashboard_stats':
                return [
                    'totalGuru' => 0,
                    'totalWalikelas' => 0,
                    'totalUsers' => 0,
                    'totalSiswa' => 0,
                    'siswaLaki' => 0,
                    'siswaPerempuan' => 0,
                    'recentGuru' => [],
                    'recentSiswa' => []
                ];
            default:
                return [];
        }
    }
}
