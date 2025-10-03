@extends('layouts.app')

{{-- For Title --}}
@section('title') Item Update @endsection


{{-- Main Content --}}
@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Item Update</h2>
        </div>
        <div class="col-lg-2 " style="margin-top:-20px;">
            <a href="{{ route('item.index') }}" class="btn btn-info btn-outline" style="margin-top:40px;"> Items List</a>
        </div>
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <form method="POST" id="updateForm" onsubmit="event.preventDefault(); Insert();">
                    {{ csrf_field() }}
                    <table class="table table-custom">
                        <tr>
                            <th>Name</th>
                            <th>Price </th>
                            <th colspan="2">Description</th>
                            <th>Type </th>
                        </tr>
                        <tr>
                            <td><input type="text" name="item_name" class="form-control" value="{{$row->item_name}}"></td>
                            <td><input type="text" name="price" class="form-control" value="{{$row->price}}"></td>
                            <td colspan="2"><input type="text" name="description" class="form-control" value="{{$row->description}}"></td>
                            <td>
                                <select name="type" class="chosen-select" aria-label="Type select">
                                    <option value="1" {{$row->type == 1 ? 'selected' : ''}}>Main Product</option>
                                    <option value="2" {{$row->type == 2 ? 'selected' : ''}}>Bump Product</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td>
                                <input type="button" onclick="Update({{ $row->id }})" class="btn btn-info btn-block" value="Update Item">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>


    </div>

@endsection

@section('scripts')

    <script>


        function Update(id) {
            $.ajax({
                url: '/item/update/' + id, // Use the id parameter directly
                type: 'POST',
                data: $('#updateForm').serialize(),
                success: function(response) {
                    if (response.status === 200) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.msg,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location.href = '/item/index';
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
    </script>
