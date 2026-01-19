@props(['hewans'])
<div x-data='{
    hewans: @json($hewans->items()),
    perPage: @json($hewans->perPage()),
    currentPage: @json($hewans->currentPage()),
    totalPages: @json($hewans->lastPage()),
    totalData: @json($hewans->total()),
    selectedHewan: null,
    selectedHewans: null,
    owners: [],
     form: {
        owner_id: "",
    },
    search: "",
    showAlert: false,
    alertMessage: "",
    alertType: "",
    fetchOwners() {
        fetch("/owners/valid", {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(res => res.json())
        .then(data => {
            this.owners = data;
        })
        .catch(() => {
            this.error = "Gagal memuat data pemilik";
        });
    },
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
    openTambahHewan() {
        window.dispatchEvent(new CustomEvent("open-tambah-hewan-modal"))
    },
    openUbah(hewan) {
        this.selectedHewan = {
        ...hewan,
        };
        window.dispatchEvent(new CustomEvent("open-ubah-hewan-modal"))
    },
    openHapus(hewan){
        this.selectedHewan = {
        ...hewan,
        };
        window.dispatchEvent(new CustomEvent("open-hapus-hewan-modal"));
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
                this.search ? this.searchhewans(prev) : this.fetchPage(prev)
            }
        },
        nextPage() {
            if(this.currentPage < this.totalPages){
                const next = this.currentPage + 1;
                this.search ? this.searchhewans(next) : this.fetchPage(next)
            }
        },
        goToPage(page) {
            if(this.search){
                this.searchhewans(page)
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
        searchhewans(page = 1){
            if (!this.search) {
                this.fetchPage(1);
                return;
            }
        fetch(`/hewan/search?query=${this.search}&page=${page}`, {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
                }
        })
                .then(res => res.json())
                .then(res => {
                this.hewans = res.data;
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
        this.hewans = res.original.data;
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
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Hewan</h3>
            </div>
        </div>


        <!-- Table -->
        <div class="overflow-hidden">
            <div class="max-w-full px-5 overflow-x-auto">
                <div class="flex flex-col gap-3 justify-between sm:flex-row sm:items-center mb-4">
                    <div>
                        <x-ui.button @click="openTambahHewan" size="sm" variant="primary">Tambah Hewan</x-ui.button>
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
                                @input.debounce.400ms="searchhewans"
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
                                Kode</th>
                            <th scope="col"
                                class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                                Nama Hewan</th>
                            <th scope="col"
                                class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                                Jenis Hewan</th>
                            <th scope="col"
                                class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 capitalize">
                                Usia</th>
                            <th scope="col"
                                class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 capitalize">
                                Berat</th>
                            <th scope="col" class="relative px-4 py-3 capitalize">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-for="(hewan, index) in hewans" :key="hewan.id">
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400"
                                        x-text="(currentPage - 1) * perPage + index + 1">
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400" x-text="hewan.code">
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-800 font-medium dark:text-gray-400"
                                        x-text="hewan.name">
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 font-medium dark:text-gray-400"
                                        x-text="hewan.type">
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 font-medium dark:text-gray-400"
                                        x-text="hewan.age">
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 font-medium dark:text-gray-400"
                                        x-text="hewan.weight">
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
                                                <a href="#" @click="openUbah(hewan)"
                                                    class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
                                                    role="menuitem">
                                                    Ubah
                                                </a>
                                                <a href="#" @click="openHapus(hewan)"
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
                    <td colspan="9" class="text-center">
                        <div x-show="hewans.length === 0" class="max-w-md text-center mx-auto py-12">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="mx-auto size-20 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z">
                                </path>
                            </svg>

                            <h2 class="mt-6 text-2xl font-bold text-gray-900">Hewan tidak ditemukan</h2>

                            <p class="mt-4 text-pretty text-gray-700">
                                Tambah hewan sekarang juga.
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

                    <button @click="nextPage" :disabled="currentPage === totalPages"
                        class="ml-2.5 flex items-center h-10 justify-center rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-gray-700 shadow-theme-xs hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>

    <x-ui.modal @open-tambah-hewan-modal.window="open = true" :isOpen="false" class="max-w-[700px]">
        <div x-data="addHewan()" x-init="init()"
            class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 lg:p-11">
            <div class="px-2 pr-14">
                <h4 class="mb-2 text-2xl font-semibold">
                    Tambah Hewan
                </h4>
            </div>

            <template x-if="open">
                <form class="flex flex-col">

                    <div class="h-[400px] overflow-y-auto p-2">

                        <div class="mb-4">
                            <label class="block text-sm font-medium">
                                Data Hewan
                            </label>
                            <input type="text" x-model="form.raw_hewan" placeholder="Milo Kucing 2Th 4.5kg"
                                class="h-11 w-full rounded-lg border px-4 text-sm" />

                            <p class="text-red-500 text-sm mt-1" x-text="error"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">
                                Pemilik
                            </label>

                            <select x-model.number="form.owner_id"
                                class="h-11 w-full rounded-lg border px-4 py-2.5 text-sm">

                                <option value="">Pilih Pemilik</option>

                                <template x-for="owner in owners" :key="owner.id">
                                    <option :value="owner.id" x-text="`${owner.name} (${owner.phone})`">
                                    </option>
                                </template>
                            </select>
                        </div>

                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="open = false" class="rounded-lg border px-4 py-2.5 text-sm">
                            Close
                        </button>

                        <button type="button" @click="submit"
                            class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm text-white">
                            Submit
                        </button>
                    </div>

                </form>
            </template>
        </div>
    </x-ui.modal>

    <x-ui.modal x-data="editHewan()" @open-ubah-hewan-modal.window="openModal($event.detail)"
        class="max-w-[700px]">
        <div x-data="editHewan()" x-init="init()"
            class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 lg:p-11">
            <div class="px-2 pr-14">
                <h4 class="mb-2 text-2xl font-semibold">
                    Ubah Hewan
                </h4>
            </div>

            <template x-if="open">
                <form class="flex flex-col">

                    <div class="h-[400px] overflow-y-auto p-2">

                        <div class="mb-4">
                            <label class="block text-sm font-medium">
                                Data Hewan
                            </label>
                            <input type="text" x-model="form.raw_hewan" placeholder="Milo Kucing 2Th 4.5kg"
                                class="h-11 w-full rounded-lg border px-4 text-sm" />

                            <p class="text-red-500 text-sm mt-1" x-text="error"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">
                                Pemilik
                            </label>

                            <select x-model.number="form.owner_id"
                                class="h-11 w-full rounded-lg border px-4 py-2.5 text-sm">

                                <option value="">Pilih Pemilik</option>

                                <template x-for="owner in owners" :key="owner.id">
                                    <option :value="owner.id" x-text="`${owner.name} (${owner.phone})`">
                                    </option>
                                </template>
                            </select>
                        </div>

                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="open = false" class="rounded-lg border px-4 py-2.5 text-sm">
                            Close
                        </button>

                        <button type="button" @click="submit"
                            class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm text-white">
                            Submit
                        </button>
                    </div>

                </form>
            </template>
        </div>
    </x-ui.modal>

    <x-ui.modal @open-hapus-hewan-modal.window="open = true" :isOpen="false" class="max-w-[600px]">
        <template x-if="open">
            <form x-data="deleteHewan()" class="flex flex-col">
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

                            <h2 class="mt-6 text-2xl font-bold text-gray-900">Hewan akan dihapus</h2>

                            <p class="mt-4 text-pretty text-gray-700"
                                x-text="`Apakah anda yakin ingin menghapus ${selectedHewan.name}?`">
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
        function addHewan() {
            return {
                open: true,
                owners: [],
                loadingOwners: false,

                form: {
                    owner_id: null,
                    raw_hewan: '',
                    name: '',
                    type: '',
                    age: null,
                    weight: null,
                },

                error: null,

                init() {
                    this.fetchOwners();
                },

                fetchOwners() {
                    fetch("{{ url('/owners/valid') }}", {
                            headers: {
                                "X-Requested-With": "XMLHttpRequest"
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            this.owners = data;
                        })
                        .catch(() => {
                            this.error = 'Gagal memuat data pemilik';
                        });
                },

                parseHewan() {
                    const clean = this.form.raw_hewan.replace(/\s+/g, ' ').trim();

                    const regex =
                        /^(.+?)\s+(.+?)\s+(\d+)\s*(tahun|thn|th)?\s+([\d.,]+)\s*(kg)?$/i;

                    const match = clean.match(regex);

                    if (!match) {
                        this.error = 'Format salah. Contoh: Milo Kucing 2Th 4.5kg';
                        return false;
                    }

                    const age = parseInt(match[3]);
                    const weight = parseFloat(match[5].replace(',', '.'));

                    if (age <= 0 || weight <= 0 || isNaN(weight)) {
                        this.error = 'Usia atau berat tidak valid';
                        return false;
                    }

                    this.form.name = match[1].toUpperCase();
                    this.form.type = match[2].toUpperCase();
                    this.form.age = age;
                    this.form.weight = weight;

                    return true;
                },

                submit() {
                    this.error = null;

                    if (!this.form.owner_id) {
                        this.error = 'Pemilik wajib dipilih';
                        return;
                    }

                    if (!this.parseHewan()) return;

                    fetch("{{ url('hewan') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
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
                            sessionStorage.setItem('alert_fail_error', err.message)
                            window.location.reload();
                            console.error(err);
                        });
                }
            }
        }

        function editHewan() {
            return {
                open: false,
                owners: [],
                petId: null,

                form: {
                    owner_id: null,
                    raw_hewan: '',
                    name: '',
                    type: '',
                    age: null,
                    weight: null,
                },

                error: null,

                openModal(petId) {
                    this.open = true;
                    this.petId = petId;
                    this.error = null;

                    this.fetchOwners();
                    this.fetchPet();
                },

                fetchOwners() {
                    fetch('/owners/valid', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            this.owners = data;
                        })
                        .catch(() => {
                            this.error = 'Gagal memuat data pemilik';
                        });
                },

                fetchPet() {
                    fetch(`/hewan/${this.petId}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(pet => {
                            this.form.owner_id = pet.owner_id;
                            this.form.raw_hewan =
                                `${pet.name} ${pet.type} ${pet.age}Th ${pet.weight}kg`;
                        });
                },

                parseHewan() {
                    this.error = null;

                    const clean = this.form.raw_hewan.replace(/\s+/g, ' ').trim();
                    const regex =
                        /^(.+?)\s+(.+?)\s+(\d+)\s*(tahun|thn|th)?\s+([\d.,]+)\s*(kg)?$/i;

                    const match = clean.match(regex);

                    if (!match) {
                        this.error = 'Format tidak valid. Contoh: Milo Kucing 2Th 4.5kg';
                        return false;
                    }

                    const age = parseInt(match[3]);
                    const weight = parseFloat(match[5].replace(',', '.'));

                    if (age <= 0 || weight <= 0 || isNaN(weight)) {
                        this.error = 'Usia atau berat tidak valid';
                        return false;
                    }

                    this.form.name = match[1].toUpperCase();
                    this.form.type = match[2].toUpperCase();
                    this.form.age = age;
                    this.form.weight = weight;

                    return true;
                },

                submit() {
                    if (!this.form.owner_id) {
                        this.error = 'Pemilik wajib dipilih';
                        return;
                    }

                    if (!this.parseHewan()) return;

                    fetch(`/hewan/${this.petId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: JSON.stringify(this.form)
                        })
                        .then(res => res.json())
                        .then(res => {
                            sessionStorage.setItem('alert_success', res.message);
                            window.location.reload();
                        })
                        .catch(() => {
                            this.error = 'Gagal menyimpan perubahan';
                        });
                }
            }
        }

        function deleteHewan() {
            return {
                submit() {
                    fetch(`{{ url('hewan') }}/${this.selectedHewan.id}`, {
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
