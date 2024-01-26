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
                                            <button class="btn btn-success">
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
</template>

<script setup>
import axios from 'axios';
import{ref , onMounted} from 'vue'

const header = "Users"
onMounted( ()=> { getUsers() })
const users = ref([])

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

const getUser = async (id) =>{
    await axios.get('http://127.0.0.1:8000/api/admin/user/'+id)
    .then(
        response =>(
            console.log(response.data.data),
            alert('One user')
        )
    )
}

</script>
