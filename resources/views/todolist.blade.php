<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Todolist</title>

    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
        crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.7/dist/axios.min.js"></script>

    <style>
        .todolist-wrapper {
            border: 1px solid #ccc;
            min-height: 100px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div id="app">

            <!-- Modal -->
            <div class="modal fade" id="modal-form" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Todolist Form</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="">Content</label>
                                <textarea v-model="content" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" @click="saveTodoList" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <div class="text-right mb-3">
                        <a href="javascript:void(0)" @click="openForm" class="btn btn-primary">Tambah</a>
                    </div>
                    <div class="text-center mb-3">
                        <input type="text" v-model="search" @change="findData" class="form-control" placeholder="Search text...">
                    </div>
                    <div class="todolist-wrapper">
                        <table class="table table-striped table-bordered">
                            <tbody>
                                <tr v-for="item in data_list">
                                    <td>@{{ item.content }} <a href="javascript:void(0)" @click="editData(item.id)" class="btn-sm btn-warning">Edit</a> <a href="javascript:void(0)" @click="deleteData(item.id)" class="btn-sm btn-danger">Delete</a></td>
                                </tr>
                                <tr v-if="!data_list.length">
                                    <td>Empty Data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-3"></div>
            </div>
        </div>
    </div>

    <script>
        var app = new Vue({
            el: '#app',
            mounted: function() {
                this.getDataList();
            },
            data: {
                data_list: [],
                content: "",
                id: "",
                search:"",
            },
            methods: {
                openForm: function() {
                    $('#modal-form').modal('show');
                },
                saveTodoList: function() {
                    var form_data = new FormData();
                    form_data.append('content', this.content);

                    if (this.id) {
                        axios.post("{{ url("api/todolist/update") }}/" + this.id, form_data)
                            .then(response => {
                                var item = response.data;
                            alert(item.message);
                            this.getDataList();
                        }).catch(
                            error => {
                                alert("Error : " + error);
                            }
                        ).finally(
                            () => {
                                $('#modal-form').modal('hide');
                            }
                        );
                    } else {
                        axios.post("{{ url("api/todolist/create") }}", form_data)
                            .then(response => {
                                var item = response.data;
                                alert(item.message);
                                this.getDataList();
                            }).catch(
                            error => {
                                alert("Error : " + error);
                            }
                        ).finally(
                            () => {
                                $('#modal-form').modal('hide');
                            }
                        );
                    }
                },
                getDataList: function() {
                    axios.get("{{ url("api/todolist/list") }}?search=" + this.search)
                        .then(response => {
                            this.data_list = response.data.data;
                    }).catch(
                        error => {
                            alert("Error : " + error);
                        }
                    );
                },
                deleteData: function (id) {
                    if (confirm("Are you sure?")) {
                        axios.post("{{ url("api/todolist/delete") }}/" + id)
                            .then(response => {
                                var item = response.data;
                                alert(item.message);
                                this.getDataList();
                            }).catch(
                            error => {
                                alert("Error : " + error);
                            }
                        );
                    }
                },
                editData: function (id) {
                    this.id = id;
                    axios.get("{{ url("api/todolist/read") }}/" + this.id)
                        .then(response => {
                            var item = response.data.data;
                            this.content = item.content;
                            this.openForm();
                    }).catch(
                        error => {
                            alert("Error : " + error);
                        }
                    );
                },
                findData: function () {
                    this.getDataList();
                },
            }
        }, );
    </script>
</body>

</html>
