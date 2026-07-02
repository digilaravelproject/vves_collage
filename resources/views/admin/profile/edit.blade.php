@extends('layouts.admin.app')
@section('title', 'Admin Profile')

@section('content')
<div class="max-w-2xl p-6 mx-auto bg-white border border-gray-200 shadow-lg rounded-2xl">
    <div class="flex items-center justify-between pb-4 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-900">Admin Profile Settings</h1>
    </div>

    @if(session('success'))
        <div class="p-3 mt-4 text-green-700 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.profile.update') }}" method="POST" class="mt-6 space-y-6">
        @csrf
        @method('PUT')

        {{-- Name --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                class="w-full px-3 py-2 mt-1 text-gray-900 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            @error('name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                class="w-full px-3 py-2 mt-1 text-gray-900 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            @error('email') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
            <input type="password" id="password" name="password"
                class="w-full px-3 py-2 mt-1 text-gray-900 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                placeholder="Leave blank to keep current password">
            @error('password') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                class="w-full px-3 py-2 mt-1 text-gray-900 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- Submit Button --}}
        <div class="pt-4 border-t border-gray-200">
            <button type="submit"
                class="w-full px-4 py-2 font-medium text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Update Profile
            </button>
        </div>
    </form>
</div>
@endsection
