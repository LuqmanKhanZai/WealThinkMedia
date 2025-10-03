@extends('layouts.app')
{{-- For Title --}}
@section('title')
    User Privialages
@endsection
<style>
    .table-customBorder td {
        padding: 1px !important;
    }

    .table-customBorder th {
        text-align: center !important;
        font-size: 12px !important;
        vertical-align: middle !important;
    }

    .table-customBorder span {
        font-size: 13px !important;
    }

    .for-text {
        font-size: 12px !important;
    }

    .table-customBorder td {
        text-align: center !important;
        padding: 1px !important;
    }
</style>
{{-- Main Content --}}
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <br>
        <form method="POST">
            {{-- <form method="POST" action="{{route('user_privilages.permission_list')}}"> --}}
            {{ csrf_field() }}
            <div class="col-md-3">

            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="">User</label>
                    <select class="chosen-select userId" name="user_id">
                        <option selected>--Select User--</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="">Module</label>
                    <select class="chosen-select moduleId" name="module_id">
                        <option selected value="0" disabled>--Select Module--</option>
                        @foreach ($modules as $module)
                            <option value="{{ $module->id }}">{{ $module->module_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            {{-- <div class="col-lg-2">
                <input type="submit" class="btn btn-info" value="Go" style="margin-top:20px;">
            </div> --}}
        </form>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">

            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <table class="table table-bordered table-customBorder">
                            <thead style="color:rgb(51, 49, 49)">
                                <tr>
                                    <th>MenuName</th>
                                    <th>canView</th>
                                    <th>canAdd</th>
                                    <th>canEdit</th>
                                    <th>canDelete</th>
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
@endsection

@section('script')
    <script>
        $('.moduleId').change(function() {
            GetPermission();
        });

        function GetPermission() {
            var userId = $('.userId').val();
            var moduleId = $('.moduleId').val();
            $.ajax({
                url: '{{ route('user_privilages.get_permission') }}',
                type: 'GET',
                data: {
                    user_id: userId,
                    module_id: moduleId
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    var submodules = response.submodule;
                    var assignPermission = response.RoleHasPermission; // This is now an array of permission IDs
                    // Clear previous table data
                    $('.table-customBorder tbody').empty();
                    $('#assignPermissionButtonRow').remove();
                    // Iterate through each submodule
                    $.each(submodules, function(index, submodule) {
                        var moduleName = submodule.submodule_name;
                        var permissions = submodule.permissions;
                        // Generate table row for each submodule
                        var row = '<tr>' +
                            '<td>' + moduleName + '</td>';
                        // Loop through permissions and generate table cells
                        $.each(permissions, function(index, permission) {
                            var checked = assignPermission.includes(permission
                                .id); // Check if permission ID is in the array
                            row += '<td>' + '<div class="checkboxStyle">' +
                                '<input name="permission[]" class="form-check-input" type="checkbox" value="' +
                                permission.id + '"' + (checked ? ' checked' : '') + '>' +
                                '</div>' +
                                '</td>';
                        });
                        row += '</tr>';
                        // Append row to table body
                        $('.table-customBorder tbody').append(row);
                    });
                    // Append button after adding table rows
                    var buttonRow = '<div class="row" id="assignPermissionButtonRow">' +
                        '<div class="col-md-2 col-md-offset-10">' +
                        '<input type="button" onclick="InsertPermission()" class="btn btn-info btn-block" value="Assign Permission">' +
                        '</div>' +
                        '</div>';
                    if (response.can_assign_permission) {
                        $('.table-customBorder').after(buttonRow);
                    }
                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                }
            });
        }


        function InsertPermission() {
            var user_id = $('.userId').val();
            var moduleId = $('.moduleId').val();
            // var permission = $('.permission').val();
            var permissionIds = [];
            // Loop through checked checkboxes to extract permission IDs
            $('input[name="permission[]"]:checked').each(function() {
                permissionIds.push($(this).val());
            });
            $.ajax({
                url: '{{ route('user_privilages.assign_permission') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    user_id: user_id,
                    // permission: permission,
                    permission: permissionIds,
                    module_id: moduleId
                },
                dataType: 'json',
                success: function(response) {
                    $('.table-customBorder tbody').empty();
                    $('#assignPermissionButtonRow').remove();
                    $('.moduleId').val('0').trigger('chosen:updated');
                    if (response.status === 200) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    } else {
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
