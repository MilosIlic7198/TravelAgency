<template>
    <form>
        <h3 class="m-2">Edit!</h3>
        <div class="m-2">
            <label for="title" class="d-block text-lg">Email:</label>
            <input
                type="text"
                class="border border-gray-200 rounded p-2"
                name="title"
                v-model="person.email"
            />
        </div>
        <div class="m-2">
            <label for="title" class="d-block text-lg">New password:</label>
            <input
                type="password"
                class="border border-gray-200 rounded p-2"
                name="title"
                v-model="newPassword"
            />
            <div class="form-text">
                Leave this empty if you don't want to reset password!
            </div>
        </div>
        <div class="form-check m-2">
            <input
                class="form-check-input"
                type="checkbox"
                value="Admin"
                id="adminCheck"
                :checked="
                    roles[0] == 'Admin' ||
                    roles[1] == 'Admin' ||
                    roles[2] == 'Admin'
                "
            />
            <label class="form-check-label" for="adminCheck"> Admin </label>
        </div>
        <div class="form-check m-2">
            <input
                class="form-check-input"
                type="checkbox"
                value="Moderator"
                id="moderatorCheck"
                :checked="
                    roles[0] == 'Moderator' ||
                    roles[1] == 'Moderator' ||
                    roles[2] == 'Moderator'
                "
            />
            <label class="form-check-label" for="moderatorCheck">
                Moderator
            </label>
        </div>
        <div class="form-check m-2">
            <input
                class="form-check-input"
                type="checkbox"
                value="User"
                id="userCheck"
                :checked="
                    roles[0] == 'User' ||
                    roles[1] == 'User' ||
                    roles[2] == 'User'
                "
            />
            <label class="form-check-label" for="userCheck"> User </label>
        </div>
        <div class="m-2">
            <button @click.prevent="save()" class="btn btn-primary">
                Save
            </button>
            <button @click.prevent="cancel()" class="btn btn-secondary">
                Cancel
            </button>
        </div>
    </form>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            person: {},
            newPassword: "",
            roles: [],
        };
    },
    methods: {
        getPerson() {
            axios
                .get(`/api/get-person/${this.$route.params.id}`)
                .then((res) => {
                    const status = JSON.parse(res.status);
                    if (status == "200") {
                        this.person = res.data[0];
                        this.roles = res.data[1];
                    }
                });
        },
        save() {
            let newRoles = [];
            let adminBox = document.getElementById("adminCheck");
            let moderatorBox = document.getElementById("moderatorCheck");
            let userBox = document.getElementById("userCheck");

            if (adminBox.checked) {
                newRoles.push("Admin");
            }
            if (moderatorBox.checked) {
                newRoles.push("Moderator");
            }
            if (userBox.checked) {
                newRoles.push("User");
            }
            if (newRoles.length == 0) {
                alert("You must have one role checked!");
                return;
            }
            const editedData = new FormData();
            editedData.append("email", this.person.email);
            if (this.newPassword) {
                if (this.newPassword.length >= 6) {
                    editedData.append("newPassword", this.newPassword);
                } else {
                    alert("New password must me at least 6 characters long!");
                    return;
                }
            }
            editedData.append("roles", newRoles);
            axios
                .post(`/api/edit-person/${this.$route.params.id}`, editedData)
                .then((res) => {
                    const status = JSON.parse(res.status);
                    if (status == "200") {
                        this.$router.push("/users");
                    }
                });
        },
        cancel() {
            this.$router.push("/users");
        },
    },
    mounted() {
        this.getPerson();
    },
};
</script>
