{{-- Remove @extends and @section, use Blade layout slot --}}
<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <h2 class="text-2xl font-bold text-white mb-6">Expenses Chart by Budget</h2>
        <div class="mb-4">
            <label for="categoryFilter" class="block text-sm font-medium text-white mb-1">Filter by Category:</label>
            <select id="categoryFilter" class="border rounded px-3 py-2 w-full max-w-xs">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <canvas id="expensesChart" height="120"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    let chartInstance = null;
    function fetchAndRenderChart(categoryId = '') {
        let url = "{{ route('expenses.chart.data') }}";
        if (categoryId) {
            url += '?category_id=' + categoryId;
        }
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('expensesChart').getContext('2d');
                if (chartInstance) {
                    chartInstance.destroy();
                }
                chartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Total Expenses',
                            data: data.expenses,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
    }
    document.addEventListener('DOMContentLoaded', function () {
        const filter = document.getElementById('categoryFilter');
        fetchAndRenderChart();
        filter.addEventListener('change', function() {
            fetchAndRenderChart(this.value);
        });
    });
    </script>
</x-app-layout>
