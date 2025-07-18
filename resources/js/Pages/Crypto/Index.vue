<template>
  <MainLayout>
    <template #default>
      <div class="space-y-6">
        <!-- Page Header -->
        <div class="sm:flex sm:items-center sm:justify-between">
          <div>
            <h1 class="text-2xl font-semibold text-gray-900">Cryptocurrency</h1>
          </div>
          <div class="mt-4 sm:mt-0">
            <button @click="showCreateWalletModal = true"
              class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              Create Wallet
            </button>
          </div>
        </div>

        <!-- Total Balance Cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">
                      Total Balance (USD)
                    </dt>
                    <dd class="text-2xl font-semibold text-gray-900">
                      {{ formatCurrency(totalBalanceUsd) }}
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">
                      Total Balance (EUR)
                    </dt>
                    <dd class="text-2xl font-semibold text-gray-900">
                      {{ formatCurrency(totalBalanceEur) }}
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <svg class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                  </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">
                      Active Wallets
                    </dt>
                    <dd class="text-2xl font-semibold text-gray-900">
                      {{ wallets.length }}
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Wallets Section -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              Your Wallets
            </h3>
          </div>
          <div class="px-4 py-5 sm:p-6 max-h-[150px] overflow-y-auto">
            <div v-if="wallets.length === 0" class="text-center py-8">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">No wallets</h3>
              <p class="mt-1 text-sm text-gray-500">
                Get started by creating your first wallet.
              </p>
            </div>
            <div v-else class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
              <div v-for="wallet in wallets" :key="wallet.id" class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                  <div>
                    <h4 class="text-sm font-medium text-gray-900">
                      {{ wallet.name }}
                    </h4>
                    <p class="text-xs text-gray-500">{{ wallet.currency }}</p>
                  </div>
                  <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900">
                      {{ formatCryptoAmount(wallet.balance) }}
                    </p>
                    <p class="text-xs text-gray-500">
                      {{ formatCurrency(wallet.balance_in_usd) }}
                    </p>
                  </div>
                </div>
                <div class="flex space-x-2">
                  <button @click="handleShowReceiveModal(wallet)"
                    class="flex-1 bg-green-600 text-white text-xs px-3 py-1 rounded hover:bg-green-700">
                    Receive
                  </button>
                  <button @click="handleShowSendModal(wallet)"
                    class="flex-1 bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700">
                    Send
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  Recent Transactions
                </h3>
              </div>
              <div>
                <a href="/crypto/transactions-page" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                  View All
                </a>
              </div>
            </div>
          </div>
          <div class="px-4 py-5 sm:p-6 max-h-[300px] overflow-y-auto">
            <div v-if="recentTransactions.length === 0" class="text-center py-8">
              <p class="text-sm text-gray-500">No transactions yet</p>
            </div>
            <div v-else class="space-y-4">
              <div v-for="transaction in recentTransactions" :key="transaction.id"
                class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center" :class="transaction.type === 'receive'
                        ? 'bg-green-100'
                        : 'bg-red-100'
                      ">
                      <svg class="w-4 h-4" :class="transaction.type === 'receive'
                          ? 'text-green-600'
                          : 'text-red-600'
                        " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path v-if="transaction.type === 'receive'" stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                      </svg>
                    </div>
                  </div>
                  <div class="ml-4">
                    <p class="text-sm font-medium text-gray-900">
                      {{ transaction.type === "receive" ? "Received" : "Sent" }}
                    </p>
                    <p class="text-xs text-gray-500">
                      {{ formatDate(transaction.created_at) }}
                    </p>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-sm font-medium" :class="transaction.type === 'receive'
                      ? 'text-green-600'
                      : 'text-red-600'
                    ">
                    {{ transaction.type === "receive" ? "+" : "-"
                    }}{{ formatCryptoAmount(transaction.amount) }}
                    {{ transaction.currency }}
                  </p>
                  <p class="text-xs text-gray-500">
                    {{ formatCurrency(transaction.amount_in_usd) }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Currency Converter -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              Currency Converter
            </h3>
          </div>
          <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
              <div>
                <label class="block text-sm font-medium text-gray-700">From</label>
                <select v-model="converter.fromCurrency" @change="convertCurrency"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                  <option v-for="currency in currencies" :key="currency.symbol" :value="currency.symbol">
                    {{ currency.symbol }} - {{ currency.name }}
                  </option>
                </select>
                <input type="number" v-model="converter.fromAmount" @input="convertCurrency" placeholder="Amount"
                  class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">To</label>
                <select v-model="converter.toCurrency" @change="convertCurrency"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                  <option v-for="currency in currencies" :key="currency.symbol" :value="currency.symbol">
                    {{ currency.symbol }} - {{ currency.name }}
                  </option>
                </select>
                <input type="number" v-model="converter.toAmount" readonly placeholder="Converted amount"
                  class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-gray-50 sm:text-sm" />
              </div>
            </div>
            <div v-if="converter.rate" class="mt-4 text-sm text-gray-500">
              Exchange rate: 1 {{ converter.fromCurrency }} =
              {{ converter.rate.toFixed(8) }} {{ converter.toCurrency }}
            </div>
          </div>
        </div>
      </div>

      <!-- Create Wallet Modal -->
      <div v-if="showCreateWalletModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
          <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
              Create New Wallet
            </h3>
            <form @submit.prevent="createWallet(newWallet.name, newWallet.currency)">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Wallet Name</label>
                  <input type="text" v-model="newWallet.name" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Currency</label>
                  <select v-model="newWallet.currency" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option v-for="currency in currencies" :key="currency.symbol" :value="currency.symbol">
                      {{ currency.symbol }} - {{ currency.name }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                <button type="submit"
                  class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                  Create Wallet
                </button>
                <button type="button" @click="showCreateWalletModal = false"
                  class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Receive Modal -->
      <div v-if="showReceiveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
          <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
              Receive {{ selectedWallet?.currency }}
            </h3>
            <div class="text-center">
              <div class="mb-4">
                <img :src="qrCode" alt="QR Code" class="mx-auto w-48 h-48 border border-gray-300 rounded" />
              </div>
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Wallet Address</label>
                <div class="flex">
                  <input type="text" :value="selectedWallet?.address" readonly
                    class="flex-1 border border-gray-300 rounded-l-md shadow-sm py-2 px-3 bg-gray-50 text-sm" />
                  <button @click="copyAddress"
                    class="bg-indigo-600 text-white px-3 py-2 rounded-r-md hover:bg-indigo-700">
                    Copy
                  </button>
                </div>
              </div>
              <button @click="showReceiveModal = false"
                class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                Close
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Send Modal -->
      <div v-if="showSendModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
          <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
              Send {{ selectedWallet?.currency }}
            </h3>
            <form @submit.prevent="sendCrypto">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">To Address</label>
                  <input type="text" v-model="sendForm.toAddress" required placeholder="Enter recipient address"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Amount</label>
                  <input type="number" v-model="sendForm.amount" required min="0.00000001" step="0.00000001"
                    placeholder="0.00000000"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                  <p class="text-xs text-gray-500 mt-1">
                    Available:
                    {{ formatCryptoAmount(selectedWallet?.balance || 0) }}
                  </p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Fee</label>
                  <input type="number" v-model="sendForm.fee" required min="0" step="0.00000001"
                    placeholder="0.00000000"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                </div>
              </div>
              <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                <button type="submit"
                  class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2 sm:text-sm">
                  Send
                </button>
                <button type="button" @click="showSendModal = false"
                  class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </template>
  </MainLayout>
</template>

<script setup lang="ts">
import MainLayout from "@/Layouts/MainLayout.vue";
import { ref, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import { generateWallet } from "@/utils/walletGenerator";

interface Wallet {
  id: number;
  name: string;
  currency: string;
  address: string;
  balance: number;
  balance_in_usd: number;
  balance_in_eur: number;
  is_active: boolean;
}

interface Transaction {
  id: number;
  transaction_hash: string;
  currency: string;
  type: "send" | "receive";
  amount: number;
  fee: number;
  from_address: string;
  to_address: string;
  status: string;
  created_at: string;
  amount_in_usd: number;
}

interface Currency {
  id: number;
  symbol: string;
  name: string;
  current_price_usd: number;
  current_price_eur: number;
}

const props = defineProps<{
  wallets: Wallet[];
  recentTransactions: Transaction[];
  currencies: Currency[];
  totalBalanceUsd: number;
  totalBalanceEur: number;
}>();

const showCreateWalletModal = ref(false);
const showReceiveModal = ref(false);
const showSendModal = ref(false);
const selectedWallet = ref<Wallet | null>(null);
const qrCode = ref("");

type SupportedCurrency = 'BTC' | 'ETH' | 'USDT' | 'LTC' | 'BCH';

const newWallet = ref({
  name: "",
  currency: "",
});

const sendForm = ref({
  toAddress: "",
  amount: "",
  fee: "",
});

const converter = ref({
  fromCurrency: "BTC",
  toCurrency: "ETH",
  fromAmount: "",
  toAmount: "",
  rate: null as number | null,
});

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat("en-US", {
    style: "currency",
    currency: "USD",
  }).format(amount);
};

const formatCryptoAmount = (amount: number) => {
  return new Intl.NumberFormat("en-US", {
    minimumFractionDigits: 8,
    maximumFractionDigits: 8,
  }).format(amount);
};

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

const createWallet = (name: string, currency: SupportedCurrency) => {
  return generateWallet(name, currency);
};

const handleShowReceiveModal = async (wallet: Wallet) => {
  selectedWallet.value = wallet;
  showReceiveModal.value = true;

  try {
    const response = await fetch(
      `/crypto/wallet-address?wallet_id=${wallet.id}`
    );
    const data = await response.json();
    qrCode.value = data.qr_code;
  } catch (error) {
    console.error("Error fetching wallet address:", error);
  }
};

const handleShowSendModal = (wallet: Wallet) => {
  selectedWallet.value = wallet;
  sendForm.value = { toAddress: "", amount: "", fee: "" };
  showSendModal.value = true;
};

const sendCrypto = () => {
  if (!selectedWallet.value) return;

  router.post(
    "/crypto/send",
    {
      wallet_id: selectedWallet.value.id,
      to_address: sendForm.value.toAddress,
      amount: sendForm.value.amount,
      fee: sendForm.value.fee,
    },
    {
      onSuccess: () => {
        showSendModal.value = false;
        sendForm.value = { toAddress: "", amount: "", fee: "" };
      },
    }
  );
};

const copyAddress = () => {
  if (selectedWallet.value) {
    navigator.clipboard.writeText(selectedWallet.value.address);
    // You could add a toast notification here
  }
};

const convertCurrency = async () => {
  if (
    !converter.value.fromAmount ||
    !converter.value.fromCurrency ||
    !converter.value.toCurrency
  )
    return;

  try {
    const response = await fetch("/crypto/convert", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN":
          document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content") || "",
      },
      body: JSON.stringify({
        from_currency: converter.value.fromCurrency,
        to_currency: converter.value.toCurrency,
        amount: parseFloat(converter.value.fromAmount),
      }),
    });

    const data = await response.json();
    converter.value.toAmount = data.to_amount.toFixed(8);
    converter.value.rate = data.rate;
  } catch (error) {
    console.error("Error converting currency:", error);
  }
};

onMounted(() => {
  if (props.currencies.length > 0) {
    converter.value.fromCurrency = props.currencies[0].symbol;
    converter.value.toCurrency =
      props.currencies[1]?.symbol || props.currencies[0].symbol;
  }
});
</script>