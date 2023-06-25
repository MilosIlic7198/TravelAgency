<template>
    <div>
        <nav class="navbar navbar-dark bg-dark navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" type="button">Travel Agency</a>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <router-link class="nav-link" to="/" exact>
                                Home
                            </router-link>
                        </li>
                        <li v-if="person == null" class="nav-item">
                            <router-link class="nav-link" to="/get-policy">
                                Get Policy
                            </router-link>
                        </li>
                        <li v-if="person == null" class="nav-item">
                            <router-link class="nav-link" to="/login">
                                Login
                            </router-link>
                        </li>
                        <li v-if="person != null" class="nav-item dropdown">
                            <a
                                class="nav-link dropdown-toggle"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                Dashboard
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <router-link
                                        class="dropdown-item"
                                        to="/blogs"
                                    >
                                        Blogs
                                    </router-link>
                                </li>
                                <li>
                                    <router-link
                                        class="dropdown-item"
                                        to="/users"
                                    >
                                        Users
                                    </router-link>
                                </li>
                                <li>
                                    <router-link
                                        class="dropdown-item"
                                        to="/insurance-policies"
                                    >
                                        Insurance Policies
                                    </router-link>
                                </li>
                            </ul>
                        </li>
                        <li v-if="person != null" class="nav-item">
                            <a
                                class="nav-link"
                                type="button"
                                @click.prevent="logout()"
                                >Logout</a
                            >
                        </li>
                        <li v-if="error.length" class="nav-item">
                            <a class="nav-link">| {{ error }}</a>
                        </li>
                        <li v-if="success.length" class="nav-item">
                            <a class="nav-link">| {{ success }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container d-flex justify-content-center">
            <router-view @personLoggedIn="loggedIn"></router-view>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            error: "",
            success: "",
            person: null,
        };
    },
    methods: {
        getUser() {
            axios
                .get("/api/user")
                .then((res) => {
                    if (res.status == 200) {
                        console.log(res.data);
                        this.person = res.data;
                    }
                })
                .catch((err) => {
                    console.log(err.response);
                });
        },
        displaySuccess(message) {
            this.success = message;
            setTimeout(() => {
                this.success = "";
            }, 3000);
        },
        displayError(error) {
            this.error = error;
            setTimeout(() => {
                this.error = "";
            }, 5000);
        },
        loggedIn(person) {
            this.person = person;
            this.displaySuccess("Welcome " + this.person.email);
        },
        logout() {
            axios
                .post("/api/logout")
                .then((res) => {
                    if (res.status == 200) {
                        console.log(res);
                        this.person = null;
                        this.$router.push({ name: "Home" }).catch((err) => {});
                    }
                })
                .catch((err) => {
                    console.log(err.response.data.message);
                    this.displayError(err.response.data.message);
                });
        },
    },
    mounted() {
        this.getUser();
    },
};
</script>
