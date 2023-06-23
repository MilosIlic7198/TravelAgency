<template>
    <div>
        <button class="btn btn-success my-4" @click="register()">Register</button>
        <table class="table" id="datatableUsers">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
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
import $ from "jquery";
export default {
    data() {
        return {
            columns: [
                { data: "id" },
                { data: "email" },
                { data: "role" },
                {
                    data: null, render: function (data, type, row, meta) {
                        return `<div class="dropdown">
                            <button class="btn btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions!
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item editUser" href="" data-id=${data.id}>Edit</a></li>
                                    <li><a class="dropdown-item deleteUser" href="" data-id=${data.id}>Delete</a></li>
                                </ul>
                            </div>`;
                    }, orderable: false, searchable: false,
                },
            ],
        };
    },
    methods: {
        register() {
            this.$router.push({ name: "Register" });
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
                    url: "/api/people",
                    type: "GET",
                },
            });
        },
        bindButtons() {
            let table = this;
            let body = $(document);
            body.on("click", ".editUser", function (e) {
                e.preventDefault();
                table.$router.push({
                    name: "EditUser",
                    params: { id: e.target.dataset.id },
                }).catch((err) => { });
            });
            body.on("click", ".deleteUser", function (e) {
                e.preventDefault();
                axios
                    .delete(`/api/delete-person/${e.target.dataset.id}`)
                    .then((res) => {
                        if (res.status == 200) {
                            console.log(res.data.message);
                            table.drawTable();
                        }
                    }).catch(err => {
                        alert(err.response.data.error);
                    });
            });
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
