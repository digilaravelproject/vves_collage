@extends('layouts.admin.app')
@section('title', 'Bulk User Onboarding (Admin Tool)')

@section('content')
<div class="space-y-6" x-data="bulkUserApp()">
    {{-- Header Section --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Bulk User Onboarding</h1>
                <span class="px-2.5 py-0.5 text-xs font-semibold bg-amber-100 text-amber-800 rounded-full border border-amber-200">
                    <i class="bi bi-shield-lock me-1"></i> Admin Tool
                </span>
            </div>
            <p class="mt-1 text-sm text-gray-500">
                Create multiple Maker / Checker accounts in a single batch. Search and assign institutions instantly. Upon submission, login credentials will be automatically emailed to each user.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.users.index') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition">
                <i class="bi bi-arrow-left me-1.5"></i> Back to Users
            </a>
        </div>
    </div>

    {{-- Error / Validation Alerts --}}
    @if ($errors->any())
        <div class="p-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl" role="alert">
            <div class="flex items-center font-bold mb-2">
                <i class="bi bi-exclamation-triangle-fill me-2 text-base"></i> Please fix the following errors:
            </div>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Quick Utilities Toolbar --}}
    <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" @click="addRow()"
                    class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition">
                    <i class="bi bi-plus-lg me-1"></i> +1 Row
                </button>
                <button type="button" @click="addMultipleRows(5)"
                    class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">
                    <i class="bi bi-plus-square me-1"></i> +5 Rows
                </button>
                <span class="h-5 w-px bg-gray-200 mx-1"></span>
                <button type="button" @click="generateAllPasswords()"
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-purple-700 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition">
                    <i class="bi bi-key me-1"></i> Auto-Generate Passwords
                </button>
                <button type="button" @click="setAllRoles('Maker')"
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition">
                    <i class="bi bi-person-gear me-1"></i> All Maker
                </button>
                <button type="button" @click="setAllRoles('Checker')"
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition">
                    <i class="bi bi-person-check me-1"></i> All Checker
                </button>
            </div>

            {{-- Searchable Apply Institution To All --}}
            <div class="relative" x-data="{ openAll: false, searchAll: '', selectedAllName: '-- Select to Apply to All --' }" @click.outside="openAll = false">
                <div class="flex items-center gap-2">
                    <label class="text-xs font-semibold text-gray-600">Apply Institution To All:</label>
                    <button type="button" @click="openAll = !openAll; if(openAll) $nextTick(() => $refs.searchAllInput.focus())"
                        class="min-w-[230px] max-w-[300px] flex items-center justify-between text-left px-3 py-1.5 text-xs border border-gray-300 rounded-lg bg-gray-50 hover:bg-white transition focus:outline-none focus:ring-1 focus:ring-blue-500 shadow-sm">
                        <span class="truncate pr-2 text-gray-700" x-text="selectedAllName"></span>
                        <i class="bi bi-search text-[11px] text-gray-500 shrink-0"></i>
                    </button>
                </div>

                <div x-show="openAll" x-cloak
                    x-transition:enter="ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="absolute right-0 top-full mt-1 w-80 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 p-2.5">
                    <div class="relative mb-2">
                        <i class="bi bi-search absolute left-2.5 top-2.5 text-gray-400 text-xs"></i>
                        <input type="text" x-ref="searchAllInput" x-model="searchAll" placeholder="Search institution name..."
                            class="w-full pl-8 pr-2 py-1.5 text-xs border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500">
                    </div>
                    <div class="max-h-56 overflow-y-auto space-y-0.5">
                        <template x-for="inst in filterInstitutions(searchAll)" :key="inst.id">
                            <div @click="applyInstitutionToAll(inst.id); selectedAllName = inst.name; openAll = false; searchAll = ''"
                                class="px-2.5 py-2 text-xs hover:bg-blue-50 hover:text-blue-700 rounded-lg cursor-pointer flex items-center justify-between text-gray-700">
                                <span x-text="inst.name"></span>
                            </div>
                        </template>
                        <div x-show="filterInstitutions(searchAll).length === 0" class="px-2 py-4 text-center text-xs text-gray-400">
                            No matching institutions found
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Area --}}
    <form action="{{ route('admin.users.bulk-store') }}" method="POST" @submit.prevent="submitForm($event)">
        @csrf

        <div class="bg-white border border-gray-200 shadow-sm rounded-xl">
            <div class="overflow-x-auto min-h-[460px] pb-32">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="w-12 px-3 py-3 text-xs font-semibold text-center text-gray-500 uppercase">#</th>
                            <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase min-w-[190px]">Full Name <span class="text-red-500">*</span></th>
                            <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase min-w-[210px]">Email Address <span class="text-red-500">*</span></th>
                            <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase min-w-[170px]">Password <span class="text-red-500">*</span></th>
                            <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase min-w-[260px]">Institution (Searchable)</th>
                            <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase min-w-[140px]">Role <span class="text-red-500">*</span></th>
                            <th class="w-16 px-3 py-3 text-xs font-semibold text-center text-gray-500 uppercase">Remove</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <template x-for="(row, index) in rows" :key="row.id">
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="px-3 py-3 text-center text-xs font-semibold text-gray-400" x-text="index + 1"></td>
                                
                                {{-- Name --}}
                                <td class="px-4 py-2.5">
                                    <input type="text" :name="`users[${index}][name]`" x-model="row.name" required
                                        placeholder="Full Name"
                                        class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                </td>

                                {{-- Email --}}
                                <td class="px-4 py-2.5">
                                    <input type="email" :name="`users[${index}][email]`" x-model="row.email" required
                                        placeholder="user@vves.org.in"
                                        class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                </td>

                                {{-- Password --}}
                                <td class="px-4 py-2.5">
                                    <div class="relative flex items-center">
                                        <input type="text" :name="`users[${index}][password]`" x-model="row.password" required
                                            placeholder="Min 6 characters"
                                            class="w-full pl-3 pr-8 py-1.5 text-xs border border-gray-300 rounded-lg font-mono focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="row.password = generatePass()" title="Generate random password"
                                            class="absolute right-1 text-gray-400 hover:text-purple-600 p-1">
                                            <i class="bi bi-arrow-repeat text-xs"></i>
                                        </button>
                                    </div>
                                </td>

                                {{-- Institution (Searchable Combobox) --}}
                                <td class="px-4 py-2.5 relative">
                                    <input type="hidden" :name="`users[${index}][institution_id]`" :value="row.institution_id">
                                    
                                    <div class="relative" @click.outside="row.instOpen = false">
                                        <button type="button" @click="row.instOpen = !row.instOpen; if(row.instOpen) { row.instSearch = ''; $nextTick(() => { const el = $refs['instSearch_' + index]; if(el) el.focus(); }); }"
                                            class="w-full flex items-center justify-between text-left px-2.5 py-1.5 text-xs border border-gray-300 rounded-lg bg-white hover:border-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 shadow-sm">
                                            <span class="truncate pr-1" :class="row.institution_id ? 'text-gray-900 font-medium' : 'text-gray-400'"
                                                x-text="getInstitutionName(row.institution_id)"></span>
                                            <div class="flex items-center gap-1 shrink-0">
                                                <i x-show="row.institution_id" @click.stop="row.institution_id = ''; row.instOpen = false"
                                                   class="bi bi-x text-xs text-gray-400 hover:text-red-500 hover:bg-gray-100 rounded p-0.5" title="Clear"></i>
                                                <i class="bi bi-chevron-down text-[10px] text-gray-400"></i>
                                            </div>
                                        </button>

                                        {{-- Search Dropdown Menu --}}
                                        <div x-show="row.instOpen" x-cloak
                                            x-transition:enter="ease-out duration-150"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            class="absolute left-0 top-full mt-1 w-72 max-w-sm bg-white border border-gray-200 rounded-xl shadow-2xl z-50 p-2">
                                            
                                            <div class="relative mb-1.5">
                                                <i class="bi bi-search absolute left-2.5 top-2.5 text-gray-400 text-xs"></i>
                                                <input type="text" :x-ref="'instSearch_' + index" x-model="row.instSearch"
                                                    placeholder="Type to search institution..."
                                                    @keydown.escape="row.instOpen = false"
                                                    class="w-full pl-8 pr-2 py-1 text-xs border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                            </div>

                                            <div class="max-h-48 overflow-y-auto space-y-0.5 divide-y divide-gray-50">
                                                <div @click="row.institution_id = ''; row.instOpen = false"
                                                    class="px-2 py-1.5 text-xs text-gray-400 italic hover:bg-gray-100 rounded-lg cursor-pointer">
                                                    -- No Institution --
                                                </div>
                                                <template x-for="inst in filterInstitutions(row.instSearch)" :key="inst.id">
                                                    <div @click="row.institution_id = inst.id; row.instOpen = false"
                                                        class="px-2.5 py-1.5 text-xs hover:bg-blue-50 hover:text-blue-700 rounded-lg cursor-pointer flex items-center justify-between"
                                                        :class="row.institution_id == inst.id ? 'bg-blue-50 text-blue-700 font-bold' : 'text-gray-700'">
                                                        <span x-text="inst.name" class="leading-tight"></span>
                                                        <i x-show="row.institution_id == inst.id" class="bi bi-check-lg text-blue-600 font-bold ml-1"></i>
                                                    </div>
                                                </template>
                                                <div x-show="filterInstitutions(row.instSearch).length === 0" class="px-2 py-3 text-center text-xs text-gray-400">
                                                    No matching institutions found
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Role --}}
                                <td class="px-4 py-2.5">
                                    <select :name="`users[${index}][role]`" x-model="row.role" required
                                        class="w-full px-2.5 py-1.5 text-xs font-medium border border-gray-300 rounded-lg bg-white focus:ring-blue-500 focus:border-blue-500"
                                        :class="row.role === 'Maker' ? 'text-emerald-700 bg-emerald-50/50' : (row.role === 'Checker' ? 'text-indigo-700 bg-indigo-50/50' : 'text-gray-700')">
                                        <option value="Maker">Maker</option>
                                        <option value="Checker">Checker</option>
                                        <option value="Staff">Staff</option>
                                        <option value="Approver">Approver</option>
                                    </select>
                                </td>

                                {{-- Action --}}
                                <td class="px-3 py-2.5 text-center">
                                    <button type="button" @click="removeRow(index)" :disabled="rows.length <= 1"
                                        class="p-1.5 text-gray-400 hover:text-red-600 disabled:opacity-30 disabled:cursor-not-allowed transition rounded-md">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            {{-- Footer Summary & Submit --}}
            <div class="flex flex-col sm:flex-row items-center justify-between p-4 bg-gray-50 border-t border-gray-200 gap-4">
                <div class="flex items-center gap-3">
                    <button type="button" @click="addRow()"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm transition">
                        <i class="bi bi-plus-lg me-1"></i> Add Another Row
                    </button>
                    <span class="text-xs text-gray-500 font-medium">
                        Total Users to Create: <strong class="text-gray-900" x-text="rows.length"></strong>
                    </span>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.users.index') }}"
                        class="px-4 py-2 text-xs font-medium text-gray-600 hover:text-gray-900 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2.5 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 shadow-md hover:shadow-lg transition">
                        <i class="bi bi-send-check me-2"></i> Create All & Send Credentials
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function bulkUserApp() {
        return {
            institutionsList: @json($institutions->map(fn($i) => ['id' => $i->id, 'name' => $i->name])),
            rows: [
                { id: 1, name: '', email: '', password: '', institution_id: '', role: 'Maker', instOpen: false, instSearch: '' },
                { id: 2, name: '', email: '', password: '', institution_id: '', role: 'Maker', instOpen: false, instSearch: '' },
                { id: 3, name: '', email: '', password: '', institution_id: '', role: 'Checker', instOpen: false, instSearch: '' },
            ],
            nextId: 4,

            filterInstitutions(query) {
                if (!query || !query.trim()) return this.institutionsList;
                const q = query.toLowerCase().trim();
                return this.institutionsList.filter(i => i.name.toLowerCase().includes(q));
            },

            getInstitutionName(id) {
                if (!id) return '-- Select Institution --';
                const found = this.institutionsList.find(i => i.id == id);
                return found ? found.name : '-- Select Institution --';
            },

            generatePass() {
                const chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$';
                let pass = '';
                for (let i = 0; i < 8; i++) {
                    pass += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                return pass;
            },

            addRow() {
                this.rows.push({
                    id: this.nextId++,
                    name: '',
                    email: '',
                    password: this.generatePass(),
                    institution_id: '',
                    role: 'Maker',
                    instOpen: false,
                    instSearch: ''
                });
            },

            addMultipleRows(count) {
                for (let i = 0; i < count; i++) {
                    this.addRow();
                }
            },

            removeRow(index) {
                if (this.rows.length > 1) {
                    this.rows.splice(index, 1);
                }
            },

            generateAllPasswords() {
                this.rows.forEach(r => {
                    r.password = this.generatePass();
                });
            },

            setAllRoles(role) {
                this.rows.forEach(r => {
                    r.role = role;
                });
            },

            applyInstitutionToAll(instId) {
                if (!instId) return;
                this.rows.forEach(r => {
                    r.institution_id = instId;
                });
            },

            submitForm(event) {
                // Quick validation
                const emptyRow = this.rows.find(r => !r.name.trim() || !r.email.trim() || !r.password.trim());
                if (emptyRow) {
                    alert('Please enter Name, Email, and Password for all rows.');
                    return;
                }

                if (confirm(`Are you sure you want to create ${this.rows.length} users? Login credentials will be emailed to all of them.`)) {
                    event.target.submit();
                }
            }
        }
    }
</script>
@endsection
