@extends('auth')

@section('auth-content')

<div class="container mt-5">
    <p>Halo {{ $user->name }},</p>
    <p>Silakan klik link di bawah untuk verifikasi email kamu:</p>
    <p><a href="{{ $url }}">{{ $url }}</a></p>
    <p>Jika kamu tidak mendaftar, abaikan email ini.</p>
</div>

@endsection