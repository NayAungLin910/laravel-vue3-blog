<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';

defineEmits(['updateSidebar'])
const props = defineProps(['editSuccess']);

const posts = ref([]);
const successMessage = ref('');

/**
 * Get posts
 */
const getAllPosts = () => {
    axios.get('/api/posts/dashboard-posts/all').then((response) => {
        posts.value = response.data.data;
    }).catch((error) => {
        console.log(error)
    })
}

onMounted(() => {
    getAllPosts();
})
</script>

<template>
    <div class="categories-list">
        <h1>Posts List</h1>
        <!-- success message -->
        <div class="success-msg" v-if="successMessage">
            <i class="fa fa-check"></i>
            {{ successMessage }}
        </div>
        <div class="success-msg" v-if="props.editSuccess">
            <i class="fa fa-check"></i>
            {{ props.editSuccess }}
        </div>

        <div class="item" v-for="(post, index) in posts" :key="post.id">
            <span>{{ index + 1 }}</span>
            <p>{{ post.title }}</p>
            <div>
                <router-link :to="{ name: 'EditPosts', params: { slug: post.slug } }" class="edit-link">
                    Edit
                </router-link>
            </div>

            <input type="submit" value="Delete" class="delete-btn" />
        </div>
        <div class="index-categories">
            <router-link :to="{ name: 'CreatePosts' }">Create post<span>&#8594;</span></router-link>
        </div>
    </div>
</template>
  
<style scoped >
.categories-list {
    min-height: 100vh;
    background: #fff;
}

.categories-list h1 {
    font-weight: 300;
    padding: 50px 0 30px 0;
    text-align: center;
}

.categories-list .item {
    display: flex;
    justify-content: right;
    align-items: center;
    max-width: 300px;
    margin: 0 auto !important;
}

.categories-list .item p {
    font-size: 16px;
}

.categories-list .item p,
.categories-list .item div,
.categories-list .item {
    margin: 15px 8px;
}

.categories ul li {
    list-style: none;
    background-color: #494949;
    margin: 20px 5px;

    border-radius: 15px;
}

.categories ul {
    display: flex;
    justify-content: center;
}

.categories a {
    color: white;
    padding: 10px 20px;
    display: inline-block;
}

.create-categories a,
.index-categories a {
    all: revert;
    margin: 20px 0;
    display: inline-block;
    text-decoration: none;
}

.create-categories a span,
.index-categories a span {
    font-size: 22px;
}

.index-categories {
    text-align: center;
}
</style>