<template>
    <div>
        <nav class="navbar navbar-dark bg-dark navbar-expand-lg">
            <div class="navbar-nav">
                <router-link class="nav-link" to="/" exact> Home </router-link>
                <router-link class="nav-link" to="/get-policy">
                    Get Policy
                </router-link>
                <router-link class="nav-link" to="/login"> Login </router-link>
                <router-link class="nav-link" to="/register">
                    Register
                </router-link>
                <router-link class="nav-link" to="/new-blog">
                    New Blog
                </router-link>
                <router-link class="nav-link" to="/dashboard">
                    Dashboard
                </router-link>
            </div>
        </nav>
        <div class="container"><router-view></router-view></div>
    </div>
</template>

<script>
export default {};
</script>
