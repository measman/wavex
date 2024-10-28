<script setup>
import AdminLayout from '../Components/AdminLayout.vue';
import UserList from './Userlist.vue';
defineProps({
    user: Object
})
</script>
<template>
    <AdminLayout>
        <UserList :user="user" />
    </AdminLayout>
</template>