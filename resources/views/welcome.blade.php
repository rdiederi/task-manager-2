@extends('layouts.app')

@section('content')
<div class="text-center mt-5">
    <h1>Welcome to Our Application!</h1>
    <p>Discover tasks and manage your projects easily.</p>

    @auth
        <a href="{{ route('tasks.index') }}" class="btn btn-primary">Go to Your Tasks</a>
    @else
        <a href="{{ route('login') }}" class="btn btn-secondary">Login</a>
        <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
    @endauth
</div>
@endsection
