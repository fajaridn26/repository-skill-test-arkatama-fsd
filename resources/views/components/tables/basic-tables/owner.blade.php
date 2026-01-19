@props(['owners'])
<div x-data='{
    owners: @json($owners->items()),
    perPage: @json($owners->perPage()),
    currentPage: @json($owners->currentPage()),
    totalPages: @json($owners->lastPage()),
    totalData: @json($owners->total()),
    selectedOwners: null,
    selectedOwner: {
    nama: "",
    email: "",
    kelas: "-",
    angkatan: "-",
    no_whatsapp: "-",
    },
    alertType: "",
    showAlert: false,
    alertMessage: "",
    handleSuccess(message) {
        this.alertMessage = message;
        this.showAlert = true;
        this.alertType = "success"
    },
    handleError(message) {
        this.alertMessage = message;
        this.showAlert = true;
        this.alertType = "error"
    },
    search: "",
    openTambahOwner(){
        window.dispatchEvent(new CustomEvent("open-tambah-owner-modal"));
    }, 
    openEdit(user){
        this.selectedOwner = {
        ...user,
        kelas: user.kelas ?? "-",
        angkatan: user.angkatan ?? "-",
        no_whatsapp: user.no_whatsapp ?? "-",
        };
        window.dispatchEvent(new CustomEvent("open-edit-user-modal"));
    },
    openReset(user){
        this.selectedOwner = {
        ...user,
        };
        window.dispatchEvent(new CustomEvent("open-reset-user-modal"));
    },
    openHapus(user){
        this.selectedOwner = {
        ...user,
        };
        window.dispatchEvent(new CustomEvent("open-hapus-user-modal"));
    },
    openImport(){
        window.dispatchEvent(new CustomEvent("open-import-modal"));
    },
    displayedPages: [],
        init() {
            this.updateDisplayedPages();

            const addMessage = sessionStorage.getItem("alert_add_success");
            const addErrorMessage = sessionStorage.getItem("alert_add_error");
            const editMessage = sessionStorage.getItem("alert_edit_success");
            const resetMessage = sessionStorage.getItem("alert_reset_success");
            const deleteMessage = sessionStorage.getItem("alert_delete_success");
            const importMessage = sessionStorage.getItem("alert_import_success");
            const importErrorMessage = sessionStorage.getItem("alert_import_error");

            if(addMessage) {
                this.handleSuccess(addMessage);
                sessionStorage.removeItem("alert_add_success");
            } else if(addErrorMessage) {
                this.handleError(addErrorMessage);
                sessionStorage.removeItem("alert_add_error");
            } else if(editMessage) {
                this.handleSuccess(editMessage);
                sessionStorage.removeItem("alert_edit_success");
            }   else if(resetMessage) {
                this.handleSuccess(resetMessage);
                sessionStorage.removeItem("alert_reset_success");
            } else if(deleteMessage) {
                this.handleSuccess(deleteMessage);
                sessionStorage.removeItem("alert_delete_success"); 
            } else if(importMessage) {
                this.handleSuccess(importMessage);
                sessionStorage.removeItem("alert_import_success"); 
            } else if(importErrorMessage) {
                this.handleError(importErrorMessage);
                sessionStorage.removeItem("alert_import_error"); 
            }
        },
    prevPage() {
            if(this.currentPage > 1){
                const prev = this.currentPage - 1;

                this.search ? this.searchowners(prev) : this.fetchPage(prev)
            }
        },
        nextPage() {
            if(this.currentPage < this.totalPages){
                const next = this.currentPage + 1;

                this.search ? this.searchowners(next) : this.fetchPage(next)
            }
        },
        goToPage(page) {
            if(this.search){
                this.searchowners(page)
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
        searchowners(page = 1) {
            if (!this.search) {
            this.fetchPage(1);
            return;
        }

    fetch(`/user/search?query=${this.search}&page=${page}`, {
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        }
    })
    .then(res => res.json())
    .then(res => {
        this.owners = res.data;
        this.currentPage = res.current_page;
        this.totalPages = res.last_page;
        this.perPage = res.per_page;
        this.totalData = res.total;
        this.updateDisplayedPages();
    })
    .catch(err => console.error(err));
}
,
        fetchPage(page = 1) {
    fetch(`?page=${page}`, {
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        }
    })
    .then(res => res.json())
    .then(res => {
        this.owners = res.data;
        this.currentPage = res.current_page;
        this.totalPages = res.last_page;
        this.perPage = res.per_page;
        this.totalData = res.total;
        this.updateDisplayedPages();
    });
}

}'
    x-init="init()">

    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <!-- Header -->
        <div class="flex flex-col gap-2 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">List Owner</h3>
            </div>
        </div>


        <!-- Table -->
        <div class="overflow-hidden">
            <div class="max-w-full px-5 overflow-x-auto">
                <div class="flex flex-col gap-3 justify-between sm:flex-row sm:items-center mb-4">
                    <div class="inline-flex gap-3">
                        <x-ui.button @click="openTambahOwner" size="sm" variant="primary">Tambah Owner</x-ui.button>
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
                                @input.debounce.400ms="searchowners"
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
                                Nama</th>
                            <th scope="col"
                                class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                                Nomor Handphone</th>
                            <th scope="col"
                                class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                                Email</th>
                            <th scope="col"
                                class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 capitalize">
                                Alamat</th>
                            <th scope="col" class="relative px-4 py-3 capitalize">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-for="(owner, index) in owners" :key="owner.id">
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400"
                                        x-text="(currentPage - 1) * perPage + index + 1">
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-800 font-medium dark:text-gray-400"
                                        x-text="owner.name">
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400"
                                        x-text="owner.phone">
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400"
                                        x-text="owner.email">
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400"
                                        x-text="owner.address">
                                    </div>
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
                                                <a href="#" @click="openEdit(user)"
                                                    class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
                                                    role="menuitem">
                                                    Ubah
                                                </a>
                                                <a href="#" @click="openHapus(user)"
                                                    class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
                                                    role="menuitem">
                                                    Hapus
                                                </a>
                                            </x-slot>
                                        </x-common.table-dropdown>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <td colspan="8" class="text-center">
                        <div x-show="owners.length === 0" class="max-w-md text-center mx-auto py-12">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="mx-auto size-20 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z">
                                </path>
                            </svg>

                            <h2 class="mt-6 text-2xl font-bold text-gray-900">User tidak ditemukan</h2>

                            {{-- <p class="mt-4 text-pretty text-gray-700">
                                Tunggu siswa mengunggah projectnya.
                            </p> --}}
                        </div>
                    </td>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
            <div class="flex items-center justify-between">
                <button @click="prevPage" :disabled="currentPage === 1"
                    :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''"
                    class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:px-3.5">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715C2.58301 9.99766 2.58301 9.99817 2.58301 9.99868Z"
                            fill="currentColor" />
                    </svg>
                    <span class="hidden sm:inline">Previous</span>
                </button>

                <span class="block text-sm font-medium text-gray-700 dark:text-gray-400 sm:hidden">
                    Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span>
                </span>

                <ul class="hidden items-center gap-0.5 sm:flex">
                    <template x-for="page in displayedPages" :key="page">
                        <li>
                            <button x-show="page !== '...'" @click="goToPage(page)"
                                :class="currentPage === page ? 'bg-blue-500 text-white' :
                                    'text-gray-700 hover:bg-blue-500/[0.08] hover:text-blue-500 dark:text-gray-400 dark:hover:text-blue-500'"
                                class="flex h-10 w-10 items-center justify-center rounded-lg text-theme-sm font-medium"
                                x-text="page"></button>
                            <span x-show="page === '...'"
                                class="flex h-10 w-10 items-center justify-center text-gray-500">...</span>
                        </li>
                    </template>
                </ul>

                <button @click="nextPage" :disabled="currentPage === totalPages"
                    :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''"
                    class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:px-3.5">
                    <span class="hidden sm:inline">Next</span>
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99763 17.4175 9.99812 17.4175 9.9986Z"
                            fill="currentColor" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <x-ui.modal @open-tambah-owner-modal.window="open = true" :isOpen="false" class="max-w-[700px]">
        <div
            class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">
            <div class="px-2 pr-14">
                <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                    Tambah Owner
                </h4>
            </div>
            <template x-if="open">
                <form x-data="addUserForm()" class="flex flex-col">
                    <div class="custom-scrollbar h-[320px] overflow-y-auto p-2">
                        <div>
                            <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                                <div class="col-span-2 lg:col-span-1">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Nama
                                    </label>
                                    <input type="text" x-model="form.name" required
                                        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                </div>
                                <div class="col-span-2 lg:col-span-1">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Nomor Handphone
                                    </label>
                                    <input type="text" x-model="form.phone" required
                                        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                </div>
                                <div class="col-span-2 lg:col-span-1">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Email
                                    </label>
                                    <input type="email" x-model="form.email" required
                                        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                </div>
                                <div class="col-span-2 lg:col-span-1">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Alamat
                                    </label>
                                    <input type="text" x-model="form.address" required
                                        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                </div>
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

    <x-ui.modal @open-hapus-user-modal.window="open = true" :isOpen="false" class="max-w-[600px]">
        <template x-if="open">
            <form x-data="hapusUser()" class="flex flex-col">
                <div
                    class="no-scrollbar relative w-full max-w-[600px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">
                    <div class="px-2">
                        <div class="text-center mx-auto py-12">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="mx-auto size-20 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z">
                                </path>
                            </svg>

                            <h2 class="mt-6 text-2xl font-bold text-gray-900">User akan dihapus</h2>

                            <p class="mt-4 text-pretty text-gray-700"
                                x-text="`Apakah anda yakin ingin menghapus ${selectedOwner.nama}?`">
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 px-2 mt-4 lg:justify-center">
                        <button @click="submit" type="button"
                            class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                            Ya, Hapus
                        </button>
                        <button @click="open = false" type="button"
                            class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                            Batal
                        </button>
                    </div>
                </div>
            </form>
        </template>
    </x-ui.modal>

    <script>
        function addUserForm() {
            return {
                form: {
                    name: '',
                    phone: '',
                    email: '',
                    address: '',
                },
                errors: {},

                submit() {
                    fetch("{{ url('owner') }}", {
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
                            sessionStorage.setItem('alert_add_error', err.message)
                            window.location.reload();
                            console.error(err);
                        });
                }
            }
        }

        function editUserForm() {
            return {
                errors: {},

                submit() {
                    fetch(`{{ url('user') }}/${this.selectedOwner.id}`, {
                            method: "PUT",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "X-Requested-With": "XMLHttpRequest",
                            },
                            body: JSON.stringify(this.selectedOwner)
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
                            sessionStorage.setItem('alert_edit_success', res.message)
                            window.location.reload();
                        })
                        .catch(err => {
                            console.error(err);
                        });
                },
            }
        }

        function resetPassword() {
            return {
                errors: {},

                submit() {
                    fetch(`{{ url('user/reset-password') }}/${this.selectedOwner.id}`, {
                            method: "PUT",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "X-Requested-With": "XMLHttpRequest",
                            },
                            body: JSON.stringify(this.selectedOwner)
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
                            sessionStorage.setItem('alert_reset_success', res.message)
                            window.location.reload();
                        })
                        .catch(err => {
                            console.error(err);
                        });
                },
            }
        }

        function hapusUser() {
            return {
                submit() {
                    fetch(`{{ url('user') }}/${this.selectedOwner.id}`, {
                            method: "DELETE",
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        })
                        .then(res => res.json())
                        .then(res => {
                            sessionStorage.setItem('alert_delete_success', res.message);
                            window.location.reload();
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                        });
                }
            }
        }
    </script>
</div>
