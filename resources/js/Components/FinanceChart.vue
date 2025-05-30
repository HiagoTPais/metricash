<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Line } from 'vue-chartjs'
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    Filler
} from 'chart.js'

// Register ChartJS components
ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    Filler
)

interface ChartData {
    labels: string[]
    datasets: {
        label: string
        data: number[]
        borderColor: string
        backgroundColor: string
        tension: number
        fill: boolean
    }[]
}

// Chart configuration
const chartData = ref<ChartData>({
    labels: [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ],
    datasets: [
        {
            label: 'Income',
            data: [5000, 6000, 5500, 7000, 6500, 8000, 7500, 9000, 8500, 10000, 9500, 11000],
            borderColor: '#10B981', // Green
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.4,
            fill: true
        },
        {
            label: 'Expenses',
            data: [4000, 4500, 5000, 5500, 6000, 6500, 7000, 7500, 8000, 8500, 9000, 9500],
            borderColor: '#EF4444', // Red
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            tension: 0.4,
            fill: true
        }
    ]
})

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        title: {
            display: true,
            text: 'Income and Expenses Progression',
            font: {
                size: 16,
                weight: 'bold' as const
            }
        },
        legend: {
            position: 'top' as const,
            labels: {
                usePointStyle: true,
                padding: 20
            }
        },
        tooltip: {
            mode: 'index' as const,
            intersect: false,
            callbacks: {
                label: (context: any) => {
                    return `${context.dataset.label}: $${context.parsed.y.toLocaleString()}`
                }
            }
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                callback: (value: any) => `$${value.toLocaleString()}`
            }
        }
    }
}
</script>

<template>
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="h-[400px]">
            <Line :data="chartData" :options="chartOptions" />
        </div>
    </div>
</template> 