<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '../Components/AdminLayout.vue';

defineProps({
    user: Object
})

// Initialize the form with required properties
const form = useForm({
    name: '',
    email: '',
    password: '',
    isAdmin: 'false',
});

const createuser = () => {
    console.log(form); 
    form.isAdmin = Boolean(form.isAdmin);
    form.post(route('user.store'));
}
</script>

<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
                <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-12">
                    <form @submit.prevent="createuser">
                        <div class="shadow sm:rounded-md sm:overflow-hidden">
                            <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                                <div>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                        User
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Use this form to create a User.
                                    </p>
                                </div>

                                <div class="grid grid-cols-6 gap-6">
                                    <!-- Name Field -->
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="name" class="block text-sm font-medium text-gray-700">
                                            Name
                                        </label>
                                        <input 
                                            v-model="form.name"
                                            type="text" id="name"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            required />
                                    </div>

                                    <!-- Email Field -->
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="email" class="block text-sm font-medium text-gray-700">
                                            Email
                                        </label>
                                        <input 
                                            v-model="form.email"
                                            type="email" id="email"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            required />
                                    </div>

                                    <!-- Password Field -->
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="password" class="block text-sm font-medium text-gray-700">
                                            Password
                                        </label>
                                        <input 
                                            v-model="form.password"
                                            type="password" id="password"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            required />
                                    </div>

                                    
                                    <!-- IsAdmin Checkbox -->
                                    <div class="col-span-6 sm:col-span-3">
                                        <!-- <label for="isAdmin" class="block text-sm font-medium text-gray-700">
                                            Is Admin?
                                        </label>
                                        <input 
                                            v-model="form.isAdmin"
                                            type="checkbox" id="isAdmin"
                                            :true-value="true" :false-value="false"
                                            class="mt-1 block border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" /> -->
                                        <label for="isAdmin" class="inline-flex items-center">
                                            <input v-model="form.isAdmin" type="checkbox" id="isAdmin" />
                                            <span class="ml-2 text-sm text-gray-700">Is Admin?</span>
                                        </label>
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
