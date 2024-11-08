<script setup>
import AdminLayout from '../Components/AdminLayout.vue';
import Dailystatuslist from './Dailystatuslist.vue';
defineProps({
    dailystatus: Object
})
</script>
<template>
    <AdminLayout>
        <Dailystatuslist :dailystatus="dailystatus" />    
    </AdminLayout>
</template>