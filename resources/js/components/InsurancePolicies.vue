<template>
    <div class="m-2">
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
        Participants
    },
    data() {
        return {
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
                    data: "description", render: function (data, type, row, meta) {
                        return data.length > 25 ?
                            data.substr(0, 25) + '…' :
                            data;
                    }
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
        deletePolicy(id) {
            axios
                .delete(`/api/delete-policy/${id}`)
                .then((res) => {
                    if (res.status == 200) {
                        console.log(res.data.message);
                        this.drawTable();
                    }
                }).catch(err => {
                    alert(err.response.data.error);
                });
        },
        participantsModal(id) {
            this.$modal.show(Participants, { id: id });
        },
        confirmModal(id) {
            this.$modal.show('dialog', {
                title: 'Delete',
                text: 'Are you sure you want to perform this action?',
                buttons: [
                    {
                        title: 'No',
                        handler: () => {
                            this.$modal.hide('dialog')
                        }
                    },
                    {
                        title: 'Yes',
                        handler: () => {
                            this.deletePolicy(id);
                            this.$modal.hide('dialog');
                        }
                    },
                ]
            })
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
                table.confirmModal(e.target.dataset.id);
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
                columns: table.columns,
                ajax: {
                    url: "/api/get-all-policies",
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
        next(true);
    },
};
</script>
