<script setup>
import { usePage, useForm } from "@inertiajs/vue3";
import AdminLayout from "../Components/AdminLayout.vue";
import { ref, watch } from "vue";

// Define props
const props = defineProps({
    transactions: Object,
    excahngerates: Object,
    currencies: Object,
    extrainfo: Array,
    todaysexchangerate: Array,
});

var convertedamount = ref();

// Form state
const form = useForm({
    excurrency: "",
    amount_from: "",
    type: "buy",
    exchange_rate: "",
    unit: "",
});

// Watcher for form.excurrency
watch(
    () => form.type,
    (newValue) => {
        getliverates(newValue);
    }
);

// Function to get live rates
const getliverates = (type) => {
    props.todaysexchangerate.forEach((rate) => {
        if (type == "buy") {
            if (rate.currency.iso3 == form.excurrency["code"]) {
                form.exchange_rate = rate.buy;
                form.unit = rate.currency.unit;
            }
        } else {
            if (rate.currency.iso3 == form.excurrency["code"]) {
                form.exchange_rate = rate.sell;
                form.unit = rate.currency.unit;
            }
        }
    });
};
watch(
    () => form.excurrency,
    (newValue) => {
        getliveratesnew(newValue);
    }
);

// Function to get live rates
const getliveratesnew = (excurrency) => {
    props.todaysexchangerate.forEach((rate) => {
        if (form.type == "buy") {
            if (rate.currency.iso3 == excurrency["code"]) {
                form.exchange_rate = rate.buy;
                form.unit = rate.currency.unit;
            }
        } else {
            if (rate.currency.iso3 == excurrency["code"]) {
                form.exchange_rate = rate.sell;
                form.unit = rate.currency.unit;
            }
        }
    });
};

watch(
    () => [form.amount_from, form.exchange_rate, form.unit],
    ([amount, exchangeRate, unit]) => {
        toamount(amount, exchangeRate, unit);
    }
);
/**
 * Calculates the converted amount based on the given amount, exchange rate and unit.
 * @param {number} amount - The amount to be converted.
 * @param {number} excahngerates - The exchange rate.
 * @param {number} unit - The unit of the currency.
 * @returns {void}
 */
const toamount = (amount, excahngerates, unit) => {
    var newamount = amount;
    var exrate = excahngerates;
    var unit = unit;
    convertedamount.value = (newamount / unit) * exrate;
};

// Function to submit the form
const submitForm = () => {
    console.log(form);
    form.post(route("transactions.store"));
};
</script>

<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">New Transaction</h1>
                <p class="mt-2 text-sm text-gray-600">Create a new currency exchange transaction</p>
            </div>

            <div class="lg:grid lg:grid-cols-12 lg:gap-8">
                <!-- Left Section: Transaction Form -->
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <form @submit.prevent="submitForm" class="space-y-6">
                            <!-- Transaction Type and Date -->
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                        Transaction Type
                                    </label>
                                    <div class="relative">
                                        <select v-model="form.type" id="type"
                                            class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-lg"
                                            required>
                                            <option value="buy">Buy Currency</option>
                                            <option value="sell">Sell Currency</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Date</label>
                                    <input type="text" disabled :value="props.extrainfo.date"
                                        class="block w-full px-3 py-3 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg" />
                                </div>
                            </div>

                            <!-- Currency Selection and Amount -->
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Exchange Currency</label>
                                    <div class="relative">
                                        <select v-model="form.excurrency"
                                            class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-lg"
                                            required>
                                            <option value="">Select Currency</option>
                                            <option v-for="item in currencies.data" :key="item.id" :value="{
                                                id: item.id,
                                                code: item.code,
                                            }">
                                                {{ item.code }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                                    <input v-model="form.amount_from" type="text"
                                        class="block w-full px-3 py-3 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-lg"
                                        required />
                                </div>
                            </div>

                            <!-- Exchange Rate and Unit -->
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Exchange Rate</label>
                                    <input v-model="form.exchange_rate" type="text"
                                        class="block w-full px-3 py-3 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-lg"
                                        required />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                                    <input v-model="form.unit" type="text"
                                        class="block w-full px-3 py-3 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-lg"
                                        required />
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end pt-4">
                                <button type="submit"
                                    class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Confirm Transaction
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Section: Transaction Summary -->
                <div class="lg:col-span-4 mt-8 lg:mt-0">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold text-gray-900">Transaction Summary</h3>
                                <span class="px-3 py-1 text-xs font-medium text-indigo-600 bg-indigo-100 rounded-full">
                                    #{{ props.extrainfo.id + 1 }}
                                </span>
                            </div>

                            <div class="space-y-4">
                                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                    <span class="text-sm text-gray-600">Transaction Type</span>
                                    <span class="text-sm font-medium text-gray-900 capitalize">{{ form.type }}</span>
                                </div>
                                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                    <span class="text-sm text-gray-600">Currency</span>
                                    <span class="text-sm font-medium text-gray-900">{{ form.excurrency.code || '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                    <span class="text-sm text-gray-600">Exchange Rate</span>
                                    <span class="text-sm font-medium text-gray-900">{{ form.exchange_rate || '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-3">
                                    <span class="text-sm text-gray-600">Amount</span>
                                    <span class="text-sm font-medium text-gray-900">{{ form.amount_from || '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Converted Amount Card -->
                        <div class="p-6 bg-gradient-to-r from-indigo-600 to-indigo-700">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm text-indigo-100">You will receive</p>
                                    <p class="text-2xl font-bold text-white mt-1">
                                        NPR {{ convertedamount?.toFixed(2) || '0.00' }}
                                    </p>
                                </div>
                                <div class="bg-white p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
