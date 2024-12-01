<script setup>
import { onMounted, reactive, ref } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import DataTable from 'datatables.net-dt';
import $ from 'jquery';
import Swal from 'sweetalert2';
defineProps({
    token: String,
});
var token = usePage().props.token;
const dataSource = ref([]);
let baseUrl = window.location.origin;
let endpointforwalletinfo = '/api/walletinfo';
onMounted(() => {
    $('#WalletTable').DataTable({
        ajax: {
            url: baseUrl + endpointforwalletinfo,
            headers: {
                'Authorization': 'Bearer ' + usePage().props.token
            },
            dataSrc: function (json) {
                dataSource.value = json.data; 
                return json.data;
            },
        },
        columns: [
            { data: 'user_id' },
            { data: 'name' },
            { data: 'code' },
            { data: 'total_balance' },
            { data: 'avg_exchange_rate' },
        ],
        destroy: true,

    });
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
                            Wallet Balance
                        </h1>

                    </div>
                </div>
                <div class="mt-8 flex flex-col">
                    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="relative text-sm text-gray-800 col-span-3">
                            <div
                                class="absolute pl-2 left-0 top-0 bottom-0 flex items-center pointer-events-none text-gray-500"
                            >
                            </div>
                        </div>

                        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg relative">
                                <table class="min-w-full divide-y divide-gray-300" id="WalletTable">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                ID</th>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                Name</th>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                Currency</th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Balance
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Exchange Rate
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
</template>
