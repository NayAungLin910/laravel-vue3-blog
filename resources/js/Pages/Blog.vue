<script setup>
import axios from 'axios'
import { ref, onMounted, watch } from 'vue'

const posts = ref([])
const categories = ref([])
const search = ref('')
const timeout = ref(null)
const links = ref([])
const paginatePage = ref('1')
const categoryChosen = ref('')

/**
 * change paginate page
 */
const changePage = (link) => {
  if (!link.url || link.active) {
    return
  }

  // get the last character of link url which maybe 1 or 2, etc.
  paginatePage.value = link.url.slice(-1)
}

/**
 * Fetch the searched posts
 */
const fetchSearchedPosts = () => {
  axios
    .get('/api/posts', {
      params: {
        search: search.value,
        category: categoryChosen.value,
        page: paginatePage.value
      }
    })
    .then((response) => {
      posts.value = response.data.data
      links.value = response.data.meta.links // links from pagination
    })
    .catch((error) => {
      console.log(error)
    })
}

/**
 * Fetch the post the user searches
 */
watch(search, categoryChosen, paginatePage, () => {

  if (timeout.value) { clearTimeout(timeout.value) };

  timeout.value = setTimeout(fetchSearchedPosts, 500)
})

/**
 * Refetch the post if the user choses category or
 * different paginate page number
 * the immediate means the watcher's callback will
 * be exectued immediately
 * 
 */
watch([categoryChosen, paginatePage], () => {
  fetchSearchedPosts()
}, { immediate: true })

/**
 * Get all categories 
 */
const getAllCategories = () => {
  axios.get('/api/categories').then((response) => {
    categories.value = response.data
  }).catch((error) => {
    console.log(error)
  })
}

/**
 * On mounted get all the categories
 */
onMounted(() => {
  getAllCategories()
})
</script>

<template>
  <div>
    <h2 class="header-title">All Blog Posts</h2>
    <div class="searchbar">
      <form action="">
        <input type="text" placeholder="Search..." name="search" v-model="search" />

        <button type="submit">
          <i class="fa fa-search"></i>
        </button>

      </form>
    </div>
    <div class="categories">
      <ul>
        <li>
          <a class="categoryLinks" :class="{ activeCategory: categoryChosen === '' }"
            @click.prevent="categoryChosen = ''">All</a>
        </li>
        <li v-for="category in categories" :key="category.id">
          <a class="categoryLinks" :class="{ activeCategory: categoryChosen === category.name }"
            @click.prevent="categoryChosen = category.name; paginatePage = '1';">
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
    <h3 class="not-found-message" v-if="!posts.length">Sorry no match was found!</h3>

    <!-- Pagination -->
    <div class="pagination" id="pagination">
      <a href="#" :class="{ active: link.active, disabled: !link.url || link.active }" v-for="(link, index) in links"
        :key="index" v-html="link.label" @click.prevent="changePage(link)"></a>
    </div>
  </div>
</template>
<style scoped>
.not-found-message {
  font-size: 30px;
  text-align: center;
  margin: 50px 0;
  color: #fff;
}

.disabled {
  pointer-events: none;
  opacity: 0.5;
}

.activeCategory {
  background-color: darkslategray;
  border-radius: 15px;
}

.categoryLinks:hover {
  cursor: pointer;
}
</style>
