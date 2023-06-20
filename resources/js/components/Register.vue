<template>
    <form>
        <h3 class="m-2">Register!</h3>
        <div class="m-2">
            <label for="email" class="d-block text-lg">Email address</label>
            <input
                type="email"
                class="border border-gray-200 rounded p-2"
                id="email"
                placeholder="Enter email"
                name="email"
                v-model="formFields.email"
            />
        </div>
        <div class="m-2">
            <label for="password" class="d-block text-lg">Password</label>
            <input
                type="password"
                class="border border-gray-200 rounded p-2"
                id="password"
                placeholder="Password"
                name="password"
                v-model="formFields.password"
            />
        </div>

        <div class="m-2">
            <button
                @click.prevent="register()"
                type="submit"
                class="btn btn-primary"
            >
                Register
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
        register() {
            axios.post("/api/register", this.formFields).then((res) => {
                const status = JSON.parse(res.status);
                if (status == "200") {
                    this.$router.push("/users").then(() => {
                        this.$router.go();
                    });
                } else {
                    this.$router.go();
                }
            });
        },
    },
};
</script>
