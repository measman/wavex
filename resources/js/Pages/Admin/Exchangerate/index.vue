<script setup>
import AdminLayout from '../Components/AdminLayout.vue';
import ExchangeRateList from './Exchangerlist.vue';
defineProps({
    exchangerates: Object
})
</script>
<template>
    <AdminLayout>
        <ExchangeRateList :exchangerates="exchangerates" />    
    </AdminLayout>
</template>