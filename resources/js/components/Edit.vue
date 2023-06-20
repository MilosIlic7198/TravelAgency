<template>
    <form>
        <h3 class="m-2">Edit!</h3>
        <div class="m-2">
            <label for="title" class="d-block text-lg">Title:</label>
            <input type="text" class="border border-gray-200 rounded p-2" name="title" v-model="blog.title" />
        </div>
        <div class="m-2">
            <label for="image" class="d-block text-lg"> Image: </label>
            <img :src="imageUrl == null ? /storage/ + blog.image : imageUrl" class="img-thumbnail" />
        </div>
        <div class="m-2">
            <input type="file" class="border border-gray-200 rounded p-2" name="image" @change="imageSelected($event)" />
        </div>
        <div class="m-2">
            <label for="description" class="d-block text-lg">Description:</label>
            <vue-editor v-model="blog.description" />
        </div>
        <div class="m-2">
            <label for="type" class="d-block text-lg">Type:</label>
            <select class="border border-gray-200 rounded p-2" name="type" v-model="blog.type">
                <option value="News">News</option>
                <option value="Post">Post</option>
            </select>
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
            blog: {},
            newImage: null,
            imageUrl: null,
        };
    },
    methods: {
        imageSelected(event) {
            this.imageUrl = URL.createObjectURL(event.target.files[0]);
            this.newImage = event.target.files[0];
        },
        getBlog() {
            axios.get(`/api/get-blog/${this.$route.params.id}`).then((res) => {
                const status = JSON.parse(res.status);
                if (status == "200") {
                    this.blog = res.data;
                }
            });
        },
        save() {
            const editedData = new FormData();
            editedData.append("title", this.blog.title);
            editedData.append("description", this.blog.description);
            editedData.append("image", this.blog.image);
            editedData.append("type", this.blog.type);
            editedData.append("imageFile", this.newImage);
            axios
                .post(`/api/edit-blog/${this.$route.params.id}`, editedData)
                .then((res) => {
                    const status = JSON.parse(res.status);
                    if (status == "200") {
                        this.$router.push("/blogs");
                    }
                });
        },
        cancel() {
            this.$router.push("/blogs");
        },
    },
    mounted() {
        this.getBlog();
    },
};
</script>
