@extends('layouts.frontend')

@section('content')
    <ol class="breadcrumb">
        <li><a href="/">Home</a></li>
        <li class="active">Register</li>
    </ol>

    <form method="post" action="/users">
        <table class="table table-striped">
            <tr>
                <th width="20%">First name</th>
                <td><input type="text" name="first_name" value="{{ @$params['first_name'] }}" class="form-control"></td>
            </tr>
            <tr>
                <th>Last name</th>
                <td><input type="text" name="last_name" value="{{ @$params['last_name'] }}" class="form-control"></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><input type="text" name="email" value="{{ @$params['email'] }}" class="form-control"></td>
            </tr>
            <tr>
                <th>Password</th>
                <td><input type="password" name="password" value="{{ @$params['password'] }}" class="form-control"></td>
            </tr>
        </table>

        <button type="submit">Create</button>
    </form>
@stop
