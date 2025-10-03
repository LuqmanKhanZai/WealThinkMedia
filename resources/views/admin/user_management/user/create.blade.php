@extends('layouts.app')

{{-- For Title --}}
@section('title')
    User Create
@endsection

{{-- Main Content --}}
@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <form method="POST" id="form">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-success">
                            {{-- <div class="panel-heading text-center">
                                <h4>Fund Transfer</h4>
                            </div> --}}
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-custom">
                                            <tr>
                                                <th>Name</th>
                                                <td colspan="3"><input type="text" name="name"
                                                        class="form-control"></td>
                                            </tr>
                                            <tr>
                                                <th>UserName</th>
                                                <td><input type="text" name="email" class="form-control"></td>
                                                <th>Password</th>
                                                <td><input type="text" name="password" class="form-control"></td>
                                            </tr>
                                            <tr>
                                                <th>Role</th>
                                                <td colspan="3">
                                                    <select class="chosen-select" name="role_id">
                                                        <option disabled selected>--Select Role--</option>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>FromTime</th>
                                                <td><input type="time" name="from_time" class="form-control"></td>
                                                <th>ToTime</th>
                                                <td><input type="time" name="to_time" class="form-control"></td>
                                            </tr>
                                        </table>
                                        <div class="form-group">
                                            <div class="row">
                                                <input type="button" onclick="Insert()" class="btn btn-info btn-block" value="Save">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="panel panel-success">
                                            <div class="panel-heading">Allow Days</div>
                                            <div class="panel-body">
                                                <div class="i-checks"><label> <input name="monday_login"
                                                            type="checkbox" value="true"> &nbsp; &nbsp; &nbsp;
                                                        Monday <i></i> </label></div>
                                                <div class="i-checks"><label> <input name="tuesday_login"
                                                            type="checkbox" value="true"> &nbsp; &nbsp; &nbsp;
                                                        Tuesday <i></i> </label></div>
                                                <div class="i-checks"><label> <input name="wednesday_login"
                                                            type="checkbox" value="true"> &nbsp; &nbsp; &nbsp;
                                                        Wednesday <i></i> </label></div>
                                                <div class="i-checks"><label> <input name="thursday_login"
                                                            type="checkbox" value="true"> &nbsp; &nbsp; &nbsp;
                                                        Thursday <i></i> </label></div>
                                                <div class="i-checks"><label> <input name="friday_login"
                                                            type="checkbox" value="true"> &nbsp; &nbsp; &nbsp;
                                                        Friday <i></i> </label></div>
                                                <div class="i-checks"><label> <input name="saturday_login"
                                                            type="checkbox" value="true"> &nbsp; &nbsp; &nbsp;
                                                        Saturday <i></i> </label></div>
                                                <div class="i-checks"><label> <input name="sunday_login"
                                                            type="checkbox" value="true"> &nbsp; &nbsp; &nbsp;
                                                        Sunday <i></i> </label></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>
    <script>
        function Insert() {
            $.ajax({
                url: '/user/store', // Use the full URL path
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
                        }).then(function() {
                            window.location.href = '/user/index';
                        });
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
    </script>
@endsection
