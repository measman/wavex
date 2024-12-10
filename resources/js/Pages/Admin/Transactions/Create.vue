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
    () => form.amount_from,
    (amount) => {
        toamount(amount);
    }
);

// Add these new watchers
watch(
    () => form.exchange_rate,
    (rate) => {
        toamount(form.amount_from);
    }
);

watch(
    () => form.unit,
    (unit) => {
        toamount(form.amount_from);
    }
);

const toamount = (amount) => {
    var newamount = amount;
    var exrate = form.exchange_rate;
    var unit = form.unit;
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
                    <div class="p-4 bg-white shadow rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900">
                            Transaction Summary
                        </h3>
                        <p class="mt-2 text-sm text-gray-500">
                            Review transaction details before submitting.
                        </p>
                        <ul class="mt-4 space-y-2 text-sm text-gray-700">
                            <li>
                                <strong>Transaction ID:</strong>
                                #{{ props.extrainfo.id + 1 }}
                            </li>
                            <li>
                                <strong>Transaction Date:</strong>
                                {{ props.extrainfo.date }}
                            </li>
                            <li>
                                <strong>Exchange Type:</strong> {{ form.type }}
                            </li>
                            <li>
                                <strong>Currency:</strong>
                                {{ form.excurrency.code }}
                            </li>
                            <li>
                                <strong>Amount:</strong> {{ form.amount_from }}
                            </li>
                            <li><strong>Converted Amount:</strong> NPR <span id="camount">{{ convertedamount }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
