<template>
    <div class="m-2">
        <div v-if="error.length" class="alert alert-danger m-2" role="alert">
            {{ error }}
        </div>
        <div v-if="success.length" class="alert alert-success m-2" role="alert">
            {{ success }}
        </div>
        <table class="table" id="datatablePolicies">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Holder Name</th>
                    <th>Holder Phone Number</th>
                    <th>Date From</th>
                    <th>Date To</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <modal name="participantsModal"></modal>
        <v-dialog />
    </div>
</template>

<script>
import "jquery/dist/jquery.min.js";
import "bootstrap/dist/css/bootstrap.min.css";
import "datatables.net-dt/js/dataTables.dataTables";
import "datatables.net-dt/css/jquery.dataTables.min.css";
import $ from "jquery";
import moment from "moment";
import Participants from "./Participants.vue";
export default {
    components: {
        Participants,
    },
    data() {
        return {
            success: "",
            error: "",
            columns: [
                { data: "id" },
                {
                    data: "type",
                    render: function (data, type, row, meta) {
                        if (data == "Group") {
                            return `<button class="btn btn-primary btn-sm participants" data-id=${row.id}>Group</button>`;
                        } else {
                            return `<button class="btn btn-primary btn-sm" disabled>Individual</button>`;
                        }
                    },
                },
                {
                    data: "description",
                    render: function (data, type, row, meta) {
                        return data.length > 25
                            ? data.substr(0, 25) + "…"
                            : data;
                    },
                },
                {
                    data: "holder_name",
                    render: function (data, type, row, meta) {
                        return data + " " + row.holder_surname;
                    },
                },
                {
                    data: "holder_phone",
                    orderable: false,
                    searchable: false,
                },
                {
                    data: "date_from",
                    render: function (data, type, row, meta) {
                        return moment(data).format("DD.MM.YYYY");
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: "date_to",
                    render: function (data, type, row, meta) {
                        return moment(data).format("DD.MM.YYYY");
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return `<button class="btn btn-danger btn-sm deletePolicy" data-id=${data.id}>Delete</button>`;
                    },
                    orderable: false,
                    searchable: false,
                },
            ],
        };
    },
    methods: {
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
        deletePolicy(id) {
            axios
                .delete(`/delete-policy/${id}`)
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
        participantsModal(id) {
            this.$modal.show(Participants, { id: id });
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
                            this.deletePolicy(id);
                            this.$modal.hide("dialog");
                        },
                    },
                ],
            });
        },
        drawTable() {
            $("#datatablePolicies").DataTable().clear().draw();
        },
        bindButtons() {
            let table = this;
            let body = $(document);
            body.on("click", ".participants", function (e) {
                e.preventDefault();
                table.participantsModal(e.target.dataset.id);
            });
            body.on("click", ".deletePolicy", function (e) {
                e.preventDefault();
                table.confirmDialog(e.target.dataset.id);
            });
        },
        initTable() {
            let table = this;
            $("#datatablePolicies").DataTable({
                stateSave: true,
                processing: true,
                serverSide: true,
                destroy: true,
                lengthMenu: [2, 5, 10, 15],
                pageLength: 10,
                columns: table.columns,
                ajax: {
                    url: "/get-all-policies",
                    type: "GET",
                },
            });
        },
    },
    mounted() {
        this.initTable();
        this.bindButtons();
    },
    beforeRouteLeave(to, from, next) {
        let table = $("#datatablePolicies").DataTable();
        table.destroy();
        return next(true);
    },
};
</script>
