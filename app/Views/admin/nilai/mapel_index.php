<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div x-data="mapelPage()" x-init="init()" class="space-y-8">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 bg-clip-text text-transparent">Manajemen Mata Pelajaran</h1>
        <button @click="openModal()" class="px-4 py-2 rounded-xl bg-gradient-to-r from-indigo-600 to-fuchsia-600 text-white font-semibold shadow hover:scale-[1.02] active:scale-95">Tambah Mapel</button>
    </div>
    <div class="bg-white rounded-2xl shadow p-6 border border-slate-200">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left bg-slate-100/70">
                    <th class="p-2 rounded-l-lg">#</th>
                    <th class="p-2">Nama Mapel</th>
                    <th class="p-2">Untuk Kelas</th>
                    <th class="p-2 rounded-r-lg">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($subjects)): ?>
                <tr><td colspan="4" class="p-4 text-center text-slate-500">Belum ada data mapel.</td></tr>
                <?php endif; ?>
                <?php foreach($subjects as $i=>$s): $grades = array_filter(explode(',',$s['grades']??'')); ?>
                <tr class="border-b last:border-none">
                    <td class="p-2 text-slate-500"><?= $i+1 ?></td>
                    <td class="p-2 font-medium"><?= esc($s['name']) ?></td>
                    <td class="p-2">
                        <?php if($grades): ?>
                            <div class="flex flex-wrap gap-1">
                            <?php foreach($grades as $g): ?>
                                <span class="px-2 py-0.5 text-[11px] bg-indigo-100 text-indigo-700 rounded-full font-semibold"><?= esc($g) ?></span>
                            <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <span class="text-slate-400 italic">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="p-2 text-xs text-slate-400">(edit nanti)</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm">
        <div @click.outside="closeModal()" class="w-full max-w-md bg-white rounded-2xl shadow-xl p-6 relative">
            <button @click="closeModal()" class="absolute top-2 right-2 text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
            <h2 class="text-lg font-semibold mb-4">Tambah Mata Pelajaran</h2>
            <form @submit.prevent="submit()" class="space-y-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-indigo-600 mb-1">Mata Pelajaran</label>
                    <select x-model="form.name" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Pilih --</option>
                        <?php foreach($master as $m): ?>
                        <option value="<?= esc($m) ?>"><?= esc($m) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-indigo-600 mb-2">Untuk Kelas</label>
                    <div class="grid grid-cols-3 gap-2 text-sm">
                        <?php for($k=1;$k<=6;$k++): ?>
                        <label class="flex items-center gap-2 p-2 rounded-lg border hover:bg-indigo-50 cursor-pointer">
                            <input type="checkbox" :value="<?= $k ?>" x-model="form.grades" class="rounded text-indigo-600 focus:ring-indigo-500" />
                            <span>Kelas <?= $k ?></span>
                        </label>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="closeModal()" class="px-4 py-2 rounded-lg text-slate-500 hover:text-slate-700">Batal</button>
                    <button type="submit" :disabled="loading" class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-indigo-600 to-fuchsia-600 text-white font-semibold shadow disabled:opacity-40">
                        <span x-show="!loading">Simpan</span>
                        <span x-show="loading" class="flex items-center gap-2"><i class="fas fa-spinner fa-spin"></i> Menyimpan...</span>
                    </button>
                </div>
                <p x-show="error" x-text="error" class="text-sm text-red-600"></p>
            </form>
        </div>
    </div>
</div>
<script>
function mapelPage(){
    return {
        showModal:false,
        loading:false,
        error:'',
        form:{ name:'', grades:[] },
        init(){},
        openModal(){ this.error=''; this.form={ name:'', grades:[] }; this.showModal=true; },
        closeModal(){ if(!this.loading) this.showModal=false; },
        async submit(){
            if(!this.form.name){ this.error='Pilih nama mata pelajaran.'; return; }
            if(this.form.grades.length===0){ this.error='Pilih minimal satu kelas.'; return; }
            this.loading=true; this.error='';
            try {
                const resp = await fetch('<?= base_url('admin/mapel/store') ?>',{ method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json'}, body: JSON.stringify({ name:this.form.name, grades:this.form.grades }) });
                const data = await resp.json();
                if(!resp.ok || data.status!=='success'){ this.error=data.message||'Gagal menyimpan'; }
                else { window.location.reload(); }
            } catch(e){ this.error=e.message; }
            finally { this.loading=false; }
        }
    }
}
</script>
<?= $this->endSection() ?>
