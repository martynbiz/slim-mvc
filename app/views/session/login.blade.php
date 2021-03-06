@extends('layouts.frontend')

@section('content')
    <ol class="breadcrumb">
        <li><a href="/">Home</a></li>
        <li class="active">Login</li>
    </ol>

    <div class="homebox">
        <div class="body">
            <div class="omb_login">
            	<h3 class="omb_authTitle">Login or <a href="#">Sign up</a></h3>
            	<div class="row omb_row-sm-offset-3 omb_socialButtons">
            	    <div class="col-xs-4 col-sm-2">
            	        <a href="#" class="btn btn-lg btn-block omb_btn-facebook">
            		        <i class="fa fa-facebook"></i>
            		        <span class="hidden-xs">Facebook</span>
            	        </a>
                    </div>
                	<div class="col-xs-4 col-sm-2">
            	        <a href="#" class="btn btn-lg btn-block omb_btn-twitter">
            		        <i class="fa fa-twitter"></i>
            		        <span class="hidden-xs">Twitter</span>
            	        </a>
                    </div>
                	<div class="col-xs-4 col-sm-2">
            	        <a href="#" class="btn btn-lg btn-block omb_btn-google">
            		        <i class="fa fa-google-plus"></i>
            		        <span class="hidden-xs">Google+</span>
            	        </a>
                    </div>
            	</div>

            	<div class="row omb_row-sm-offset-3 omb_loginOr">
            		<div class="col-xs-12 col-sm-6">
            			<hr class="omb_hrOr">
            			<span class="omb_spanOr">or</span>
            		</div>
            	</div>

            	<div class="row omb_row-sm-offset-3">
            		<div class="col-xs-12 col-sm-6">
                        <form method="post" action="/session">
            				<div class="input-group">
            					<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            					<input type="text" class="form-control" name="email" placeholder="email address" value="{{ @param['email'] }}">
            				</div>

            				<div class="input-group">
            					<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
            					<input  type="password" class="form-control" name="password" placeholder="Password" value="">
            				</div>

            				<button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
            			</form>
            		</div>
            	</div>
            	<div class="row omb_row-sm-offset-3">
            		<div class="col-xs-12 col-sm-3">
            			<label class="checkbox">
            				<input type="checkbox" value="remember-me">Remember Me
            			</label>
            		</div>
            		<div class="col-xs-12 col-sm-3">
            			<p class="omb_forgotPwd">
            				<a href="#">Forgot password?</a>
            			</p>
            		</div>
            	</div>
            </div>
        </div>
    </div>
@stop
