<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{header}}</div>
                    <div class="card-body">
                        <div class=" card-body d-grid gap-2 col-6 mx-auto">
                            <button class="btn btn-warning"
                             @click="increment" :disabled="blockutton">Update Quote
                             </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th><th>Quote</th><th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <tr v-for="quote, i in quotes" :key="quote.id">
                                        <td>{{ (i+1) }}</td>
                                        <td>{{ quote.quote }}</td>
                                        <td>
                                            <button class="btn btn-success"
                                            @click="$event => addFavorite(quote.quote, quote.author, quote.category)">
                                                Favorite
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
import{ref , onMounted, computed} from 'vue'

const props = defineProps({
    userid: {
        type: Text,
        required: true
    }
});

const header = "Quotes"
onMounted( ()=> { getQuotes() })
const quotes = ref([])
const counter = ref(0);

const increment = () => {
    counter.value++
    getQuotes()
}

const reset = () => {
    counter.value = 0;
}

const blockutton = computed(() => {
    console.log(counter.value )
    if (counter.value >= 30) {
     return true   
    }
})

setInterval(() => {
   reset()
}, (1000 * 60));

const getQuotes = async () =>{
    await axios.get('http://127.0.0.1:8000/api/quotes').then(
        response =>(
            quotes.value = response.data.data.quotes
        ) 
    )
}

const addFavorite = async (quote, author, category) =>{
    await axios.post('http://127.0.0.1:8000/api/favoritequotes',  {
        quote: quote,
        author: author,
        user_id: props.userid
    })
    .then(
        response =>(
            alert('Successfully added to favorites')
        )
    ).catch(error => {
        let data = error.response.data.data.number_quotes_saved ? error.response.data.data.number_quotes_saved : error.response.data.data
        alert(error.response.data.message + '' +  data)
    })
}
</script>
