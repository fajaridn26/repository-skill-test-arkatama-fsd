@props(['tanggal', 'bulan', 'tahun'])
<!-- Metric Group Four -->
<div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
    <div class="grid grid-cols-1 gap-8 md:grid-cols-3 sm:grid-cols-2">
        <div x-data="filterTanggal('{{ $tanggal }}')">
            <h3 class="text-gray-800 dark:text-gray-300">Filter Tanggal</h3>
            <div class="relative mt-4">
                <input type="date" name="tanggal_sewa" x-model="tanggal" @change="filterHari"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                    onclick="this.showPicker()" />
                <span class="absolute top-1/2 right-3.5 -translate-y-1/2 pointer-events-none">
                    <svg class="fill-gray-700 dark:fill-gray-400" width="14" height="14" viewBox="0 0 14 14"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M4.33317 0.0830078C4.74738 0.0830078 5.08317 0.418794 5.08317 0.833008V1.24967H8.9165V0.833008C8.9165 0.418794 9.25229 0.0830078 9.6665 0.0830078C10.0807 0.0830078 10.4165 0.418794 10.4165 0.833008V1.24967L11.3332 1.24967C12.2997 1.24967 13.0832 2.03318 13.0832 2.99967V4.99967V11.6663C13.0832 12.6328 12.2997 13.4163 11.3332 13.4163H2.6665C1.70001 13.4163 0.916504 12.6328 0.916504 11.6663V4.99967V2.99967C0.916504 2.03318 1.70001 1.24967 2.6665 1.24967L3.58317 1.24967V0.833008C3.58317 0.418794 3.91896 0.0830078 4.33317 0.0830078ZM4.33317 2.74967H2.6665C2.52843 2.74967 2.4165 2.8616 2.4165 2.99967V4.24967H11.5832V2.99967C11.5832 2.8616 11.4712 2.74967 11.3332 2.74967H9.6665H4.33317ZM11.5832 5.74967H2.4165V11.6663C2.4165 11.8044 2.52843 11.9163 2.6665 11.9163H11.3332C11.4712 11.9163 11.5832 11.8044 11.5832 11.6663V5.74967Z"
                            fill="" />
                    </svg>
                </span>
            </div>
            <div
                class="mt-4 rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
                <h4 class="text-title-sm font-bold text-teal-500 dark:text-teal-500"
                    x-text="new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(total)">
                </h4>

                <div class="mt-4 flex items-end justify-between sm:mt-5">
                    <div>
                        <p class="text-theme-sm text-gray-700 dark:text-gray-400">
                            Pendapatan Hari <span class="font-semibold dark:text-gray-300"
                                x-text="tanggalFormatted"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div x-data="filterBulan('{{ $bulan }}')">
            <h3 class="text-gray-800 dark:text-gray-300">Filter Bulan</h3>
            <div class="relative mt-4">
                <input type="month" x-model="bulan" @change="filterBulan"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                    onclick="this.showPicker()" />
            </div>
            <div
                class="mt-4 rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
                <h4 class="text-title-sm font-bold text-teal-500 dark:text-teal-500"
                    x-text="new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(total)">
                </h4>

                <div class="mt-4 flex items-end justify-between sm:mt-5">
                    <div>
                        <p class="text-theme-sm text-gray-700 dark:text-gray-400">
                            Pendapatan Bulan <span class="font-semibold dark:text-gray-300"
                                x-text="bulanFormatted"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div x-data="filterTahun('{{ $tahun }}')">
            <h3 class="text-gray-800 dark:text-gray-300">Filter Tahun</h3>
            <div class="relative mt-4">
                <select name="year" id="year" x-model="tahun" @change="filterTahun"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    @php
                        $currentYear = date('Y');
                        $startYear = $currentYear - 5;
                    @endphp

                    @for ($i = $currentYear; $i >= $startYear; $i--)
                        <option value="{{ $i }}" {{ old('year') == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
                <span
                    class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-700 dark:text-gray-400">
                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
            </div>
            <div
                class="mt-4 rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
                <h4 class="text-title-sm font-bold text-teal-500 dark:text-teal-500"
                    x-text="new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(total)">
                </h4>

                <div class="mt-4 flex items-end justify-between sm:mt-5">
                    <div>
                        <p class="text-theme-sm text-gray-700 dark:text-gray-400">
                            Pendapatan Tahun <span class="font-semibold dark:text-gray-300" x-text="tahun"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
    x-data="grafikPendapatan('{{ $tahun }}')">
    <div class="flex border-b border-gray-100 dark:border-gray-800 items-center justify-between mb-4 p-5">
        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Grafik Pendapatan</h3>
        <div class="relative">
            <select x-model="tahun"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                @change="grafikPendapatan">
                @php
                    $currentYear = date('Y');
                    $startYear = $currentYear - 5;
                @endphp

                @for ($i = $currentYear; $i >= $startYear; $i--)
                    <option value="{{ $i }}" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                        {{ $i }}
                    </option>
                @endfor
            </select>
            <span
                class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-700 dark:text-gray-400">
                <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        </div>
    </div>

    <div class="custom-scrollbar max-w-full overflow-x-auto">
        <div id="chartOne" class="min-w-[1000px]"></div>
    </div>
</div>

<div class="col-span-12 max-w-full overflow-x-auto custom-scrollbar" x-data="jadwalLapangan('{{ $tanggal }}')">
    <div
        class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-8 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="items-center text-center sm:flex-row sm:items-center sm:justify-between">
            <div class="border-b border-gray-100 dark:border-gray-800">
                <h3 class="items-center text-center text-xl font-semibold text-gray-800 dark:text-gray-300">
                    Jadwal Lapangan Badminton
                </h3>
                <div class="mt-3 mb-4">
                    <input type="date" name="tanggal_sewa" x-model="tanggal" @change="fetchJadwal"
                        onclick="this.showPicker()"
                        class="dark:bg-gray-900 dark:text-gray-300 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 appearance-none rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent py-2.5 pl-6 text-sm text-gray-800" />

                </div>
                <p class="text-gray-700 dark:text-gray-200 text-sm font-regular mt-2 mb-4" x-text="tanggalFormatted">
                </p>
            </div>
            <div class="inline-flex gap-8 mt-8 dark:text-gray-300">
                <div class="flex flex-row gap-4">
                    <span class="px-3 py-3 bg-gray-600 rounded-md"></span>
                    <h5 class="font-light">Tersedia</h5>
                </div>
                <div class="flex flex-row gap-4">
                    <span class="px-3 py-3 bg-orange-600 rounded-md"></span>
                    <h5 class="font-light">Dipesan</h5>
                </div>
            </div>
        </div>

        @php
            $totalLapangan = 4;
        @endphp

        <div class="flex grid grid-cols-1 gap-8 md:grid-cols-4 sm:grid-cols-2 sm:gap-8 mb-8 mt-16">
            @for ($lapangan = 1; $lapangan <= $totalLapangan; $lapangan++)
                <div class="flex flex-col items-center">
                    <h4 class="dark:text-gray-300 mb-4">Lapangan {{ $lapangan }}</h4>
                    <div class="flex grid grid-cols-3 px-6 gap-4 mt-4">
                        @foreach (jam_sewa() as $jam)
                            <div class="flex justify-center p-3 text-white rounded-lg"
                                :class="isBooked({{ $lapangan }}, {{ $jam }}) ? 'bg-orange-600' : 'bg-gray-600'">
                                <h4>{{ format_jam($jam) }}</h4>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>



<script>
    function jadwalLapangan(tanggalLapangan) {
        return {
            tanggal: tanggalLapangan,
            tanggalFormatted: '',
            jadwal: [],

            init() {
                this.formatTanggal();
                this.fetchJadwal();
            },

            fetchJadwal() {
                fetch(`/jadwal?tanggal_sewa=${this.tanggal}`)
                    .then(res => res.json())
                    .then(data => {
                        this.jadwal = data.jadwal;
                        this.formatTanggal();
                    })
            },

            formatTanggal() {
                if (!this.tanggal) return;

                const date = new Date(this.tanggal);

                this.tanggalFormatted = date.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
            },

            isBooked(lapangan, jam) {
                return this.jadwal.some(item =>
                    item.nomor_lapangan == lapangan &&
                    jam >= item.jam_awal_sewa && jam < item.jam_akhir_sewa
                )
            },
        }
    }

    function filterTanggal(tanggal) {
        return {
            tanggal: tanggal,
            tanggalFormatted: '',
            total: 0,

            init() {
                this.formatTanggal();
                this.filterHari();
            },

            filterHari() {
                fetch(`/filterHari?tanggal_sewa=${this.tanggal}`)
                    .then(res => res.json())
                    .then(data => {
                        this.tanggal = data.tanggal;
                        this.total = data.total;
                        this.formatTanggal();
                    })
            },

            formatTanggal() {
                if (!this.tanggal) return;

                const date = new Date(this.tanggal);

                this.tanggalFormatted = date.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
            },
        }
    }

    function filterBulan(bulan) {
        return {
            bulan: bulan,
            bulanFormatted: '',
            total: 0,

            init() {
                this.formatBulan();
                this.filterBulan();
            },

            filterBulan() {
                fetch(`/filterBulan?bulan_sewa=${this.bulan}`)
                    .then(res => res.json())
                    .then(data => {
                        this.bulan = data.bulan;
                        this.total = data.total;
                        this.formatBulan();
                    })
            },

            formatBulan() {
                if (!this.bulan) return;

                const month = new Date(this.bulan);

                this.bulanFormatted = month.toLocaleDateString('id-ID', {
                    month: 'long',
                    year: 'numeric'
                });
            },
        }
    }

    function filterTahun(tahun) {
        return {
            tahun: tahun,
            total: 0,

            init() {
                this.filterTahun();
            },

            filterTahun() {
                fetch(`/filterTahun?tahun_sewa=${this.tahun}`)
                    .then(res => res.json())
                    .then(data => {
                        this.tahun = data.tahun;
                        this.total = data.total;
                    })
            },
        }
    }

    function grafikPendapatan(tahun) {
        return {
            tahun: tahun,

            init() {
                const waitChart = setInterval(() => {
                    if (window.chartOne) {
                        clearInterval(waitChart);
                        this.grafikPendapatan();
                    }
                }, 50);
            },

            grafikPendapatan() {
                fetch(`/grafikPendapatan?tahun_sewa=${this.tahun}`)
                    .then(res => res.json())
                    .then(res => {
                        window.chartOne.updateSeries([{
                            name: "Pendapatan",
                            data: res.data,
                        }]);
                    });
            },
        }
    }
</script>
