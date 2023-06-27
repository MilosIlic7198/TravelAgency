<template>
    <div>
        <button class="btn btn-success my-4" @click="register()">
            Register
        </button>
        <div v-if="error.length" class="alert alert-danger m-2" role="alert">
            {{ error }}
        </div>
        <div v-if="success.length" class="alert alert-success m-2" role="alert">
            {{ success }}
        </div>
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
        <modal name="editUserModal"></modal>
        <v-dialog />
    </div>
</template>

<script>
import "jquery/dist/jquery.min.js";
import "bootstrap/dist/css/bootstrap.min.css";
import "datatables.net-dt/js/dataTables.dataTables";
import "datatables.net-dt/css/jquery.dataTables.min.css";
import $ from "jquery";
import EditUser from "./EditUser.vue";
export default {
    components: {
        EditUser,
    },
    data() {
        return {
            success: "",
            error: "",
            columns: [
                { data: "id" },
                { data: "email" },
                { data: "role" },
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        const jsonRow = JSON.stringify(row);
                        return `<div class="dropdown">
                            <button class="btn btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions!
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item editUser" type="button" data-user=${jsonRow}>Edit</a></li>
                                    <li><a class="dropdown-item deleteUser" type="button" data-id=${data.id}>Delete</a></li>
                                </ul>
                            </div>`;
                    },
                    orderable: false,
                    searchable: false,
                },
            ],
        };
    },
    methods: {
        editUserModal(user) {
            this.$modal.show(EditUser, { user: user });
        },
        deleteUser(id) {
            axios
                .delete(`/delete-person/${id}`)
                .then((res) => {
                    if (res.status == 200) {
                        console.log(res.data.message);
                        this.displaySuccess(res.data.message);
                        this.drawTable();
                    }
                })
                .catch((err) => {
                    console.log(err.response.data.error);
                    this.displayError(err.response.data.error);
                });
        },
        confirmDialog(id) {
            this.$modal.show("dialog", {
                title: "Delete",
                text: "Are you sure you want to perform this action?",
                buttons: [
                    {
                        title: "No",
                        handler: () => {
                            this.$modal.hide("dialog");
                        },
                    },
                    {
                        title: "Yes",
                        handler: () => {
                            this.deleteUser(id);
                            this.$modal.hide("dialog");
                        },
                    },
                ],
            });
        },
        displaySuccess(message) {
            this.success = message;
            setTimeout(() => {
                this.success = "";
            }, 3000);
        },
        displayError(error) {
            this.error = error;
            setTimeout(() => {
                this.error = "";
            }, 5000);
        },
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
                pageLength: 10,
                columns: table.columns,
                ajax: {
                    url: "/people",
                    type: "GET",
                },
            });
        },
        bindButtons() {
            let table = this;
            let body = $(document);
            body.on("click", ".editUser", function (e) {
                e.preventDefault();
                const user = JSON.parse(e.target.dataset.user);
                table.editUserModal(user);
            });
            body.on("click", ".deleteUser", function (e) {
                e.preventDefault();
                table.confirmDialog(e.target.dataset.id);
            });
        },
    },
    mounted() {
        this.initTable();
        this.bindButtons();
    },
    beforeRouteLeave(to, from, next) {
        let table = $("#datatableUsers").DataTable();
        table.destroy();
        return next(true);
    },
};
</script>
