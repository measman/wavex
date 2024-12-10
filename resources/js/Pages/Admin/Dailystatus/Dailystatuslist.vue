<script setup>
import { usePage, Link, useForm, router } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import { ref, computed } from "vue";

let props = defineProps({
    dailystatus: {
        type: Object,
        required: false,
        default: () => ({})
    }
});

const form = useForm({});
const todayDate = new Date().toISOString().split('T')[0];

const status = computed(() => {
    if (!props.dailystatus || !props.dailystatus.status) {
        return 'day_end';
    }
    return props.dailystatus.status;
});

const displayDate = computed(() => {
    if (!props.dailystatus || !props.dailystatus.date) {
        return todayDate;
    }
    return props.dailystatus.date;
});

const isButtonDisabled = computed(() => {
    return !props.dailystatus || status.value === 'day_end';
});

const dailyStatusEnd = () => {
    if (props.dailystatus && props.dailystatus.id) {
        form.post(route('dailystatus.insertstatus', props.dailystatus.id));
    }
}
</script>

<template>
    <div class="bg-gray-100 py-10">
        <div class="mx-auto max-w-7xl">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-xl font-semibold text-gray-900">
                            Daily Status
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
                                                Date</th>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                Status</th>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                Actions</th>

                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        <tr>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                {{ displayDate }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                {{ status }}
                                            </td>
                                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-left text-sm font-medium sm:pr-6">
                                                <Link 
                                                    @click="dailyStatusEnd()" 
                                                    :class="{
                                                        'cursor-not-allowed opacity-50': isButtonDisabled,
                                                        'text-indigo-600 hover:text-indigo-900': !isButtonDisabled
                                                    }" 
                                                    :disabled="isButtonDisabled"
                                                >
                                                    Close Day
                                                </Link>
                                            </td>
                                        </tr>
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
