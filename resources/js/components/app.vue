<template>
    <div>
        <nav class="navbar navbar-dark bg-dark navbar-expand-lg">
            <div class="navbar-nav">
                <router-link class="nav-link" to="/" exact> Home </router-link>
                <router-link v-if="person == null" class="nav-link" to="/get-policy">
                    Get Policy
                </router-link>
                <router-link v-if="person == null" class="nav-link" to="/login"> Login </router-link>
                <router-link v-if="person == null" class="nav-link" to="/register">
                    Register
                </router-link>
                <router-link v-if="person != null" class="nav-link" to="/new-blog">
                    New Blog
                </router-link>
                <router-link v-if="person != null" class="nav-link" to="/dashboard">
                    Dashboard
                </router-link>
                <button v-if="person != null" class="nav-link" @click="logout()">Logout</button>
            </div>
        </nav>
        <div class="container d-flex justify-content-center"><router-view></router-view></div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            person: null,
        };
    },
    methods: {
        logout() {
            axios.post('/api/logout').then(() => {
                this.$router
                    .push('/')
                    .then(() => { this.$router.go() })
            })
        }
    },
    mounted() {
        axios.get('/api/user').then((res) => {
            this.person = res.data;
        })
    }
};
</script>
