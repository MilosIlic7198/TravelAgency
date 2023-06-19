<template>
    <form>
        <h3 class="m-2">Add new blog!</h3>
        <div class="m-2">
            <label for="title" class="d-block text-lg">Title:</label>
            <input
                type="text"
                class="border border-gray-200 rounded p-2"
                name="title"
                v-model="formFields.title"
            />
        </div>
        <div class="m-2">
            <label for="description" class="d-block text-lg"
                >Description:</label
            >
            <vue-editor v-model="formFields.description"/>
        </div>
        <div class="m-2">
            <label for="image" class="d-block text-lg"> Image: </label>
            <input
                type="file"
                class="border border-gray-200 rounded p-2"
                name="image"
                @change="imageSelected($event)"
            />
        </div>
        <select
            class="border border-gray-200 rounded p-2 m-2"
            name="type"
            v-model="formFields.type"
        >
            <option selected value="">Choose the type of the blog!</option>
            <option value="News">News</option>
            <option value="Post">Post</option>
        </select>
        <div class="m-2">
            <button
                type="submit"
                class="btn btn-success"
                @click.prevent="createBlog()"
            >
                Create Blog
            </button>
        </div>
    </form>
</template>

<script>
import axios from "axios";
export default {
    data() {
        return {
            formFields: {
                title: "",
                description: "",
                image: "",
                type: "",
            },
        };
    },
    methods: {
        imageSelected(event) {
            this.formFields.image = event.target.files[0];
        },
        createBlog() {
            const formData = new FormData();
            formData.append("title", this.formFields.title);
            formData.append("description", this.formFields.description);
            formData.append("image", this.formFields.image);
            formData.append("type", this.formFields.type);
            axios.post("/api/create-blog", formData).then((res) => {
                const status = JSON.parse(res.status);
                if (status == "200") {
                    this.$router.push("/dashboard");
                }
            });
        },
    },
};
</script>
