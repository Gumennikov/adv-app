@extends('layouts.app')

<form action="http://adv-app/logout" method="POST">
    <button type="submit">Logout</button>
</form>

@section('content')
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item"><a href="{{ route('cabinet.home') }}" class="nav-link active">Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('cabinet.adverts.index') }}" class="nav-link">Adverts</a></li>
        <li class="nav-item"><a href="{{ route('cabinet.favorites.index') }}" class="nav-link">Favorites</a></li>
        <li class="nav-item"><a href="{{ route('cabinet.profile.home') }}" class="nav-link">Profile</a></li>
    </ul>
@endsection
