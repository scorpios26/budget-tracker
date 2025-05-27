<x-app-layout>
    <div x-data="{
        openBudget: false,
        openCategory: false,
        openExpense: false,
        editOpen: false,
        expenseId: null,
        editTitle: '',
        editAmount: '',
        editDate: '',
        editCategory: '',
        showTable: 'expenses', // 'expenses' or 'budget'
        openEditModal(id, title, amount, date, categoryId) {
            this.editOpen = true;
            this.expenseId = id;
            this.editTitle = title;
            this.editAmount = amount;
            this.editDate = date;
            this.editCategory = categoryId;
        }
    }">
    <div class="max-w-7xl mx-auto p-6">
        

        {{-- Dashboard Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Budget -->
            <div class="bg-blue-100 p-6 rounded-lg shadow cursor-pointer" @click="showTable = 'budget'">
                <h3 class="text-lg font-bold text-blue-700">Total Budget</h3>
                <p class="text-2xl mt-2 font-semibold text-blue-900">₱{{ number_format($budgetAmount, 2) }}</p>
            </div>

            <!-- Total Expenses -->
            <div class="bg-red-100 p-6 rounded-lg shadow cursor-pointer" @click="showTable = 'expenses'">
                <h3 class="text-lg font-bold text-red-700">Total Expenses</h3>
                <p class="text-2xl mt-2 font-semibold text-red-900">₱{{ number_format($totalExpenses, 2) }}</p>
            </div>

            <!-- Remaining Balance -->
            <div class="bg-green-100 p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold text-green-700">Remaining Balance</h3>
                <p class="text-2xl mt-2 font-semibold text-green-900">₱{{ number_format($remaining, 2) }}</p>
            </div>
        </div>

        {{-- Action Buttons Row --}}
        <div x-data="{ openBudget: false, openCategory: false, openExpense: false }" class="mt-6 flex flex-wrap gap-3">
            <button @click="openBudget = true"
                class="bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700">+ Add Budget</button>

            <button @click="openCategory = true"
                class="bg-green-600 text-white text-sm px-4 py-2 rounded hover:bg-green-700">+ Add Category</button>

            <button @click="openExpense = true"
                class="bg-red-600 text-white text-sm px-4 py-2 rounded hover:bg-red-700">+ Add Expense</button>

            {{-- Budget Modal --}}
            <div x-show="openBudget" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div @click.away="openBudget = false" class="bg-white p-6 rounded shadow-lg w-full max-w-md">
                    <h2 class="text-lg font-bold mb-4">Add Budget</h2>
                    <form action="{{ route('budget.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Amount</label>
                            <input type="number" name="amount" step="0.01" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-200 focus:border-blue-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Month</label>
                            <input type="month" name="month" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" @click="openBudget = false"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Category Modal --}}
            <div x-show="openCategory" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div @click.away="openCategory = false" class="bg-white p-6 rounded shadow-lg w-full max-w-md">
                    <h2 class="text-lg font-bold mb-4">Add Category</h2>
                    <form action="{{ route('category.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category</label>
                            <input type="text" name="name" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" @click="openCategory = false"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Expense Modal --}}
            <div x-show="openExpense" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
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
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tables Grid -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-5 gap-2">
            <!-- Expenses Table (80%) -->
            <div class="md:col-span-4 bg-white p-6 rounded-lg shadow overflow-x-auto" x-show="showTable === 'expenses'">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">All Expenses</h3>
                    <a href="{{ route('expenses.exportCsv') }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm">Download CSV</a>
                </div>
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100 text-left text-sm font-medium text-gray-700">
                            <th class="px-4 py-2">Title</th>
                            <th class="px-4 py-2">Amount</th>
                            <th class="px-4 py-2">Category</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $expense)
                            <tr class="border-b text-sm text-gray-700">
                                <td class="px-4 py-2">{{ $expense->title }}</td>
                                <td class="px-4 py-2">₱{{ number_format($expense->amount, 2) }}</td>
                                <td class="px-4 py-2">
                                    {{ $expense->category ? $expense->category->name : 'N/A' }}
                                </td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($expense->date)->format('F d, Y') }}</td>
                                <td class="px-4 py-2 space-x-2">
                                    <!-- Edit Button -->
                                    <button @click="openEditModal({{ $expense->id }}, '{{ $expense->title }}', '{{ $expense->amount }}', '{{ $expense->date }}', {{ $expense->category_id }})"
                                        class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm">
                                        Edit
                                    </button>
                                    <!-- Delete Form -->
                                    <form action="{{ route('expense.destroy', $expense->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-2 py-1 text-xs text-white bg-red-600 rounded hover:bg-red-700"
                                            onclick="return confirm('Are you sure you want to delete this expense?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $expenses->links() }}
                </div>
            </div>

            <!-- Budget Table (80%) -->
            <div class="md:col-span-4 bg-white p-6 rounded-lg shadow overflow-x-auto" x-show="showTable === 'budget'">
                <h3 class="text-lg font-bold mb-4">All Budgets</h3>
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100 text-left text-sm font-medium text-gray-700">
                            <th class="px-4 py-2">Amount</th>
                            <th class="px-4 py-2">Month</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($budgets as $budget)
                            <tr class="border-b text-sm text-gray-700">
                                <td class="px-4 py-2">₱{{ number_format($budget->amount, 2) }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($budget->month)->format('F Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Category Table (20%) -->
            <div class="md:col-span-1 bg-white p-6 rounded-lg shadow overflow-x-auto">
                <h3 class="text-lg font-bold mb-4">All Categories</h3>
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100 text-left text-sm font-medium text-gray-700">
                            <th class="px-4 py-2">Category Name</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr class="border-b text-sm text-gray-700">
                                <td class="px-4 py-2">{{ $category->name }}</td>
                                <td class="px-4 py-2 space-x-2">
                                    <!-- Optional: Add delete button for category -->
                                    <form action="{{ route('category.destroy', $category->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-2 py-1 text-xs text-white bg-red-600 rounded hover:bg-red-700"
                                            onclick="return confirm('Are you sure you want to delete this category?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Edit Expense Modal --}}
        <div x-show="editOpen" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div @click.away="editOpen = false" class="bg-white p-6 rounded shadow-lg w-full max-w-md">
                <h2 class="text-lg font-bold mb-4">Edit Expense</h2>
                <form :action="`/expense/${expenseId}`" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input
                            type="text"
                            name="title"
                            x-model="editTitle"
                            required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Amount</label>
                        <input
                            type="number"
                            name="amount"
                            step="0.01"
                            x-model="editAmount"
                            required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <select
                            name="category_id"
                            x-model="editCategory"
                            required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        >
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option :selected="editCategory == {{ $category->id }}" value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date</label>
                        <input
                            type="date"
                            name="date"
                            x-model="editDate"
                            required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        >
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="editOpen = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">Update</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

        <script>
            function openEditModal(id) {
                document.querySelector('[x-data]').__x.$data.editOpen = true;
                document.querySelector('[x-data]').__x.$data.expenseId = id;
            }
        </script>
        <script>
        function openEditModal(id, title, amount, date, categoryId) {
            const component = document.querySelector('[x-data]').__x.$data;
            component.editOpen = true;
            component.expenseId = id;
            component.editTitle = title;
            component.editAmount = amount;
            component.editDate = date;
            component.editCategory = categoryId;
        }
    </script>




        {{-- SweetAlert Messages --}}
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
    </div>
</x-app-layout>
