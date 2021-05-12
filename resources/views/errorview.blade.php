@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">DashboardX</div>

                <div class="panel-body">
                  

                    
					
					<p>
					    ID: {{ $id}}
					</p>
					
					<p> Name:  {{ $name}} </p>
					
					<p> Email:  {{ $email}}</p>
					
					{{-- Auth::user()->name --}}
					{{-- Auth::user()->name --}}
					</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
