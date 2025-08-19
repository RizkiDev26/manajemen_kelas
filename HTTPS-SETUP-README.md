# HTTPS Setup Guide for CodeIgniter 4 on Port 443

This guide will help you configure your CodeIgniter 4 application to run on HTTPS port 443.

## What Has Been Changed

1. **App Configuration Updated:**
   - `baseURL` changed from `http://localhost:8080/` to `https://localhost:443/`
   - `forceGlobalSecureRequests` enabled to force HTTPS connections
   - All requests will now be redirected to HTTPS

## Setup Options

### Option 1: Using PHP Built-in Development Server (Recommended for Development)

#### Step 1: Generate SSL Certificates
```bash
# Run this command to generate self-signed SSL certificates
generate-ssl-cert.bat
```

This will create:
- `certificates/localhost.crt` (SSL certificate)
- `certificates/localhost.key` (Private key)

#### Step 2: Start HTTPS Server
```bash
# Method A: Using batch script (Run as Administrator)
start-https-server.bat

# Method B: Using PHP script
php https-server.php

# Method C: Manual command (Run as Administrator)
php -S localhost:443 -t public/
```

**Important:** Port 443 requires administrator privileges on Windows.

### Option 2: Using Apache Web Server

#### Step 1: Generate SSL Certificates
Run `generate-ssl-cert.bat` as described above.

#### Step 2: Configure Apache
1. Copy `apache-https-vhost.conf` to your Apache `sites-available` directory
2. Enable SSL module: `a2enmod ssl`
3. Enable the site: `a2ensite apache-https-vhost.conf`
4. Restart Apache

#### Step 3: Update Apache Configuration
Make sure these modules are enabled in Apache:
- mod_ssl
- mod_rewrite
- mod_headers

### Option 3: Using Nginx (Alternative)

Create an Nginx configuration file:

```nginx
server {
    listen 443 ssl;
    server_name localhost;
    root C:/Users/acer/OneDrive/ci4-project/public;
    index index.php;

    ssl_certificate certificates/localhost.crt;
    ssl_certificate_key certificates/localhost.key;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}

# Redirect HTTP to HTTPS
server {
    listen 80;
    server_name localhost;
    return 301 https://$server_name$request_uri;
}
```

## Testing the Setup

1. **Start your chosen server method**
2. **Open your browser and navigate to:** `https://localhost:443/`
3. **Accept the security warning** (for self-signed certificates)
4. **Test login with:**
   - Username: `admin`
   - Password: `123456`

## Browser Security Warning

Since we're using self-signed certificates, your browser will show a security warning. This is normal for development. To proceed:

1. Click "Advanced" or "Show details"
2. Click "Proceed to localhost (unsafe)" or similar option
3. The application should load normally

## Production Considerations

For production environments:

1. **Use proper SSL certificates** from a trusted Certificate Authority (CA)
2. **Update the baseURL** to your actual domain name
3. **Configure proper firewall rules** for port 443
4. **Use a proper web server** (Apache/Nginx) instead of PHP's built-in server
5. **Implement additional security headers** and configurations

## Troubleshooting

### Common Issues:

1. **"Permission denied" on port 443:**
   - Run the server as Administrator on Windows
   - Use `sudo` on Linux/Mac

2. **"Address already in use":**
   - Another service is using port 443
   - Check if Apache/IIS is already running
   - Use `netstat -an | findstr :443` to check

3. **SSL certificate errors:**
   - Regenerate certificates using `generate-ssl-cert.bat`
   - Ensure certificate files exist in the `certificates/` directory

4. **Application not loading:**
   - Check that the `public/` directory contains `index.php`
   - Verify the baseURL in `app/Config/App.php`
   - Check server error logs

### Checking if Port 443 is Available:
```bash
netstat -an | findstr :443
```

### Testing SSL Certificate:
```bash
openssl x509 -in certificates/localhost.crt -text -noout
```

## Files Created

- `generate-ssl-cert.bat` - SSL certificate generation script
- `start-https-server.bat` - HTTPS server startup script
- `https-server.php` - PHP HTTPS server script
- `apache-https-vhost.conf` - Apache virtual host configuration
- `certificates/` - Directory for SSL certificates (created when running scripts)

## Security Notes

- Self-signed certificates are only suitable for development
- The generated certificates are valid for 365 days
- For production, always use certificates from a trusted CA
- Consider implementing additional security measures like HSTS, CSP, etc.

## Next Steps

After setting up HTTPS, you can:
1. Test all application functionality
2. Update any hardcoded HTTP URLs in your code
3. Configure additional security headers
4. Set up proper SSL certificates for production
