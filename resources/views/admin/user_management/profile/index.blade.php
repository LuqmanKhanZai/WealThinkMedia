@extends('layouts.app')

{{-- For Title --}}
@section('title')
    Profile
@endsection

{{-- Main Content --}}
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="ibox float-e-margins">
            <div class="ibox-title">

                <form method="POST" id="form">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 ">

                            <div class="panel panel-success">
                                <div class="panel-heading text-center">
                                    <h4>Profile Update Form</h4>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-custom">

                                        <tr>
                                            <th>Old Password</th>
                                            <td colspan="3"><input type="text" name="old_password"
                                                    class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Password</th>
                                            <td colspan="3"><input type="text" name="password" class="form-control">
                                            </td>
                                        </tr>


                                        <tr>
                                            <th>Confirm Password </th>
                                            <td colspan="3"><input type="text" name="password_confirmation"
                                                    class="form-control">
                                            </td>
                                        </tr>



                                        <tr>
                                            <td>
                                                <input type="button" onclick="Insert()" class="btn btn-info btn-block"
                                                    value="Save">
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


    </div>
@endsection

@section('script')
    <script>
        function Insert() {
            $.ajax({
                url: '/profile/store', // Use the full URL path
                type: 'POST',
                data: $('#form').serialize(),
                success: function(response) {

                    console.log(response);

                    if (response.status === 400) {
                        Swal.fire({
                            title: 'Error!',
                            text: response.msg,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                    if (response.status === 200) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.msg,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location.href = '/home';
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.msg,
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
    </script>
@endsection
