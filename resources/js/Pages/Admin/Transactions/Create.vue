<script setup>
import { usePage, useForm } from '@inertiajs/vue3';
import AdminLayout from '../Components/AdminLayout.vue';
import { ref, watch } from 'vue';

// Define props
const props = defineProps({
    transactions: Object,
    excahngerates: Object,
    currencies: Object,
    todaysexchangerate: Array
});

// Form state
const form = useForm({
    excurrency: "",
    amount_from: "",
    type: "",
    status: "",
    exchange_rate: "",
    unit: ""
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
    props.todaysexchangerate.forEach(rate => {
        if (type == 'buy') {
            if (rate.currency.iso3 == form.excurrency['code']) {
            form.exchange_rate=rate.buy;
            form.unit=rate.currency.unit;
        }
        } else {
            if (rate.currency.iso3 == form.excurrency['code']) {
            form.exchange_rate=rate.sell;
            form.unit=rate.currency.unit;
        }
        }
        
    });
    
};

// Function to submit the form
const submitForm = () => {
    console.log(form);
    form.post(route('transactions.store'));
};
</script>

<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
                <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-12">
                    <form @submit.prevent="submitForm">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Transacitons
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Use this form to create Transaction.
                            </p>
                        </div>
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="exrate" class="block text-sm font-medium text-gray-700">
                                    Select Exchange Currency:
                                </label>
                                <select v-model="form.excurrency" id="exrate"
                                    class="{'mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm}"
                                    required>
                                    <option value="">Select</option>
                                    <option v-for="item in currencies.data" :key="item.id"
                                        :value="{ id: item.id, code: item.code }">
                                        {{ item.code }}</option>
                                </select>
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="amount_from" class="block text-sm font-medium text-gray-700">Amount</label>
                                <input v-model="form.amount_from" type="text" id="amount_from"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required />
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="type" class="block text-sm font-medium text-gray-700">
                                    Select Exchange:
                                </label>
                                <select v-model="form.type" id="type"
                                    class="{'mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm}"
                                    required>
                                    <option value="">Select</option>
                                    <option value="buy">Buy</option>
                                    <option value="sell">Sell</option>
                                </select>
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="status" class="block text-sm font-medium text-gray-700">
                                    Select Status:
                                </label>
                                <select v-model="form.status" id="status"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required>
                                    <option value="">Select</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Failed">Failed</option> <!-- Added 'failed' option -->
                                </select>
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

                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Submit
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </AdminLayout>
</template>