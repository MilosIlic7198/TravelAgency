@extends('layouts/app')
@section('content')
<app-component :user_logged_in="{{json_encode(Auth::user())}}"></app-component>
@endsection
