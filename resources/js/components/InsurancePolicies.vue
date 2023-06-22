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
        <Modal v-if="showModal">
            <h3 slot="header">Are you sure you want to delete this police?</h3>
            <button
                slot="footer"
                class="btn btn-success mx-1"
                @click="deletePolice"
            >
                Yes
            </button>
            <button
                slot="footer"
                class="btn btn-success mx-1"
                @click="showModal = false"
            >
                No
            </button>
        </Modal>
    </div>
</template>

<script>
import "jquery/dist/jquery.min.js";
import "bootstrap/dist/css/bootstrap.min.css";
import "datatables.net-dt/js/dataTables.dataTables";
import "datatables.net-dt/css/jquery.dataTables.min.css";
import $ from "jquery";
import moment from "moment";
import Modal from "./Modal.vue";

export default {
    components: {
        Modal,
    },
    data() {
        return {
            showModal: false,
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
                { data: "description" },
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
                        return `<button class="btn btn-danger btn-sm" id="deletePolicy" data-id=${data.id}>Delete</button>`;
                    },
                    orderable: false,
                    searchable: false,
                },
            ],
        };
    },
    methods: {
        deletePolice() {
            console.log();
            this.showModal = false;
        },
        bindButtons() {
            let table = this;
            let body = $(document);
            body.on("click", ".participants", function (e) {
                table.$router
                    .push({
                        name: "Participants",
                        params: { id: e.target.dataset.id },
                    })
                    .catch((err) => {});
            });
            body.on("click", "#deletePolicy", function (e) {
                e.preventDefault();
                table.showModal = true;
                /* const response = confirm("Are you sure you want to do that?");
                if (response) {
                    axios
                        .post(`/api/delete-policy/${e.target.dataset.id}`)
                        .then((res) => {
                            if (res.status == 200 && res.data == "Success") {
                                table.drawTable();
                            } else if (res.data == "Records not found!") {
                                alert(res.data);
                            } else if (res.data == "Bad query!") {
                                alert(res.data);
                            } else if (res.data == "General exception!") {
                                alert(res.data);
                            }
                        });
                } */
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
