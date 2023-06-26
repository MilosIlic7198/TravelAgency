<template>
    <div class="row justify-content-sm-center">
        <div v-if="blogs.length == 0" class="col-sm-auto m-2">
            <h3 class="m-2">No blogs!</h3>
        </div>
        <div v-else class="col-sm-auto m-2">
            <h3 class="m-2">Blogs!</h3>
            <div class="m-2" v-for="blog in blogs">
                <h3>Title: {{ blog.title }}</h3>
                <img :src="'/storage/' + blog.image" alt="" class="img-thumbnail" />
                <dl>
                    <dt class="mb-2">Description:</dt>
                    <dd v-html="blog.description"></dd>
                </dl>
                <button class="btn btn-info mb-2" @click="overviewBlog(blog.id)">View blog!</button>
                <p>Publication date: {{ blog.publication_date }}</p>
                <p>Author: {{ blog.author }}</p>
                <p>Type: {{ blog.type == "Post" ? "Post" : "News" }}</p>
                <hr />
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
export default {
    data() {
        return {
            blogs: [],
        };
    },
    methods: {
        overviewBlog(id) {
            this.$router
                .push({
                    name: "Overview",
                    params: { id: id },
                })
                .catch((err) => { });
        }
    },
    mounted() {
        axios
            .get("/get-all-blogs")
            .then((res) => {
                if (res.status == 200) {
                    console.log(res.data);
                    this.blogs = res.data;
                    this.blogs = this.blogs.map((one) => {
                        one.publication_date = moment(
                            one.publication_date
                        ).format("DD.MM.YYYY HH:mm:ss a");
                        one.description = one.description.replace(/(<([^>]+)>)/ig, '');
                        one.description = one.description.length > 25 ? one.description.substr(0, 25) + "…" : one.description;
                        return one;
                    });
                }
            })
            .catch((err) => {
                console.log(err.response.data.error);
                this.blogs = [];
            });
    },
};
</script>
