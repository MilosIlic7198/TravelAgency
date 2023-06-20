<template>
    <p>Hello!</p>
</template>

<script>
import axios from "axios";
export default {
    data() {
        return {
            participants: [],
        };
    },
    mounted() {
        axios
            .get(`/api/get-all-participants/${this.$route.params.id}`)
            .then((res) => {
                const status = JSON.parse(res.status);
                if (status == "200") {
                    this.participants = res.data;
                }
            });
    },
};
</script>
