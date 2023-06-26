<template>
    <div>
        <div v-if="error.length" class="alert alert-danger m-2" role="alert">
            {{ error }}
        </div>
        <form v-else>
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
                <input type="file" class="border border-gray-200 rounded p-2" name="image"
                    @change="imageSelected($event)" />
            </div>
            <div class="m-2">
                <label for="description" class="d-block text-lg">Description:</label>
                <vue-editor id="editBlogEditor" v-model="blog.description" />
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
    </div>
</template>

<script>
export default {
    data() {
        return {
            error: "",
            blog: {},
            newImage: null,
            imageUrl: null,
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
            this.imageUrl = URL.createObjectURL(event.target.files[0]);
            this.newImage = event.target.files[0];
        },
        getBlog() {
            axios
                .get(`/get-blog/${this.$route.params.id}`)
                .then((res) => {
                    if (res.status == 200) {
                        console.log(res.data[0]);
                        this.blog = res.data[0];
                    }
                })
                .catch((err) => {
                    this.blog = {};
                    console.log(err.response.data.error);
                    displayError(err.response.data.error);
                });
        },
        save() {
            if (
                !this.blog.title ||
                !this.blog.description ||
                !this.blog.image ||
                !this.blog.type
            ) {
                this.displayError("You have empty fields!");
                return;
            }
            const editedBlogData = new FormData();
            editedBlogData.append("title", this.blog.title);
            editedBlogData.append("description", this.blog.description);
            editedBlogData.append("image", this.blog.image);
            editedBlogData.append("type", this.blog.type);
            editedBlogData.append("imageFile", this.newImage);
            axios
                .post(`/edit-blog/${this.$route.params.id}`, editedBlogData)
                .then((res) => {
                    if (res.status == 200) {
                        console.log(res.data.message);
                        this.$router.push({ name: "Blogs" });
                    }
                })
                .catch((err) => {
                    console.log(err.response.data.error);
                    this.displayError(err.response.data.error);
                });
        },
        cancel() {
            this.$router.push({ name: "Blogs" });
        },
    },
    mounted() {
        this.getBlog();
    },
};
</script>

<style>
#editBlogEditor {
    height: 300px;
    width: 840px;
}
</style>
