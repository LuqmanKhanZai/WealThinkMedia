@extends('layouts.app')
{{-- For Title --}}
@section('title')
    Role Privialages
@endsection
<style>
    .table-customBorder td {
        padding: 1px !important;
    }

    /* .table-customBorder td, tr, th{
            border: none !important;
        } */
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
            {{-- <form method="POST" action="{{route('role_privilages.permission_list')}}"> --}}
            {{ csrf_field() }}
            <div class="col-md-3">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="">Role</label>
                    <select class="chosen-select roleId" name="role_id">
                        <option selected>--Select Role--</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="">Module</label>
                    <select class="chosen-select moduleId" name="module_id">
                        <option selected>--Select Module--</option>
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
                    {{-- <div class="ibox-title">
                        <h5>Roles List</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>

                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div> --}}
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
                                {{-- <tr>
                                    <td>SubModuleName</td>
                                    <td>PermissonNameone</td>
                                    <td>PermissonNameTwo</td>
                                    <td>PermissonNameThree</td>
                                    <td>PermissonNameFour</td>
                                </tr> --}}
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
            var roleId = $('.roleId').val();
            var moduleId = $('.moduleId').val();
            $.ajax({
                url: '{{ route('role_privilages.get_permission') }}',
                type: 'GET',
                data: {
                    role_id: roleId,
                    module_id: moduleId
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    var submodules = response.submodule;
                    var assignPermission = response.RoleHasPermission;
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
                            var checked = assignPermission.some(function(rolePermission) {
                                return rolePermission.permission_id === permission.id;
                            });
                            var permissionDisplayName = permission.name.split('.').pop();
                            row += '<td>' + '<div class="checkboxStyle">' +
                                // permissionDisplayName + '&nbsp;&nbsp;&nbsp;&nbsp;' +
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
                    // $('.table-customBorder').after(buttonRow);
                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                }
            });
        }

        function InsertPermission() {
            var role_id = $('.roleId').val();
            var moduleId = $('.moduleId').val();

            // var permission = $('.permission').val();
            var permissionIds = [];
            // Loop through checked checkboxes to extract permission IDs
            $('input[name="permission[]"]:checked').each(function() {
                permissionIds.push($(this).val());
            });
            $.ajax({
                url: '{{ route('role_privilages.assign_permission') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    role_id: role_id,
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
