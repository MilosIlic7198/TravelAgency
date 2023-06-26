<template>
    <div>
        <div v-if="error.length" class="alert alert-danger m-2" role="alert">
            {{ error }}
        </div>
        <div v-else class="m-2">
            <h3 class="border border-gray-200 rounded p-2">{{ blog.title }}</h3>
            <img :src="'/storage/' + blog.image" alt="" class="img-thumbnail" />
            <dl class="border border-gray-200 rounded p-2 overviewScroll">
                <dd v-html="blog.description"></dd>
            </dl>
            <p class="border border-gray-200 rounded p-2">
                {{
                    blog.publication_date == null
                    ? "Not published yet!"
                    : blog.publication_date
                }}
            </p>
            <p class="border border-gray-200 rounded p-2">{{ blog.author }}</p>
        </div>
    </div>
</template>

<script>
import axios from "axios";
export default {
    data() {
        return {
            error: "",
            blog: {},
        };
    },
    methods: {
        displayError(error) {
            this.error = error;
        },
    },
    mounted() {
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
                this.displayError(err.response.data.error);
            });
    },
};
</script>

<style>
.overviewScroll {
    height: 300px;
    overflow-y: scroll;
    width: 510px;
}
</style>
