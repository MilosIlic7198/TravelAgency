<template>
    <table class="table" id="datatable">
        <thead>
            <tr>
                <th>Title</th>
                <th>Image</th>
                <th>Description</th>
                <th>Status</th>
                <th>Type</th>
                <th>Creation</th>
                <th>Archiving</th>
                <th>Publication</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</template>

<script>
import "jquery/dist/jquery.min.js";
import "bootstrap/dist/css/bootstrap.min.css";
import "datatables.net-dt/js/dataTables.dataTables";
import "datatables.net-dt/css/jquery.dataTables.min.css";
import axios from "axios";
import $ from "jquery";
import moment from "moment";
export default {
    data() {
        return {
            columns: [
                { data: "title" },
                {
                    data: "image",
                    render: function (data, type, row, meta) {
                        return (
                            '<img src="/storage/' +
                            data +
                            '" class="img-thumbnail"">'
                        );
                    },
                    orderable: false,
                    searchable: false,
                },
                { data: "description" },
                { data: "status" },
                { data: "type" },
                {
                    data: "creation_date",
                    render: function (data, type, row, meta) {
                        return moment(data).format("DD.MM.YYYY HH:mm:ss a");
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: "archiving_date",
                    render: function (data, type, row, meta) {
                        if (data == null) {
                            return "Not archived yet!";
                        }
                        return moment(data).format("DD.MM.YYYY HH:mm:ss a");
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: "publication_date",
                    render: function (data, type, row, meta) {
                        if (data == null) {
                            return "Not published yet!";
                        }
                        return moment(data).format("DD.MM.YYYY HH:mm:ss a");
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return `<div class="dropdown">
                            <button class="btn btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions!
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" id="overview" href="#" data-id=${data.id}>Overview</a></li>
                                <li><a class="dropdown-item" id="edit" href="#" data-id=${data.id}>Edit</a></li>
                                <li><a class="dropdown-item" id="delete" href="#" data-id=${data.id}>Delete</a></li>
                                <li><a class="dropdown-item" id="publish" href="#" data-id=${data.id}>Publish</a></li>
                                <li><a class="dropdown-item" id="archive" href="#" data-id=${data.id}>Archive</a></li>
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
        drawTable() {
            $("#datatable").DataTable().clear().draw();
        },
        initTable() {
            let table = this;
            $("#datatable").DataTable({
                stateSave: true,
                processing: true,
                serverSide: true,
                destroy: true,
                lengthMenu: [2, 5, 10, 15, 20],
                columns: table.columns,
                ajax: {
                    url: "/api/get-blogs",
                    type: "GET",
                },
            });
        },
        bindButtons() {
            let table = this;
            let body = $(document);
            body.on("click", "#overview", function (e) {
                table.$router.push({
                    name: "Overview",
                    params: { id: e.target.dataset.id },
                });
            });
            body.on("click", "#edit", function (e) {
                table.$router.push({
                    name: "Edit",
                    params: { id: e.target.dataset.id },
                });
            });
            body.on("click", "#delete", function (e) {
                axios
                    .post(`/api/delete-blog/${e.target.dataset.id}`)
                    .then((res) => {
                        if (res.status == 200 && res.data == "Success") {
                            table.drawTable();
                        } else if (
                            res.status == 500 &&
                            res.data == "Records not found!"
                        ) {
                            alert("Records not found!");
                        } else if (
                            res.status == 500 &&
                            res.data == "Bad query!"
                        ) {
                            alert("Bad query!");
                        } else if (
                            res.status == 500 &&
                            res.data == "General exception!"
                        ) {
                            alert("General exception!");
                        }
                    });
            });
            body.on("click", "#publish", function (e) {
                axios
                    .post(`/api/publish-blog/${e.target.dataset.id}`)
                    .then((res) => {
                        const status = JSON.parse(res.status);
                        if (status == "200") {
                            table.drawTable();
                        }
                    });
            });
            body.on("click", "#archive", function (e) {
                axios
                    .post(`/api/archive-blog/${e.target.dataset.id}`)
                    .then((res) => {
                        const status = JSON.parse(res.status);
                        if (status == "200") {
                            table.drawTable();
                        }
                    });
            });
        },
    },
    mounted() {
        this.initTable();
        this.bindButtons();
    },
};
</script>
