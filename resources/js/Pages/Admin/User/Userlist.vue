<script setup>
import { onMounted, reactive, ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import DataTable from 'datatables.net-dt';
import $ from 'jquery';
import Swal from 'sweetalert2';


const dataSource = ref([]);

onMounted(() => {
  $('#userTable').DataTable({
    ajax: {
      url: 'http://localhost:8000/api/userinfo',
      dataSrc: function (json) {
        dataSource.value = json.data; // Set the dataSource to the fetched data
        return json.data;
      },
    },
    columns: [
      { data: 'id' },
      { data: 'name' },
      { data: 'email' },
      { data: 'last_login' },
      { data: 'created_at' },
      { data: 'action_buttons' }
    ],
    destroy: true,

  });
});

$(document).on('click', '.user-delete', function () {
  var user_id = $(this).data('id');
  console.log(user_id);
  if (confirm("Are you sure you want to delete it?")) {
    $.ajax({
      url: "http://127.0.0.1:8000/api/userdelete",
      method: "POST",
      data: {
        id: user_id
      },
      dataType: "JSON",
      success: function (data) {
        Swal.fire({
          title: 'Success',
          text: data.message,
          icon: 'success',
          confirmButtonText: 'OK',
          buttonsStyling: true
        }).then(() => {
          $('#userTable').DataTable().ajax.reload(null, false);
        });
      }
    });
  }
});

$(document).on('click', '.user-edit', function () {
  openModal();
  var user_id = $(this).data('id');
  $.ajax({
    url: "http://127.0.0.1:8000/api/useredit",
    method: "POST",
    data: {
      id: user_id
    },
    dataType: "JSON",
    success: function (data) {
      form.id = data.id,
        form.name = data.name;
      form.email = data.email;
      form.password = data.password;
      form.isAdmin = data.isAdmin;
      console.log(data);
    }
  });
});

var openModal = () => {
  document.getElementById("myModal").classList.remove("hidden");
}

var closeModal = () => {
  document.getElementById("myModal").classList.add("hidden");
}

const form = reactive({
  id: '',
  name: '',
  email: '',
  password: '',
  isAdmin: false,
});

const edituser = () => {
  console.log(form);
  $.ajax({
    url: "http://127.0.0.1:8000/api/userupdate",
    method: "POST",
    data: form,
    dataType: "JSON",
    success: function (data) {
      Swal.fire({
        title: 'Success',
        text: data.message,
        icon: 'success',
        confirmButtonText: 'OK',
        buttonsStyling: true
      }).then(() => {
        $('#userTable').DataTable().ajax.reload(null, false);
        closeModal();
      });
    }
  });
}
</script>
<style>
div.dt-container select.dt-input {
  padding: 4px;
  width: 60px;
}
</style>
<template>
  <div class="bg-gray-100 py-10">
    <div class="mx-auto max-w-8xl">
      <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
          <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">User</h1>
          </div>

          <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <Link :href="route('user.create')"
              class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
            Add User
            </Link>
          </div>
        </div>

        <div class="mt-8 flex flex-col">
          <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
              <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg relative">
                <table class="min-w-full divide-y divide-gray-300" id="userTable">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                        ID
                      </th>
                      <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                        Name
                      </th>
                      <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                        Email
                      </th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                        Last Login
                      </th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                        Created At
                      </th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                        Action
                      </th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 bg-white">
                    <!-- Table data will be populated by DataTable -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="myModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-1/3">
      <!-- Modal Header -->
      <div class="flex justify-between items-center border-b p-4">
        <h2 class="text-lg font-bold">Modal Title</h2>
        <button class="text-gray-400 hover:text-gray-600" :onclick="closeModal">
          &times;
        </button>
      </div>
      <!-- Modal Body -->
      <form @submit.prevent="edituser">
        <div class="shadow sm:rounded-md sm:overflow-hidden">
          <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
            <div class="grid grid-cols-6 gap-6">
              <input type="hidden" name="hidden" v-model="form.id">
              <!-- Name Field -->
              <div class="col-span-6 sm:col-span-3">
                <label for="name" class="block text-sm font-medium text-gray-700">
                  Name
                </label>
                <input v-model="form.name" type="text" id="name"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  required />
              </div>

              <!-- Email Field -->
              <div class="col-span-6 sm:col-span-3">
                <label for="email" class="block text-sm font-medium text-gray-700">
                  Email
                </label>
                <input v-model="form.email" type="email" id="email"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  required />
              </div>

              <!-- Password Field -->
              <div class="col-span-6 sm:col-span-3">
                <label for="password" class="block text-sm font-medium text-gray-700">
                  Password
                </label>
                <input v-model="form.password" type="password" id="password"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
              </div>


              <!-- IsAdmin Checkbox -->
              <div class="col-span-6 sm:col-span-3">

                <label for="isAdmin" class="inline-flex items-center">
                  <input v-model="form.isAdmin" type="checkbox" id="isAdmin" />
                  <span class="ml-2 text-sm text-gray-700">Is Admin?</span>
                </label>
              </div>
            </div>
          </div>

          <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button type="button"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4"
              @click="closeModal">
              Cancel
            </button>

            <button type="submit"
              class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              Save
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
