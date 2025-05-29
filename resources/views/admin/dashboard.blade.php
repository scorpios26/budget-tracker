@extends('layouts.app')

@section('content')
<div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-blue-100 p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold text-blue-700">Total Users</h2>
            <p class="text-2xl mt-2 font-bold text-blue-900">{{ \App\Models\User::count() }}</p>
        </div>
        <div class="bg-green-100 p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold text-green-700">Total Budgets</h2>
            <p class="text-2xl mt-2 font-bold text-green-900">{{ \App\Models\Budget::count() }}</p>
        </div>
        <div class="bg-red-100 p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold text-red-700">Total Expenses</h2>
            <p class="text-2xl mt-2 font-bold text-red-900">{{ \App\Models\Expense::count() }}</p>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Recent Users</h2>
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-100 text-left text-sm font-medium text-gray-700">
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Admin</th>
                    <th class="px-4 py-2">Registered</th>
                </tr>
            </thead>
            <tbody>
                @foreach (\App\Models\User::latest()->take(5)->get() as $user)
                    <tr class="border-b text-sm text-gray-700">
                        <td class="px-4 py-2">{{ $user->name }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">{{ $user->is_admin ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-2">{{ $user->created_at->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
