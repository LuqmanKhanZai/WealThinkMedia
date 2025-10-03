@extends('layouts.app')
{{-- For Title --}}
@section('title')
    Item
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="ibox-title">

            <form method="POST" id="form" onsubmit="event.preventDefault(); Insert();">
                {{ csrf_field() }}
                <div class="panel panel-info">
                    <div class="panel-heading text-center">Create Product</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-custom">
                                    <tr>
                                        <th>Name</th>
                                        <th>Price </th>
                                        <th colspan="2">Description</th>
                                        <th>Type </th>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="item_name" class="form-control"></td>
                                        <td><input type="text" name="price" class="form-control"></td>
                                        <td colspan="2"><input type="text" name="description" class="form-control"></td>
                                        <td>
                                            <select name="type" class="chosen-select"
                                                aria-label="Type select">
                                                <option value="1" selected>Main Product</option>
                                                <option value="2">Bump Product</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"></td>
                                        <td >
                                            <input type="button" onclick="Insert()" class="btn btn-info btn-block"
                                                value="Add Product">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                       
                    </div>
                </div>

            </form>

        </div>

 

    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>items List</h5>
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
                                        <th>ItemName</th>
                                        <th>Price</th>
                                        <th>Description</th>
                                        <th>Type</th>
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
@endsection

@section('script')
    <script>
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('item.get_data') !!}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'item_name', name: 'item_name' },
                { data: 'price', name: 'price' },
                { data: 'description', name: 'description' },
                { 
                    data: 'type', name: 'type',
                    render: function(data) {
                        return data == 1 
                            ? '<span class="btn badge badge-info">Main Product</span>' 
                            : '<span class="btn badge badge-warning">Bump Product</span>';
                    }
                },
                { data: 'action', name: 'action' },
            ]
        });

        function Insert() {
            $.ajax({
                url: '/item/store',
                type: 'POST',
                data: $('#form').serialize(),
                success: function(response) {
                    if (response.status === 200) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.msg,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                        InputClear();
                        $('#users-table').DataTable().ajax.reload();
                    } else if(response.status === 409){
                        Swal.fire({
                            title: 'Warning!',
                            text: response.msg,
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                    }else{
                        Swal.fire({
                            title: 'Error!',
                            text: response.msgErr,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    // If the response has a status of 422, it's a validation error
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        // Display each validation error using Toastr
                        Object.values(errors).forEach(function(error) {
                            toastr.error(error[0]);
                        });
                    } else if (xhr.status === 403) { // Corrected 'else if'
                        // Warning SweetAlert
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
                        url: '{{ url('item/delete') }}/' + id,
                        success: function(data) {
                            swal.fire("Success", "item delted successfully", "success");
                            $('#users-table').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            // If the response has a status of 422, it's a validation error
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;
                                // Display each validation error using Toastr
                                Object.values(errors).forEach(function(error) {
                                    toastr.error(error[0]);
                                });
                            } else if (xhr.status === 403) { // Corrected 'else if'
                                // Warning SweetAlert
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
                    Swal.fire('item not deleted', '', 'info')
                }
            });
        }

        function Status(id) {
            $.ajax({
                url: '{{ url('item/status') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                },
                type: "POST",
                success: function(data) {
                    var val = data.status
                    var html = '';
                    if (val == 1) {
                        html = "<span class='btn badge badge-info'>Active</span>";
                    } else {
                        html = "<span class='btn badge badge-danger'>Disabled</span>";
                    }
                    $('#users-table').DataTable().ajax.reload();
                    document.getElementById(id).innerHTML = html;

                    toastr.success("item Status Update");
                },

                error: function(error) {
                    toastr.error('Some Thing Went Wrong', 'ERROR !')
                }
            });
        }

        function InputClear() {
            $('.cashPrice').val('');
            $('.installmentPrice').val('');
            $('.advance').val('');
            $('.duration').val('');
            $('.installmentMonth').val('');
            $('.proFee').val('');
            $('.incentive').val('');
            $('.incentive').val('');
            $('.branchId').val('0').trigger('chosen:updated');
            $('.itemId').val('0').trigger('chosen:updated');
        }
    </script>
@endsection

