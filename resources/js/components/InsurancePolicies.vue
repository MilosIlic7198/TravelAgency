<template>
    <div v-if="policies.length == 0">
        <p>No policies! :D</p>
    </div>
    <div v-else>
        <div
            class="border border-gray-200 rounded p-2 m-2"
            v-for="policy in policies"
        >
            <p v-if="policy.type == 'Group'">
                <span
                    ><b>Type:</b>
                    <a href="#" @click.prevent="participants(policy.id)">{{
                        policy.type
                    }}</a></span
                >
            </p>
            <p v-else><b>Type:</b> {{ policy.type }}</p>
            <p><b>Description:</b> {{ policy.description }}</p>
            <p>
                <b>Holder Name:</b> {{ policy.holder_name }}
                {{ policy.holder_surname }}
            </p>
            <p><b>Holder Phone Number:</b> {{ policy.holder_phone }}</p>
            <p><b>Date From:</b> {{ policy.date_from }}</p>
            <p><b>Date To:</b> {{ policy.date_to }}</p>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
export default {
    data() {
        return {
            policies: [],
        };
    },
    methods: {
        participants(id) {
            this.$router.push({
                name: "Participants",
                params: { id: id },
            });
        },
    },
    mounted() {
        axios.get("/api/get-all-policies").then((res) => {
            const status = JSON.parse(res.status);
            if (status == "200") {
                this.policies = res.data.map((policy) => {
                    policy.date_from = moment(policy.date_from).format(
                        "DD.MM.YYYY"
                    );
                    policy.date_to = moment(policy.date_from).format(
                        "DD.MM.YYYY"
                    );
                    return policy;
                });
            }
        });
    },
};
</script>
