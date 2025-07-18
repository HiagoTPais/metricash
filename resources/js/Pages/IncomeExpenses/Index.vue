<script setup lang="ts">
import { ref, computed, watch } from "vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import { router } from "@inertiajs/vue3";
import { watchEffect } from "vue";
import * as XLSX from "xlsx";

interface Entry {
  id: number;
  type: "income" | "expense";
  amount: number;
  description: string;
  category: string;
  date: string;
  invoiceFile: string;
}

const props = defineProps<{
  totalIncome: number;
  totalExpenses: number;
  balance: number;
  incomeExpenses: {
    data: {
      id: number;
      date: string;
      description: string;
      category: string;
      type: string;
      amount: number;
      invoiceFile: null;
    }[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
}>();

interface Filters {
  type: "" | "income" | "expense";
  category: string;
  description: string;
  dateRange: "all" | "today" | "week" | "month" | "year";
  page: number;
}

// State
const search = ref("");
const showAddModal = ref(false);
const addOrEdit = ref("");
const modalMode = ref("");
const currentId = ref("");
const entries = ref<Entry[]>([]);
const fileInput = ref<HTMLInputElement | null>(null);
const filters = ref<Filters>({
  type: "",
  category: "",
  description: "",
  dateRange: "all",
  page: 1,
});

const newEntry = ref<Omit<Entry, "id">>({
  type: "income",
  amount: 0,
  description: "",
  category: "",
  date: new Date().toISOString().split("T")[0],
  invoiceFile: "",
});

// Categories
const categories = [
  "Salary",
  "Freelance",
  "Investments",
  "Rent",
  "Utilities",
  "Food",
  "Transportation",
  "Entertainment",
  "Shopping",
  "Other",
];

// Computed properties
const filteredEntries = computed(() => {
  return entries.value.filter((entry) => {
    if (filters.value.type && entry.type !== filters.value.type) return false;
    if (filters.value.category && entry.category !== filters.value.category)
      return false;

    return true;
  });
});

const showModal = (mode: "add" | "edit" | "delete", item?: any) => {
  modalMode.value = mode;

  if (mode == "edit") {
    currentId.value = item.id;

    newEntry.value = { ...item };
  } else if (mode == "delete") {
    currentId.value = item.id;
  }

  showAddModal.value = true;
};

const getDateRangeFilter = (dateRange: string) => {
  const today = new Date();
  today.setHours(0, 0, 0, 0);

  const startDate = new Date(today);

  switch (dateRange) {
    case "today":
      return {
        start: today.toISOString().split("T")[0],
        end: today.toISOString().split("T")[0],
      };
    case "week":
      startDate.setDate(today.getDate() - 7);
      return {
        start: startDate.toISOString().split("T")[0],
        end: today.toISOString().split("T")[0],
      };
    case "month":
      startDate.setMonth(today.getMonth() - 1);
      return {
        start: startDate.toISOString().split("T")[0],
        end: today.toISOString().split("T")[0],
      };
    case "year":
      startDate.setFullYear(today.getFullYear() - 1);
      return {
        start: startDate.toISOString().split("T")[0],
        end: today.toISOString().split("T")[0],
      };
    default:
      return { start: "", end: "" };
  }
};

const applyFilters = () => {
  const dateRange = getDateRangeFilter(filters.value.dateRange);
  console.log("TESTE DE FILTRO");

  router.get(
    route("income-expenses.index"),
    {
      type: filters.value.type,
      category: filters.value.category,
      dateRange: filters.value.dateRange,
      description: filters.value.description,
      startDate: dateRange.start,
      endDate: dateRange.end,
      page: filters.value.page,
    },
    {
      preserveState: true,
      preserveScroll: true,
    }
  );
};

const handlePageChange = (page: number) => {
  if (props.incomeExpenses) {
    filters.value.page = page;
    applyFilters();
  }
};

watch(
  () => [
    filters.value.type,
    filters.value.category,
    filters.value.dateRange,
    filters.value.description,
  ],
  () => {
    if (props.incomeExpenses) {
      filters.value.page = 1;
      applyFilters();
    }
  }
);

const addEntry = async () => {
  if (modalMode.value === "add") {
    const entryToAdd: Omit<Entry, "id"> = {
      amount: parseFloat(newEntry.value.amount.toString()),
      type: newEntry.value.type,
      description: newEntry.value.description,
      category: newEntry.value.category,
      date: newEntry.value.date,
    };

    router.post(route("income-expenses.store"), { ...entryToAdd });
  } else if (modalMode.value === "edit" && currentId.value) {
    router.put(route("income-expenses.update", currentId.value), {
      // Use router.put para atualizar
      amount: parseFloat(newEntry.value.amount.toString()),
      type: newEntry.value.type,
      description: newEntry.value.description,
      category: newEntry.value.category,
      date: newEntry.value.date,
    });
  } else if (modalMode.value === "delete" && currentId.value) {
    router.delete(route("income-expenses.delete", currentId.value));
  }

  showAddModal.value = false;

  resetNewEntry();
};

const resetNewEntry = () => {
  newEntry.value = {
    type: "income",
    amount: 0,
    description: "",
    category: "",
    date: new Date().toISOString().split("T")[0],
  };
};

const formatDate = (dateString: string) => {
  const date = new Date(dateString);
  return date.toLocaleDateString("pt-BR"); // 'en-US'
};

watch(
  () => [
    filters.value.type,
    filters.value.category,
    filters.value.dateRange,
    filters.value.description,
  ],
  () => {
    filters.value.page = 1;
    applyFilters();
  }
);

const exportToExcel = () => {
  // Prepare the data for export
  const exportData = props.incomeExpenses.data.map((entry) => ({
    Date: formatDate(entry.date),
    Description: entry.description,
    Category: entry.category,
    Type: entry.type,
    Amount: entry.type === "income" ? `+$${entry.amount}` : `-$${entry.amount}`,
  }));

  let ws = XLSX.utils.json_to_sheet(exportData);

  var wb = XLSX.utils.book_new();

  XLSX.utils.book_append_sheet(wb, ws, "Income & Expenses");

  XLSX.writeFile(
    wb,
    `income-expenses-${new Date().toISOString().split("T")[0]}.xlsx`
  );
};

const handleFileImport = (event: Event) => {
  const file = event.target.files[0];
  this.invoiceFile = file;
};
</script>

<template>
  <MainLayout>
    <div class="space-y-6">
      <!-- Page Header -->

      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-900">Income & Expenses</h1>

        <div>
          <button
            @click="exportToExcel"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg
              class="-ml-1 mr-2 h-5 w-5"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <rect
                x="3"
                y="8"
                width="13"
                height="13"
                rx="2"
                ry="2"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
              <path
                d="M15 3h6m0 0v6m0-6L10 14"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
            </svg>
            Export
          </button>
          &nbsp;
          <button
            @click="showModal('add')"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg
              class="-ml-1 mr-2 h-5 w-5"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
              />
            </svg>
            Add Entry
          </button>
        </div>
      </div>

      <!-- Summary Cards -->
      <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg
                  class="h-6 w-6 text-green-500"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">
                    Total Income
                  </dt>
                  <dd class="flex items-baseline">
                    <div class="text-2xl font-semibold text-gray-900">
                      ${{ totalIncome }}
                    </div>
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
                <svg
                  class="h-6 w-6 text-red-500"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">
                    Total Expenses
                  </dt>
                  <dd class="flex items-baseline">
                    <div class="text-2xl font-semibold text-gray-900">
                      ${{ totalExpenses }}
                    </div>
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
                <svg
                  class="h-6 w-6 text-indigo-500"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                  />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">
                    Balance
                  </dt>
                  <dd class="flex items-baseline">
                    <div class="text-2xl font-semibold text-gray-900">
                      ${{ balance }}
                    </div>
                  </dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white shadow rounded-lg p-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
          <div>
            <label for="type" class="block text-sm font-medium text-gray-700"
              >Type</label
            >
            <select
              id="type"
              v-model="filters.type"
              class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
            >
              <option value="">All</option>
              <option value="income">Income</option>
              <option value="expense">Expense</option>
            </select>
          </div>
          <div>
            <label
              for="category"
              class="block text-sm font-medium text-gray-700"
              >Category</label
            >
            <select
              id="category"
              v-model="filters.category"
              class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
            >
              <option value="">All Categories</option>
              <option
                v-for="category in categories"
                :key="category"
                :value="category"
              >
                {{ category }}
              </option>
            </select>
          </div>
          <div>
            <label for="date" class="block text-sm font-medium text-gray-700"
              >Date Range</label
            >
            <select
              id="date"
              v-model="filters.dateRange"
              class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
            >
              <option value="">All Time</option>
              <option value="today">Today</option>
              <option value="week">This Week</option>
              <option value="month">This Month</option>
              <option value="year">This Year</option>
            </select>
          </div>
          <div>
            <label for="date" class="block text-sm font-medium text-gray-700"
              >Description</label
            >
            <input
              type="text"
              id="description"
              v-model="filters.description"
              class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
            />
          </div>
        </div>
      </div>

      <!-- Entries Table -->
      <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th
                scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Date
              </th>
              <th
                scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Description
              </th>
              <th
                scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Category
              </th>
              <th
                scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Type
              </th>
              <th
                scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Amount
              </th>
              <th scope="col" class="relative px-6 py-3">
                <span class="sr-only">Actions</span>
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="entry in props.incomeExpenses.data" :key="entry.id">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(entry.date) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ entry.description }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ entry.category }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  :class="[
                    entry.type === 'income'
                      ? 'bg-green-100 text-green-800'
                      : 'bg-red-100 text-red-800',
                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                  ]"
                >
                  {{ entry.type }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ entry.type === "income" ? "+" : "-" }}${{ entry.amount }}
              </td>
              <td
                class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
              >
                <button
                  class="text-indigo-600 hover:text-indigo-900 mr-3"
                  @click="showModal('edit', entry)"
                >
                  Edit
                </button>

                <button
                  class="text-red-600 hover:text-red-900"
                  @click="showModal('delete', entry)"
                >
                  Delete
                </button>
              </td>
            </tr>
            <tr
              v-if="
                !props.incomeExpenses.data ||
                props.incomeExpenses.data.length === 0
              "
            >
              <td
                colspan="6"
                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center"
              >
                No entries found
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div
          v-if="props.incomeExpenses?.last_page > 1"
          class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6"
        >
          <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
              <button
                @click="
                  handlePageChange(props.incomeExpenses?.current_page - 1)
                "
                :disabled="props.incomeExpenses?.current_page === 1"
                :class="[
                  'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md',
                  props.incomeExpenses?.current_page === 1
                    ? 'bg-gray-100 text-gray-400'
                    : 'bg-white text-gray-700 hover:bg-gray-50',
                ]"
              >
                Previous
              </button>
              <button
                @click="
                  handlePageChange(props.incomeExpenses?.current_page + 1)
                "
                :disabled="
                  props.incomeExpenses?.current_page ===
                  props.incomeExpenses?.last_page
                "
                :class="[
                  'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md',
                  props.incomeExpenses?.current_page ===
                  props.incomeExpenses?.last_page
                    ? 'bg-gray-100 text-gray-400'
                    : 'bg-white text-gray-700 hover:bg-gray-50',
                ]"
              >
                Next
              </button>
            </div>
            <div
              class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between"
            >
              <div>
                <p class="text-sm text-gray-700">
                  Showing
                  <span class="font-medium">{{
                    (props.incomeExpenses?.current_page - 1) *
                      props.incomeExpenses?.per_page +
                    1
                  }}</span>
                  to
                  <span class="font-medium">{{
                    Math.min(
                      props.incomeExpenses?.current_page *
                        props.incomeExpenses?.per_page,
                      props.incomeExpenses?.total
                    )
                  }}</span>
                  of
                  <span class="font-medium">{{
                    props.incomeExpenses?.total
                  }}</span>
                  results
                </p>
              </div>
              <div>
                <nav
                  class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                  aria-label="Pagination"
                >
                  <button
                    @click="
                      handlePageChange(props.incomeExpenses?.current_page - 1)
                    "
                    :disabled="props.incomeExpenses?.current_page === 1"
                    :class="[
                      'relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium',
                      props.incomeExpenses?.current_page === 1
                        ? 'text-gray-300'
                        : 'text-gray-500 hover:bg-gray-50',
                    ]"
                  >
                    <span class="sr-only">Previous</span>
                    <svg
                      class="h-5 w-5"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                      aria-hidden="true"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        clip-rule="evenodd"
                      />
                    </svg>
                  </button>
                  <button
                    v-for="page in props.incomeExpenses?.last_page"
                    :key="page"
                    @click="handlePageChange(page)"
                    :class="[
                      'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                      page === props.incomeExpenses?.current_page
                        ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                    ]"
                  >
                    {{ page }}
                  </button>
                  <button
                    @click="
                      handlePageChange(props.incomeExpenses?.current_page + 1)
                    "
                    :disabled="
                      props.incomeExpenses?.current_page ===
                      props.incomeExpenses?.last_page
                    "
                    :class="[
                      'relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium',
                      props.incomeExpenses?.current_page ===
                      props.incomeExpenses?.last_page
                        ? 'text-gray-300'
                        : 'text-gray-500 hover:bg-gray-50',
                    ]"
                  >
                    <span class="sr-only">Next</span>
                    <svg
                      class="h-5 w-5"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                      aria-hidden="true"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"
                      />
                    </svg>
                  </button>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Add Entry Modal -->
      <div v-if="showAddModal" class="fixed z-10 inset-0 overflow-y-auto">
        <div
          class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
        >
          <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
          </div>
          <span
            class="hidden sm:inline-block sm:align-middle sm:h-screen"
            aria-hidden="true"
            >&#8203;</span
          >
          <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
          >
            <div
              v-if="modalMode == 'add' || modalMode == 'edit'"
              class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4"
            >
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                {{ modalMode === "add" ? "Add New Entry" : "Edit Entry" }}
              </h3>
              <div class="space-y-4">
                <div>
                  <label
                    for="entryType"
                    class="block text-sm font-medium text-gray-700"
                    >Type</label
                  >
                  <select
                    id="entryType"
                    v-model="newEntry.type"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                  >
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                  </select>
                </div>
                <div>
                  <label
                    for="amount"
                    class="block text-sm font-medium text-gray-700"
                    >Amount</label
                  >
                  <input
                    type="text"
                    id="amount"
                    v-mask="'money'"
                    v-model="newEntry.amount"
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                </div>
                <div>
                  <label
                    for="description"
                    class="block text-sm font-medium text-gray-700"
                    >Description</label
                  >
                  <input
                    type="text"
                    id="description"
                    v-model="newEntry.description"
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                </div>
                <div>
                  <label
                    for="category"
                    class="block text-sm font-medium text-gray-700"
                    >Category</label
                  >
                  <select
                    id="category"
                    v-model="newEntry.category"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                  >
                    <option
                      v-for="category in categories"
                      :key="category"
                      :value="category"
                    >
                      {{ category }}
                    </option>
                  </select>
                </div>
                <div>
                  <label
                    for="date"
                    class="block text-sm font-medium text-gray-700"
                    >Date</label
                  >

                  <div class="flex items-center space-x-2 mt-1">
                    <input
                      type="date"
                      id="date"
                      v-model="newEntry.date"
                      class="flex-grow focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                    />
                    <div>
                      <input
                        type="file"
                        ref="fileInput"
                        class="hidden"
                        @change="handleFileImport"
                      />
                      <button
                        @click="$refs.fileInput.click()"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                      >
                        <svg
                          class="-ml-1 mr-2 h-5 w-5"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke="currentColor"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"
                          />
                        </svg>
                        Import Invoice
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-else class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                Are you sure you want to delete?
              </h3>
            </div>
            <div
              class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
            >
              <button
                type="button"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                :class="
                  modalMode == 'edit' || modalMode == 'add'
                    ? 'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500'
                    : 'bg-red-600 hover:bg-red-700 focus:ring-red-500'
                "
                @click="addEntry"
              >
                {{
                  modalMode == "edit" || modalMode == "add"
                    ? "Send Entry"
                    : "Delete"
                }}
              </button>
              <button
                type="button"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                @click="showAddModal = false"
              >
                Cancel
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>
