<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';

defineEmits(['updateSidebar']);

const props = defineProps(['slug'])
const post = ref({})
const relatedPosts = ref([])

const fetchPost = () => {
    axios.get(`/api/posts/${props.slug}`).then((res) => {
        post.value = res.data.data
    }).catch((error) => {
        console.log(error);
    })
}

const fetchRelatedPost = () => {
    axios.get(`/api/posts/related-posts/${props.slug}`).then((res) => {
        relatedPosts.value = res.data.data
    }).catch((error) => {
        console.log(error);
    })
}

onMounted(() => {
    fetchPost()
    fetchRelatedPost()
})

</script>

<template>
    <section class="single-blog-post">
        <h1>{{ post.title }}</h1>

        <p class="time-and-author">
            {{ post.created_at }}
            <span>Written By {{ post.user }}</span>
        </p>

        <div class="single-blog-post-ContentImage" data-aos="fade-left">
            <img :src="`/${post.imagePath}`" :alt="`The post, ${post.name}'s image`" />
        </div>

        <div class="about-text">
            <p>
                {{ post.body }}
            </p>
        </div>
    </section>
    <section class="recommended">
        <p>Related</p>
        <div class="recommended-cards">
            <router-link v-for="relatedPost in relatedPosts" :key="relatedPost.id"
                :to="{ name: 'SingleBlog', params: { slug: relatedPost.slug } }">
                <div class="recommended-card">
                    <img :src="`/${relatedPost.imagePath}`" :alt="`The post, ${relatedPost.title}'s image'`"
                        loading="lazy" />
                    <h4>
                        {{ relatedPost.title }}
                    </h4>
                </div>
            </router-link>
        </div>
    </section>
</template>
