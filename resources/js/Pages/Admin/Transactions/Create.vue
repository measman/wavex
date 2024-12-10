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
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
                <!-- Left Section: Existing Content -->
                <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-8">
                    <form @submit.prevent="submitForm">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Transacitons
                            </h3>
                            
                        </div>
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="type" class="block text-sm font-medium text-gray-700">
                                    Select Exchange:
                                </label>
                                <select v-model="form.type" id="type"
                                    class="{'mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm}"
                                    required>
                                    <option value="buy">Buy</option>
                                    <option value="sell">Sell</option>
                                </select>
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                                <input type="text" id="date" disabled :value="props.extrainfo.date"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="exrate" class="block text-sm font-medium text-gray-700">
                                    Select Exchange Currency:
                                </label>
                                <select v-model="form.excurrency" id="exrate"
                                    class="{'mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm}"
                                    required>
                                    <option value="">Select</option>
                                    <option v-for="item in currencies.data" :key="item.id" :value="{
                                        id: item.id,
                                        code: item.code,
                                    }">
                                        {{ item.code }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="fromcurrency" class="block text-sm font-medium text-gray-700">From
                                    Currency</label>
                                <input type="text" id="fromcurrency" disabled :value="form.excurrency.code"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="amount_from" class="block text-sm font-medium text-gray-700">Amount</label>
                                <input v-model="form.amount_from" type="text" id="amount_from"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required />
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="tocurrency" class="block text-sm font-medium text-gray-700">To
                                    Currency</label>
                                <input type="text" id="tocurrency" disabled value="NPR"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="exchange_rate" class="block text-sm font-medium text-gray-700">Exchange
                                    Rate</label>
                                <input v-model="form.exchange_rate" type="text" id="exchange_rate"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="unit" class="block text-sm font-medium text-gray-700">Unit</label>
                                <input v-model="form.unit" type="text" id="unit"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required />
                            </div>

                            <!-- <div class="col-span-6 sm:col-span-3"></div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="camount" class="block text-sm font-medium text-gray-700">Converted Amount</label>
                                <input type="text" id="camount" disabled
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            </div> -->
                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Section: New Content -->
                <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-4">
                    <div class="p-6 bg-white shadow-lg rounded-xl border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">
                                Transaction Summary
                            </h3>
                        </div>
                        
                        <p class="mb-6 text-sm text-gray-600 bg-indigo-50 p-3 rounded-lg">
                            Please review your transaction details carefully before proceeding with submission.
                        </p>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Transaction ID</div>
                                <div class="font-semibold text-gray-900">#{{ props.extrainfo.id + 1 }}</div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Transaction Date</div>
                                <div class="font-semibold text-gray-900">{{ props.extrainfo.date }}</div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Exchange Type</div>
                                <div class="font-semibold text-gray-900">{{ form.type }}</div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Currency</div>
                                <div class="font-semibold text-gray-900">{{ form.excurrency.code }}</div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Exchange Rate</div>
                                <div class="font-semibold text-gray-900">{{ form.exchange_rate }}</div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Unit</div>
                                <div class="font-semibold text-gray-900">{{ form.unit }}</div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Amount to Exchange</div>
                                <div class="font-semibold text-gray-900">{{ form.excurrency.code }} {{ form.amount_from }}</div>
                            </div>
                        </div>

                        <div class="p-6 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-xl shadow-md">
                            <div class="flex justify-between items-center">
                                <div class="text-white">
                                    <div class="text-sm opacity-80 mb-1">Amount to Receive</div>
                                    <div class="text-3xl font-bold">
                                        NPR <span id="camount">{{ convertedamount }}</span>
                                    </div>
                                </div>
                                <div class="bg-white p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
