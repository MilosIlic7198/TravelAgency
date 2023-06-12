<template>
    <div v-if="blogs.length == 0">
        <p>No blogs! :D</p>
    </div>
    <div v-else>
        <div v-for="blog in blogs">
            <h3>Title: {{ blog.title }}</h3>
            <img :src="'/storage/' + blog.image" alt="" class="img-thumbnail">
            <p>Description: {{ blog.description }}</p>
            <p>Status: {{ blog.status }}</p>
            <p>Type: {{ blog.type }}</p>
            <p>Creation date: {{ blog.creation_date }}</p>
            <p>Archiving date: {{ blog.archiving_date == null ? 'not archived!' : blog.archiving_date }}</p>
            <p>Publication date: {{ blog.publication_date == null ? 'not published!' : blog.publication_date }}</p>
            <button @click="blogOverview(blog.id)" class="btn btn-primary">Overview</button>
            <button @click="editBlog(blog.id)" class="btn btn-success">Edit</button>
            <button @click="deleteBlog(blog.id)" class="btn btn-danger">Delete</button>
            <button @click="publishBlog(blog.id)" class="btn btn-info">Publish</button>
            <button @click="archiveBlog(blog.id)" class="btn btn-dark">Archive</button>
            <hr>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
export default {
    data() {
        return {
            blogs: []
        };
    },
    methods: {
        blogOverview(id) {
            console.log(id)
        },
        editBlog(id) {
            console.log(id)
        },
        deleteBlog(id) {
            console.log(id)
        },
        publishBlog(id) {
            console.log(id)
        },
        archiveBlog(id) {
            console.log(id)
        }
    },
    mounted() {
        axios.get('/api/get-blogs').then((res) => {
            const status =
                JSON.parse(res.status);
            if (status == '200') {
                this.blogs = res.data;
            }
        });;
    }
};
</script>
