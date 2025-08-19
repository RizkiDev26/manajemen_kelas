# PowerShell script to generate self-signed SSL certificate for localhost
# This script uses Windows built-in certificate tools

Write-Host "Generating self-signed SSL certificate for localhost..." -ForegroundColor Green

# Create certificates directory if it doesn't exist
if (!(Test-Path "certificates")) {
    New-Item -ItemType Directory -Path "certificates"
    Write-Host "Created certificates directory" -ForegroundColor Yellow
}

try {
    # Generate self-signed certificate using PowerShell
    $cert = New-SelfSignedCertificate -DnsName "localhost" -CertStoreLocation "cert:\LocalMachine\My" -KeyAlgorithm RSA -KeyLength 2048 -Provider "Microsoft RSA SChannel Cryptographic Provider" -KeyExportPolicy Exportable -KeyUsage DigitalSignature,KeyEncipherment -Type SSLServerAuthentication

    # Export certificate to file
    $certPath = "certificates\localhost.crt"
    $keyPath = "certificates\localhost.key"
    
    # Export certificate
    Export-Certificate -Cert $cert -FilePath $certPath -Type CERT
    
    # Export private key (requires password for security)
    $password = ConvertTo-SecureString -String "localhost" -Force -AsPlainText
    Export-PfxCertificate -Cert $cert -FilePath "certificates\localhost.pfx" -Password $password
    
    Write-Host "SSL certificate generated successfully!" -ForegroundColor Green
    Write-Host "Certificate: $certPath" -ForegroundColor Cyan
    Write-Host "PFX file: certificates\localhost.pfx (password: localhost)" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Note: The certificate has been installed in the Local Machine certificate store." -ForegroundColor Yellow
    Write-Host "You may need to trust it in your browser or export it to PEM format for some servers." -ForegroundColor Yellow
    Write-Host ""
    Write-Host "To start the HTTPS server, run: .\start-https-server.bat" -ForegroundColor Green
    
} catch {
    Write-Host "Error generating certificate: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host ""
    Write-Host "Alternative: Install OpenSSL and run generate-ssl-cert.bat" -ForegroundColor Yellow
    Write-Host "Download OpenSSL from: https://slproweb.com/products/Win32OpenSSL.html" -ForegroundColor Cyan
}

Write-Host ""
Write-Host "Press any key to continue..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
