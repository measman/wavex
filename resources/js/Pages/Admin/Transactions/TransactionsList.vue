<script setup>
import { usePage, Link, useForm, router } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import { ref, computed, watch } from "vue";

defineProps({
    transactions: {
        type: Object,
        required: true,
    },
});

let search = ref(usePage().props.search),
    pageNumber = ref(1);

let transactionsUrl = computed(() => {
    let url = new URL(route("transactions.index"));
    url.searchParams.append("page", pageNumber.value);
    if(search.value){
        url.searchParams.append("search",search.value);
    }
    return url;
});

watch(
    () => transactionsUrl.value,
    (updatedtransactionsUrl) => {
        router.visit(updatedtransactionsUrl, {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    }
);

const updatedPageNumber = (link) => {
    pageNumber.value = link.url.split("=")[1];
};

const deleteForm = useForm({});
const deletetransaction = (transactionId) => {
    if (confirm("Are you sure you want to delete?")) {
        deleteForm.delete(route("transactions.destroy", transactionId));
    }
};
</script>

<template>
    <div class="bg-gray-100 py-10">
        <div class="mx-auto max-w-7xl">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-xl font-semibold text-gray-900">
                            Transactions
                        </h1>
                    </div>
                    <div class="mt-4 sm:mt-0 sm:ml-4 sm:flex-none">
                        <Link
                            :href="route('transactions.create')"
                            class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
                        >
                            Add Transactions
                        </Link>
                    </div>
                </div>

                <div class="mt-8 flex flex-col">
                    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="relative text-sm text-gray-800 col-span-3">
                            <div
                                class="absolute pl-2 left-0 top-0 bottom-0 flex items-center pointer-events-none text-gray-500">
                                <MagnifyingGlass />
                            </div>

                            <input v-model="search" type="text" autocomplete="off" placeholder="Search  data..."
                                id="search"
                                class="block rounded-lg border-0 mx-8 py-2 pl-10 text-gray-900 ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />

                        </div>
                        <div
                            class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8"
                        >
                            <div
                                class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg relative"
                            >
                                <table
                                    class="min-w-full divide-y divide-gray-300"
                                >
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                            >
                                                ID
                                            </th>
                                            <th
                                                scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                            >
                                                User
                                            </th>
                                            <th
                                                scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                            >
                                                From Amount
                                            </th>
                                            <th
                                                scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                            >
                                                To Amount
                                            </th>
                                            <th
                                                scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                            >
                                                Exchange Rate
                                            </th>
                                            <th
                                                scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                            >
                                                Type
                                            </th>
                                            <th
                                                scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                            >
                                                Status
                                            </th>

                                            <th
                                                scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                            >
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="divide-y divide-gray-200 bg-white"
                                    >
                                        <tr
                                            v-for="transaction in transactions.data"
                                            :key="transaction.id"
                                        >
                                            <td
                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                                            >
                                                {{ transaction.id }}
                                            </td>
                                            <td
                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                                            >
                                                {{ transaction.user.name }}
                                            </td>
                                            <td
                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                                            >
                                                {{
                                                    transaction.exchange_rate
                                                        .from_currency.code
                                                }}
                                                {{ transaction.from_amount }}
                                            </td>
                                            <td
                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                                            >
                                                {{
                                                    transaction.exchange_rate
                                                        .to_currency.code
                                                }}
                                                {{ transaction.to_amount }}
                                            </td>
                                            <td
                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                                            >
                                                {{
                                                    transaction.exchange_rate
                                                        .rate
                                                }}
                                            </td>
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{
                                                transaction.type }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"><span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-green-400 border border-green-100 dark:border-green-500">{{
                                                transaction.status }}</span></td>

                                            <td
                                                class="relative whitespace-nowrap py-4 pl-3 pr-4 text-left text-sm font-medium sm:pr-6"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'transactions.edit',
                                                            transaction.id
                                                        )
                                                    "
                                                    class="text-indigo-600 hover:text-indigo-900"
                                                    >Edit</Link
                                                >
                                                <Link
                                                    @click="
                                                        deletetransaction(
                                                            transaction.id
                                                        )
                                                    "
                                                    class="ml-2 text-indigo-600 hover:text-indigo-900"
                                                >
                                                    Delete
                                                </Link>
                                            </td>
                                        </tr>
                                        <!-- Handle empty state if no data is present -->
                                    </tbody>
                                </table>
                            </div>
                            <Pagination
                                :data="transactions"
                                :updatedPageNumber="updatedPageNumber"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
