<script setup>
import { usePage , useForm} from '@inertiajs/vue3';
import AdminLayout from '../Components/AdminLayout.vue';

const currencies=usePage().props.currencies;
console.log(currencies);
const form = useForm({
    from_currency_id:"",
    to_currency_id:"",
    rate:""
});

const createexchangerate = () =>{
    console.log(form);
    form.post(route('exchangeratestore'));
}
console.log(form);
</script>
<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
                <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-12">
                    <form @submit.prevent="createexchangerate">
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
                                            v-model="form.from_currency_id"
                                            id="currency_from"
                                            class="{'mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm}"
                                            
                                            required>
                                            <option value="">Select</option>
                                            <option v-for="item in currencies" :key="item.id" :value="item.id">
                                                {{ item.name }}</option>
                                        </select>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="section_id" class="block text-sm font-medium text-gray-700">
                                            Currency To
                                        </label>
                                        <select  
                                            v-model="form.to_currency_id"
                                            id="currency_to"
                                            class="{'mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm}"
                                           
                                            required>
                                            <option value="">Select</option>
                                            <option v-for="item2 in currencies" :key="item2.id"
                                                :value="item2.id">{{ item2.name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="rate" class="block text-sm font-medium text-gray-700">
                                            Rate
                                        </label>
                                        <input 
                                            v-model="form.rate"
                                            type="text" id="rate"
                                            class="{'mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm}"
                                            required />
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                <a
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
                                Cancel
                            </a>
                                <button type="submit"
                                    class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Save
                                </button>
                            </div> 
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>