<script setup>
import AdminLayout from '../Components/AdminLayout.vue';
import Walletlist from './Walletlist.vue';
defineProps({
    wallets: Object
})
</script>
<template>
    <AdminLayout>
        <Walletlist :wallets = 'wallets' />
    </AdminLayout>
</template>