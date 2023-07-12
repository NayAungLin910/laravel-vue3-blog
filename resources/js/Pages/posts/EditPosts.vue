<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const props = defineProps(['slug']);
const emit = defineEmits(['showEditSuccess']);
const router = useRouter();

const successShow = ref('');
const fields = ref({
    category_id: ''
});
const errors = ref({});
const url = ref('');
const categories = ref([]);

/**
 * Fetch the selected post
 */
const fetchPost = () => {
    axios.get(`/api/posts/${props.slug}`).then((res) => {
        fields.value = res.data.data
        url.value = '/' + res.data.data.imagePath;
    }).catch((error) => {
        if (error.response.status === 403) {
            router.push({ name: 'DashboardPostsList' });
        }
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

/**
 * Grab the file from file input
 */
const grabFile = (event) => {
    const file = event.target.files[0]
    fields.value.file = file;
    url.value = URL.createObjectURL(file)
    URL.revokeObjectURL(file);
}

/**
 * Fetch the categories
 */
onMounted(() => {
    fetchPost();
    getAllCategories();
});

/**
 * submit create post request
 */
const submit = () => {
    /**
     * since axios.put method will not work due to the image
     * also containing in the request, a new form data will be
     * created witht the _method PUT, so that PUT request will
     * be hidden while the axios.post will be used
     */
    let fd = new FormData();
    fd.append('title', fields.value.title);
    fd.append('category_id', fields.value.category_id);
    if (fields.value.file) {
        fd.append('file', fields.value.file);
    }
    fd.append('body', fields.value.body);
    fd.append('_method', "PUT");

    // submit request to save the edit
    axios.post(`/api/posts/${props.slug}`, fd, {
        headers: { 'Content-Type': "multipart/form-data" }
    }).then((res) => {
        emit('showEditSuccess', 'Post has been edited successfully!');

        router.push({ name: 'DashboardPostsList' })
    }).catch((error) => {
        errors.value = error.response.data.errors;
        if (error.response.status === 403) {
            router.push({ name: 'DashboardPostsList' });
        }
    })
}   

</script>

<template>
    <main class="create-post">
        <div class="container">
            <h1>Edit Post!</h1>

            <!-- success message -->
            <div class="success-msg" v-if="successShow">
                <i class="fa fa-check"></i>
                {{ successShow }}
            </div>

            <!-- Contact Form -->
            <div class="contact-form">
                <form @submit.prevent="submit">
                    <!-- Title -->
                    <label for="title"><span>Title</span></label>
                    <input type="text" id="title" name="title" v-model="fields.title" />
                    <span v-if="errors.title" class="error">{{ errors.title[0] }}</span>
                    <br />

                    <!-- Image -->
                    <label for="image"><span>Image</span></label>
                    <input type="file" id="image" @input="grabFile" />
                    <div class="preview">
                        <img :src="url" :alt="fields.file ? fields.file.name : ''" />
                    </div>
                    <span class="error" v-if="errors.file">{{ errors.file[0] }}</span>
                    <br />

                    <!-- Drop down -->
                    <label for="categories"><span>Choose a category:</span></label>
                    <select name="category_id" v-model="fields.category_id" id="categories">
                        <option value="" selected disabled>Select a Category</option>
                        <option :value="category.id" v-for="category in categories" :key="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                    <span class="error" v-if="errors.category_id">{{ errors.category_id[0] }}</span>
                    <br />

                    <!-- Body-->
                    <label for="body"><span>Body</span></label>
                    <textarea id="body" v-model="fields.body" name="body"></textarea>
                    <span class="error" v-if="errors.body">{{ errors.body[0] }}</span>

                    <!-- Button -->
                    <input class="add-post-btn" type="submit" value="Submit" />
                </form>
            </div>
        </div>
    </main>
</template>
  
<style scoped>
.create-post {
    background-color: #fff;
    padding: 0 3vw;
}

.container input,
textarea,
select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    margin-top: 6px;
    margin-bottom: 20px;
    font-size: 16px;
}

h1 {
    text-align: center;
    padding: 60px 0 50px 0;
}

.add-post-btn {
    background-color: black;
    color: white;
    border: none;
    cursor: pointer;
    transition: 0.3s ease;
}

.add-post-btn:hover {
    transform: translateY(-4px);
}

.preview img {
    max-width: 100%;
    max-height: 120px;
}
</style>