<?php
// Script untuk membuat konfigurasi production untuk InfinityFree

echo "=== BUAT KONFIGURASI PRODUCTION ===\n\n";

// Template Database.php untuk InfinityFree
$databaseConfig = '<?php

namespace Config;

use CodeIgniter\Database\Config;

class Database extends Config
{
    /**
     * The default database connection.
     */
    public array $default = [
        \'DSN\'          => \'\',
        \'hostname\'     => \'sql200.infinityfree.com\', // Ganti sesuai server InfinityFree
        \'username\'     => \'if0_XXXXXXX\',              // Ganti dengan username database Anda
        \'password\'     => \'PASSWORD_ANDA\',            // Ganti dengan password database Anda
        \'database\'     => \'if0_XXXXXXX_sdngu09\',      // Ganti dengan nama database Anda
        \'DBDriver\'     => \'MySQLi\',
        \'DBPrefix\'     => \'\',
        \'pConnect\'     => false,
        \'DBDebug\'      => false, // Set false untuk production
        \'charset\'      => \'utf8\',
        \'DBCollat\'     => \'utf8_general_ci\',
        \'swapPre\'      => \'\',
        \'encrypt\'      => false,
        \'compress\'     => false,
        \'strictOn\'     => false,
        \'failover\'     => [],
        \'port\'         => 3306,
        \'numberNative\' => false,
        \'dateFormat\'   => [
            \'date\'     => \'Y-m-d\',
            \'datetime\' => \'Y-m-d H:i:s\',
            \'time\'     => \'H:i:s\',
        ],
    ];

    /**
     * This database connection is used when running PHPUnit database tests.
     */
    public array $tests = [
        \'DSN\'         => \'\',
        \'hostname\'    => \'127.0.0.1\',
        \'username\'    => \'\',
        \'password\'    => \'\',
        \'database\'    => \':memory:\',
        \'DBDriver\'    => \'SQLite3\',
        \'DBPrefix\'    => \'db_\',  // Needed to ensure we\'re working correctly with prefixes live. DO NOT REMOVE FOR CI DEVS
        \'pConnect\'    => false,
        \'DBDebug\'     => true,
        \'charset\'     => \'utf8\',
        \'DBCollat\'    => \'utf8_general_ci\',
        \'swapPre\'     => \'\',
        \'encrypt\'     => false,
        \'compress\'    => false,
        \'strictOn\'    => false,
        \'failover\'    => [],
        \'port\'        => 3306,
        \'numberNative\' => false,
    ];

    public function __construct()
    {
        parent::__construct();

        // Ensure that we always set the database group to \'tests\' if
        // we are currently running an automated test suite, so that
        // we don\'t accidentally overwrite live data on accident.
        if (ENVIRONMENT === \'testing\') {
            $this->defaultGroup = \'tests\';
        }
    }
}
';

// Buat folder deployment/app/Config jika belum ada
$configPath = __DIR__ . '/deployment/app/Config';
if (!is_dir($configPath)) {
    mkdir($configPath, 0755, true);
}

file_put_contents($configPath . '/Database.php', $databaseConfig);
echo "✓ Created production Database.php config\n";

// Template App.php untuk InfinityFree
$appConfig = '<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    /**
     * Base Site URL - GANTI DENGAN DOMAIN INFINITYFREE ANDA
     */
    public string $baseURL = \'https://yourdomain.infinityfreeapp.com/\'; // GANTI INI!

    /**
     * Allowed Hostnames in the URL other than the value of baseURL.
     */
    public array $allowedHostnames = [];

    /**
     * Index File
     */
    public string $indexPage = \'\';

    /**
     * URI PROTOCOL
     */
    public string $uriProtocol = \'REQUEST_URI\';

    /**
     * Default Locale
     */
    public string $defaultLocale = \'en\';

    /**
     * Negotiate Locale
     */
    public bool $negotiateLocale = false;

    /**
     * Supported Locales
     */
    public array $supportedLocales = [\'en\'];

    /**
     * Application Timezone
     */
    public string $appTimezone = \'Asia/Jakarta\';

    /**
     * Default Character Set
     */
    public string $charset = \'UTF-8\';

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
    public string $CSRFProtection = \'cookie\';

    /**
     * CSRF Token Randomization
     */
    public bool $CSRFTokenRandomize = false;

    /**
     * CSRF Token Name
     */
    public string $CSRFTokenName = \'csrf_token_name\';

    /**
     * CSRF Header Name
     */
    public string $CSRFHeaderName = \'X-CSRF-TOKEN\';

    /**
     * CSRF Cookie Name
     */
    public string $CSRFCookieName = \'csrf_cookie_name\';

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
    public ?string $CSRFSameSite = \'Lax\';

    /**
     * Content Security Policy
     */
    public bool $CSPEnabled = false;
}
';

file_put_contents($configPath . '/App.php', $appConfig);
echo "✓ Created production App.php config\n";

// Buat file env production
$envProduction = '
#--------------------------------------------------------------------
# ENVIRONMENT PRODUCTION - INFINITYFREE
#--------------------------------------------------------------------

CI_ENVIRONMENT = production

#--------------------------------------------------------------------
# APP
#--------------------------------------------------------------------

app.baseURL = \'https://yourdomain.infinityfreeapp.com/\'
app.forceGlobalSecureRequests = true

#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------

database.default.hostname = sql200.infinityfree.com
database.default.database = if0_XXXXXXX_sdngu09
database.default.username = if0_XXXXXXX
database.default.password = PASSWORD_ANDA
database.default.DBDriver = MySQLi
database.default.DBPrefix = 
database.default.port = 3306

#--------------------------------------------------------------------
# SECURITY
#--------------------------------------------------------------------

encryption.key = 

#--------------------------------------------------------------------
# SESSION
#--------------------------------------------------------------------

session.driver = files
session.cookieName = ci_session
session.expiration = 7200
session.savePath = 
session.matchIP = false
session.timeToUpdate = 300
session.regenerateDestroy = false

#--------------------------------------------------------------------
# LOGGER
#--------------------------------------------------------------------

logger.threshold = 4
';

file_put_contents(__DIR__ . '/deployment/env', $envProduction);
echo "✓ Created production .env file\n";

// Buat petunjuk instalasi
$installGuide = '
# PETUNJUK INSTALASI KE INFINITYFREE

## 1. SETUP ACCOUNT INFINITYFREE
- Daftar di https://infinityfree.net
- Buat subdomain atau gunakan domain sendiri
- Catat detail database MySQL yang diberikan

## 2. UPDATE KONFIGURASI
Edit file berikut dengan detail akun InfinityFree Anda:

### deployment/app/Config/Database.php
- hostname: sql200.infinityfree.com (atau sesuai yang diberikan)
- username: if0_XXXXXXX (ganti dengan username Anda)
- password: PASSWORD_ANDA (ganti dengan password database Anda)
- database: if0_XXXXXXX_sdngu09 (ganti dengan nama database Anda)

### deployment/app/Config/App.php
- baseURL: https://yourdomain.infinityfreeapp.com/ (ganti dengan domain Anda)

### deployment/env
- Sesuaikan semua pengaturan dengan detail akun Anda

## 3. EXPORT DATABASE
- Export database lokal menggunakan phpMyAdmin
- Import ke database InfinityFree via control panel

## 4. UPLOAD FILES
Gunakan File Manager atau FTP:
- Upload folder htdocs/ ke public_html/
- Upload folder app/ ke root directory
- Upload folder vendor/ ke root directory  
- Upload folder writable/ ke root directory
- Upload file env ke root sebagai .env

## 5. SET PERMISSIONS
- writable/: 755
- writable/cache/: 755
- writable/logs/: 755
- writable/session/: 755
- writable/uploads/: 755

## 6. TESTING
- Test akses website: https://yourdomain.infinityfreeapp.com
- Test login admin: username: admin
- Test login walikelas: username: 199303292019031...
- Test fitur absensi dan rekap

## CATATAN PENTING
- InfinityFree tidak support SSH dan composer
- Upload folder vendor/ yang sudah di-generate
- Database prefix WAJIB if0_XXXXXXX_
- Max 5GB storage, 20GB bandwidth/month
- PHP max_execution_time: 30 detik
';

file_put_contents(__DIR__ . '/deployment/INSTALL_GUIDE.txt', $installGuide);
echo "✓ Created installation guide\n";

echo "\n=== KONFIGURASI PRODUCTION SELESAI ===\n";
echo "File yang dibuat:\n";
echo "- deployment/app/Config/Database.php (UPDATE REQUIRED)\n";
echo "- deployment/app/Config/App.php (UPDATE REQUIRED)\n";
echo "- deployment/env (UPDATE REQUIRED)\n";
echo "- deployment/INSTALL_GUIDE.txt\n\n";
echo "LANGKAH SELANJUTNYA:\n";
echo "1. Edit file konfigurasi dengan detail InfinityFree Anda\n";
echo "2. Jalankan prepare_deployment.php\n";
echo "3. Copy folder app/, vendor/, writable/ ke deployment/\n";
echo "4. Upload ke InfinityFree\n";
?>
