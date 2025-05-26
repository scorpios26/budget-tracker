<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        {{-- <h2 class="text-2xl font-semibold mb-6">Dashboard</h2> --}}
        <div x-data="{ open: false }" class="mb-6">
    <!-- Add Category Button -->
    <button @click="open = true"
        class="mb-1 bg-blue-600 text-white text-sm px-3 py-1 rounded hover:bg-blue-700">
        + Add Category
    </button>

    <!-- Modal Category -->
    <div x-show="open"
         x-transition
         class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div @click.away="open = false" class="bg-white p-6 rounded shadow-lg w-full max-w-md">

            <h2 class="text-lg font-bold mb-4">Add Category</h2>

            <form action="{{ route('category.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <input type="text" name="name" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="open = false"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Budget -->
<div x-data="{ open: false }" class="bg-blue-100 p-6 rounded-lg shadow relative">
    <h3 class="text-lg font-bold text-blue-700">Total Budget</h3>
    <p class="text-2xl mt-2 font-semibold text-blue-900">₱{{ number_format($budgetAmount, 2) }}</p>

    <!-- Add Budget Button -->
    <button @click="open = true"
        class="absolute top-4 right-4 bg-blue-600 text-white text-sm px-3 py-1 rounded hover:bg-blue-700">
        + Add Budget
    </button>

    <!-- Modal -->
    <div x-show="open"
         x-transition
         class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div @click.away="open = false" class="bg-white p-6 rounded shadow-lg w-full max-w-md">

            <h2 class="text-lg font-bold mb-4">Add Budget</h2>

            <form action="{{ route('budget.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Amount</label>
                    <input type="number" name="amount" step="0.01" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Month</label>
                    <input type="month" name="month" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="open = false"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


            <!-- Total Expenses -->
<div x-data="{ openExpense: false }" class="bg-red-100 p-6 rounded-lg shadow relative">
    <h3 class="text-lg font-bold text-red-700">Total Expenses</h3>
    <p class="text-2xl mt-2 font-semibold text-red-900">₱{{ number_format($totalExpenses, 2) }}</p>

    <!-- Add Expense Button -->
    <button @click="openExpense = true"
        class="absolute top-4 right-4 bg-red-600 text-white text-sm px-3 py-1 rounded hover:bg-red-700">
        + Add Expense
    </button>

    <!-- Modal -->
    <div x-show="openExpense"
         x-transition
         class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div @click.away="openExpense = false" class="bg-white p-6 rounded shadow-lg w-full max-w-md">

            <h2 class="text-lg font-bold mb-4">Add Expense</h2>

            <form action="{{ route('expense.store') }}" method="POST" class="space-y-4">
    @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Amount</label>
                    <input type="number" name="amount" step="0.01" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category_id" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>


                <div>
                    <label class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="date" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="openExpense = false"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Save
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


            <!-- Remaining -->
            <div class="bg-green-100 p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold text-green-700">Remaining Balance</h3>
                <p class="text-2xl mt-2 font-semibold text-green-900">₱{{ number_format($remaining, 2) }}</p>
            </div>
        </div>
    </div>

    @if (session('success'))
    <script>
        Swal.fire({
            title: 'Success!',
            text: '{{ session('success') }}',
            icon: 'success',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
@endif

@if ($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ $errors->first() }}',
    });
</script>
@endif





    
</x-app-layout>
