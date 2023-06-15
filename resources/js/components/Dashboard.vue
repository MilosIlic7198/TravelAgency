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
        <tbody>
        </tbody>
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
                { "data": "title" },
                {
                    "data": "image",
                    "render": function (data, type, row, meta) {
                        return '<img src="/storage/' + data + '" class="img-thumbnail"">';
                    },
                },
                { "data": "description" },
                { "data": "status" },
                { "data": "type" },
                {
                    "data": "creation_date",
                    "render": function (data, type, row, meta) {
                        return moment(data).format("DD.MM.YYYY HH:mm:ss a");
                    }
                },
                { "data": "archiving_date" },
                { "data": "publication_date" },
                {
                    "data": null,
                    "render": function (data, type, row, meta) {
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
                    }
                },
            ],
            columnDefs: [
                { "targets": 0, "orderable": true },
                { "targets": 1, "orderable": false, "searchable": false },
                { "targets": 2, "orderable": true },
                { "targets": 3, "orderable": true },
                { "targets": 4, "orderable": true },
                { "targets": 5, "orderable": true },
                { "targets": 6, "orderable": true },
                { "targets": 7, "orderable": true },
            ]
        };
    },
    methods: {
        initTable() {
            let table = this
            $('#datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "destroy": true,
                "columns": table.columns,
                "columnsDefs": table.columnDefs,
                "ajax": {
                    url: '/api/get-blogs',
                    type: 'GET',
                },
            });
        },
        bindButtons() {
            let body = $("#datatable tbody");
            body.on('click', '#overview', function (e) {
                console.log(e.target.dataset.id);
            })
            body.on('click', '#edit', function (e) {
                console.log(e.target.dataset.id);
            })
            body.on('click', '#delete', function (e) {
                console.log(e.target.dataset.id);
            })
            body.on('click', '#publish', function (e) {
                console.log(e.target.dataset.id);
            })
            body.on('click', '#archive', function (e) {
                console.log(e.target.dataset.id);
            })
        }
    },
    mounted() {
        this.initTable();
        this.bindButtons();
    },
};
</script>
