@extends('layouts.admin.app')
@section('title', 'SMTP Settings')

@section('content')
    <div class="space-y-6 p-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">⚙️ SMTP Settings</h1>
        </div>

        {{-- Session Alerts --}}
        @if (session('success'))
            <div class="flex p-4 text-sm text-green-700 border border-green-200 rounded-lg bg-green-50 shadow-md" role="alert">
                <i class="bi bi-check-circle-fill me-3"></i>
                <div class="font-medium">{{ session('success') }}</div>
            </div>
        @endif

        {{-- SMTP Form Card --}}
        <div class="bg-white border border-gray-200 shadow-lg rounded-2xl p-6">
            <form action="{{ route('admin.smtp.store') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Input Styling Definition --}}
                @php
                    $inputClasses = 'w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition shadow-sm';
                    $labelClasses = 'block text-sm font-medium text-gray-700 mb-1';
                @endphp

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    {{-- Host --}}
                    <div>
                        <label for="host" class="{{ $labelClasses }}">Host</label>
                        <input type="text" id="host" name="host" value="{{ $setting->host ?? '' }}"
                            class="{{ $inputClasses }}" placeholder="e.g., smtp.gmail.com">
                    </div>

                    {{-- Port (ID added for JavaScript targeting) --}}
                    <div>
                        <label for="port" class="{{ $labelClasses }}">Port</label>
                        <input type="number" id="port" name="port" value="{{ $setting->port ?? '' }}"
                            class="{{ $inputClasses }}" placeholder="e.g., 587 or 465">
                    </div>

                    {{-- Username --}}
                    <div>
                        <label for="username" class="{{ $labelClasses }}">Username</label>
                        <input type="text" id="username" name="username" value="{{ $setting->username ?? '' }}"
                            class="{{ $inputClasses }}" placeholder="Your SMTP username">
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="{{ $labelClasses }}">Password</label>
                        <input type="password" id="password" name="password" value="{{ $setting->password ?? '' }}"
                            class="{{ $inputClasses }}" placeholder="Your SMTP password">
                    </div>
                </div>

                <hr class="border-gray-200">

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                    {{-- Encryption (ID and onChange added) --}}
                    <div>
                        <label for="encryption" class="{{ $labelClasses }}">Encryption</label>
                        <select id="encryption" name="encryption" class="{{ $inputClasses }}" onchange="syncPort()">
                            @php $currentEncryption = strtolower($setting->encryption ?? 'tls'); @endphp
                            <option value="tls" @selected($currentEncryption == 'tls')>TLS (Recommended)</option>
                            <option value="ssl" @selected($currentEncryption == 'ssl')>SSL</option>
                            <option value="none" @selected($currentEncryption == 'none' || $currentEncryption == '')>None (Port
                                25)</option>
                        </select>
                    </div>

                    {{-- From Address --}}
                    <div>
                        <label for="from_address" class="{{ $labelClasses }}">From Address</label>
                        <input type="email" id="from_address" name="from_address" value="{{ $setting->from_address ?? '' }}"
                            class="{{ $inputClasses }}" placeholder="e.g., no-reply@yoursite.com">
                    </div>

                    {{-- From Name --}}
                    <div>
                        <label for="from_name" class="{{ $labelClasses }}">From Name</label>
                        <input type="text" id="from_name" name="from_name" value="{{ $setting->from_name ?? '' }}"
                            class="{{ $inputClasses }}" placeholder="e.g., Your Company Name">
                    </div>
                </div>

                <hr class="border-gray-200">

                {{-- Receiving Email --}}
                <div>
                    <label for="receiving_email" class="{{ $labelClasses }}">Receiving Email (where admin wants to receive
                        leads)</label>
                    <input type="email" id="receiving_email" name="receiving_email"
                        value="{{ $setting->receiving_email ?? '' }}" class="{{ $inputClasses }}"
                        placeholder="e.g., admin@yoursite.com">
                </div>

                {{-- Save and Test Buttons --}}
                <div class="pt-4 flex items-center gap-4">
                    <button type="submit"
                        class="px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl shadow-lg hover:bg-indigo-700 hover:-translate-y-0.5 transition active:scale-95">
                        <i class="bi bi-save me-2"></i> Save Configuration
                    </button>
                    
                    <button type="button" id="test-connection-btn"
                        class="px-6 py-2.5 text-sm font-bold text-indigo-600 bg-indigo-50 border border-indigo-200 rounded-xl hover:bg-indigo-100 transition active:scale-95 flex items-center gap-2">
                        <span class="spinner hidden animate-spin w-4 h-4 border-2 border-current border-t-transparent rounded-full" role="status"></span>
                        <i class="bi bi-send-check test-icon"></i>
                        Test Connection
                    </button>
                </div>
            </form>
        </div>

        {{-- Toast Notification for Test Result --}}
        <div id="smtp-toast" class="fixed bottom-10 right-10 z-50 transform translate-y-20 opacity-0 transition-all duration-300">
            <div class="flex items-center p-4 rounded-2xl shadow-2xl border bg-white min-w-[300px]">
                <div class="toast-icon-container w-10 h-10 rounded-full flex items-center justify-center mr-3"></div>
                <div class="flex-1">
                    <p class="toast-title font-bold text-sm"></p>
                    <p class="toast-message text-xs text-gray-500"></p>
                </div>
                <button type="button" onclick="hideToast()" class="ml-4 text-gray-400 hover:text-gray-600">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- JavaScript to sync port and encryption and handle test connection --}}
    <script>
        function syncPort() {
            const encryptionSelect = document.getElementById('encryption');
            const portInput = document.getElementById('port');
            const encryption = encryptionSelect.value;
            const currentPort = portInput.value;

            const ports = {
                'tls': '587',
                'ssl': '465',
                'none': '25'
            };

            if (!currentPort || Object.values(ports).includes(currentPort)) {
                if (ports[encryption]) {
                    portInput.value = ports[encryption];
                }
            }
        }

        document.getElementById('test-connection-btn').addEventListener('click', async function() {
            const btn = this;
            const icon = btn.querySelector('.test-icon');
            const spinner = btn.querySelector('.spinner');
            const form = btn.closest('form');
            
            // UI Feedback
            btn.disabled = true;
            icon.classList.add('hidden');
            spinner.classList.remove('hidden');
            
            const formData = new FormData(form);
            
            try {
                const response = await fetch("{{ route('admin.smtp.test') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    showToast('Success!', data.message, 'success');
                } else {
                    showToast('Connection Failed', data.message || 'Check your credentials.', 'error');
                }
            } catch (error) {
                showToast('Error', 'An unexpected error occurred.', 'error');
            } finally {
                btn.disabled = false;
                icon.classList.remove('hidden');
                spinner.classList.add('hidden');
            }
        });

        function showToast(title, message, type) {
            const toast = document.getElementById('smtp-toast');
            const toastTitle = toast.querySelector('.toast-title');
            const toastMsg = toast.querySelector('.toast-message');
            const iconContainer = toast.querySelector('.toast-icon-container');
            
            toastTitle.innerText = title;
            toastMsg.innerText = message;
            
            if (type === 'success') {
                toastTitle.className = 'toast-title font-bold text-sm text-emerald-600';
                iconContainer.className = 'toast-icon-container w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-emerald-100 text-emerald-600';
                iconContainer.innerHTML = '<i class="bi bi-check2-circle text-xl"></i>';
                toast.querySelector('.flex').className = 'flex items-center p-4 rounded-2xl shadow-2xl border border-emerald-100 bg-white min-w-[300px]';
            } else {
                toastTitle.className = 'toast-title font-bold text-sm text-rose-600';
                iconContainer.className = 'toast-icon-container w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-rose-100 text-rose-600';
                iconContainer.innerHTML = '<i class="bi bi-exclamation-triangle text-xl"></i>';
                toast.querySelector('.flex').className = 'flex items-center p-4 rounded-2xl shadow-2xl border border-rose-100 bg-white min-w-[300px]';
            }
            
            toast.classList.remove('translate-y-20', 'opacity-0');
            
            setTimeout(hideToast, 5000);
        }

        function hideToast() {
            const toast = document.getElementById('smtp-toast');
            toast.classList.add('translate-y-20', 'opacity-0');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const portInput = document.getElementById('port');
            if (!portInput.value) {
                syncPort();
            }
        });
    </script>
@endsection
