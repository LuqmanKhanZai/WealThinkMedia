@extends('layouts.app')
{{-- For Title --}}
@section('title')
    User
@endsection
{{-- Main Content --}}
@section('content')
    <div class="row wrapper white-bg page-heading">
        <div class="col-lg-10">
            <h2>User List</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ url('home') }}">Home</a>
                </li>
                <li>
                    <a>User</a>
                </li>
                <li class="active">
                    <strong>User List</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">
            <a href="{{ route('user.create') }}" class="btn btn-info btn-outline" style="margin-top:40px;">Add
                User</a>
        </div>
    </div>

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="ibox-title">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Users List</h5>
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
                                <table class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            {{--  <th>Status</th>  --}}
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr id="customer_row_{{ $user->id }}">
                                                <td>{{ $user->id }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                

                                               <td>
                                                    @foreach ($user->roles as $role)
                                                        <span class="badge badge-primary">{{ $role->name }}</span>
                                                    @endforeach
                                                </td>



                                                <td>
                                                    <div class="btn-group btn-group-xs">
                                                        {{--  <a onclick="Status({{ $user->id }})" class="btn btn-warning"><i
                                                                class="fa fa-ban"></i></a>  --}}
                                                        <a href="{{ route('user.edit', $user->id) }}"
                                                            class="btn btn-success"><i class="fa fa-edit"></i></a>
                                                        @can('user.delete')
                                                        <a onclick="Delete({{ $user->id }})" class="btn btn-danger"><i
                                                                class="fa fa-trash"></i></a>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function Status(id) {
            $.ajax({
                url: '{{ url('user/status') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                },
                type: "POST",
                success: function(data) {
                    var val = data.customer_status;
                    var html = '';
                    if (val == 1) {
                        html = "<span class='btn badge badge-info'>Active</span>";
                    } else {
                        html = "<span class='btn badge badge-danger'>Disabled</span>";
                    }

                    toastr.success("Customer Status Updated");
                    // Update the status cell with the new HTML
                    $('#customer_row_' + id + ' td:nth-child(11)').html(html); // Update the status cell
                },

                error: function(error) {
                    toastr.error('Something Went Wrong', 'ERROR !')
                }
            });
        }

        function Delete(id) {
            swal.fire({
                title: "Are you sure?",
                text: "You will not be able to recover this customer!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: '{{ url('user/delete') }}/' + id,
                        data: {
                            _token: "{{ csrf_token() }}" // Laravel requires CSRF for delete
                        },
                        success: function(data) {
                            // Remove the deleted customer row from the table
                            $('#customer_row_' + id).remove();
                            swal.fire("Success", "Customer deleted successfully", "success");
                            toastr.success("Customer deleted successfully");
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
                    Swal.fire('Customer not deleted', '', 'info')
                }
            });
        }
    </script>
@endsection
