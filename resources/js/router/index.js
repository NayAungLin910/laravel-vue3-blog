import { createRouter, createWebHistory } from "vue-router";

import About from '../pages/About.vue'
import Home from "../pages/Home.vue";
import Blog from "../pages/Blog.vue";
import Contact from "../pages/Contact.vue";
import SingleBlog from "../pages/SingleBlog.vue";
import Login from "../Pages/Login.vue";
import Register from "../Pages/Register.vue";
import Dashboard from "../Pages/Dashboard.vue";
import CreateCategories from "../Pages/categories/CreateCategories.vue";
import CategoriesList from "../Pages/categories/CategoriesList.vue";
import EditCategories from "../Pages/categories/EditCategories.vue";

const routes = [
    {
        path: "/",
        name: "Home",
        component: Home,
    },
    {
        path: "/about",
        name: "About",
        component: About,
    },
    {
        path: "/blog",
        name: "Blog",
        component: Blog,
    },
    {
        path: "/contact",
        name: "Contact",
        component: Contact,
    },
    {
        path: "/blog/:slug",
        name: "SingleBlog",
        component: SingleBlog,
        props: true,
    },
    {
        path: "/login",
        name: "Login",
        component: Login,
        meta: { requiresGuest: true }
    },
    {
        path: "/register",
        name: "Register",
        component: Register,
        meta: { requiresGuest: true }
    },
    {
        path: "/dashboard",
        name: "Dashboard",
        component: Dashboard,
        meta: { requiresAuth: true }
    },
    {
        path: '/categories/create',
        name: 'CreateCategories',
        component: CreateCategories,
        meta: { requiresAuth: true }
    },
    {
        path: '/categories',
        name: 'CategoriesList',
        component: CategoriesList,
        meta: { requiresAuth: true }
    },
    {
        path: '/categories/:id/edit',
        name: 'EditCategories',
        component: EditCategories,
        meta: { requiresAuth: true },
        props: true,
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});


router.beforeEach((to, from) => {
    const authenticated = localStorage.getItem('authenticated');

    if (to.meta.requiresGuest && authenticated) {
        // if authenticated user is trying to access guest routes
        return {
            name: 'Dashboard',
        }
    } else if (to.meta.requiresAuth && !authenticated) {
        // if unauthenticated user is trying to access auth routes
        return {
            name: 'Login',
        }
    }
})

export default router;
