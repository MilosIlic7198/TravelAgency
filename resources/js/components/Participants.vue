<template>
    <div class="m-2">
        <table class="table" id="datatableParticipants">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Birthdate</th>
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
    props: ["id"],
    data() {
        return {
            columns: [
                { data: "name" },
                { data: "surname" },
                {
                    data: "birthdate",
                    render: function (data, type, row, meta) {
                        return moment(data).format("DD.MM.YYYY");
                    },
                    orderable: false,
                    searchable: false,
                },
            ],
        };
    },
    methods: {
        initTable() {
            let table = this;
            $("#datatableParticipants").DataTable({
                stateSave: true,
                processing: true,
                serverSide: true,
                destroy: true,
                lengthMenu: [2, 5, 10],
                columns: table.columns,
                ajax: {
                    url: `/api/get-all-participants/${table.id}`,
                    type: "GET",
                },
            });
        },
    },
    mounted() {
        this.initTable();
    },
    beforeRouteLeave(to, from, next) {
        let table = $("#datatableParticipants").DataTable();
        table.destroy();
        next(true);
    },
};
</script>
