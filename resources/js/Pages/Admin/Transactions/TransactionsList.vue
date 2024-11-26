<script setup>
import { onMounted, reactive, ref } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import DataTable from 'datatables.net-dt';
import $ from 'jquery';
import Swal from 'sweetalert2';
defineProps({
    transactions: {
        type: Object,
        required: true,
        
    },
    token:String,
});
var token=usePage().props.token;
console.log(token);
const dataSource = ref([]);
let baseUrl = window.location.origin;
let endpointfortransactioninfo = '/api/transactioninfo';
let endpointfortransactionedit = '/api/transactionedit';
let endpointfortransactionupdate = '/api/transactionupdate';
let endpointfortransactiondelete = '/api/transactiondelete';

onMounted(() => {
    $('#TransactionTable').DataTable({
        ajax: {
            url: baseUrl + endpointfortransactioninfo,
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
        url:  baseUrl + endpointfortransactionedit,
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

</script>
<style>
div.dt-container select.dt-input {
    padding: 4px;
    width: 60px;
}
</style>
<template>
    <div class="bg-gray-100 py-10">
        <div class="mx-auto max-w-8xl">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-xl font-semibold text-gray-900">
                            Transactions
                        </h1>
                    </div>
                    <div class="mt-4 sm:mt-0 sm:ml-4 sm:flex-none">
                        <Link :href="route('transactions.create')"
                            class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                        Add Transactions
                        </Link>
                    </div>
                </div>

                <div class="mt-8 flex flex-col">
                    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg relative">
                                <table class="min-w-full divide-y divide-gray-300" id="TransactionTable">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                ID
                                            </th>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                User
                                            </th>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                From Amount
                                            </th>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                To Amount
                                            </th>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                Exchange Rate
                                            </th>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                Type
                                            </th>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                Status
                                            </th>

                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="transactionModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-1/3">
            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b p-4">
                <h2 class="text-lg font-bold">Edit Transaction</h2>
                <button class="text-gray-400 hover:text-gray-600" :onclick="closeModal">
                    &times;
                </button>
            </div>
            <!-- Modal Body -->
            <form @submit.prevent="edittransaction">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <input type="hidden" name="hidden" v-model="form.id">
                            <!-- Name Field -->
                            <div class="col-span-6 sm:col-span-6">
                                <label for="type" class="block text-sm font-medium text-gray-700">
                                    Select Type:
                                </label>
                                <select v-model="form.status" id="type"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required>
                                    <option value="Completed">Completed</option>
                                    <option value="Failed">Failed</option>
                                    <option value="Pending">Pending</option>
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
