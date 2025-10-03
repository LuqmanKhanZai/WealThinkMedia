@extends('layouts.app')
@section('title') Access Denied @endsection
{{-- For Title --}}
@section('content')

<div class="row">
    <div class="col-lg-12">
        
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4">
                <div class="panel panel-warning" style="margin: auto; margin-top: 150px;">
                    <div class="panel-heading">
                        <i class="fa fa-warning"></i> Warning Panel
                    </div>
                    <div class="panel-body">
                        <p>User does not have the right permissions.</p>
                        <p>You don’t have access to this page. Please contact your administrator if you believe this is an error.</p>
                    </div>
                    <div class="panel-footer">
                        <a href="{{ url()->previous() }}" class="btn btn-warning btn-block">Go Back</a>
                    </div>
                </div>
            </div>
        </div>
         
    </div>
</div>

@endsection