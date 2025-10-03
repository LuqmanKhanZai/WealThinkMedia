@extends('layouts.app')
{{-- For Title --}}
@section('title')
    Role
@endsection
{{-- Main Content --}}
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-3">
        </div>
        <form method="POST" id="form" onsubmit="event.preventDefault(); Insert();">
            <div class="col-lg-4">
                {{ csrf_field() }}
                <div class="form-group" style="margin-top:20px;">
                    <label for="">Name</label>
                    <input type="text" class="form-control" name="name">
                </div>
            </div>
            <div class="col-lg-2">
                @can('role.create')
                <input type="button" onclick="Insert()" class="btn btn-info" value="Add Role" style="margin-top:40px;">
                @endcan
            </div>
        </form>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Roles List</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>

                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="users-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        {{-- <th>Status</th> --}}
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal inmodal fade" id="ModelShow" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                    <h4 class="modal-title">Update Role</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" id="updateForm">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="category_name">Name</label>
                            <input type="text" id="category_name" class="form-control" name="name">
                        </div>
                        <div class="form-group">
                            <label for=""></label>
                            <input type="button" onclick="Update()" class="btn btn-info btn-block" value="Update">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function() {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('role.get_data') !!}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    // {
                    //     data: 'status',
                    //     name: 'status',
                    //     render: function(data, type, full, meta) {
                    //         if (data == 1) {
                    //             return '<span class="btn badge badge-info">Active</span>';
                    //         } else {
                    //             return '<span class="btn badge badge-danger">Disabled</span>';
                    //         }
                    //     }
                    // },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            });
        });


        function Insert() {
            $.ajax({
                url: '/role/store', // Use the full URL path
                type: 'POST',
                data: $('#form').serialize(),
                success: function(response) {
                    if (response.status === 200) {
                        // Display SweetAlert success message
                        Swal.fire({
                            title: 'Success!',
                            text: response.msg,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                        $('#users-table').DataTable().ajax.reload();
                    } else {
                        // Display SweetAlert error message
                        Swal.fire({
                            title: 'Error!',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        Object.values(errors).forEach(function(error) {
                            toastr.error(error[0]);
                        });
                    } else if (xhr.status === 403) {
                        Swal.fire({
                            title: 'Warning!',
                            text: xhr.responseJSON.msg,
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred during the request.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }


        function Update() {
            var id = currentCategoryId;
            $.ajax({
                url: '/role/update/' + id, // Update the URL with the category ID
                type: 'POST', // Use the appropriate HTTP method
                data: $('#updateForm').serialize(), // Include the updated data
                success: function(response) {
                    if (response.status === 200) {
                        // Display SweetAlert success message
                        $("#ModelShow").modal('hide');
                        Swal.fire({
                            title: 'Success!',
                            text: response.msg,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                        $('#users-table').DataTable().ajax.reload();
                    } else {
                        // Display SweetAlert error message
                        Swal.fire({
                            title: 'Error!',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        Object.values(errors).forEach(function(error) {
                            toastr.error(error[0]);
                        });
                    } else if (xhr.status === 403) {
                        Swal.fire({
                            title: 'Warning!',
                            text: xhr.responseJSON.msg,
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred during the request.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }

        function Delete(id) {
            swal.fire({
                title: "Are you sure?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: '{{ url('role/delete') }}/' + id,
                        success: function(data) {
                            swal.fire("Success", "Role deleted successfully", "success");
                            $('#users-table').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;
                                Object.values(errors).forEach(function(error) {
                                    toastr.error(error[0]);
                                });
                            } else if (xhr.status === 403) {
                                Swal.fire({
                                    title: 'Warning!',
                                    text: xhr.responseJSON.msg,
                                    icon: 'warning',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'An error occurred during the request.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        }
                    });
                } else {
                    Swal.fire('Role not deleted', '', 'info');
                }
            });
        }


        function Status(id) {
            $.ajax({
                url: '{{ url('role/status') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                },
                type: "POST",
                success: function(data) {
                    var val = data.role
                    var html = '';
                    if (val == 1) {
                        html = "<span class='btn badge badge-info'>Active</span>";
                    } else {
                        html = "<span class='btn badge badge-danger'>Disabled</span>";
                    }
                    $('#users-table').DataTable().ajax.reload();
                    document.getElementById(id).innerHTML = html;

                    toastr.success("Role Status Update");
                },

                error: function(error) {
                    toastr.error('Some Thing Went Wrong', 'ERROR !')
                }
            });
        }

        var currentCategoryId;

        function ModelShow(id, name) {
            currentCategoryId = id;
            $("#category_name").val(name);
            $("#id").val(currentCategoryId);
        }
    </script>
@endsection
