<script setup>
import AdminLayout from '../Components/AdminLayout.vue';
import { usePage, useForm } from '@inertiajs/vue3';

defineProps({
    exchangerate: Object,
    test: String
});

// let exchangerate = usePage().props.exchangerate;

// const form = useForm({
//     from_currency_id: exchangerate.from_currency.id,
//     to_currency_id: exchangerate.to_currency.id,
//     rate: exchangerate.rate
// });

// const updateexcahngerate = () => {
//     console.log(form);
// }
</script>

<template>
    <!-- <AdminLayout>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
                <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-12">
                    <form @submit.prevent="updateexcahngerate">
                        <div class="shadow sm:rounded-md sm:overflow-hidden">
                            <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                                <div>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                        Exchange Rate
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Use this form to create a Excahnge Rate.
                                    </p>
                                </div>

                                <div class="grid grid-cols-6 gap-6">
                                    

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="class_id" class="block text-sm font-medium text-gray-700">
                                            Currency From 
                                        </label>
                                        <select  
                                            v-model="from_currency_id"
                                            id="currency_from"
                                            class="{'mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm}"
                                            
                                            required>
                                            <option value="">Select</option>
                                            <option v-for="item in currencies.data" :key="item.id" :value="item.id">
                                                {{ item.name }}</option>
                                        </select>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="section_id" class="block text-sm font-medium text-gray-700">
                                            Currency To
                                        </label>
                                        <select  
                                            v-model="to_currency_id"
                                            id="currenct_to"
                                            class="{'mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm}"
                                           
                                            required>
                                            <option value="">Select</option>
                                            <option v-for="item2 in currencies.data" :key="item2.id"
                                                :value="item2.id">{{ item2.name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="rate" class="block text-sm font-medium text-gray-700">
                                            Rate
                                        </label>
                                        <input  
                                            v-model="rate"
                                            type="text" id="rate"
                                            class="{'mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm}"
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
    </AdminLayout> -->
</template>