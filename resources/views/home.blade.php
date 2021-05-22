@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                                    
                
                    <!-- Flash message if Success -->
				    @if(session()->has('flashMessageX'))
                        <div class="alert alert-success">
                            {!! session()->get('flashMessageX') !!} <!--Displays content without html escaping -->
                        </div>
                    @endif
				    <!-- Flash message -->
				
                    <!-- Flash message if Failed -->
				    @if(session()->has('flashMessageFailX'))
                        <div class="alert alert-danger">
                            {!! session()->get('flashMessageFailX') !!} <!--Displays content without html escaping -->
                        </div>
                    @endif
				     <!-- Flash message if Failed -->				
				
                    <!-- Display form validation errors var 2 -->
				    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- End Display form validation errors var 2 -->				
					
					
                    <div class="panel-heading text-warning">
                        <i class="fa fa-shopping-basket" style="font-size:84px"></i>
                        Shop <span class="small text-danger">*</span> 
                    </div>
                
                    <div class="panel-body">
                        <p> <i class="fa fa-check-circle-o" style="font-size:24px"></i> Different categories.</p>
                        <p> <i class="fa fa-check-circle-o" style="font-size:24px"></i> Personal cart.</p>
                        <p> <i class="fa fa-check-circle-o" style="font-size:24px"></i> On-line LiqPay/PayPal payment.</p>
                        <p> <i class="fa fa-check-circle-o" style="font-size:24px"></i> Admin panel.</p>
                        <p> <i class="fa fa-check-circle-o" style="font-size:24px"></i> <a href="{{ url('/shopSimple') }}"> Go to shop </a></p>
                    </div>
                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
