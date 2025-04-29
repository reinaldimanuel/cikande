<div class="container">
    <p>Halo, {{ $user->name }},</p>
    <br>
    <p>Silakan klik link di bawah untuk verifikasi email kamu:</p>
    <p><a href="{{ $url }}">{{ $url }}</a></p>
    <br>
    <p>Jika kamu tidak mendaftar/mengubah, abaikan email ini.</p>
</div>