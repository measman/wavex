<script setup>
import DataTable from 'datatables.net-dt';
import { onMounted, ref } from 'vue';
import $ from 'jquery';
var dataSource = ref([]);
onMounted(() => {
    // $.ajax({
    //     type: "GET",
    //     url: "http://localhost:8000/api/feetest",
    //     success: function (response) {
    //         console.log(response);
    //     }
    // });

    // Initialize the DataTable
    $('#random').DataTable({
        ajax: {
            url: 'http://localhost:8000/api/feetest',
            dataSource: 'data',
        }
        ,
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'code' },
            { data: 'symbol' },
            { data: 'created_at' }
        ],
        destroy: true,
    }
    );

});
</script>

<template>
    <h2>Random Static Data Table</h2>

    <table id="random">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Age</th>
                <th>Country</th>
            </tr>
        </thead>
    </table>
</template>
