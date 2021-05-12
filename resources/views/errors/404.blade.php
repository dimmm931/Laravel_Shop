@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Error happened</div>

                <div class="panel-body">
				
                    <div class="alert alert-danger">
                        NOT FOUND
					</div>
					
					<center>
					<!--<img class="img-exception" src="{{URL::to('/')}}/images/error.png"  alt=""/>--> <!-- image -->
					<img class="img-exception" src="{{URL::to('/')}}/images/error2.jpg"  alt=""/> <!-- image -->
					<img class="img-exception" src="{{URL::to('/')}}/images/error3.jpg"  alt=""/> <!-- image -->
                    </center>
					
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
