<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
const overlayVisibility = ref(false);
const loggedIn = ref(false);
const route = useRoute();
const editSuccess = ref('');

// toggle overlay
const showOverlay = () => {
    overlayVisibility.value = true;
}

const hideOverlay = () => {
    overlayVisibility.value = false;
}

// update sidebar
const updateSidebar = () => {
    loggedIn.value = !loggedIn.value;
}

// show edit success message
const showEditSuccess = (message) => {
    editSuccess.value = message;
    setInterval(() => {
        editSuccess.value = '';
    }, 2500);
    console.log(message);
}

// check local storage on mounted
onMounted(() => {
    if (localStorage.getItem('authenticated')) {
        loggedIn.value = true;
    } else {
        loggedIn.value = false;
    }
});
</script>
<template>
    <div id="wrapper">

        <!-- sidebar -->
        <div class="sidebar" :class="{ showOverlay: overlayVisibility }">
            <span class="closeButton" @click="hideOverlay">&times;</span>
            <p class="brand-title"><a href="">Alphayo Blog</a></p>

            <div class="side-links">
                <ul>
                    <li><router-link @click="hideOverlay" :to="{ name: 'Home' }">Home</router-link></li>
                    <li><router-link @click="hideOverlay" :to="{ name: 'Blog' }">Blog</router-link></li>
                    <li><router-link @click="hideOverlay" :to="{ name: 'About' }">About</router-link></li>
                    <li><router-link @click="hideOverlay" :to="{ name: 'Contact' }">Contact</router-link></li>
                    <li><router-link v-if="!loggedIn" @click="hideOverlay" :to="{ name: 'Register' }">Register</router-link>
                    </li>
                    <li><router-link v-if="!loggedIn" @click="hideOverlay" :to="{ name: 'Login' }">Login</router-link></li>
                    <li><router-link v-if="loggedIn" @click="hideOverlay"
                            :to="{ name: 'Dashboard' }">Dashboard</router-link></li>
                </ul>
            </div>

            <!-- sidebar footer -->
            <footer class="sidebar-footer">
                <div>
                    <a href=""><i class="fab fa-facebook-f"></i></a>
                    <a href=""><i class="fab fa-instagram"></i></a>
                    <a href=""><i class="fab fa-twitter"></i></a>
                </div>

                <small>&copy 2021 Alphayo Blog</small>
            </footer>
        </div>

        <!-- Menu Button -->
        <div class="menuButton" @click="showOverlay">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>

        <!-- main -->
        <main class="container">
            <!-- render components based on the page visited -->
            <router-view :editSuccess="editSuccess" :key="route.path" @show-edit-success="showEditSuccess"
                @update-sidebar="updateSidebar"></router-view>
        </main>

        <!-- Main footer -->
        <footer class="main-footer">
            <div>
                <a href=""><i class="fab fa-facebook-f"></i></a>
                <a href=""><i class="fab fa-instagram"></i></a>
                <a href=""><i class="fab fa-twitter"></i></a>
            </div>
            <small>&copy; 2021 Alphayo Blog</small>
        </footer>
    </div>
</template>
<style scoped>
.showOverlay {
    width: 100%;
    z-index: 5;
}
</style>
