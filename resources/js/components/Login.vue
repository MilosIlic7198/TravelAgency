<template>
    <form>
        <h3 class="m-2">Login!</h3>
        <div class="m-2">
            <label for="email" class="d-block text-lg">Email address</label>
            <input type="email" class="border border-gray-200 rounded p-2" id="email" placeholder="Enter email" name="email"
                v-model="formFields.email" />
        </div>
        <div class="m-2">
            <label for="password" class="d-block text-lg">Password</label>
            <input type="password" class="border border-gray-200 rounded p-2" id="password" placeholder="Password"
                name="password" v-model="formFields.password" />
        </div>

        <div class="m-2">
            <button @click.prevent="login()" type="submit" class="btn btn-primary">
                Login
            </button>
        </div>
    </form>
</template>

<script>
export default {
    data() {
        return {
            formFields: {
                email: "",
                password: "",
            },
        };
    },
    methods: {
        login() {
            if (!this.formFields.email || !this.formFields.password) {
                alert("You have empty fields!");
                return;
            }
            axios.post("/api/login", this.formFields).then((res) => {
                if (res.status == 200) {
                    console.log(res.data);
                    this.$emit("personLoggedIn", res.data);
                    this.$router.push({ name: 'Blogs' });
                }
            }).catch(err => {
                alert(err.response.data.error);
                this.formFields = {
                    email: "",
                    password: "",
                }
            });
        },
    },
};
</script>
