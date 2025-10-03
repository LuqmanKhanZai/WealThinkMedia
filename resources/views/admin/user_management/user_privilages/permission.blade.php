@extends('layouts.management')
{{-- For Title --}}
@section('title')
    Role Privialages
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
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">

            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>User : {{ $user->name }} || Role : {{ $role->name }} || Module : {{ $module->module_name }} </h5>
                    </div>
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
                            <form action="{{ route('user_privilages.assign_permission') }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="role_id" value="{{ $role->id }}">
                                <tbody>
                                    @foreach ($submodules as $submodule)
                                        <tr>
                                            <td>{{ $submodule->submodule_name }}</td>
                                            @foreach ($submodule->permissions as $permission)
                                                <td>
                                                    <div class="checkboxStyle">
                                                        <input type="checkbox" name="permission[]" class="form-check-input"
                                                            value="{{ $permission->id }}"
                                                            @if ($RoleHasPermission->contains('permission_id', $permission->id)) checked @endif>
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="4"></td>
                                        <td><button type="submit" class="btn btn-primary btn-block">Save</button></td>
                                    </tr>
                                </tbody>
                            </form>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
