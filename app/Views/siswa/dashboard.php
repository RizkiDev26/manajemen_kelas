<?= $this->extend('layouts/siswa_layout') ?>
<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div x-data="dashboardApp()" x-init="init()" class="space-y-10">
    <!-- Header Title (plain white like screenshot) -->
    <h1 class="text-3xl md:text-4xl font-bold leading-tight">Selamat Datang, <span x-text="studentName"></span></h1>

    <!-- Video + Progress Pills Layout -->
    <section class="grid lg:grid-cols-2 gap-10 items-start">
        <!-- Video Column -->
    <div class="space-y-4" x-ref="videoColumn">
            <div class="relative rounded-xl border border-slate-200 bg-white shadow overflow-hidden flex items-center justify-center p-2">
                <!-- Skeleton while video loading -->
                <div x-show="!videoLoaded" class="w-[720px] h-[400px] max-w-full bg-slate-200/70 animate-pulse flex items-center justify-center text-slate-500 text-lg select-none rounded-lg">Memuat Video...</div>
                <div class="w-[720px] h-[400px] max-w-full" x-show="videoLoaded" x-cloak x-ref="videoFrame">
                    <iframe :src="videoSrc" width="720" height="400" class="w-full h-full rounded-lg" title="Video Kebiasaan" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen @load="videoLoaded=true; measureVideo()"></iframe>
                </div>
                <!-- Fallback frame if no video ID configured -->
                <template x-if="!videoId">
                    <div class="absolute inset-0 flex items-center justify-center flex-col gap-4 pointer-events-none">
                        <div class="w-24 h-24 rounded-full bg-white/70 flex items-center justify-center shadow-inner"><i class="fa-solid fa-play text-3xl text-slate-600"></i></div>
                        <p class="font-semibold text-slate-700 text-xl">Frame Video Youtube</p>
                    </div>
                </template>
            </div>
            <p class="text-xl font-medium tracking-tight">7 Kebiasaan Anak Indonesia Hebat</p>
        </div>

        <!-- Progress Pills Column -->
        <div class="w-full flex lg:justify-start">
            <!-- Progress container: remove fixed height & overflow to avoid clipping; make responsive -->
            <div class="relative rounded-3xl p-8 pt-10 bg-gradient-to-br from-white via-indigo-50/60 to-fuchsia-50/60 border border-slate-200 shadow-sm flex flex-col w-full max-w-[720px] min-h-[400px]" x-ref="progressBox">
                <div class="absolute -top-16 -right-10 w-64 h-64 bg-gradient-to-br from-fuchsia-200/40 to-rose-300/40 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-20 -left-10 w-72 h-72 bg-gradient-to-tr from-indigo-200/40 to-emerald-200/30 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative text-center mb-6">
                    <div class="inline-flex items-center gap-3 px-10 py-4 rounded-full bg-gradient-to-r from-amber-300 via-amber-200 to-amber-300 font-bold text-xl tracking-tight text-slate-800 shadow-lg ring-1 ring-amber-400/40">
                        <i class="fa-solid fa-bolt text-amber-600"></i>
                        <span>Progress Hari ini</span>
                    </div>
                    <div class="mt-4 text-sm font-medium tracking-tight text-slate-600" x-show="!loadingProgress" x-cloak>
                        <span x-text="todayDone"></span> / 7 Kebiasaan • <span x-text="todayPercent+'%'"></span>
                    </div>
                    <div class="mt-4 flex justify-center" x-show="loadingProgress" x-cloak>
                        <div class="h-4 w-40 rounded bg-amber-200/60 animate-pulse"></div>
                    </div>
                    <!-- Action Buttons moved here -->
                    <div class="mt-5 flex flex-wrap justify-center gap-3">
                        <a href="<?= base_url('siswa/habits') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-fuchsia-600 text-white font-semibold shadow hover:shadow-lg hover:-translate-y-0.5 transition">
                            <i class="fa-solid fa-pen-to-square"></i> <span>Input Hari Ini</span>
                        </a>
                        <a href="<?= base_url('siswa/habits/monthly-report') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 font-semibold shadow-sm hover:bg-slate-50 transition">
                            <i class="fa-solid fa-calendar-days"></i> <span>Rekap Bulanan</span>
                        </a>
                    </div>
                </div>
                <!-- Skeleton Pills 3x3 -->
                <div x-show="loadingProgress" class="grid grid-cols-3 gap-4 flex-1" x-cloak>
                    <template x-for="i in 9" :key="'skel-'+i">
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-100/70 animate-pulse h-20"></div>
                    </template>
                </div>
                <!-- Actual Pills: 3 x 3 grid (last 2 empty slots) -->
                <div x-show="!loadingProgress" class="grid grid-cols-3 gap-4 relative flex-1" x-cloak>
                    <template x-for="i in 9" :key="'pill-'+i">
                        <template x-if="habitStatus[i-1]">
                            <button @click="goInput()" class="group relative w-full h-20 rounded-2xl px-4 py-3 text-[13px] font-semibold tracking-tight flex flex-col items-start justify-center gap-2 border transition shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/40"
                                    :class="habitStatus[i-1].done ? 'bg-emerald-500/10 border-emerald-300 text-emerald-700 hover:bg-emerald-500/15' : 'bg-white border-slate-300 hover:bg-slate-50 hover:border-slate-400'" :aria-pressed="habitStatus[i-1].done">
                                <div class="flex items-center gap-3 w-full">
                                    <span class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center text-base shadow-inner" :class="habitStatus[i-1].done ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-600 group-hover:bg-slate-300'">
                                        <i class="fa-solid" :class="iconFor(habitStatus[i-1].id)"></i>
                                    </span>
                                    <span class="flex-1 text-left" x-text="habitStatus[i-1].name"></span>
                                    <span x-show="habitStatus[i-1].done" x-cloak class="relative inline-flex items-center justify-center ml-auto">
                                        <span class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs shadow">✓</span>
                                    </span>
                                </div>
                            </button>
                        </template>
                        <template x-if="!habitStatus[i-1]">
                            <div class="w-full h-20 rounded-2xl border border-dashed border-slate-300 bg-white/40 flex items-center justify-center text-slate-300 text-sm select-none">—</div>
                        </template>
                    </template>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Stats with Skeleton -->
    <section class="grid md:grid-cols-3 gap-6">
        <!-- Streak -->
        <div class="rounded-2xl p-6 bg-gradient-to-br from-white to-indigo-50 border border-indigo-100 shadow-sm flex flex-col gap-3 relative">
            <div class="flex items-center gap-3 text-indigo-600">
                <span class="w-11 h-11 rounded-xl flex items-center justify-center bg-indigo-100"><i class="fa-solid fa-fire text-lg"></i></span>
                <h3 class="font-semibold tracking-tight">Streak Beruntun</h3>
            </div>
            <div x-show="loadingStats" class="h-10 w-32 rounded-lg bg-indigo-100/60 animate-pulse" x-cloak></div>
            <div x-show="!loadingStats" x-cloak class="text-3xl font-bold" x-text="stats.current_streak + ' Hari'"></div>
            <p class="text-xs text-slate-500">Hari berturut-turut dengan performa bagus.</p>
            <div class="mt-auto text-[11px] text-indigo-600/70">Terbaik: <span x-show="!loadingStats" x-text="stats.best_streak + ' hari'" x-cloak></span><span x-show="loadingStats" class="inline-block h-3 w-14 bg-indigo-100/60 animate-pulse rounded" x-cloak></span></div>
        </div>
        <!-- Weekly -->
        <div class="rounded-2xl p-6 bg-gradient-to-br from-white to-fuchsia-50 border border-fuchsia-100 shadow-sm flex flex-col gap-3 relative">
            <div class="flex items-center gap-3 text-fuchsia-600">
                <span class="w-11 h-11 rounded-xl flex items-center justify-center bg-fuchsia-100"><i class="fa-solid fa-bullseye text-lg"></i></span>
                <h3 class="font-semibold tracking-tight">Progress Mingguan</h3>
            </div>
            <div x-show="loadingStats" class="h-10 w-28 rounded-lg bg-fuchsia-100/60 animate-pulse" x-cloak></div>
            <div x-show="!loadingStats" x-cloak class="text-3xl font-bold" x-text="weeklyPercent + '%'" :class="weeklyPercent >=80 ? 'text-fuchsia-600' : 'text-fuchsia-700'"></div>
            <div class="h-2 rounded-full bg-fuchsia-100 overflow-hidden">
                <div class="h-full bg-gradient-to-r from-fuchsia-500 to-rose-500 transition-all duration-500" :style="'width:'+(loadingStats?0:weeklyPercent)+'%'"></div>
            </div>
            <p class="text-xs text-slate-500">
                <span x-show="loadingStats" class="inline-block h-3 w-40 bg-fuchsia-100/60 animate-pulse rounded" x-cloak></span>
                <span x-show="!loadingStats" x-text="stats.weekly_progress.completed + ' dari ' + stats.weekly_progress.total + ' kebiasaan potensial'" x-cloak></span>
            </p>
        </div>
        <!-- Today -->
        <div class="rounded-2xl p-6 bg-gradient-to-br from-white to-rose-50 border border-rose-100 shadow-sm flex flex-col gap-3 relative">
            <div class="flex items-center gap-3 text-rose-600">
                <span class="w-11 h-11 rounded-xl flex items-center justify-center bg-rose-100"><i class="fa-solid fa-star text-lg"></i></span>
                <h3 class="font-semibold tracking-tight">Target Hari Ini</h3>
            </div>
            <div class="flex items-end gap-2">
                <div x-show="loadingProgress" class="h-10 w-16 bg-rose-100/60 rounded-lg animate-pulse" x-cloak></div>
                <div x-show="!loadingProgress" x-cloak class="text-3xl font-bold" x-text="todayDone+'/7'"></div>
                <div x-show="!loadingProgress" x-cloak class="text-sm text-slate-500 mb-1" x-text="todayPercent+'%'"></div>
            </div>
            <div class="h-2 rounded-full bg-rose-100 overflow-hidden">
                <div class="h-full bg-gradient-to-r from-rose-500 to-indigo-500 transition-all duration-500" :style="'width:'+(loadingProgress?0:todayPercent)+'%'"></div>
            </div>
            <p class="text-xs text-slate-500">Lengkapi semua 7 kebiasaan untuk hari ini!</p>
        </div>
    </section>

    <!-- Progress Hari Ini Pills -->
    <section class="rounded-3xl p-6 md:p-8 bg-white border border-slate-200 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <h2 class="text-xl font-semibold tracking-tight flex items-center gap-2"><i class="fa-solid fa-circle-check text-indigo-600"></i> Progress Hari Ini</h2>
            <a href="<?= base_url('siswa/habits') ?>" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 flex items-center gap-1"><i class="fa-solid fa-pen"></i> Input Sekarang</a>
        </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <template x-for="h in habitStatus" :key="'grid-'+h.id">
                <div @click="goInput()" class="group cursor-pointer rounded-2xl p-4 border flex flex-col gap-3 transition hover:shadow-md"
                     :class="h.done ? 'bg-gradient-to-br from-emerald-50 to-white border-emerald-200' : 'bg-slate-50 border-slate-200 hover:bg-white'">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white shadow"
                             :class="h.done ? 'bg-emerald-500' : 'bg-slate-300 text-slate-600'">
                            <i class="fa-solid" :class="h.done ? 'fa-check' : 'fa-minus'"></i>
                        </div>
                        <div class="font-medium text-sm leading-snug" x-text="h.name"></div>
                    </div>
                    <div class="pl-1 text-[11px] tracking-wide uppercase font-semibold"
                         :class="h.done ? 'text-emerald-600' : 'text-slate-400'"
                         x-text="h.done ? 'SELESAI' : 'BELUM'"></div>
                </div>
            </template>
        </div>
    </section>

    <!-- Motivasi / Tips (optional simple rotation) -->
    <section class="rounded-3xl p-6 md:p-8 bg-gradient-to-br from-indigo-50 via-fuchsia-50 to-rose-50 border border-slate-200/60">
        <h2 class="text-xl font-semibold mb-4 tracking-tight bg-gradient-to-r from-indigo-600 via-fuchsia-600 to-rose-600 bg-clip-text text-transparent">Tips Kebiasaan Hebat</h2>
        <p class="text-slate-600 leading-relaxed" x-text="currentTip"></p>
    </section>
</div>

<script>
function dashboardApp(){
    return {
        studentName: <?= json_encode(session('student_name') ?? session('username') ?? 'Siswa') ?>,
        habits: <?= json_encode(array_map(fn($h)=>['id'=>$h['id'],'name'=>$h['name']], $habits ?? [])) ?>,
        habitStatus: [],
        stats: {current_streak:0, best_streak:0, weekly_progress:{completed:0,total:0}},
        todayDone: 0,
        todayPercent: 0,
        weeklyPercent: 0,
        loadingStats: true,
        loadingProgress: true,
    videoId: 'lv1f_2jHOtI', // YouTube video ID
        videoLoaded: false,
    videoHeight: null, // legacy
        tips: [
            'Bangun lebih awal membuatmu lebih siap menghadapi hari.',
            'Sholat tepat waktu melatih disiplin dan ketenangan.',
            'Olahraga ringan 15 menit cukup menjaga kebugaran.',
            'Konsumsi makanan sehat memberi energi untuk belajar.',
            'Luangkan waktu membaca atau belajar hal baru setiap hari.',
            'Berinteraksi positif dengan teman membangun karakter sosial.',
            'Istirahat cukup penting untuk pertumbuhan dan konsentrasi.'
        ],
        currentTip: '',
        init(){
            this.currentTip = this.tips[Math.floor(Math.random()*this.tips.length)];
            this.habitStatus = this.habits.map(h=>({...h, done:false}));
            this.fetchToday();
            this.fetchStats();
        },
        get videoSrc(){
            if(!this.videoId) return '';
            return 'https://www.youtube.com/embed/'+this.videoId+'?rel=0&modestbranding=1&playsinline=1';
        },
        async fetchToday(){
            try {
                const today = new Date().toISOString().slice(0,10);
                const res = await fetch('<?= base_url('siswa/summary') ?>?date='+today);
                const json = await res.json();
                const rows = json.data || [];
                const doneSet = new Set(rows.filter(r=>Number(r.value_bool)===1 || r.value_time || (r.value_number && r.value_number>0)).map(r=>Number(r.habit_id)));
                this.habitStatus = this.habitStatus.map(h=>({...h, done: doneSet.has(Number(h.id))}));
                this.todayDone = this.habitStatus.filter(h=>h.done).length;
                this.todayPercent = Math.round((this.todayDone/7)*100);
                this.loadingProgress = false;
            } catch(e){ console.error(e); }
        },
        async fetchStats(){
            try {
                const res = await fetch('<?= base_url('siswa/stats') ?>');
                const json = await res.json();
                Object.assign(this.stats, json);
                const wp = this.stats.weekly_progress;
                this.weeklyPercent = wp.total ? Math.round((wp.completed/wp.total)*100) : 0;
                this.loadingStats = false;
            } catch(e){ console.error(e); }
        },
        goInput(){ window.location.href='<?= base_url('siswa/habits') ?>'; },
        iconFor(id){
            switch(Number(id)){
                case 1: return 'fa-sun';
                case 2: return 'fa-mosque';
                case 3: return 'fa-dumbbell';
                case 4: return 'fa-apple-whole';
                case 5: return 'fa-book-open';
                case 6: return 'fa-people-group';
                case 7: return 'fa-moon';
                default: return 'fa-circle';
            }
        },
        measureVideo(){
            this.$nextTick(()=>{
                const el = this.$refs.videoFrame;
                if(el){
                    const h = el.clientHeight; // aspect-video actual height
                    if(h && Math.abs(h - this.videoHeight) > 5){
                        this.videoHeight = h + 32; // add a little padding allowance
                    }
                }
            });
        }
    }
}
</script>
<?= $this->endSection() ?>
