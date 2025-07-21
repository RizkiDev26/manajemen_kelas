<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    /**
     * Base Site URL - GANTI DENGAN DOMAIN INFINITYFREE ANDA
     */
    public string $baseURL = 'https://sdngu09.rf.gd/'; // GANTI INI!

    /**
     * Allowed Hostnames in the URL other than the value of baseURL.
     */
    public array $allowedHostnames = [];

    /**
     * Index File
     */
    public string $indexPage = '';

    /**
     * URI PROTOCOL
     */
    public string $uriProtocol = 'REQUEST_URI';

    /**
     * Default Locale
     */
    public string $defaultLocale = 'en';

    /**
     * Negotiate Locale
     */
    public bool $negotiateLocale = false;

    /**
     * Supported Locales
     */
    public array $supportedLocales = ['en'];

    /**
     * Application Timezone
     */
    public string $appTimezone = 'Asia/Jakarta';

    /**
     * Default Character Set
     */
    public string $charset = 'UTF-8';

    /**
     * URI PROTOCOL
     */
    public bool $forceGlobalSecureRequests = true; // HTTPS untuk production

    /**
     * Reverse Proxy IPs
     */
    public array $proxyIPs = [];

    /**
     * CSRF Protection Method
     */
    public string $CSRFProtection = 'cookie';

    /**
     * CSRF Token Randomization
     */
    public bool $CSRFTokenRandomize = false;

    /**
     * CSRF Token Name
     */
    public string $CSRFTokenName = 'csrf_token_name';

    /**
     * CSRF Header Name
     */
    public string $CSRFHeaderName = 'X-CSRF-TOKEN';

    /**
     * CSRF Cookie Name
     */
    public string $CSRFCookieName = 'csrf_cookie_name';

    /**
     * CSRF Expire
     */
    public int $CSRFExpire = 7200;

    /**
     * CSRF Regenerate
     */
    public bool $CSRFRegenerate = true;

    /**
     * CSRF Exclude URIs
     */
    public array $CSRFExcludeURIs = [];

    /**
     * CSRF SameSite
     */
    public ?string $CSRFSameSite = 'Lax';

    /**
     * Content Security Policy
     */
    public bool $CSPEnabled = false;
}
