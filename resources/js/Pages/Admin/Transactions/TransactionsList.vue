<script setup>
import { onMounted, reactive, ref } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import DataTable from 'datatables.net-dt';
import $ from 'jquery';
import Swal from 'sweetalert2';
defineProps({
    users: {
        type: Object,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});
var usersdata = usePage().props.users;
console.log(usersdata);
var token = usePage().props.token;
console.log(token);
let baseUrl = window.location.origin;
let endpointfortransactioninfo = '/api/transactioninfo';
let endpointfortransactionedit = '/api/transactionedit';
let endpointfortransactionupdate = '/api/transactionupdate';
let endpointfortransactiondelete = '/api/transactiondelete';
let endpointfortransactionsearch = '/api/transactionsearch';

onMounted(() => {
    $('#TransactionTable').DataTable({
        ajax: {
            url: baseUrl + endpointfortransactioninfo,
            headers: {
                'Authorization': 'Bearer ' + usePage().props.token
            },
            dataSrc: function (json) {
                console.log(json);
                return json.data;
            },
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'from_currency' },
            { data: 'to_currency' },
            { data: 'exchangerate' },
            { data: 'type' },
            { data: 'status' },
            { data: 'action_buttons' }
        ],
        destroy: true,

    });
});
var openModal = () => {
    document.getElementById("transactionModal").classList.remove("hidden");
}

var closeModal = () => {
    document.getElementById("transactionModal").classList.add("hidden");
}

const form = reactive({
    id: '',
    status: '',
});
$(document).on('click', '.transaction-edit', function () {
    openModal();
    var transaction_id = $(this).data('id');
    $.ajax({
        url: baseUrl + endpointfortransactionedit,
        headers: {
            'Authorization': 'Bearer ' + usePage().props.token
        },
        method: "POST",
        data: {
            id: transaction_id
        },
        dataType: "JSON",
        success: function (data) {
            form.id = data.id,
                form.status = data.status;
            console.log(data);
        }
    });
});
const edittransaction = () => {
    console.log(form);
    $.ajax({
        url: baseUrl + endpointfortransactionupdate,
        headers: {
            'Authorization': 'Bearer ' + usePage().props.token
        },
        method: "POST",
        data: form,
        dataType: "JSON",
        success: function (data) {
            Swal.fire({
                title: 'Success',
                text: data.message,
                icon: 'success',
                confirmButtonText: 'OK',
                buttonsStyling: true
            }).then(() => {
                $('#TransactionTable').DataTable().ajax.reload(null, false);
                closeModal();
            });
        }
    });
}
$(document).on('click', '.transaction-delete', function () {
    var transaction_id = $(this).data('id');
    console.log(transaction_id);
    if (confirm("Are you sure you want to delete it?")) {
        $.ajax({
            url: baseUrl + endpointfortransactiondelete,
            headers: {
                'Authorization': 'Bearer ' + usePage().props.token
            },
            method: "POST",
            data: {
                id: transaction_id
            },
            dataType: "JSON",
            success: function (data) {
                Swal.fire({
                    title: 'Success',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK',
                    buttonsStyling: true
                }).then(() => {
                    $('#TransactionTable').DataTable().ajax.reload(null, false);
                });
            }
        });
    }
});


const filterform = reactive({
    status: '',
    type: '',
    id: ''
});

const filtertransaction = () => {
    console.log(filterform);
    $('#TransactionTable').DataTable({
    ajax: {
        url: baseUrl + endpointfortransactionsearch,
        headers: {
            'Authorization': 'Bearer ' + usePage().props.token
        },
        method: "POST",
        data: {
            id: filterform.id,
            status: filterform.status,
            type: filterform.type
        },
        dataSrc: function (json) {
            console.log('Response from server:', json); // Log the entire response
            return json.data; // Assuming the data you need is in a `data` property
        }
    },
    columns: [
        { data: 'id' },
        { data: 'name' },
        { data: 'from_currency' },
        { data: 'to_currency' },
        { data: 'exchangerate' },
        { data: 'type' },
        { data: 'status' },
        { data: 'action_buttons' }
    ],
    destroy: true,
});


}

var openFilterModal = () => {
    document.getElementById("filterModal").classList.remove("hidden");
}

var closeFilterModal = () => {
    document.getElementById("filterModal").classList.add("hidden");
    $('#TransactionTable').DataTable({
        ajax: {
            url: baseUrl + endpointfortransactioninfo,
            headers: {
                'Authorization': 'Bearer ' + usePage().props.token
            },
            dataSrc: function (json) {
                console.log(json);
                return json.data;
            },
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'from_currency' },
            { data: 'to_currency' },
            { data: 'exchangerate' },
            { data: 'type' },
            { data: 'status' },
            { data: 'action_buttons' }
        ],
        destroy: true,

    });
}
$(document).on('click', '#filterbutton', function () {
    openFilterModal();
});

</script>
<style>
div.dt-container select.dt-input {
    padding: 4px;
    width: 60px;
}
.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
    @apply border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    @apply bg-indigo-600 text-white border-indigo-600 !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    @apply bg-indigo-100 text-indigo-700 border-indigo-100 !important;
}
</style>
<template>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8 md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-3xl font-bold leading-7 text-gray-900 sm:text-4xl">
                        Transactions
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Manage and monitor all currency exchange transactions
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                    <button id="filterbutton" type="button"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-5 w-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                    <Link :href="route('transactions.create')"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        New Transaction
                    </Link>
                </div>
            </div>

            <!-- Filter Modal -->
            <div id="filterModal" class="hidden">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                    <form @submit.prevent="filtertransaction" class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    User
                                </label>
                                <select v-model="filterform.id"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Users</option>
                                    <option v-for="user in usersdata" :key="user.id" :value="user.id">
                                        {{ user.name }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Status
                                </label>
                                <select v-model="filterform.status"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Status</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Failed">Failed</option>
                                    <option value="Pending">Pending</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Transaction Type
                                </label>
                                <select v-model="filterform.type"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Types</option>
                                    <option value="buy">Buy</option>
                                    <option value="sell">Sell</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" @click="closeFilterModal"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Clear
                            </button>
                            <button type="submit"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Apply Filters
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Transaction Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="TransactionTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Debit
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Credit
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Exchange Rate
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- DataTables will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="transactionModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Edit Transaction Status
                            </h3>
                            <div class="mt-4">
                                <form @submit.prevent="edittransaction">
                                    <input type="hidden" v-model="form.id">
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Status
                                        </label>
                                        <select v-model="form.status"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="Completed">Completed</option>
                                            <option value="Failed">Failed</option>
                                            <option value="Pending">Pending</option>
                                        </select>
                                    </div>
                                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                        <button type="submit"
                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                            Save Changes
                                        </button>
                                        <button type="button" @click="closeModal"
                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
