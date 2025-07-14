<template>
    <MainLayout>
        <template #default>
            <div class="space-y-6">
                <!-- Page Header -->
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">Financial Indicators</h1>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <div class="flex space-x-3">
                            <select v-model="period" @change="updateFilters" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                                <option value="monthly">Monthly</option>
                                <option value="annual">Annual</option>
                            </select>
                            <input type="date" v-model="startDate" @change="updateFilters" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                            <input type="date" v-model="endDate" @change="updateFilters" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                        </div>
                    </div>
                </div>

                <!-- Account Balances Section -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Account Balances</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            <div v-for="account in accountBalances" :key="account.id" 
                                class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">{{ account.name }}</h4>
                                        <p class="text-xs text-gray-500">{{ account.code }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-semibold" 
                                            :class="account.balance >= 0 ? 'text-green-600' : 'text-red-600'">
                                            {{ formatCurrency(account.balance) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Income vs Expenses Section -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Income vs Expenses</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="space-y-4">
                            <div v-for="(data, index) in incomeExpensesData" :key="index" 
                                class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-gray-900">{{ formatPeriod(data.period) }}</h4>
                                    <span class="text-sm font-semibold" 
                                        :class="data.net >= 0 ? 'text-green-600' : 'text-red-600'">
                                        {{ formatCurrency(data.net) }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500">Income</p>
                                        <p class="text-sm font-medium text-green-600">{{ formatCurrency(data.income) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Expenses</p>
                                        <p class="text-sm font-medium text-red-600">{{ formatCurrency(data.expenses) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profit/Loss Section -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Profit/Loss Analysis</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                <h4 class="text-sm font-medium text-green-800">Total Income</h4>
                                <p class="text-2xl font-bold text-green-600">{{ formatCurrency(profitLoss.income) }}</p>
                            </div>
                            <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                                <h4 class="text-sm font-medium text-red-800">Total Expenses</h4>
                                <p class="text-2xl font-bold text-red-600">{{ formatCurrency(profitLoss.expenses) }}</p>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                <h4 class="text-sm font-medium text-blue-800">Net Profit</h4>
                                <p class="text-2xl font-bold" 
                                    :class="profitLoss.is_profitable ? 'text-green-600' : 'text-red-600'">
                                    {{ formatCurrency(profitLoss.net_profit) }}
                                </p>
                                <p class="text-sm text-gray-600">Margin: {{ profitLoss.profit_margin.toFixed(1) }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Projected Cash Flow Section -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Projected Cash Flow</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h4 class="text-sm font-medium text-gray-900">Average Monthly Income</h4>
                                    <p class="text-lg font-semibold text-green-600">{{ formatCurrency(projectedCashFlow.avg_income) }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h4 class="text-sm font-medium text-gray-900">Average Monthly Expenses</h4>
                                    <p class="text-lg font-semibold text-red-600">{{ formatCurrency(projectedCashFlow.avg_expenses) }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <h4 class="text-sm font-medium text-gray-900">Projections</h4>
                                <div v-for="(projection, index) in projectedCashFlow.projections" :key="index" 
                                    class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <h5 class="text-sm font-medium text-blue-900">{{ formatPeriod(projection.month) }}</h5>
                                        <span class="text-sm font-semibold" 
                                            :class="projection.projected_net >= 0 ? 'text-green-600' : 'text-red-600'">
                                            {{ formatCurrency(projection.projected_net) }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 text-xs">
                                        <div>
                                            <p class="text-gray-600">Income</p>
                                            <p class="font-medium text-green-600">{{ formatCurrency(projection.projected_income) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600">Expenses</p>
                                            <p class="font-medium text-red-600">{{ formatCurrency(projection.projected_expenses) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </template>
    </MainLayout>
</template>

<script setup lang="ts">
import MainLayout from '@/Layouts/MainLayout.vue'
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

interface AccountBalance {
    id: number
    name: string
    code: string
    type: string
    balance: number
}

interface IncomeExpenseData {
    period: string
    income: number
    expenses: number
    net: number
}

interface ProfitLoss {
    income: number
    expenses: number
    net_profit: number
    profit_margin: number
    is_profitable: boolean
}

interface ProjectedCashFlow {
    historical: any[]
    projections: any[]
    avg_income: number
    avg_expenses: number
}

interface EquityChange {
    change: number
    is_positive: boolean
}

const props = defineProps<{
    accountBalances: AccountBalance[]
    incomeExpensesData: IncomeExpenseData[]
    projectedCashFlow: ProjectedCashFlow
    profitLoss: ProfitLoss
    equityChange: EquityChange
    period: string
    startDate: string
    endDate: string
}>()

const period = ref(props.period)
const startDate = ref(props.startDate)
const endDate = ref(props.endDate)

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount)
}

const formatPeriod = (period: string) => {
    if (period.includes('-')) {
        const [year, month] = period.split('-')
        const date = new Date(parseInt(year), parseInt(month) - 1)
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long' })
    } else {
        return `Year ${period}`
    }
}

const updateFilters = () => {
    router.get('/indicators', {
        period: period.value,
        start_date: startDate.value,
        end_date: endDate.value
    }, {
        preserveState: true,
        preserveScroll: true
    })
}
</script> 