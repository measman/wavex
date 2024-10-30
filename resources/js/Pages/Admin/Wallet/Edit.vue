<script setup>
import AdminLayout from '../Components/AdminLayout.vue';
import { usePage, useForm } from '@inertiajs/vue3';

defineProps({
    wallet: Object,
    currencies: Object,
    users:Object
});

let wallet=usePage().props.wallet.data;

const form = useForm({
    user_id: wallet.user.id,
    currency_id: wallet.currency.id,
    balance: wallet.balance
});

const updatewallet = () => {
    console.log(form.data());  // Logs form data to confirm state
    form.put(route('wallets.update', wallet.id));
};
</script>

<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
                <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-12">
                    <form @submit.prevent="updatewallet">
                        <div class="shadow sm:rounded-md sm:overflow-hidden">
                            <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                                <div>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                        Wallet Balance
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Use this form to update.
                                    </p>
                                </div>

                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="currency_from" class="block text-sm font-medium text-gray-700">
                                            Name
                                        </label>
                                        <select  
                                            v-model="form.user_id"
                                            id="from_currency_id"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            required>
                                            <option value="">Select</option>
                                            <option v-for="item in users.data" :key="item.id" :value="item.id">
                                                {{ item.name }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="currency_to" class="block text-sm font-medium text-gray-700">
                                            Currency To
                                        </label>
                                        <select  
                                            v-model="form.currency_id"
                                            id="currency_id"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            required>
                                            <option value="">Select</option>
                                            <option v-for="item2 in currencies.data" :key="item2.id" :value="item2.id">
                                                {{ item2.name }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="rate" class="block text-sm font-medium text-gray-700">
                                            Balance
                                        </label>
                                        <input  
                                            v-model="form.balance"
                                            type="text" id="balance"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            required />
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                <button type="submit"
                                    class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Update
                                </button>
                            </div> 
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
