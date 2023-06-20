<template>
    <div>
        <button class="btn btn-success my-4" @click="register()">Register</button>
        <table class="table" id="datatableUsers">
            <thead>
                <tr>
                    <th>ID</th>
                </tr>
            </thead>
            <tbody></tbody>
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
            columns: [
                { data: "id" },
            ],
        };
    },
    methods: {
        register() {
            this.$router.push("/register");
        },
        drawTable() {
            $("#datatableUsers").DataTable().clear().draw();
        },
        initTable() {
            let table = this;
            $("#datatableUsers").DataTable({
                stateSave: true,
                processing: true,
                serverSide: true,
                destroy: true,
                lengthMenu: [2, 5, 10, 15, 20],
                columns: table.columns,
                ajax: {
                    url: "",
                    type: "GET",
                },
            });
        },
        bindButtons() {
            let table = this;
            let body = $(document);
        },
    },
    mounted() {
        this.initTable();
        this.bindButtons();
    },
    beforeRouteLeave(to, from, next) {
        let table = $('#datatableUsers').DataTable();
        table.destroy();
        next(true);
    }
};
</script>
