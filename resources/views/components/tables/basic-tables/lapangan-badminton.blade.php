@props(['bookings', 'tanggal'])
<div x-data='{
    bookings: @json($bookings->items()),
    perPage: @json($bookings->perPage()),
    currentPage: @json($bookings->currentPage()),
    totalPages: @json($bookings->lastPage()),
    totalData: @json($bookings->total()),
    selectedBooking: null,
    selectedBookings: null,
    search: "",
    showAlert: false,
    alertMessage: "",
    alertType: "",
    handleSuccess(message) {
        this.alertMessage = message;
        this.showAlert = true;
        this.alertType = "success";
    },
    handleError(message) {
        this.alertMessage = message;
        this.showAlert = true;
        this.alertType = "error";
    },
    openBooking() {
        window.dispatchEvent(new CustomEvent("open-booking-modal"))
    },
    openUbahBooking(booking) {
        this.selectedBooking = {
        ...booking,
        durasi: `${booking.jam_akhir_sewa - booking.jam_awal_sewa}`,
        harga_sewa: 25000,
        };
        window.dispatchEvent(new CustomEvent("open-ubah-booking-modal"))
    },
    openPerbaruiStatus(booking) {
        this.selectedBooking = {
        ...booking,
        };
        window.dispatchEvent(new CustomEvent("open-perbarui-status-booking-modal"))
    },
    openModalJadwal(booking) {
        this.selectedBooking = {
        ...booking,
        };
        window.dispatchEvent(new CustomEvent("open-jadwal-modal"))
    },
    displayedPages: [],
        init() {
            this.updateDisplayedPages();

            const addMessage = sessionStorage.getItem("alert_add_success");
            const failMessage = sessionStorage.getItem("alert_fail_error");
            const editMessage = sessionStorage.getItem("alert_edit_success");
            const deleteMessage = sessionStorage.getItem("alert_delete_success");
            
            if(addMessage) {
                this.handleSuccess(addMessage);
                sessionStorage.removeItem("alert_add_success");
            } else if(editMessage) {
                this.handleSuccess(editMessage);
                sessionStorage.removeItem("alert_edit_success"); 
            } else if(failMessage) { 
                this.handleError(failMessage);
                sessionStorage.removeItem("alert_fail_error");
            } else if(deleteMessage) { 
                this.handleSuccess(deleteMessage);
                sessionStorage.removeItem("alert_delete_success");
            }
        },
    prevPage() {
            if(this.currentPage > 1){
                const prev = this.currentPage - 1;
                this.search ? this.searchBookings(prev) : this.fetchPage(prev)
            }
        },
        nextPage() {
            if(this.currentPage < this.totalPages){
                const next = this.currentPage + 1;
                this.search ? this.searchBookings(next) : this.fetchPage(next)
            }
        },
        goToPage(page) {
            if(this.search){
                this.searchBookings(page)
            } else {
                this.currentPage = page;
                this.fetchPage(page);
            }
        },
        updateDisplayedPages() {
            this.displayedPages = [];
            if (this.totalPages <= 5) {
                for (let i = 1; i <= this.totalPages; i++) this.displayedPages.push(i);
            } else {
                if (this.currentPage <= 3) {
                    this.displayedPages = [1,2,3,4,"...",this.totalPages];
                } else if (this.currentPage >= this.totalPages - 2) {
                    this.displayedPages = [1,"...",this.totalPages-3,this.totalPages-2,this.totalPages-1,this.totalPages];
                } else {
                    this.displayedPages = [1,"...",this.currentPage-1,this.currentPage,this.currentPage+1,"...",this.totalPages];
                }
            }
        },
        searchBookings(page = 1){
            if (!this.search) {
                this.fetchPage(1);
                return;
            }
        fetch(`/lapangan-badminton/search?query=${this.search}&page=${page}`, {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
                }
        })
                .then(res => res.json())
                .then(res => {
                this.bookings = res.data;
                this.currentPage = res.current_page;
                this.totalPages = res.last_page;
                this.perPage = res.per_page;
                this.totalData = res.total;
                this.updateDisplayedPages();
                })
                .catch(err => console.error(err));
        },
        fetchPage(page = 1) {
    fetch(`?page=${page}`, {
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        }
    })
    .then(res => res.json())
    .then(res => {
        this.bookings = res.original.data;
        this.currentPage = res.original.current_page;
        this.totalPages = res.original.last_page;
        this.perPage = res.original.per_page;
        this.totalData = res.original.total;
        this.updateDisplayedPages();
    });
}

}'
    x-init="init()">

    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <!-- Header -->
        <div class="flex flex-col gap-2 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Lapangan Badminton</h3>
            </div>
            <a href="#" @click="openModalJadwal" class="text-sm text-blue-500 hover:text-blue-700">Lihat Jadwal</a>
        </div>


        <!-- Table -->
        <div class="overflow-hidden">
            <div class="max-w-full px-5 overflow-x-auto">
                <div class="flex flex-col gap-3 justify-between sm:flex-row sm:items-center mb-4">
                    <div>
                        <x-ui.button @click="openBooking" size="sm" variant="primary">Booking</x-ui.button>
                    </div>
                    <form>
                        <div class="relative">
                            <button type="button" class="absolute -translate-y-1/2 left-4 top-1/2">
                                <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20"
                                    viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                                        fill="" />
                                </svg>
                            </button>
                            <input type="text" placeholder="Search..." x-model="search"
                                @input.debounce.400ms="searchBookings"
                                class="h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-[42px] pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-blue-800 xl:w-[300px]" />
                        </div>
                    </form>
                </div>
                <div x-show="showAlert" x-transition class="mb-4">
                    <template x-if="alertType === 'success'">
                        <x-ui.alert variant="success" title="Berhasil" :showLink="false">
                            <p class="text-sm text-gray-500 dark:text-gray-400" x-text="alertMessage"></p>
                        </x-ui.alert>
                    </template>
                    <template x-if="alertType === 'error'">
                        <x-ui.alert variant="error" title="Gagal" :showLink="false">
                            <p class="text-sm text-gray-500 dark:text-gray-400" x-text="alertMessage"></p>
                        </x-ui.alert>
                    </template>
                </div>
                <table class="min-w-full">
                    <thead>
                        <tr class="border-gray-200 border-y dark:border-gray-700">
                            <th scope="col"
                                class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                                No</th>
                            <th scope="col"
                                class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                                Nomor Lapangan</th>
                            <th scope="col"
                                class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                                Nama Penyewa</th>
                            <th scope="col"
                                class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 capitalize">
                                Tanggal Sewa</th>
                            <th scope="col"
                                class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 capitalize">
                                Jam Sewa</th>
                            <th scope="col"
                                class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 capitalize">
                                Durasi</th>
                            <th scope="col"
                                class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 capitalize">
                                Total Harga</th>
                            <th scope="col"
                                class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 capitalize">
                                Status</th>
                            <th scope="col" class="relative px-4 py-3 capitalize">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-for="(booking, index) in bookings" :key="booking.id">
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400"
                                        x-text="(currentPage - 1) * perPage + index + 1">
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400"
                                        x-text="booking.nomor_lapangan">
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-800 font-medium dark:text-gray-400"
                                        x-text="booking.nama_penyewa">
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400"
                                        x-text="formatTanggal(booking.tanggal_sewa)">
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400"
                                        x-text="`${booking.jam_awal_sewa} - ${booking.jam_akhir_sewa}`">
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400"
                                        x-text="`${booking.jam_akhir_sewa - booking.jam_awal_sewa} Jam`">
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400"
                                        x-text="new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(booking.total_harga_sewa)">
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-sm font-medium rounded-full"
                                        :class="{
                                            'bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-500': booking
                                                .status === 1,
                                            'bg-sky-50 text-sky-600 dark:bg-sky-500/15 dark:text-sky-500': booking
                                                .status === 2,
                                            'bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-500': booking
                                                .status === 3
                                        }"
                                        x-text="{1: 'Tersedia', 2: 'Dipesan', 3: 'Selesai', 4: 'Dibatalkan'}[booking.status]">
                                    </span>
                                </td>


                                <td class="px-4 py-4 text-sm font-medium text-right whitespace-nowrap">
                                    <div class="flex justify-center relative">
                                        <x-common.table-dropdown>
                                            <x-slot name="button">
                                                <button type="button" id="options-menu" aria-haspopup="true"
                                                    aria-expanded="true" class="text-gray-500 dark:text-gray-400'">
                                                    <svg class="fill-current" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M5.99902 10.245C6.96552 10.245 7.74902 11.0285 7.74902 11.995V12.005C7.74902 12.9715 6.96552 13.755 5.99902 13.755C5.03253 13.755 4.24902 12.9715 4.24902 12.005V11.995C4.24902 11.0285 5.03253 10.245 5.99902 10.245ZM17.999 10.245C18.9655 10.245 19.749 11.0285 19.749 11.995V12.005C19.749 12.9715 18.9655 13.755 17.999 13.755C17.0325 13.755 16.249 12.9715 16.249 12.005V11.995C16.249 11.0285 17.0325 10.245 17.999 10.245ZM13.749 11.995C13.749 11.0285 12.9655 10.245 11.999 10.245C11.0325 10.245 10.249 11.0285 10.249 11.995V12.005C10.249 12.9715 11.0325 13.755 11.999 13.755C12.9655 13.755 13.749 12.9715 13.749 12.005V11.995Z"
                                                            fill="currentColor" />
                                                    </svg>
                                                </button>
                                            </x-slot>

                                            <x-slot name="content">
                                                <a href="#" @click="openUbahBooking(booking)"
                                                    class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
                                                    role="menuitem">
                                                    Ubah Booking
                                                </a>
                                                <a href="#" @click="openPerbaruiStatus(booking)"
                                                    class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
                                                    role="menuitem">
                                                    Perbarui Status
                                                </a>
                                            </x-slot>
                                        </x-common.table-dropdown>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <td colspan="9" class="text-center">
                        <div x-show="bookings.length === 0" class="max-w-md text-center mx-auto py-12">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="mx-auto size-20 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z">
                                </path>
                            </svg>

                            <h2 class="mt-6 text-2xl font-bold text-gray-900">Booking tidak ditemukan</h2>

                            <p class="mt-4 text-pretty text-gray-700">
                                Tambah booking sekarang juga.
                            </p>
                        </div>
                    </td>
                </table>

            </div>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between">
                <p
                    class="border-b border-gray-100 pb-3 text-center text-sm font-medium text-gray-500 xl:border-b-0 xl:pb-0 xl:text-left dark:border-gray-800 dark:text-gray-400">
                    Showing <span x-text="currentPage"></span> of <span x-text="totalData"></span> entries
                </p>
                <div class="flex items-center justify-center gap-0.5 pt-3 xl:justify-end xl:pt-0">
                    <button @click="prevPage" :disabled="currentPage === 1"
                        class="mr-2.5 flex items-center h-10 justify-center rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-gray-700 shadow-theme-xs hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]"
                        disabled="disabled">
                        Previous
                    </button>

                    <template x-for="page in displayedPages" :key="page">
                        <button x-show="page !== '...'" @click="goToPage(page)"
                            :class="currentPage === page ? 'bg-blue-500/[0.08] text-brand-500' :
                                'text-gray-700 dark:text-gray-400'"
                            class="flex h-10 w-10 items-center justify-center rounded-lg text-theme-sm font-medium"
                            x-text="page"></button>
                        <span x-show="page === '...'"
                            class="flex h-10 w-10 items-center justify-center text-gray-500">...</span>
                    </template>

                    {{-- <button @click="goToPage(page)" :class="currentPage === page ? 'bg-blue-500/[0.08] text-brand-500' : 'text-gray-700 dark:text-gray-400'" class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium hover:bg-blue-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500 bg-blue-500/[0.08] text-brand-500">
                    1
                </button>

                <span x-show="currentPage &gt; 3" class="flex h-10 w-10 items-center justify-center rounded-lg hover:bg-blue-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500" style="display: none;">...</span>

                <template x-for="page in pagesAroundCurrent" :key="page">
                    <button @click="goToPage(page)" :class="currentPage === page ? 'bg-blue-500/[0.08] text-brand-500' : 'text-gray-700 dark:text-gray-400'" class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium hover:bg-blue-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500" x-text="page"></button>
                </template><button @click="goToPage(page)" :class="currentPage === page ? 'bg-blue-500/[0.08] text-brand-500' : 'text-gray-700 dark:text-gray-400'" class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium hover:bg-blue-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500 text-gray-700 dark:text-gray-400" x-text="page">2</button>

                <span x-show="currentPage &lt; totalPages - 2" class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-700 hover:bg-blue-500/[0.08] hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-500" style="display: none;">...</span>

                <button x-show="totalPages &gt; 1" @click="goToPage(totalPages)" :class="currentPage === totalPages ? 'bg-blue-500/[0.08] text-brand-500' : 'text-gray-700 dark:text-gray-400'" class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium hover:bg-blue-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500 text-gray-700 dark:text-gray-400" x-text="totalPages">3</button> --}}

                    <button @click="nextPage" :disabled="currentPage === totalPages"
                        class="ml-2.5 flex items-center h-10 justify-center rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-gray-700 shadow-theme-xs hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>

    <x-ui.modal @open-booking-modal.window="open = true" :isOpen="false" class="max-w-[700px]">
        <div
            class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">
            <div class="px-2 pr-14">
                <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                    Booking
                </h4>
            </div>
            <template x-if="open">
                <form x-data="addBooking()" class="flex flex-col">
                    <div class="custom-scrollbar h-[400px] overflow-y-auto p-2">
                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Nama Penyewa
                                </label>
                                <input type="text" x-model="form.nama_penyewa"
                                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                            </div>
                            {{-- <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Tanggal Sewa
                                </label>
                                <x-form.date-picker id="tanggal_sewa" :model="'form.tanggal_sewa'" />
                            </div> --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Tanggal Sewa
                                </label>
                                <div class="relative">
                                    <input type="date" x-model="form.tanggal_sewa"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                        onclick="this.showPicker()" />
                                    <span class="absolute top-1/2 right-3.5 -translate-y-1/2 pointer-events-none">
                                        <svg class="fill-gray-700 dark:fill-gray-400" width="14" height="14"
                                            viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M4.33317 0.0830078C4.74738 0.0830078 5.08317 0.418794 5.08317 0.833008V1.24967H8.9165V0.833008C8.9165 0.418794 9.25229 0.0830078 9.6665 0.0830078C10.0807 0.0830078 10.4165 0.418794 10.4165 0.833008V1.24967L11.3332 1.24967C12.2997 1.24967 13.0832 2.03318 13.0832 2.99967V4.99967V11.6663C13.0832 12.6328 12.2997 13.4163 11.3332 13.4163H2.6665C1.70001 13.4163 0.916504 12.6328 0.916504 11.6663V4.99967V2.99967C0.916504 2.03318 1.70001 1.24967 2.6665 1.24967L3.58317 1.24967V0.833008C3.58317 0.418794 3.91896 0.0830078 4.33317 0.0830078ZM4.33317 2.74967H2.6665C2.52843 2.74967 2.4165 2.8616 2.4165 2.99967V4.24967H11.5832V2.99967C11.5832 2.8616 11.4712 2.74967 11.3332 2.74967H9.6665H4.33317ZM11.5832 5.74967H2.4165V11.6663C2.4165 11.8044 2.52843 11.9163 2.6665 11.9163H11.3332C11.4712 11.9163 11.5832 11.8044 11.5832 11.6663V5.74967Z"
                                                fill="" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Jam Awal Sewa
                                </label>
                                <div class="relative">
                                    <select
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                        :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                        @change="hitungDurasi()" x-model.number="form.jam_awal_sewa">
                                        <option value=""
                                            class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                            Pilih Jam
                                        </option>
                                        @foreach (jam_sewa() as $jam)
                                            <option value="{{ $jam }}">{{ format_jam($jam) }}</option>
                                        @endforeach
                                    </select>
                                    <span
                                        class="absolute top-1/2 right-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M3.04175 9.99984C3.04175 6.15686 6.1571 3.0415 10.0001 3.0415C13.8431 3.0415 16.9584 6.15686 16.9584 9.99984C16.9584 13.8428 13.8431 16.9582 10.0001 16.9582C6.1571 16.9582 3.04175 13.8428 3.04175 9.99984ZM10.0001 1.5415C5.32867 1.5415 1.54175 5.32843 1.54175 9.99984C1.54175 14.6712 5.32867 18.4582 10.0001 18.4582C14.6715 18.4582 18.4584 14.6712 18.4584 9.99984C18.4584 5.32843 14.6715 1.5415 10.0001 1.5415ZM9.99998 10.7498C9.58577 10.7498 9.24998 10.4141 9.24998 9.99984V5.4165C9.24998 5.00229 9.58577 4.6665 9.99998 4.6665C10.4142 4.6665 10.75 5.00229 10.75 5.4165V9.24984H13.3334C13.7476 9.24984 14.0834 9.58562 14.0834 9.99984C14.0834 10.4141 13.7476 10.7498 13.3334 10.7498H10.0001H9.99998Z"
                                                fill="" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Jam Akhir Sewa
                                </label>
                                <div class="relative">
                                    <select
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                        :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                        @change="hitungDurasi()" x-model.number="form.jam_akhir_sewa">
                                        <option value=""
                                            class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                            Pilih Jam
                                        </option>
                                        @foreach (jam_sewa() as $jam)
                                            <option value="{{ $jam }}">{{ format_jam($jam) }}</option>
                                        @endforeach
                                    </select>
                                    <span
                                        class="absolute top-1/2 right-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M3.04175 9.99984C3.04175 6.15686 6.1571 3.0415 10.0001 3.0415C13.8431 3.0415 16.9584 6.15686 16.9584 9.99984C16.9584 13.8428 13.8431 16.9582 10.0001 16.9582C6.1571 16.9582 3.04175 13.8428 3.04175 9.99984ZM10.0001 1.5415C5.32867 1.5415 1.54175 5.32843 1.54175 9.99984C1.54175 14.6712 5.32867 18.4582 10.0001 18.4582C14.6715 18.4582 18.4584 14.6712 18.4584 9.99984C18.4584 5.32843 14.6715 1.5415 10.0001 1.5415ZM9.99998 10.7498C9.58577 10.7498 9.24998 10.4141 9.24998 9.99984V5.4165C9.24998 5.00229 9.58577 4.6665 9.99998 4.6665C10.4142 4.6665 10.75 5.00229 10.75 5.4165V9.24984H13.3334C13.7476 9.24984 14.0834 9.58562 14.0834 9.99984C14.0834 10.4141 13.7476 10.7498 13.3334 10.7498H10.0001H9.99998Z"
                                                fill="" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Durasi
                                </label>
                                <div class="relative">
                                    <span
                                        class="absolute top-1/2 right-0 inline-flex h-11 -translate-y-1/2 items-center justify-center border-l border-gray-400 py-3 pr-3 pl-3.5 text-gray-500 dark:border-gray-800 dark:text-gray-400">
                                        Jam
                                    </span>
                                    <input type="number" x-model="form.durasi" disabled
                                        class="cursor-not-allowed dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-gray-200 px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                </div>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Nomor Lapangan
                                </label>
                                <div class="relative z-20 bg-transparent">
                                    <select x-model.number="form.nomor_lapangan"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                        :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                        @change="isOptionSelected = true">
                                        <option value=""
                                            class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                            Pilih Nomor Lapangan
                                        </option>
                                        <option value="1"
                                            class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                            1
                                        </option>
                                        <option value="2"
                                            class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                            2
                                        </option>
                                        <option value="3"
                                            class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                            3
                                        </option>
                                        <option value="4"
                                            class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                            4
                                        </option>
                                    </select>
                                    <span
                                        class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-700 dark:text-gray-400">
                                        <svg class="stroke-current" width="20" height="20"
                                            viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Harga
                                </label>
                                <input type="text" :value="formatRupiah(form.harga_sewa)" disabled
                                    class="cursor-not-allowed dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-gray-200 bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                <input type="hidden" name="harga_sewa" x-model.number="form.harga_sewa" />
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Total Harga
                                </label>
                                <input type="text" :value="formatRupiah(form.total_harga_sewa)" disabled
                                    class="cursor-not-allowed dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-gray-200 bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                <input type="hidden" name="total_harga_sewa"
                                    x-model.number="form.total_harga_sewa" />
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                        <button @click="open = false" type="button"
                            class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                            Close
                        </button>
                        <button @click="submit" type="button"
                            class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                            Submit
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </x-ui.modal>

    <x-ui.modal @open-ubah-booking-modal.window="open = true" :isOpen="false" class="max-w-[700px]">
        <div
            class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">
            <div class="px-2 pr-14">
                <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                    Ubah Booking
                </h4>
            </div>
            <template x-if="open">
                <form x-data="addBooking()" class="flex flex-col">
                    <div class="custom-scrollbar h-[400px] overflow-y-auto p-2">
                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Nama Penyewa
                                </label>
                                <input type="text" x-model="selectedBooking.nama_penyewa"
                                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Tanggal Sewa
                                </label>
                                {{-- <input type="hidden" name="tanggal_sewa" x-model="form.tanggal_sewa"> --}}
                                <x-form.date-picker id="tanggal_sewa" :model="'selectedBooking.tanggal_sewa'" />
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Jam Awal Sewa
                                </label>
                                <div class="relative">
                                    <select
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                        :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                        @change="hitungDurasi()" x-model.number="selectedBooking.jam_awal_sewa">
                                        <option value=""
                                            class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                            Pilih Jam
                                        </option>
                                        @foreach (jam_sewa() as $jam)
                                            <option value="{{ $jam }}">{{ format_jam($jam) }}</option>
                                        @endforeach
                                    </select>
                                    <span
                                        class="absolute top-1/2 right-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M3.04175 9.99984C3.04175 6.15686 6.1571 3.0415 10.0001 3.0415C13.8431 3.0415 16.9584 6.15686 16.9584 9.99984C16.9584 13.8428 13.8431 16.9582 10.0001 16.9582C6.1571 16.9582 3.04175 13.8428 3.04175 9.99984ZM10.0001 1.5415C5.32867 1.5415 1.54175 5.32843 1.54175 9.99984C1.54175 14.6712 5.32867 18.4582 10.0001 18.4582C14.6715 18.4582 18.4584 14.6712 18.4584 9.99984C18.4584 5.32843 14.6715 1.5415 10.0001 1.5415ZM9.99998 10.7498C9.58577 10.7498 9.24998 10.4141 9.24998 9.99984V5.4165C9.24998 5.00229 9.58577 4.6665 9.99998 4.6665C10.4142 4.6665 10.75 5.00229 10.75 5.4165V9.24984H13.3334C13.7476 9.24984 14.0834 9.58562 14.0834 9.99984C14.0834 10.4141 13.7476 10.7498 13.3334 10.7498H10.0001H9.99998Z"
                                                fill="" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Jam Akhir Sewa
                                </label>
                                <div class="relative">
                                    <select
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                        :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                        @change="hitungDurasi()" x-model.number="selectedBooking.jam_akhir_sewa">
                                        <option value=""
                                            class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                            Pilih Jam
                                        </option>
                                        @foreach (jam_sewa() as $jam)
                                            <option value="{{ $jam }}">{{ format_jam($jam) }}</option>
                                        @endforeach
                                    </select>
                                    <span
                                        class="absolute top-1/2 right-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M3.04175 9.99984C3.04175 6.15686 6.1571 3.0415 10.0001 3.0415C13.8431 3.0415 16.9584 6.15686 16.9584 9.99984C16.9584 13.8428 13.8431 16.9582 10.0001 16.9582C6.1571 16.9582 3.04175 13.8428 3.04175 9.99984ZM10.0001 1.5415C5.32867 1.5415 1.54175 5.32843 1.54175 9.99984C1.54175 14.6712 5.32867 18.4582 10.0001 18.4582C14.6715 18.4582 18.4584 14.6712 18.4584 9.99984C18.4584 5.32843 14.6715 1.5415 10.0001 1.5415ZM9.99998 10.7498C9.58577 10.7498 9.24998 10.4141 9.24998 9.99984V5.4165C9.24998 5.00229 9.58577 4.6665 9.99998 4.6665C10.4142 4.6665 10.75 5.00229 10.75 5.4165V9.24984H13.3334C13.7476 9.24984 14.0834 9.58562 14.0834 9.99984C14.0834 10.4141 13.7476 10.7498 13.3334 10.7498H10.0001H9.99998Z"
                                                fill="" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Durasi
                                </label>
                                <div class="relative">
                                    <span
                                        class="absolute top-1/2 right-0 inline-flex h-11 -translate-y-1/2 items-center justify-center border-l border-gray-400 py-3 pr-3 pl-3.5 text-gray-500 dark:border-gray-800 dark:text-gray-400">
                                        Jam
                                    </span>
                                    <input type="number" x-model="selectedBooking.durasi" disabled
                                        class="cursor-not-allowed dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-gray-200 px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                </div>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Nomor Lapangan
                                </label>
                                <div class="relative z-20 bg-transparent">
                                    <select x-model.number="selectedBooking.nomor_lapangan"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                        :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                        @change="isOptionSelected = true">
                                        <option value=""
                                            class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                            Pilih Nomor Lapangan
                                        </option>
                                        <option value="1"
                                            class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                            1
                                        </option>
                                        <option value="2"
                                            class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                            2
                                        </option>
                                        <option value="3"
                                            class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                            3
                                        </option>
                                        <option value="4"
                                            class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                            4
                                        </option>
                                    </select>
                                    <span
                                        class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-700 dark:text-gray-400">
                                        <svg class="stroke-current" width="20" height="20"
                                            viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Harga
                                </label>
                                <input type="text" :value="formatRupiah(selectedBooking.harga_sewa)" disabled
                                    class="cursor-not-allowed dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-gray-200 bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                <input type="hidden" name="harga_sewa"
                                    x-model.number="selectedBooking.harga_sewa" />
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Total Harga
                                </label>
                                <input type="text" :value="formatRupiah(selectedBooking.total_harga_sewa)" disabled
                                    class="cursor-not-allowed dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-gray-200 bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                <input type="hidden" name="total_harga_sewa"
                                    x-model.number="selectedBooking.total_harga_sewa" />
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                        <button @click="open = false" type="button"
                            class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                            Close
                        </button>
                        <button @click="submit" type="button"
                            class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                            Submit
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </x-ui.modal>

    <x-ui.modal @open-perbarui-status-booking-modal.window="open = true" :isOpen="false" class="max-w-[700px]">
        <div
            class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">
            <div class="px-2 pr-14">
                <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                    Perbarui Status
                </h4>
            </div>
            <template x-if="open">
                <form x-data="addBooking()" class="flex flex-col">
                    <div class="custom-scrollbar h-[300px] overflow-y-auto p-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Status
                            </label>
                            <div class="relative z-20 bg-transparent">
                                <select x-model="selectedBooking.status"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                    @change="isOptionSelected = true">
                                    <option disabled selected value=""
                                        class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Pilih Status
                                    </option>
                                    <option disabled selected value="2"
                                        class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Dipesan
                                    </option>
                                    <option value="3" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Selesai
                                    </option>
                                    <option value="4" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Dibatalkan
                                    </option>
                                </select>
                                <span
                                    class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-700 dark:text-gray-400">
                                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                        <button @click="open = false" type="button"
                            class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                            Close
                        </button>
                        <button @click="submit" type="button"
                            class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                            Submit
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </x-ui.modal>

    <x-ui.modal @open-jadwal-modal.window="open = true" :isOpen="false" class="max-w-[1200px]">
        <div class="no-scrollbar relative w-full max-w-[1200px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11"
            x-data="jadwalLapangan('{{ $tanggal }}')">
            <template x-if="open">
                <div class="custom-scrollbar h-[560px] overflow-y-auto p-2">
                    <div
                        class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
                        <div class="items-center text-center sm:flex-row sm:items-center sm:justify-between">
                            <div class="border-b border-gray-100 dark:border-gray-800">
                                <h3
                                    class="items-center text-center text-xl font-semibold text-gray-800 dark:text-gray-300">
                                    Jadwal Lapangan Badminton
                                </h3>
                                <div class="mt-3 mb-4">
                                    <input type="date" name="tanggal_sewa" x-model="tanggal"
                                        @change="fetchJadwal" onclick="this.showPicker()"
                                        class="dark:bg-gray-900 dark:text-gray-300 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 appearance-none rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent py-2.5 pl-6 text-sm text-gray-800" />

                                </div>
                                <p class="text-gray-700 dark:text-gray-200 text-md font-regular mt-2 mb-4"
                                    x-text="tanggalFormatted">
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
                                                :class="isBooked({{ $lapangan }}, {{ $jam }}) ?
                                                    'bg-orange-600' : 'bg-gray-600'">
                                                <h4>{{ format_jam($jam) }}</h4>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </x-ui.modal>

    <script>
        function formatTanggal(tanggal) {
            if (!tanggal) return '';
            const date = new Date(tanggal);

            return new Intl.DateTimeFormat('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            }).format(date);
        }

        function addBooking() {
            return {
                form: {
                    nama_penyewa: '',
                    nomor_lapangan: null,
                    tanggal_sewa: null,
                    jam_awal_sewa: null,
                    jam_akhir_sewa: null,
                    durasi: 0,
                    harga_sewa: 25000,
                    total_harga_sewa: 0,
                },
                formatRupiah(value) {
                    return new Intl.NumberFormat('id-ID', {
                        style: "currency",
                        currency: "IDR",
                        minimumFractionDigits: 0
                    }).format(value)
                },
                hitungDurasi() {
                    const {
                        jam_awal_sewa,
                        jam_akhir_sewa,
                        harga_sewa,
                    } = this.form

                    if (jam_awal_sewa !== null && jam_akhir_sewa !== null) {
                        if (jam_akhir_sewa > jam_awal_sewa) {
                            this.form.durasi = jam_akhir_sewa - jam_awal_sewa
                            this.form.total_harga_sewa = this.form.durasi * harga_sewa
                        } else {
                            this.form.durasi = 0;
                            this.form.total_harga_sewa = 0;
                            alert('Jam akhir harus lebih besar dari jam awal');
                        }
                    }
                },
                submit() {
                    fetch("{{ url('lapangan-badminton') }}/", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "X-Requested-With": "XMLHttpRequest",
                            },
                            body: JSON.stringify(this.form)
                        })
                        .then(async res => {
                            if (!res.ok) {
                                const data = await res.json();
                                this.errors = data.errors || {};
                                throw data;
                            }
                            return res.json();
                        })
                        .then(res => {
                            sessionStorage.setItem('alert_add_success', res.message)
                            window.location.reload();
                        })
                        .catch(err => {
                            // sessionStorage.setItem('alert_add_error', err.message)
                            // window.location.reload();
                            console.error(err);
                        });
                }
            }
        }

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
    </script>
</div>
