<script setup>
import { onMounted, reactive, ref } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import DataTable from 'datatables.net-dt';
import $ from 'jquery';
import Swal from 'sweetalert2';


defineProps({
    token: String,
});
const dataSource = ref([]);
let baseUrl = window.location.origin;
let localtransactioninfo = '/api/localtransactioninfo';
let localtransactionedit = '/api/localtransactionedit';
let localtransactionupdate = '/api/localtransactionupdate';
let localtransactiondelete = '/api/localtransactiondelete';
onMounted(() => {
    $('#LocalTransactionTable').DataTable({
        ajax: {
            url: baseUrl + localtransactioninfo,
            headers: {
                'Authorization': 'Bearer ' + usePage().props.token
            },
            dataSrc: function (json) {
                dataSource.value = json.data; // Set the dataSource to the fetched data
                return json.data;
            },
        },
        columns: [
            { data: 'id' },
            { data: 'currency_name' },
            { data: 'amount' },
            { data: 'type' },
            { data: 'action_buttons' }
        ],
        destroy: true,

    });
});

$(document).on('click', '.localtransaction-delete', function () {
    var user_id = $(this).data('id');
    console.log(user_id);
    if (confirm("Are you sure you want to delete it?")) {
        $.ajax({
            url: baseUrl + localtransactiondelete,
            headers: {
                'Authorization': 'Bearer ' + usePage().props.token
            },
            method: "POST",
            data: {
                id: user_id
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
                    $('#LocalTransactionTable').DataTable().ajax.reload(null, false);
                });
            }
        });
    }
});

$(document).on('click', '.localtransaction-edit', function () {
    openModal();
    var tid = $(this).data('id');
    console.log(tid);
    $.ajax({
        url: baseUrl + localtransactionedit,
        headers: {
            'Authorization': 'Bearer ' + usePage().props.token
        },
        method: "POST",
        data: {
            id: tid
        },
        dataType: "JSON",
        success: function (data) {
            form.id = data.id,
                form.currency_name = data.currency_name;
            form.amount = data.amount;
            form.type = data.type;
            console.log(data);
        }
    });
});

var openModal = () => {
    document.getElementById("myModal").classList.remove("hidden");
}

var closeModal = () => {
    document.getElementById("myModal").classList.add("hidden");
}

const form = reactive({
    id: '',
    currency_name: '',
    amount: '',
    type: '',
});

const editlocaltransaction = () => {
    console.log(form);
    $.ajax({
        url: baseUrl + localtransactionupdate,
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
                $('#LocalTransactionTable').DataTable().ajax.reload(null, false);
                closeModal();
            });
        }
    });
}
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
    <div class="bg-gray-100 py-10">
        <div class="mx-auto max-w-7xl">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="mb-8 md:flex md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <h1 class="text-3xl font-bold leading-7 text-gray-900 sm:text-4xl">
                            Local Transactions
                        </h1>
                        <p class="mt-1 text-sm text-gray-500">
                            Manage and monitor all local transactions.
                        </p>
                    </div>
                    <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                        <Link :href="route('localtransactions.create')"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        New Transaction
                        </Link>
                    </div>
                </div>

                <div class="mt-8 flex flex-col">
                    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg relative">
                                <table class="min-w-full divide-y divide-gray-300" id="LocalTransactionTable">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                ID
                                            </th>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                currency
                                            </th>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                Amount
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Type
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        <!-- Table data will be populated by DataTable -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="myModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-1/3">
            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b p-4">
                <h2 class="text-lg font-bold">Modal Title</h2>
                <button class="text-gray-400 hover:text-gray-600" :onclick="closeModal">
                    &times;
                </button>
            </div>
            <!-- Modal Body -->
            <form @submit.prevent="editlocaltransaction">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <input type="hidden" name="hidden" v-model="form.id">
                            <!-- Name Field -->
                            <div class="col-span-6 sm:col-span-3">
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    currency
                                </label>
                                <input v-model="form.currency_name" type="text" id="name"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required disabled />
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    Amount
                                </label>
                                <input v-model="form.amount" type="text" id="name"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required />
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Type
                                </label>
                                <select v-model="form.type"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="Deposit">Desposit</option>
                                    <option value="Withdraw">Withdrawal</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="button"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4"
                            @click="closeModal">
                            Cancel
                        </button>

                        <button type="submit"
                            class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
