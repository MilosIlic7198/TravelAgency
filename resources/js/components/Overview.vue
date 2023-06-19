<template>
    <div class="m-2">
        <h3 class="border border-gray-200 rounded p-2">{{ blog.title }}</h3>
        <img :src="'/storage/' + blog.image" alt="" class="img-thumbnail" />
        <p class="border border-gray-200 rounded p-2">
            <span v-html="blog.description"></span>
        </p>
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
            const status = JSON.parse(res.status);
            if (status == "200") {
                this.blog = res.data;
            }
        });
    },
};
</script>
