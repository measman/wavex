<script setup>
import { ref, computed } from 'vue';
import AdminLayout from './Components/AdminLayout.vue';
import { usePage } from '@inertiajs/vue3';

// Define props to receive data
const props = defineProps({
    daily_status: Object,
    todayexchangerate: Array, // Ensure todayexchangerate is an array
});

const currentPage = ref(1);
const itemsPerPage = 5;

// Paginated data
const paginatedData = computed(() => {
    const startIndex = (currentPage.value - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    return props.todayexchangerate.slice(startIndex, endIndex);
});

// Total number of pages
const totalPages = computed(() => {
    return Math.ceil(props.todayexchangerate.length / itemsPerPage);
});

// Change page
const goToPage = (page) => {
    if (page > 0 && page <= totalPages.value) {
        currentPage.value = page;
    }
};
</script>

<template>
    <AdminLayout>
        <h1 class="text-xl font-bold mb-4">Daily Status : {{ props.daily_status.data.status }}</h1>
        <div class="bg-gray-100 py-10">
            <div class="mx-auto max-w-7xl">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h1 class="text-xl font-semibold text-gray-900">
                                Today's ExchangeRate
                            </h1>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col">
                        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg relative">
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                    Currency</th>
                                                <th scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                    Unit</th>
                                                <th scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                    Buy</th>
                                                <th scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                    Sell
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white">
                                            <tr v-for="ter in paginatedData" :key="ter.currency.iso3">
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                    {{ ter.currency.iso3 }}</td>
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                    {{ ter.currency.unit }}</td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ ter.buy }}</td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ ter.sell }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Pagination Controls -->
                            <div class="flex justify-between mt-4">
                                <button
                                    :disabled="currentPage === 1"
                                    @click="goToPage(currentPage - 1)"
                                    class="ml-8 px-4 py-2 text-sm text-white bg-blue-500 rounded hover:bg-blue-700"
                                >
                                    Previous
                                </button>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm">Page {{ currentPage }} of {{ totalPages }}</span>
                                </div>
                                <button
                                    :disabled="currentPage === totalPages"
                                    @click="goToPage(currentPage + 1)"
                                    class="mr-8 px-4 py-2 text-sm text-white bg-blue-500 rounded hover:bg-blue-700"
                                >
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
