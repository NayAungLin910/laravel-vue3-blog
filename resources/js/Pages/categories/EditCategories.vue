<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';

let field = ref({});
let errors = ref({});
let success = ref(false);
const props = defineProps(['id']);

// submit post request to edit category
const submit = () => {
    axios.put(`/api/categories/${props.id}/`, field.value).then((response) => {
        errors.value = {};
        success.value = true;

        setInterval(() => {
            success.value = false;
        }, 2500);
    }).catch((error) => {
        errors.value = error.response.data.errors;
    })
}

// fetch the chosen category info
onMounted(() => {
    axios.get(`/api/categories/${props.id}`).then((response) => {
        field.value = response.data;
    }).catch((error) => {
        errors.value = error.response.data.errors;
    })
});

</script>

<template>
    <div id="create-categories">
        <div id="contact-us">
            <h1>Edit Category!</h1>

            <!-- success message -->
            <div class="success-msg" v-if="success">
                <i class="fa fa-check"></i>
                Category updated successfully!
            </div>

            <div class="contact-form">
                <form @submit.prevent="submit">

                    <!-- name -->
                    <label for="name"><span>Name</span></label>
                    <input type="text" id="name" v-model="field.name" />
                    <span v-if="errors.name" class="error">{{ errors.name[0] }}</span>

                    <input type="submit" value="Save" />
                </form>
            </div>
            <div class="create-categories">
                <router-link :to="{ name: 'CategoriesList' }">
                    Categories list <span>&#8594;</span>
                </router-link>
            </div>
        </div>
    </div>
</template>

<style scoped>
#create-categories {
    background-color: #f3f4f6;
    height: 90vh;
    padding: 50px;
}
</style>
