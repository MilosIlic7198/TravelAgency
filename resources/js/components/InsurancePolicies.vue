<template>
    <div class="m-2">
        <table class="table" id="datatablePolicies">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Holder Name</th>
                    <th>Holder Phone Number</th>
                    <th>Date From</th>
                    <th>Date To</th>
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
import moment from "moment";
export default {
    data() {
        return {
            columns: [
                {
                    data: "type", render: function (data, type, row, meta) {
                        if (data == "Group") {
                            return `<a class="participants" href="" data-id=${row.id}>Group</a>`;
                        } else {
                            return "Individual";
                        }
                    },
                },
                { data: "description" },
                {
                    data: "holder_name", render: function (data, type, row, meta) {
                        return data + " " + row.holder_surname;
                    }
                },
                {
                    data: "holder_phone", orderable: false,
                    searchable: false,
                },
                {
                    data: "date_from", render: function (data, type, row, meta) {
                        return moment(data).format("DD.MM.YYYY");
                    }, orderable: false,
                    searchable: false,
                },
                {
                    data: "date_to", render: function (data, type, row, meta) {
                        return moment(data).format("DD.MM.YYYY");
                    }, orderable: false,
                    searchable: false,
                },
            ],
        };
    },
    methods: {
        bindButtons() {
            let table = this;
            let body = $(document);
            body.on("click", ".participants", function (e) {
                e.preventDefault();
                table.$router.push({
                    name: "Participants",
                    params: { id: e.target.dataset.id },
                }).catch((err) => { })
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
        }
    },
    mounted() {
        this.initTable();
        this.bindButtons();
    },
    beforeRouteLeave(to, from, next) {
        let table = $('#datatablePolicies').DataTable();
        table.destroy();
        next(true);
    }
};
</script>
