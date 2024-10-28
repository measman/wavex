<script setup>
import AdminLayout from '../Components/AdminLayout.vue';
import CurrenciesList from './CurrenciesList.vue';
defineProps({
    currencies: Object
})
</script>
<template>
    <AdminLayout>
        <CurrenciesList :currencies="currencies" />    
    </AdminLayout>
</template>