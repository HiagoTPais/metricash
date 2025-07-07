<template>
    <MainLayout>
        <template #default>
            <div class="space-y-6">
                <!-- Page Header -->
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">Transaction History</h1>
                        <p class="mt-2 text-sm text-gray-700">View and filter your cryptocurrency transactions</p>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Filters</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Type</label>
                                <select v-model="filters.type" @change="loadTransactions" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">All Types</option>
                                    <option value="send">Send</option>
                                    <option value="receive">Receive</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Currency</label>
                                <select v-model="filters.currency" @change="loadTransactions" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">All Currencies</option>
                                    <option v-for="currency in currencies" :key="currency.symbol" :value="currency.symbol">
                                        {{ currency.symbol }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" v-model="filters.startDate" @change="loadTransactions" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="date" v-model="filters.endDate" @change="loadTransactions" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transactions Table -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Transactions</h3>
                        <p class="mt-1 text-sm text-gray-500">Detailed transaction history with filters</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Currency</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction Hash</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="transaction in transactions.data" :key="transaction.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ formatDate(transaction.created_at) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                            :class="transaction.type === 'receive' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                                            {{ transaction.type === 'receive' ? 'Received' : 'Sent' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ transaction.currency }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm" 
                                        :class="transaction.type === 'receive' ? 'text-green-600' : 'text-red-600'">
                                        {{ transaction.type === 'receive' ? '+' : '-' }}{{ formatCryptoAmount(transaction.amount) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ formatCryptoAmount(transaction.fee) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                            :class="getStatusClass(transaction.status)">
                                            {{ transaction.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center">
                                            <span class="font-mono text-xs">{{ transaction.transaction_hash.substring(0, 10) }}...</span>
                                            <button @click="copyHash(transaction.transaction_hash)" class="ml-2 text-indigo-600 hover:text-indigo-900">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div v-if="transactions.last_page > 1" class="px-4 py-3 border-t border-gray-200 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <button @click="loadTransactions(transactions.current_page - 1)" 
                                    :disabled="transactions.current_page === 1"
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50">
                                    Previous
                                </button>
                                <button @click="loadTransactions(transactions.current_page + 1)" 
                                    :disabled="transactions.current_page === transactions.last_page"
                                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50">
                                    Next
                                </button>
                            </div>
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Showing <span class="font-medium">{{ transactions.from }}</span> to <span class="font-medium">{{ transactions.to }}</span> of <span class="font-medium">{{ transactions.total }}</span> results
                                    </p>
                                </div>
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                        <button @click="loadTransactions(transactions.current_page - 1)" 
                                            :disabled="transactions.current_page === 1"
                                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50">
                                            Previous
                                        </button>
                                        <button @click="loadTransactions(transactions.current_page + 1)" 
                                            :disabled="transactions.current_page === transactions.last_page"
                                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50">
                                            Next
                                        </button>
                                    </nav>
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

interface Transaction {
    id: number
    transaction_hash: string
    currency: string
    type: 'send' | 'receive'
    amount: number
    fee: number
    from_address: string
    to_address: string
    status: string
    created_at: string
    amount_in_usd: number
}

interface Currency {
    id: number
    symbol: string
    name: string
}

interface PaginatedData {
    data: Transaction[]
    current_page: number
    last_page: number
    from: number
    to: number
    total: number
}

const props = defineProps<{
    currencies: Currency[]
}>()

const transactions = ref<PaginatedData>({
    data: [],
    current_page: 1,
    last_page: 1,
    from: 0,
    to: 0,
    total: 0
})

const filters = ref({
    type: '',
    currency: '',
    startDate: '',
    endDate: ''
})

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const formatCryptoAmount = (amount: number) => {
    return new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 8,
        maximumFractionDigits: 8
    }).format(amount)
}

const getStatusClass = (status: string) => {
    switch (status) {
        case 'confirmed':
            return 'bg-green-100 text-green-800'
        case 'pending':
            return 'bg-yellow-100 text-yellow-800'
        case 'failed':
            return 'bg-red-100 text-red-800'
        default:
            return 'bg-gray-100 text-gray-800'
    }
}

const copyHash = (hash: string) => {
    navigator.clipboard.writeText(hash)
    // You could add a toast notification here
}

const loadTransactions = async (page = 1) => {
    try {
        const params = new URLSearchParams({
            page: page.toString(),
            ...filters.value
        })
        
        const response = await fetch(`/crypto/transactions?${params}`)
        const data = await response.json()
        transactions.value = data
    } catch (error) {
        console.error('Error loading transactions:', error)
    }
}

onMounted(() => {
    loadTransactions()
})
</script> 