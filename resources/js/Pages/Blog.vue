<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';

defineEmits(['updateSidebar', 'showEditSuccess'])
const porps = defineProps(['editSuccess'])
const posts = ref([]);
const categories = ref([]);

/**
 * Filter posts by category
 */
const filterByCategory = (name) => {
  axios
    .get('/api/posts', {
      params: {
        category: name,
      }
    })
    .then((response) => {
      posts.value = response.data.data;
      console.log(response.data.data)
    })
    .catch((error) => {
      console.log(error)
    })
}

/**
 * Get posts
 */
const getAllPosts = () => {
  axios.get('/api/posts').then((response) => {
    posts.value = response.data.data;
  }).catch((error) => {
    console.log(error)
  })
}

/**
 * Get all categories 
 */
const getAllCategories = () => {
  axios.get('/api/categories').then((response) => {
    categories.value = response.data;
  }).catch((error) => {
    console.log(error)
  })
}

onMounted(() => {
  getAllPosts();
  getAllCategories();
})
</script>

<template>
  <h2 class="header-title">All Blog Posts</h2>
  <div class="searchbar">
    <form action="">
      <input type="text" placeholder="Search..." name="search" />

      <button type="submit">
        <i class="fa fa-search"></i>
      </button>

    </form>
  </div>
  <div class="categories">
    <ul>
      <li v-for="category in categories" :key="category.id">
        <a @click="filterByCategory(category.name)">
          {{ category.name }}
        </a>
      </li>
    </ul>
  </div>
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
