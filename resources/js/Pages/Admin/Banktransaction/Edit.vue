<script setup>
import { usePage, useForm } from '@inertiajs/vue3';
import AdminLayout from '../Components/AdminLayout.vue';

// const currencies=usePage().props.currencies;
defineProps({
    users: Object,
    banks:Object
})

let banks=usePage().props.banks.data;
// console.log(currencies.data);
const form = useForm({
    transaction_date: banks.transaction_date,
    bank_from: banks.bank_from,
    bank_to: banks.bank_to,
    sender: banks.sender,
    receiver: banks.receiver,
    transaction_method: banks.transaction_method,
    transaction_amount: banks.transaction_amount,
    remarks: banks.remarks,
    sender_bank_branch: banks.sender_bank_branch,
    receiver_bank_branch: banks.receiver_bank_branch,
    sender_contact: banks.sender_contact,
    receiver_contact: banks.receiver_contact,
    created_by: banks.created_by.id
});


const submitForm = () => {
    console.log(form);
    form.put(route('banktransactions.update', banks.id));
}
console.log(form);
</script>
<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
                <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-12">
                    <form @submit.prevent="submitForm">
                        <div>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                        Bank Transacitons
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Use this form to create a Bank Transaction.
                                    </p>
                            </div>
                        <div class="grid grid-cols-6 gap-6">
                            
                            <div class="col-span-6 sm:col-span-3">
                                <label for="transaction_date"
                                    class="block text-sm font-medium text-gray-700">Transaction Date</label>
                                <input v-model="form.transaction_date" type="date" id="transaction_date"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="bank_from" class="block text-sm font-medium text-gray-700">Bank From</label>
                                <input v-model="form.bank_from" type="text" id="bank_from"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="bank_to" class="block text-sm font-medium text-gray-700">Bank To</label>
                                <input v-model="form.bank_to" type="text" id="bank_to"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="sender" class="block text-sm font-medium text-gray-700">Sender</label>
                                <input v-model="form.sender" type="text" id="sender"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="receiver" class="block text-sm font-medium text-gray-700">Receiver</label>
                                <input v-model="form.receiver" type="text" id="receiver"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="transaction_method"
                                    class="block text-sm font-medium text-gray-700">Transaction Method</label>
                                <input v-model="form.transaction_method" type="text" id="transaction_method"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="transaction_amount"
                                    class="block text-sm font-medium text-gray-700">Transaction Amount</label>
                                <input v-model="form.transaction_amount" type="number" id="transaction_amount"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required />
                            </div>



                            <div class="col-span-6 sm:col-span-3">
                                <label for="sender_bank_branch" class="block text-sm font-medium text-gray-700">Sender
                                    Bank Branch</label>
                                <input v-model="form.sender_bank_branch" type="text" id="sender_bank_branch"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="receiver_bank_branch"
                                    class="block text-sm font-medium text-gray-700">Receiver Bank Branch</label>
                                <input v-model="form.receiver_bank_branch" type="text" id="receiver_bank_branch"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="sender_contact" class="block text-sm font-medium text-gray-700">Sender
                                    Contact</label>
                                <input v-model="form.sender_contact" type="text" id="sender_contact"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="receiver_contact" class="block text-sm font-medium text-gray-700">Receiver
                                    Contact</label>
                                <input v-model="form.receiver_contact" type="text" id="receiver_contact"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="class_id" class="block text-sm font-medium text-gray-700">
                                    Created By:
                                </label>
                                <select v-model="form.created_by" id="created_by"
                                    class="{'mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm}"
                                    required>
                                    <option value="">Select</option>
                                    <option v-for="item in users.data" :key="item.id" :value="item.id">
                                        {{ item.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                            <textarea v-model="form.remarks" id="remarks"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required></textarea>
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