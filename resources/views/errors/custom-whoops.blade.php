@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Error happened</div>

                <div class="panel-body">
				
                    <div class="alert alert-danger">
                        Instead of whoops message
					</div>
					
					<p>Details: <b>{!! $exception->getMessage() !!}</b></p>
					
					<center>
					    <i class="fa fa-close" style="font-size:58px;color:red"></i>
					<!--<img class="img-exception" src="{{URL::to('/')}}/images/error.png"  alt=""/>--> <!-- image -->
			
                    </center>
					
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
