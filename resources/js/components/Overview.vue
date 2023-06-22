<template>
    <div class="m-2">
        <h3 class="border border-gray-200 rounded p-2">{{ blog.title }}</h3>
        <img :src="'/storage/' + blog.image" alt="" class="img-thumbnail" />
        <dl class="border border-gray-200 rounded p-2">
            <dt>Description:</dt>
            <dd v-html="blog.description"></dd>
        </dl>
        <p class="border border-gray-200 rounded p-2">Publication date: {{ blog.publication_date }}</p>
        <p class="border border-gray-200 rounded p-2">Author: {{ blog.author }}</p>
    </div>
</template>

<script>
import axios from "axios";
export default {
    data() {
        return {
            blog: {},
        };
    },
    mounted() {
        axios.get(`/api/get-blog/${this.$route.params.id}`).then((res) => {
            if (res.status == 200) {
                this.blog = res.data;
            }
        }).catch(err => {
            alert(err.response.data);
            this.blog = {};
        });
    },
};
</script>
