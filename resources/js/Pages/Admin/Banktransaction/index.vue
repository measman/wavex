<script setup>
import AdminLayout from '../Components/AdminLayout.vue';
import Banktransactionlist from './Banktransactionlist.vue';

defineProps({
    banks:Object
})
</script>

<template>
<AdminLayout>
    <Banktransactionlist :banks="banks"/>
</AdminLayout>

</template>