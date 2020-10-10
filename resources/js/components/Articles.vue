<template>
    <div>
        <h2>Articles</h2>
        <form v-if="isAdmin" @submit.prevent="addArticle" class="mb-3">
            <div class="form-group">
                <input type="text" v-model="article.title" placeholder="Title...." class="form-control">                 
            </div>
            <div class="form-group">
                <textarea v-model="article.body" rows="4" placeholder="Body...." class="form-control"></textarea>                 
            </div>
            <button type="submit" class="btn btn-secondary btn-block" v-text="edit ? 'Edit':'Create'"></button>
            <button @click="reset" class="btn btn-info btn-block text-light">Reset</button>
        </form>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li :class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                    <a @click="fetchArticles(pagination.first_page_url)" class="page-link" href="#">First</a>
                </li>
                <li :class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                    <a @click="fetchArticles(pagination.prev_page_url)" class="page-link" href="#">Previous</a>
                </li>
                <li class="page-item disabled">
                    <a class="page-link text-dark" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a>
                </li>
                <li :class="[{ disabled: !pagination.next_page_url }]" class="page-item">
                    <a @click="fetchArticles(pagination.next_page_url)" class="page-link" href="#">Next</a>
                </li>
                <li :class="[{ disabled: pagination.current_page === pagination.last_page }]" class="page-item">
                    <a @click="fetchArticles(pagination.last_page_url)" class="page-link" href="#">Last</a>
                </li>
            </ul>
        </nav>
        <div class="card card-body mb-2" v-for="article in articles" :key="article.id">
            <h4>{{ article.title }}</h4>
            <p>{{ article.body }}</p>
            <hr>
            <button class="btn btn-danger mb-2" v-if="isAdmin" @click="deleteArticle(article.id)">Delete</button>
            <button class="btn btn-warning" v-if="isAdmin" @click="editArticle(article)">Edit</button>
        </div>
    </div>
</template>

<script>
import VueCookies from 'vue-cookies'
import VueJwtDecode from 'vue-jwt-decode'
import Swal from 'sweetalert2'

export default {
    data(){
        return{
            articles: [],
            article: {
                id: '',
                title: '',
                body: '',
                article_id: ''
            },
            pagination: {},
            edit: false
        }
    },
    computed: {
        isAdmin(){
            let roles = JSON.parse(localStorage.getItem('roles'));
            if (roles.some(role => role === 'admin')) {
                return true;
            }else{
                return false
            }
        },
        isUser(){
            let roles = JSON.parse(localStorage.getItem('roles'));
            if (roles.some(role => role === 'user')) {
                return true;
            }else{
                return false
            }
        }
    },
    created(){
        this.fetchArticles()
    }, 
    updated(){
      this.inspect()
    },
    mounted(){
      this.inspect()
    },
    methods:{
        fetchArticles(page_url){
            let vm = this
            page_url = page_url || '/api/articles'
            fetch(page_url)
            .then(res => res.json() )
            .then(response => {
                this.articles = response.data;    
                vm.makePagination(response.meta, response.links)          
            })
            .catch(err => console.log(err) )
        },
        makePagination(meta, links){
            let pagination = {
                current_page: meta.current_page,
                last_page: meta.last_page,
                next_page_url: links.next,
                prev_page_url: links.prev,
                first_page_url: links.first,
                last_page_url: links.last
            };
            this.pagination = pagination
        },
        deleteArticle(id){
            if (confirm('Are you sure?')) {
                fetch(`api/article/${id}`, {
                    method: 'delete'
                })
                .then(res => res.json())
                .then(res => {
                    // Swal.fire('Success!!', 'Article Deleted', 'success')
                    alert('Article Deleted')
                    console.log(res);
                    this.fetchArticles(this.pagination.first_page_url)

                })
                .catch(err => {
                    console.log(err);
                    
                })
            }
                
        },

        addArticle(){
            if (this.article.title === "" || this.article.body) {
                Swal.fire('Please fill all fields', '', 'error')
                return;
            }
            if (this.edit === false) {
                // Add
                fetch('api/article', {
                    method: 'post',
                    body: JSON.stringify(this.article),
                    headers:{
                        'content-type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.article.title = '';
                    this.article.body = '';
                    this.fetchArticles(this.pagination.first_page_url)

                    // Swal.fire('Success', 'Article Added', 'success')
                })
                .catch(err => console.log(err) );
            }else{
                // Edit
                fetch(`api/article/${this.article.id}`, {
                    method: 'put',
                    body: JSON.stringify(this.article),
                    headers:{
                        'content-type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.reset()
                    this.fetchArticles(this.pagination.first_page_url)

                    // Swal.fire('Success', 'Updated', 'success')
                    alert('Updated successfully')
                })
                .catch(err => console.log(err)
                );
            }
        },

        editArticle({id, title, body}){
            this.edit = true;
            this.article.id = id
            this.article.article_id = id
            this.article.title = title
            this.article.body = body
        },

        reset(){
            this.edit = false;
            this.article.id = ''
            this.article.article_id = ''
            this.article.title = ''
            this.article.body = ''
        },

        inspect(){
            console.log("CHECKING...");
            const token = JSON.parse(this.$store.state.jwt);
            if (token) {
                try {
                    var decoded = VueJwtDecode.decode(token);
                    // console.log(decoded);
                    const exp = decoded.exp;
                    // const issuedAt = decoded.iat;

                    // let expDateObj = new Date(exp * 1000);
                    // let utcString = expDateObj.toTimeString();
                    // console.log(utcString); //expiry time

                    // let issuedDateObj = new Date(issuedAt * 1000);
                    // let utcString2 = issuedDateObj.toTimeString();
                    // console.log(utcString2); //Issued time

                    let now = Date.now() / 1000
                    let currentTimestamp = parseInt(now.toFixed())
                    
                    let timeExpire = exp - currentTimestamp;
                    // console.log(timeExpire);
                    if (timeExpire <= 5) {
                        console.log("TIME IS UP!!");
                        this.$store.dispatch("logout");
                    }                

                }catch (error) {
                    console.log(error);
                }
            }
        },


        
    }
}
</script>

<style>

</style>