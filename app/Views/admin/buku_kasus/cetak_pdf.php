<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kasus Siswa - <?= esc($kasus['nama_siswa']) ?></title>
    <style>
        @page {
            margin: 2cm;
            size: A4;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        
        .header h2 {
            font-size: 14pt;
            margin: 5px 0;
            font-weight: normal;
        }
        
        .header p {
            margin: 2px 0;
            font-size: 10pt;
        }
        
        .content {
            margin: 20px 0;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
            border-bottom: 1px solid #666;
            padding-bottom: 5px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .info-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        
        .info-table .label {
            width: 30%;
            font-weight: bold;
        }
        
        .info-table .separator {
            width: 5%;
            text-align: center;
        }
        
        .info-table .value {
            width: 65%;
        }
        
        .description-box {
            border: 1px solid #333;
            padding: 15px;
            margin: 10px 0;
            min-height: 80px;
            background-color: #f9f9f9;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border: 1px solid #333;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-belum { background-color: #ffebee; }
        .status-proses { background-color: #fff3e0; }
        .status-selesai { background-color: #e8f5e8; }
        
        .severity-ringan { background-color: #e8f5e8; }
        .severity-sedang { background-color: #fff3e0; }
        .severity-berat { background-color: #ffebee; }
        
        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        
        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .signature-table td {
            width: 50%;
            padding: 20px;
            text-align: center;
            vertical-align: top;
        }
        
        .signature-box {
            border: 1px solid #333;
            height: 80px;
            margin: 10px 0;
            position: relative;
        }
        
        .signature-name {
            font-weight: bold;
            margin-top: 10px;
        }
        
        .signature-title {
            font-style: italic;
            font-size: 10pt;
        }
        
        .footer {
            position: fixed;
            bottom: 1cm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9pt;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Laporan Kasus Siswa</h1>
        <h2>SD Negeri Gunung 09</h2>
        <p>Jl. Raya Gunung No. 123, Gunung, Kec. Sindang, Kab. Indramayu</p>
        <p>Telp: (0234) 123456 | Email: sdngunung09@gmail.com</p>
    </div>

    <!-- Content -->
    <div class="content">
        <!-- Data Siswa -->
        <div class="section">
            <div class="section-title">Data Siswa</div>
            <table class="info-table">
                <tr>
                    <td class="label">Nama Lengkap</td>
                    <td class="separator">:</td>
                    <td class="value"><?= esc($kasus['nama_siswa']) ?></td>
                </tr>
                <tr>
                    <td class="label">NIS</td>
                    <td class="separator">:</td>
                    <td class="value"><?= esc($kasus['nis']) ?></td>
                </tr>
                <tr>
                    <td class="label">Kelas</td>
                    <td class="separator">:</td>
                    <td class="value"><?= esc($kasus['kelas']) ?></td>
                </tr>
                <?php if (!empty($kasus['jenis_kelamin'])): ?>
                <tr>
                    <td class="label">Jenis Kelamin</td>
                    <td class="separator">:</td>
                    <td class="value"><?= $kasus['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($kasus['tempat_lahir']) && !empty($kasus['tanggal_lahir'])): ?>
                <tr>
                    <td class="label">Tempat, Tanggal Lahir</td>
                    <td class="separator">:</td>
                    <td class="value"><?= esc($kasus['tempat_lahir']) ?>, <?= date('d F Y', strtotime($kasus['tanggal_lahir'])) ?></td>
                </tr>
                <?php endif; ?>
            </table>
        </div>

        <!-- Data Kasus -->
        <div class="section">
            <div class="section-title">Data Kasus</div>
            <table class="info-table">
                <tr>
                    <td class="label">Tanggal Kejadian</td>
                    <td class="separator">:</td>
                    <td class="value"><?= date('d F Y', strtotime($kasus['tanggal_kejadian'])) ?></td>
                </tr>
                <tr>
                    <td class="label">Jenis Kasus</td>
                    <td class="separator">:</td>
                    <td class="value"><?= esc($kasus['jenis_kasus']) ?></td>
                </tr>
                <tr>
                    <td class="label">Tingkat Keparahan</td>
                    <td class="separator">:</td>
                    <td class="value">
                        <?php
                        $severityClass = match($kasus['tingkat_keparahan']) {
                            'Ringan' => 'severity-ringan',
                            'Sedang' => 'severity-sedang',
                            'Berat' => 'severity-berat',
                            default => ''
                        };
                        ?>
                        <span class="status-badge <?= $severityClass ?>"><?= esc($kasus['tingkat_keparahan']) ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="label">Status</td>
                    <td class="separator">:</td>
                    <td class="value">
                        <?php
                        $statusClass = match($kasus['status']) {
                            'belum_ditangani' => 'status-belum',
                            'dalam_proses' => 'status-proses',
                            'selesai' => 'status-selesai',
                            default => ''
                        };
                        $statusText = match($kasus['status']) {
                            'belum_ditangani' => 'Belum Ditangani',
                            'dalam_proses' => 'Dalam Proses',
                            'selesai' => 'Selesai',
                            default => 'Unknown'
                        };
                        ?>
                        <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="label">Pelapor</td>
                    <td class="separator">:</td>
                    <td class="value"><?= esc($kasus['nama_guru']) ?></td>
                </tr>
            </table>
        </div>

        <!-- Deskripsi Kasus -->
        <div class="section">
            <div class="section-title">Deskripsi Kasus</div>
            <div class="description-box">
                <?= nl2br(esc($kasus['deskripsi_kasus'])) ?>
            </div>
        </div>

        <!-- Tindakan yang Diambil -->
        <?php if (!empty($kasus['tindakan_yang_diambil'])): ?>
        <div class="section">
            <div class="section-title">Tindakan yang Diambil</div>
            <div class="description-box">
                <?= nl2br(esc($kasus['tindakan_yang_diambil'])) ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Catatan Guru -->
        <?php if (!empty($kasus['catatan_guru'])): ?>
        <div class="section">
            <div class="section-title">Catatan Guru</div>
            <div class="description-box">
                <?= nl2br(esc($kasus['catatan_guru'])) ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Tanda Tangan -->
        <div class="signature-section">
            <div class="section-title">Tanda Tangan</div>
            <table class="signature-table">
                <tr>
                    <td>
                        <div>Mengetahui,</div>
                        <div><strong>Kepala Sekolah</strong></div>
                        <div class="signature-box"></div>
                        <div class="signature-name">(__________________)</div>
                        <div class="signature-title">NIP. ________________</div>
                    </td>
                    <td>
                        <div>Gunung, <?= date('d F Y') ?></div>
                        <div><strong>Guru Pelapor</strong></div>
                        <div class="signature-box"></div>
                        <div class="signature-name"><?= esc($kasus['nama_guru']) ?></div>
                        <div class="signature-title">NIP. ________________</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dicetak pada <?= date('d F Y H:i:s') ?> | Halaman 1 dari 1</p>
    </div>
</body>
</html>
