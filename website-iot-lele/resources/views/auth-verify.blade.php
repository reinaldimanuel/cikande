<div class="container">
    <p>Halo {{ $user->name }},</p>
    <p>Silakan klik link di bawah untuk verifikasi email kamu:</p>
    <p><a href="{{ $url }}">{{ $url }}</a></p>
    <p>Jika kamu tidak mendaftar/mengubah, abaikan email ini.</p>
    <br>
    <p>SIMKO - Sistem Monitoring Kolam</p>
</div>