<script setup>
import axios from 'axios';
import { reactive, ref, onMounted } from 'vue';

let field = ref({});
let errors = ref({});
let success = ref(false);

// submit post request to create category
const submit = () => {
    axios.post("/api/categories/create", field.value).then(() => {
        field.value = {};
        errors.value = {};
        success.value = true;

        setInterval(() => {
            success.value = false;
        }, 2500);
    }).catch((error) => {
        errors.value = error.response.data.errors;
    })
}

</script>

<template>
    <div id="create-categories">
        <div id="contact-us">
            <h1>Create New Category!</h1>

            <!-- success message -->
            <div class="success-msg" v-if="success">
                <i class="fa fa-check"></i>
                Category created successfully
            </div>

            <div class="contact-form">
                <form @submit.prevent="submit">

                    <!-- name -->
                    <label for="name"><span>Name</span></label>
                    <input type="text" id="name" v-model="field.name" />
                    <span v-if="errors.name" class="error">{{ errors.name[0] }}</span>

                    <input type="submit" value="Submit" />
                </form>
            </div>
            <div class="create-categories">
                <a href="">Categories list <span>&#8594;</span></a>
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
