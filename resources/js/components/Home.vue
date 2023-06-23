<template>
    <div>
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
                        <dt>Description:</dt>
                        <dd v-html="blog.description"></dd>
                    </dl>
                    <p>Publication date: {{ blog.publication_date }}</p>
                    <p>Author: {{ blog.author }}</p>
                    <p>Type: {{ blog.type == "Post" ? "Post" : "News" }}</p>
                    <hr />
                </div>
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
    mounted() {
        axios.get("/api/get-all-blogs").then((res) => {
            if (res.status == 200) {
                console.log(res.data);
                this.blogs = res.data;
                this.blogs = this.blogs.map((one) => {
                    one.publication_date = moment(one.publication_date).format("DD.MM.YYYY HH:mm:ss a");
                    return one;
                });
            }
        }).catch(err => {
            alert(err.response.data.error);
            this.blogs = [];
        });
    },
};
</script>
