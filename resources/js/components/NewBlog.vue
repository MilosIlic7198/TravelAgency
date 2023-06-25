<template>
    <form>
        <h3 class="m-2">Add new blog!</h3>
        <div v-if="error.length" class="alert alert-danger m-2" role="alert">
            {{ error }}
        </div>
        <div class="m-2">
            <label for="title" class="d-block text-lg">Title:</label>
            <input
                type="text"
                class="border border-gray-200 rounded p-2"
                name="title"
                v-model="newBlog.title"
            />
        </div>
        <div class="m-2">
            <label for="description" class="d-block text-lg"
                >Description:</label
            >
            <vue-editor v-model="newBlog.description" />
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
            v-model="newBlog.type"
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
            error: "",
            newBlog: {
                title: "",
                description: "",
                image: "",
                type: "",
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
        imageSelected(event) {
            this.newBlog.image = event.target.files[0];
        },
        createBlog() {
            if (
                !this.newBlog.title ||
                !this.newBlog.description ||
                !this.newBlog.image ||
                !this.newBlog.type
            ) {
                this.displayError("You have empty fields!");
                return;
            }
            const newBlogData = new FormData();
            newBlogData.append("title", this.newBlog.title);
            newBlogData.append("description", this.newBlog.description);
            newBlogData.append("image", this.newBlog.image);
            newBlogData.append("type", this.newBlog.type);
            axios
                .post("/api/create-blog", newBlogData)
                .then((res) => {
                    if (res.status == 200) {
                        console.log(res.data.message);
                        this.$router.push({ name: "Blogs" });
                    }
                })
                .catch((err) => {
                    this.newBlog = {
                        title: "",
                        description: "",
                        image: "",
                        type: "",
                    };
                    console.log(err.response.data.error);
                    this.displayError(err.response.data.error);
                });
        },
    },
};
</script>
