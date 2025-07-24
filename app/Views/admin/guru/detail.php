<?= $this->extend('admin/layout'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Detail Data Guru</h3>
                    <div class="btn-group">
                        <a href="<?= base_url('admin/guru/edit/' . $guru['id']); ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="<?= base_url('admin/guru'); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    
                    <!-- Data Pribadi -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-user"></i> Data Pribadi
                            </h5>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Nama Lengkap</strong></td>
                                    <td>: <?= esc($guru['nama']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Kelamin</strong></td>
                                    <td>: 
                                        <?php if ($guru['jk'] == 'L'): ?>
                                            <span class="badge bg-primary">Laki-laki</span>
                                        <?php elseif ($guru['jk'] == 'P'): ?>
                                            <span class="badge bg-pink">Perempuan</span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Tempat Lahir</strong></td>
                                    <td>: <?= esc($guru['tempat_lahir']) ?: '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Lahir</strong></td>
                                    <td>: <?= $guru['tanggal_lahir'] ? date('d/m/Y', strtotime($guru['tanggal_lahir'])) : '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Agama</strong></td>
                                    <td>: <?= esc($guru['agama']) ?: '-'; ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>NIK</strong></td>
                                    <td>: <?= esc($guru['nik']) ?: '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>No. KK</strong></td>
                                    <td>: <?= esc($guru['no_kk']) ?: '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>NPWP</strong></td>
                                    <td>: <?= esc($guru['npwp']) ?: '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Kewarganegaraan</strong></td>
                                    <td>: <?= esc($guru['kewarganegaraan']) ?: '-'; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Data Kepegawaian -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-id-card"></i> Data Kepegawaian
                            </h5>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>NIP</strong></td>
                                    <td>: <?= esc($guru['nip']) ?: '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>NUPTK</strong></td>
                                    <td>: <?= esc($guru['nuptk']) ?: '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Status Kepegawaian</strong></td>
                                    <td>: 
                                        <?php if ($guru['status_kepegawaian']): ?>
                                            <span class="badge bg-info"><?= esc($guru['status_kepegawaian']); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis PTK</strong></td>
                                    <td>: <?= esc($guru['jenis_ptk']) ?: '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Tugas Mengajar</strong></td>
                                    <td>: <?= esc($guru['tugas_mengajar']) ?: '-'; ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Pangkat/Golongan</strong></td>
                                    <td>: <?= esc($guru['pangkat_golongan']) ?: '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Status Keaktifan</strong></td>
                                    <td>: <?= esc($guru['status_keaktifan']) ?: '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Sumber Gaji</strong></td>
                                    <td>: <?= esc($guru['sumber_gaji']) ?: '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>TMT PNS</strong></td>
                                    <td>: <?= $guru['tmt_pns'] ? date('d/m/Y', strtotime($guru['tmt_pns'])) : '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>TMT Pengangkatan</strong></td>
                                    <td>: <?= $guru['tmt_pengangkatan'] ? date('d/m/Y', strtotime($guru['tmt_pengangkatan'])) : '-'; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Kontak -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-phone"></i> Kontak
                            </h5>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Telepon</strong></td>
                                    <td>: <?= esc($guru['telepon']) ?: '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>HP</strong></td>
                                    <td>: <?= esc($guru['hp']) ?: '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Email</strong></td>
                                    <td>: <?= esc($guru['email']) ?: '-'; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-map-marker-alt"></i> Alamat
                            </h5>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="20%"><strong>Alamat Jalan</strong></td>
                                    <td>: <?= esc($guru['alamat_jalan']) ?: '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>RT/RW</strong></td>
                                    <td>: <?= esc($guru['rt']) ?: '-'; ?> / <?= esc($guru['rw']) ?: '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Dusun</strong></td>
                                    <td>: <?= esc($guru['nama_dusun']) ?: '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Desa/Kelurahan</strong></td>
                                    <td>: <?= esc($guru['desa_kelurahan']) ?: '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Kecamatan</strong></td>
                                    <td>: <?= esc($guru['kecamatan']) ?: '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Kode Pos</strong></td>
                                    <td>: <?= esc($guru['kode_pos']) ?: '-'; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Data Keluarga -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-users"></i> Data Keluarga
                            </h5>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="20%"><strong>Nama Suami/Istri</strong></td>
                                    <td>: <?= esc($guru['nama_suami_istri']) ?: '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>NIP Suami/Istri</strong></td>
                                    <td>: <?= esc($guru['nip_suami_istri']) ?: '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Pekerjaan Suami/Istri</strong></td>
                                    <td>: <?= esc($guru['pekerjaan_suami_istri']) ?: '-'; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Data Keuangan -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-credit-card"></i> Data Keuangan
                            </h5>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Bank</strong></td>
                                    <td>: <?= esc($guru['bank']) ?: '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Nomor Rekening</strong></td>
                                    <td>: <?= esc($guru['nomor_rekening']) ?: '-'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Rekening Atas Nama</strong></td>
                                    <td>: <?= esc($guru['rekening_atas_nama']) ?: '-'; ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Nama Wajib Pajak</strong></td>
                                    <td>: <?= esc($guru['nama_wajib_pajak']) ?: '-'; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-pink {
    background-color: #e91e63 !important;
}
</style>
<?= $this->endSection(); ?>
