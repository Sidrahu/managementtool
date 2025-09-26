<div>
    <canvas id="tasksStatusChart"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('tasksStatusChart').getContext('2d');

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json(array_keys($data)),
                    datasets: [{
                        data: @json(array_values($data)),
                        backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444']
                    }]
                }
            });
        });
    </script>
</div>
