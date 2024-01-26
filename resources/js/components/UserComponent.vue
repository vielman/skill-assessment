<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{header}}</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th><th>Name</th><th>Email</th><th>Active</th><th>Limit</th><th colspan="2" style="text-align: center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <tr v-for="user, i in users" :key="user.id">
                                        <td>{{ (i+1) }}</td>
                                        <td>{{ user.name }}</td>
                                        <td>{{ user.email }}</td>
                                        <td v-if="user.is_active">Yes</td>
                                        <td v-else>No</td>
                                        <td>{{ user.number_quotes_saved }}</td>
                                        <td>
                                            <button class="btn btn-success"
                                            @click=" selecteUser(user)" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                Edit
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger"
                                            @click="$event => banningUser(user.id)">
                                                banning 
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit User limit </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>ID: {{currentUser.id}} - Name: {{currentUser.name}} - Email: {{currentUser.email}}</p>
                    <label for="qty">Quotes Limit : </label>
                    <input type="number" name="qty" id="qty" v-model="currentUser.number_quotes_saved">
                </div>
                <div class="modal-footer">
                    <button @click="updateUser(currentUser.id,currentUser.number_quotes_saved)" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                    <button @click="getUsers()" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>            
</template>

<script setup>
import axios from 'axios';
import{ref , onMounted} from 'vue'

const header = "Users"
onMounted( ()=> { getUsers() })
const users = ref([])
const currentUser = ref({})

const getUsers = async () =>{
    await axios.get('http://127.0.0.1:8000/api/admin/users').then(
        response =>(
            users.value = response.data.data
        ) 
    )
}

const banningUser = async (id) =>{
    await axios.patch('http://127.0.0.1:8000/api/admin/user/'+id)
    .then(
        response =>(
            alert('Ban user successfully'),
            getUsers()
        )
    )
}

const updateUser = async (id, qty) =>{
    await axios.put('http://127.0.0.1:8000/api/admin/user',  {
        id: id,
        number_quotes_saved: qty,
    })
    .then(
        response =>(
            alert('quantity was successfully changed'),
            getUsers()
        )
    ).catch(error => {
        alert(error.response.data.message)
        getUsers()
    })
}

const selecteUser = async (user) =>{
    currentUser.value = user
}
</script>
