<!------------ Form For for unlogged user (in checkout view) --------->
<!-- It is display:none by default, hidden by Bootstrap class .collapse, in order to appear a user should click "Proceed with <button class="btn"><a href="#">one-click buy </a></button>) -->

<!-- Form with user's details, i.e address, cell, etc -->
<!-- When user clicks "Proceed unlogged", fill the form and submit and validation fails, so show this form with class ".in" -->
<div class="col-sm-12 col-xs-12 shadowX collapse {{((count($errors) > 0)) ? ' in' : ''}}" id="unloggedUserForm"> <!-- hidden by Bootstrap class .collapse -->
	<p> Your Order ID is <b> {{$uuid}} </b> </p>
	<h2> Shipping details </h2>
	<form class="form-horizontal" method="post" class="form-assign" action="{{url('/payPage1')}}">
		<input type="hidden" value="{{csrf_token()}}" name="_token"/>
			  
	    <!-- Name --> 
        <div class="form-group{{ $errors->has('u_name') ? ' has-error' : '' }}">
            <label for="u_name" class="col-md-4 control-label">Name</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="u_name" value="{{ old('u_name')  }}" placeholder="Your name" required /> 
                @if ($errors->has('u_name'))
                    <span class="help-block"> <strong>{{ $errors->first('u_name') }}</strong> </span>
                @endif 
			</div>
        </div>			
              
	    <!-- Email --> 
        <div class="form-group{{ $errors->has('u_email') ? ' has-error' : '' }}">
            <label for="u_email" class="col-md-4 control-label">E-mail</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="u_email" value="{{ old('u_email')  }}" placeholder="Your email" required />       
                @if ($errors->has('u_email'))
                    <span class="help-block"> <strong>{{ $errors->first('u_email') }}</strong> </span>
                @endif 
			</div>
        </div>
			   
			   
	    <!-- Address --> 
        <div class="form-group{{ $errors->has('u_address') ? ' has-error' : '' }}">
            <label for="u_address" class="col-md-4 control-label">Address</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="u_address" value="{{ old('u_address') }}" placeholder="Your address" required />       
                @if ($errors->has('u_address'))
                    <span class="help-block"> <strong>{{ $errors->first('u_address') }}</strong> </span>
                @endif 
		    </div>
        </div>
			   
	    <!-- u_phone --> 
        <div class="form-group{{ $errors->has('u_phone') ? ' has-error' : '' }}">
            <label for="u_phone" class="col-md-4 control-label">Phone</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="u_phone" value="{{ old('u_phone') }}" placeholder="Your phone" required />       
                @if ($errors->has('u_phone'))
                    <span class="help-block"> <strong>{{ $errors->first('u_phone') }}</strong> </span>
                @endif 
			</div>
        </div>
			   
         
		<!-- Other hidden fields/inputs -->
		<input type="hidden" name="u_uuid" value="{{ $uuid }}"  />            <!-- UUID-->
	    <input type="hidden" name="u_sum" value="{{ $totalSum }}" />           <!-- Sum -->
        <input type="hidden" name="u_items_in_order" value="{{ count($_SESSION['cart_dimmm931_1604938863']) }}" /> <!-- Sum -->
		<!-- Other hidden fields/inputs -->
				
        <div class="form-group">
            <div class="col-md-8 col-md-offset-4">
                <button type="submit" class="btn btn-primary shadowX submitX rounded"> Done </button>
				<span class='small font-italic text-danger' >You have to clear the Cart here as well</span>
			</div>
		</div>
		      
	</form><hr>
</div>
<!-- End Form with user's details, i.e address, cell, etc -->
	  		