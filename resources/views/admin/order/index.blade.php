@extends('layouts.app')
{{-- For Title --}}
@section('title')
    Orders
@endsection

@section('content')

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Orders List</h5>
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
                            {{-- <table class="table table-striped table-bordered table-hover" id="users-table"> --}}
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>UsersName</th>
                                        <th>Email</th>
                                        <th>Amount</th>
                                        {{-- <th>StripeId</th>
                                        <th>Status</th> --}}
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                                            <td>{{ $order->user->email ?? 'N/A' }}</td>
                                            <td>{{ $order->amount }}</td>
                                            {{-- <td>{{ $order->stripe_charge_id }}</td> --}}
                                            {{-- <td> --}}
                                                {{-- {{ ucfirst($order->status) }} --}}
                                                {{-- @if ($order->status == 'completed')
                                                    <span class="badge badge-success">Completed</span>
                                                @elseif ($order->status == 'pending')
                                                    <span class="badge badge-warning">Pending</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ ucfirst($order->status) }}</span>
                                                @endif --}}
                                            {{-- </td> --}}
                                            <td>
                                                <a href="{{ route('order.print', $order->id) }}" class="btn btn-info btn-sm">View</a>
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
    </script>
@endsection

