<script setup>
import { usePage , useForm} from '@inertiajs/vue3';
import AdminLayout from '../Components/AdminLayout.vue';
defineProps({
    currency:{type:Object,required:true}
})

let currency=usePage().props.currency.data;
// console.log(currencies);
const form = useForm({
    name:currency?.name,
    symbol:currency?.symbol,
    code:currency?.code
});

const createCurrency = () =>{
    console.log(form);
    form.put(route('currencies.update',currency.id));
}
// console.log(form);
</script>
<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
                <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-12">
                    <form @submit.prevent="createCurrency">
                        <div class="shadow sm:rounded-md sm:overflow-hidden">
                            <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                                <div>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                        Currency
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Use this form to create a Currency.
                                    </p>
                                </div>

                                <div class="grid grid-cols-6 gap-6">
                                    
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="code" class="block text-sm font-medium text-gray-700">
                                            Code
                                        </label>
                                        <input 
                                            v-model="form.code"
                                            type="text" id="code"
                                            class="{'mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm}"
                                            required />
                                    </div>
                                    
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="name" class="block text-sm font-medium text-gray-700">
                                            Name
                                        </label>
                                        <input 
                                            v-model="form.name"
                                            type="text" id="name"
                                            class="{'mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm}"
                                            required />
                                    </div>
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="symbol" class="block text-sm font-medium text-gray-700">
                                            Symbol
                                        </label>
                                        <input 
                                            v-model="form.symbol"
                                            type="text" id="symbol"
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