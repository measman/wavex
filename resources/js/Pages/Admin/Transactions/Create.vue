<script setup>
import { usePage, useForm } from '@inertiajs/vue3';
import AdminLayout from '../Components/AdminLayout.vue';

// const currencies=usePage().props.currencies;
defineProps({
    transactions: Object,
    excahngerates: Object
})

const form = useForm({
    exrate: "",
    amount_from: "",
    type: "",
    status: ""
});


const submitForm = () => {
    console.log(form);
    form.post(route('transactions.store'));
}
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
                                    Select Exchange:
                                </label>
                                <select v-model="form.exrate" id="exrate"
                                    class="{'mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm}"
                                    required>
                                    <option value="">Select</option>
                                    <option v-for="item in excahngerates.data" :key="item.id"
                                        :value="{ id: item.id, rate: item.rate, from_currency_id: item.from_currency.id, to_currency_id: item.to_currency.id }">
                                        {{ item.from_currency.code }} To {{ item.to_currency.code }}</option>
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