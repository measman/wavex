<script setup>
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import AdminLayout from './Components/AdminLayout.vue';
import { usePage } from '@inertiajs/vue3';

// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
});
defineProps({
    daily_status:Object,
    todayexchangerate:Object,
})
//let daily_status=usePage().props.daily_status.data;
</script>
<template>
<AdminLayout>
    <h1 class="text-xl font-bold mb-4">Daily Status : {{ daily_status.data.status }}</h1> 
</AdminLayout>
</template>
