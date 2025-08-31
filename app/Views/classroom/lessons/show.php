<?php $isStudentView = ($role==='siswa'); ?>
<?= $this->extend($isStudentView ? 'layouts/siswa_layout' : 'admin/layout') ?>
<?= $this->section('content') ?>
<div class="px-4 sm:px-6 lg:px-8 py-6 max-w-[1600px]">
    <div class="grid lg:grid-cols-12 gap-10">
        <!-- Header area spanning full width so sidebar starts aligned with content box -->
        <div class="lg:col-span-12">
            <div class="mb-6 flex items-center justify-between">
                <a href="/classroom/lessons" class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-500 font-medium"><i class="fas fa-arrow-left text-xs"></i> Kembali</a>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold tracking-wide <?= $lesson['visibility']==='published' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>">
                    <?= strtoupper($lesson['visibility']) ?>
                </span>
            </div>
            <h1 class="text-3xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-blue-600 leading-tight mb-3">
                <?= esc($lesson['judul']) ?>
            </h1>
            <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 mb-2">
                <span class="inline-flex items-center gap-1"><i class="fas fa-layer-group"></i> Kelas <?= esc($lesson['kelas']) ?></span>
                <?php if($lesson['published_at']): ?><span class="inline-flex items-center gap-1"><i class="fas fa-check-circle"></i> Dipublish <?= esc($lesson['published_at']) ?></span><?php endif; ?>
            </div>
        </div>
        <!-- Main content column -->
        <div class="<?= ($role==='siswa') ? 'lg:col-span-12' : 'lg:col-span-9' ?>">
    <div class="prose max-w-none bg-white/90 backdrop-blur p-6 rounded-2xl border border-gray-100 shadow-sm">
    <?= $lesson['konten'] ?>
    </div>
    <?php if(!empty($lesson['video_url'])): ?>
    <div class="mt-8">
        <h3 class="font-semibold mb-3 text-sm uppercase tracking-wide text-gray-600">Video Pembelajaran</h3>
        <?php
            $url = $lesson['video_url'];
            $embed = null;
            if(preg_match('~youtu(?:\.be|be\.com)/(?:watch\?v=|embed/|shorts/)?([A-Za-z0-9_-]{6,})~',$url,$m)){
                $vid = $m[1];
                $embed = 'https://www.youtube.com/embed/'.$vid;
            }
        ?>
        <?php if($embed): ?>
            <div class="aspect-video rounded-xl overflow-hidden shadow border border-gray-200">
                <iframe src="<?= esc($embed) ?>" class="w-full h-full" allowfullscreen loading="lazy"></iframe>
            </div>
        <?php else: ?>
            <a href="<?= esc($url) ?>" target="_blank" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-500 text-sm font-medium"><i class="fas fa-play-circle"></i> Buka Video</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php if (!empty($attachments)): ?>
    <div class="mt-8">
        <h3 class="font-semibold mb-3 text-sm uppercase tracking-wide text-gray-600">Lampiran</h3>
        <div class="grid sm:grid-cols-2 gap-3">
            <?php foreach($attachments as $att): ?>
            <a class="group flex items-center gap-3 p-3 rounded-xl border border-gray-200 bg-white hover:border-indigo-300 hover:bg-indigo-50/50 transition" href="/classroom/attachment/<?= $att['id'] ?>/download">
                <div class="w-10 h-10 flex-shrink-0 bg-gradient-to-br from-indigo-500 to-blue-500 text-white rounded-lg flex items-center justify-center shadow">
                    <i class="fas fa-file text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate group-hover:text-indigo-700"><?= esc($att['original_name']) ?></p>
                    <p class="text-[11px] text-gray-400">
                        <?php if($att['size_bytes']) echo round($att['size_bytes']/1024).' KB'; else echo 'File'; ?>
                    </p>
                </div>
                <i class="fas fa-download text-xs text-indigo-400 group-hover:text-indigo-600"></i>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    </div>
    <?php if($role !== 'siswa'): ?>
    <!-- Sidebar Right: Viewers aligned with top of content box -->
    <div class="lg:col-span-3 space-y-6">
            <div class="bg-white/90 backdrop-blur rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-600 flex items-center gap-2"><i class="fas fa-user-check text-indigo-500"></i> Siswa Membaca</h3>
                    <span class="text-[11px] px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600 font-semibold"><?= isset($viewers)?count($viewers):0 ?></span>
                </div>
                <?php if (empty($viewers)): ?>
                    <p class="text-xs text-gray-400">Belum ada siswa yang membuka materi ini.</p>
                <?php else: ?>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach($viewers as $vw): $nm = trim($vw['nama'] ?? 'Siswa'); ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold tracking-wide bg-green-100 text-green-700 shadow-sm">
                                <i class="fas fa-user text-[10px] mr-1 opacity-70"></i><?= esc($nm) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
