<script setup>
import axios from 'axios';
import { useRouter } from 'vue-router';
import { ref, onMounted, defineEmits } from 'vue';

const name = ref('');
const router = useRouter();
const emit = defineEmits(['updateSidebar']);

/**
 * Get the user name
 */
onMounted(() => {
    axios.get('/api/user').then((response) => {
        name.value = response.data.name; // get the name of the authenticated user
    }).catch((error) => {
        if (error.response.status = 401) {
            emit('updateSidebar');
            localStorage.removeItem('authenticated');
            router.push({ name: 'Login' });
        }
    })
});

/**
 * Logout the user
 */
const logout = () => {
    axios.post('/api/logout').then(() => {
        router.push({ name: 'Home' });
        localStorage.removeItem('authenticated');
        emit('updateSidebar');
    }).catch((error) => {
        console.log(error);
    })
}
</script>

<template>
    <div id="backend-view">
        <div class="logout"><a href="#" @click="logout">Log out</a></div>
        <h1 class="heading">Dashboard</h1>
        <span>Hi {{ name }} ?</span>
        <div class="links">
            <ul>
                <li><a href="">Create Post</a></li>
                <li>
                    <router-link :to="{ name: 'CreateCategories' }">
                        Create Categories
                    </router-link>
                </li>
                <li><a href="">Categories List</a></li>
            </ul>
        </div>
    </div>
</template>

<style scoped>
/* dashboard */
#backend-view {
    text-align: center;
    background-color: #f3f4f6;
    height: 100vh;
    padding-top: 15vh;
}

.logout {
    position: absolute;
    top: 30px;
    right: 40px;
}

.heading {
    margin-bottom: 5px;
}

.links {
    margin-top: 30px;
    margin-left: auto;
    margin-right: auto;
    background: #ffffff;
    max-width: 500px;
    padding: 15px;
    border-radius: 15px;
}

.links ul {
    list-style: none;
}

.links a {
    all: revert;
    font-size: 26px;
    display: inline-block;
    margin: 10px 0;
}
</style>
