<div>
    <canvas id="usersGrowthChart"></canvas>

    <script>
        document.addEventListener('livewire:init', () => {
            const ctx = document.getElementById('usersGrowthChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Users',
                        data: @json($data),
                        borderColor: 'rgb(59, 130, 246)',  
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        fill: true,
                        tension: 0.3
                    }]
                }
            });
        });
    </script>
</div>
