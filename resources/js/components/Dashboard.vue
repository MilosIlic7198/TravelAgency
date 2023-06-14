<template>
    <div v-if="blogs.length == 0">
        <p>You dont have blogs! :D</p>
    </div>
    <div v-else>
        <table class="table" id="datatable">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Type</th>
                    <th>Creation</th>
                    <th>Archiving</th>
                    <th>Publication</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="blog in blogs" :key="blog.id">
                    <td>{{ blog.title }}</td>
                    <td><img :src="'/storage/' + blog.image" alt="" class="img-thumbnail"></td>
                    <td>{{ blog.description }}</td>
                    <td>{{ blog.status }}</td>
                    <td>{{ blog.type }}</td>
                    <td>{{ blog.creation_date }}</td>
                    <td>{{ blog.archiving_date }}</td>
                    <td>{{ blog.publication_date }}</td>
                    <td>
                        <button @click="blogOverview(blog.id)" class="btn btn-primary btn-sm m-1">Overview</button>
                        <button @click="editBlog(blog.id)" class="btn btn-success btn-sm m-1">Edit</button>
                        <button @click="deleteBlog(blog.id)" class="btn btn-danger btn-sm m-1">Delete</button>
                        <button @click="publishBlog(blog.id)" class="btn btn-info btn-sm m-1">Publish</button>
                        <button @click="archiveBlog(blog.id)" class="btn btn-dark btn-sm m-1">Archive</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import "jquery/dist/jquery.min.js";
import "bootstrap/dist/css/bootstrap.min.css";
import "datatables.net-dt/js/dataTables.dataTables";
import "datatables.net-dt/css/jquery.dataTables.min.css";
import axios from "axios";
import $ from "jquery";
export default {
    data() {
        return {
            blogs: [],
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
        },
    },
    mounted() {
        axios.get('/api/get-blogs').then((res) => {
            const status =
                JSON.parse(res.status);
            if (status == '200') {
                this.blogs = res.data;
                this.blogs = this.blogs.map(blog =>
                    'id: ' + blog.id +
                    'title: ' + blog.title +
                    'description: ' + blog.description +
                    'status: ' + blog.status +
                    'type: ' + blog.type +
                    'creation_date: ' + blog.creation_date.toLocaleDateString('de-CH') +
                    'archiving_date: ' + blog.archiving_date +
                    'publication_date: ' + blog.publication_date);
                $(document).ready(function () {
                    $('#datatable').DataTable();
                });
            }
        });
    },
};
</script>
