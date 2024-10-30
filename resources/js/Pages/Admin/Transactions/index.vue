<script setup>
import AdminLayout from '../Components/AdminLayout.vue';
import TransactionsList from './TransactionsList.vue';
defineProps({
    transactions: Object
})
</script>
<template>
    <AdminLayout>
        <TransactionsList :transactions="transactions" />    
    </AdminLayout>
</template>