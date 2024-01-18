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
                                        <th>#</th><th>Quote</th><th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <tr v-for="favo, i in favorites.data" :key="favo.id">
                                        <td>{{ (i+1) }}</td>
                                        <td>{{ favo.quote }}</td>
                                        <td>
                                            <button class="btn btn-danger"
                                            @click="$event => deleteFavorite(favo.id)">
                                                Delete
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

const header = "Favorites Quotes"
onMounted( ()=> {getFavorites()})
const favorites = ref([])

const getFavorites = async () =>{
    await axios.get('http://127.0.0.1:8000/api/favoritequotes').then(
        response =>(
            favorites.value = response.data
        ) 
    )
}

const deleteFavorite = async (id) =>{
    await axios.delete('http://127.0.0.1:8000/api/favoritequotes/'+id).then(
        response =>(
            getFavorites()
        )
    )
    
}
</script>
