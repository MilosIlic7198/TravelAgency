<template>
    <div>
        <nav class="navbar navbar-dark bg-dark navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="">Travel Agency</a>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <router-link class="nav-link" to="/" exact> Home </router-link>
                        </li>
                        <li v-if="person == null" class="nav-item">
                            <router-link class="nav-link" to="/get-policy">
                                Get Policy
                            </router-link>
                        </li>
                        <li v-if="person == null" class="nav-item">
                            <router-link class="nav-link" to="/login"> Login </router-link>
                        </li>
                        <li v-if="person != null" class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Dashboard
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <router-link class="dropdown-item" to="/blogs">
                                        Blogs
                                    </router-link>
                                </li>
                                <li>
                                    <router-link class="dropdown-item" to="/users">
                                        Users
                                    </router-link>
                                </li>
                                <li>
                                    <router-link class="dropdown-item" to="/insurance-policies">
                                        Insurance Policies
                                    </router-link>
                                </li>
                            </ul>
                        </li>
                        <li v-if="person != null" class="nav-item">
                            <a class="nav-link" href="" role="button" @click.prevent="logout()">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container d-flex justify-content-center"><router-view @personLoggedIn="loggedIn"></router-view></div>
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
        loggedIn(person) {
            this.person = person;
        },
        logout() {
            axios.post('/api/logout').then((res) => {
                if (res.status == 200) {
                    this.person = null;
                    this.$router.push({ name: 'Home' }).catch((err) => { });
                }
            }).catch(err => {
                alert(err.response.data);
            });
        }
    },
    mounted() {
        axios.get('/api/user').then((res) => {
            this.person = res.data;
        })
    }
};
</script>
