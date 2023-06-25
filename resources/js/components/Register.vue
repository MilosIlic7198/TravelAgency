<template>
    <form>
        <h3 class="m-2">Register!</h3>
        <div v-if="error.length" class="alert alert-danger m-2" role="alert">
            {{ error }}
        </div>
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
            error: "",
            formFields: {
                email: "",
                password: "",
            },
        };
    },
    methods: {
        displayError(error) {
            this.error = error;
            setTimeout(() => {
                this.error = "";
            }, 5000);
        },
        validateEmail(email) {
            const emailRegexp = new RegExp(
                /^[a-zA-Z0-9][\-_\.\+\!\#\$\%\&\'\*\/\=\?\^\`\{\|]{0,1}([a-zA-Z0-9][\-_\.\+\!\#\$\%\&\'\*\/\=\?\^\`\{\|]{0,1})*[a-zA-Z0-9]@[a-zA-Z0-9][-\.]{0,1}([a-zA-Z][-\.]{0,1})*[a-zA-Z0-9]\.[a-zA-Z0-9]{1,}([\.\-]{0,1}[a-zA-Z]){0,}[a-zA-Z0-9]{0,}$/i
            );
            return emailRegexp.test(email);
        },
        validatePassword(pass) {
            const passRegexp = new RegExp(
                /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/
            );
            return passRegexp.test(pass);
        },
        register() {
            if (!this.formFields.email || !this.formFields.password) {
                this.displayError("You have empty fields!");
                return;
            }
            if (!this.validateEmail(this.formFields.email)) {
                this.displayError("Invalid email!");
                return;
            }
            if (!this.validatePassword(this.formFields.password)) {
                this.displayError(
                    "Password should contain atleast one number, one special character and be from 6 to 16 characters long!"
                );
                return;
            }
            axios
                .post("/api/register", this.formFields)
                .then((res) => {
                    if (res.status == 200) {
                        console.log(res.data);
                        this.$emit("personLoggedIn", res.data);
                        this.$router.push({ name: "Users" });
                    }
                })
                .catch((err) => {
                    this.formFields = {
                        email: "",
                        password: "",
                    };
                    console.log(err.response.data.error);
                    this.displayError(err.response.data.error);
                });
        },
    },
};
</script>
