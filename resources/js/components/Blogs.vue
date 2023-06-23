<template>
    <div>
        <button class="btn btn-success my-4" @click.prevent="newBlog()">
            New Blog
        </button>
        <table class="table" id="datatableBlog">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Type</th>
                    <th>Creation</th>
                    <th>Archiving</th>
                    <th>Publication</th>
                    <th>Author</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
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
export default {
    data() {
        return {
            columns: [
                { data: "id" },
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
                {
                    data: "description", render: function (data, type, row, meta) {
                        return data.length > 25 ?
                            data.substr(0, 25) + '…' :
                            data;
                    }
                },
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
                { data: "author" },
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return `<div class="dropdown">
                            <button class="btn btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions!
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item overviewBlog" href="" data-id=${data.id}>Overview</a></li>
                                <li><a class="dropdown-item editBlog" href="" data-id=${data.id}>Edit</a></li>
                                <li><a class="dropdown-item deleteBlog" href="" data-id=${data.id}>Delete</a></li>
                                <li><a class="dropdown-item publishBlog" href="" data-id=${data.id}>Publish</a></li>
                                <li><a class="dropdown-item archiveBlog" href="" data-id=${data.id}>Archive</a></li>
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
        deleteBlog(id) {
            axios
                .delete(`/api/delete-blog/${id}`)
                .then((res) => {
                    if (res.status == 200) {
                        console.log(res.data.message);
                        this.drawTable();
                    }
                }).catch(err => {
                    alert(err.response.data.error);
                });
        },
        confirmModal(id) {
            this.$modal.show('dialog', {
                title: "Delete",
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
                            this.deleteBlog(id);
                            this.$modal.hide('dialog');
                        }
                    },
                ]
            })
        },
        newBlog() {
            this.$router.push({ name: "NewBlog" });
        },
        drawTable() {
            $("#datatableBlog").DataTable().clear().draw();
        },
        initTable() {
            let table = this;
            $("#datatableBlog").DataTable({
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
            body.on("click", ".overviewBlog", function (e) {
                e.preventDefault();
                table.$router
                    .push({
                        name: "Overview",
                        params: { id: e.target.dataset.id },
                    })
                    .catch((err) => { });
            });
            body.on("click", ".editBlog", function (e) {
                e.preventDefault();
                table.$router
                    .push({
                        name: "EditBlog",
                        params: { id: e.target.dataset.id },
                    })
                    .catch((err) => { });
            });
            body.on("click", ".deleteBlog", function (e) {
                e.preventDefault();
                table.confirmModal(e.target.dataset.id);
            });
            body.on("click", ".publishBlog", function (e) {
                e.preventDefault();
                axios
                    .put(`/api/publish-blog/${e.target.dataset.id}`)
                    .then((res) => {
                        if (res.status == 200) {
                            console.log(res.data.message);
                            table.drawTable();
                        }
                    })
                    .catch(err => {
                        alert(err.response.data.error);
                    });
            });
            body.on("click", ".archiveBlog", function (e) {
                e.preventDefault();
                axios
                    .put(`/api/archive-blog/${e.target.dataset.id}`)
                    .then((res) => {
                        if (res.status == 200) {
                            console.log(res.data.message);
                            table.drawTable();
                        }
                    })
                    .catch(err => {
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
        let table = $("#datatableBlog").DataTable();
        table.destroy();
        next(true);
    },
};
</script>
