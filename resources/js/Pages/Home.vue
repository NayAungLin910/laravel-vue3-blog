<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';

defineEmits(['updateSidebar'])
const posts = ref([]);

/**
 * Get posts
 */
const getAllPosts = () => {
    axios.get('/api/posts/home-posts').then((response) => {
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
    <!-- header -->
    <header class="header">
        <div class="header-text">
            <h1>Alphayo Blog</h1>
            <h4>Home of verified news...</h4>
        </div>
        <div class="overlay"></div>
    </header>

    <h2 class="header-title">Latest Blog Posts</h2>
    <section class="cards-blog latest-blog">
        <div class="card-blog-content" v-for="post in posts" :key="post.id">
            <img :src="`${post.imagePath}`" :alt="`The post, ${post.name}'s image`" />
            <p>
                {{ post.created_at }}
                <span style="float: right">Written By {{ post.user }}</span>
            </p>
            <h4 style="font-weight: bolder">
                <router-link :to="{ name: 'SingleBlog', params: { slug: post.slug } }">
                    {{ post.title }}
                </router-link>
            </h4>
        </div>
    </section>
</template>
