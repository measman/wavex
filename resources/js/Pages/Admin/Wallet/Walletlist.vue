<script setup>
import { usePage ,Link,useForm,router } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import {ref, computed, watch} from "vue";
defineProps({
    wallets: {
        type:Object,
        required:true
    }
});
//const deleteForm=useForm({});
//const deletewal = (walId) =>{
 //   if (confirm('Are you sure you want to delete?')) {
 //       deleteForm.delete(route('wallets.destroy',walId));
 //  }
//}




// let search = ref(usePage().props.search),
//  pageNumber =ref(1);

// let walletUrl = computed(()=> {
//     let url = new URL(route("wallets.index"));
//     url.searchParams.append("page", pageNumber.value);
//     if(search.value){
//         url.searchParams.append("search",search.value);
//     }
//     return url;
// });

// const updatedPageNumber = (link) => {
//     pageNumber.value = link.url.split("=")[1];
// }

// watch(()=> walletUrl.value,
//     (updatedwalletUrl) => {
//         router.visit(updatedwalletUrl,{
//             preserveScroll: true,
//             preserveState: true,
//             replace:true,
//         });
//     }
// );

// watch(()=> search.value,
//     (value) => {
//        if(value){
//         pageNumber.value = 1;
//        }
//     }
// );
</script>

<template>
    <div class="bg-gray-100 py-10">
        <div class="mx-auto max-w-7xl">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-xl font-semibold text-gray-900">
                            Wallet Balance
                        </h1>

                    </div>
                </div>

                <div class="mt-8 flex flex-col">
                    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="relative text-sm text-gray-800 col-span-3">
                            <div
                                class="absolute pl-2 left-0 top-0 bottom-0 flex items-center pointer-events-none text-gray-500"
                            >
                            </div>

                            <!-- <input
                                v-model="search"
                                type="text"
                                autocomplete="off"
                                placeholder="Search  data..."
                                id="search"
                                class="block rounded-lg border-0 mx-8 py-2 pl-10 text-gray-900 ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            /> -->
                            
                        </div>

                        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg relative">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                ID</th>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                Name</th>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                Currency</th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Balance
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        <tr v-for="wal in wallets" :key="wal.user_id">
                                            <td
                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                {{ wal.user_id }}</td>
                                            <td
                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                {{ wal.name }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{
                                                wal.code }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ wal.total_balance
                                                }}</td>
                                            <!-- <td
                                                class="relative whitespace-nowrap py-4 pl-3 pr-4 text-left text-sm font-medium sm:pr-6">
                                                <Link :href="route('wallets.edit',wal.id)"
                                                    class="text-indigo-600 hover:text-indigo-900">Edit</Link>
                                                    <Link 
                                                        @click="deletewal(wal.id)"
                                                        class="ml-2 text-indigo-600 hover:text-indigo-900">
                                                        Delete
                                                    </Link>

                                            </td> -->
                                        </tr>

                                 </tbody>
                                </table>
                            </div>
                            <!-- <Pagination :data="wallets" :updatedPageNumber="updatedPageNumber" /> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
