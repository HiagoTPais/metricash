<script setup lang="ts">
import MainLayout from '@/Layouts/MainLayout.vue'
import { ref, onMounted } from 'vue'
import axios from 'axios'

// Icons components (you'll need to create these or use a library like @heroicons/vue)
const ArrowUpIcon = {
    template: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
    </svg>`
}

const ArrowDownIcon = {
    template: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
    </svg>`
}

const stats = ref<{
    name: string,
    value: string | null,
    change: { type: string, value: string },
    icon: any
}[]>([
    {
        name: 'Total Balance',
        value: null,
        change: { type: 'increase', value: '' },
        icon: ArrowUpIcon
    },
    {
        name: 'Monthly Income',
        value: null,
        change: { type: 'increase', value: '' },
        icon: ArrowUpIcon
    },
    {
        name: 'Monthly Expenses',
        value: null,
        change: { type: 'decrease', value: '' },
        icon: ArrowDownIcon
    },
    {
        name: 'Profit Margin',
        value: null,
        change: { type: 'increase', value: '' },
        icon: ArrowUpIcon
    }
])

const statsLoading = ref(true)

const currencies = ref<any>(null)
const loading = ref(true)
const error = ref<string | null>(null)

const currencyList = [
    { id: 'usd', name: 'US Dollar', symbol: 'USD' },
    { id: 'eur', name: 'Euro', symbol: 'EUR' },
    { id: 'bitcoin', name: 'Bitcoin', symbol: 'BTC' },
    { id: 'ethereum', name: 'Ethereum', symbol: 'ETH' },
    { id: 'brl', name: 'Brazilian Real', symbol: 'BRL' },
    { id: 'gbp', name: 'British Pound', symbol: 'GBP' },
    { id: 'jpy', name: 'Japanese Yen', symbol: 'JPY' },
]

onMounted(async () => {
    try {
        // Fetch dashboard stats
        const statsRes = await axios.get('/api/dashboard-stats')
        const s = statsRes.data
        stats.value[0].value = formatCurrency(s.total_balance)
        stats.value[1].value = formatCurrency(s.monthly_income)
        stats.value[2].value = formatCurrency(s.monthly_expenses)
        stats.value[3].value = s.profit_margin.toFixed(2) + '%'
        statsLoading.value = false
    } catch (e) {
        statsLoading.value = false
    }

    try {
        const res = await axios.get('/api/currencies')
        currencies.value = res.data
    } catch (e: any) {
        error.value = 'Failed to load currency data.'
    } finally {
        loading.value = false
    }
})

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount)
}
</script>

<template>
    <MainLayout>
        <template #default>
            <div class="space-y-6">
                <!-- Page Header -->
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <div v-for="stat in stats" :key="stat.name" class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <component :is="stat.icon" class="h-6 w-6 text-gray-400" aria-hidden="true" />
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">{{ stat.name }}</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900">
                                                <span v-if="!statsLoading && stat.value !== null">{{ stat.value }}</span>
                                                <span v-else class="text-gray-400 animate-pulse">Loading...</span>
                                            </div>
                                            <!-- Optionally, you can show change values if you want -->
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CoinMarketCap-style Table -->
                <div class="mt-10">
                    <h2 class="text-xl font-semibold mb-4">Market Overview</h2>
                    <div class="bg-white shadow rounded-lg overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Symbol</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price (USD)</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">24h Change</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Market Cap</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-if="loading">
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Loading...</td>
                                </tr>
                                <tr v-else-if="error">
                                    <td colspan="6" class="px-6 py-4 text-center text-red-500">{{ error }}</td>
                                </tr>
                                <tr v-else v-for="(currency, idx) in currencyList" :key="currency.id">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ idx + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ currency.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold">{{ currency.symbol }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span v-if="currencies && currencies[currency.id] && currencies[currency.id].usd">
                                            {{ formatCurrency(currencies[currency.id].usd) }}
                                        </span>
                                        <span v-else>-</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span v-if="currencies && currencies[currency.id] && currencies[currency.id].usd_24h_change">
                                            <span :class="currencies[currency.id].usd_24h_change >= 0 ? 'text-green-600' : 'text-red-600'">
                                                {{ currencies[currency.id].usd_24h_change.toFixed(2) }}%
                                            </span>
                                        </span>
                                        <span v-else>-</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span v-if="currencies && currencies[currency.id] && currencies[currency.id].usd_market_cap">
                                            {{ formatCurrency(currencies[currency.id].usd_market_cap) }}
                                        </span>
                                        <span v-else>-</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </template>
    </MainLayout>
</template>
