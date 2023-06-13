<template>
    <div v-if="blogs.length == 0">
        <p>No new blogs! :D</p>
    </div>
    <div v-else>
        <div v-for="blog in blogs">
            <h3>Title: {{ blog.title }}</h3>
            <img :src="'/storage/' + blog.image" alt="" class="img-thumbnail" />
            <p>Description: {{ blog.description }}</p>
            <p>Creation date: {{ blog.creation_date }}</p>
            <hr />
        </div>
    </div>
</template>

<script>
import axios from "axios";
export default {
    data() {
        return {
            blogs: [],
        };
    },
    mounted() {
        axios.get("/api/get-all-blogs").then((res) => {
            const status = JSON.parse(res.status);
            if (status == "200") {
                this.blogs = res.data;
            }
        });
    },
};
</script>
