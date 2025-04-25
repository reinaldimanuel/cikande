@extends('app')

@section('content')

<div class="container mt-3">
    <!-- Message Alert -->
    @if(session('status'))
        <div class="alert alert-success mb-3 alert-dismissible fade show" role="alert"">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mb-3 alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <h1 class="mb-4 fs-3">Pengaturan Akun</h1>

    <div class="row g-4">        
        <!-- Card 1 -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fw-semibold fs-5 bg-light">Informasi Pengguna</div>
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    <p class="card-text"><strong>Email:</strong> {{ auth()->user()->email ?? 'dummy@example.com' }}</p>
                    <p class="card-text"><strong>Name:</strong> {{ auth()->user()->name ?? 'John Doe' }}</p>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="col-md-6">
            <div class="card h-100">
            <div class="card-header fw-semibold fs-5 bg-light">Ubah Password</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('settings.password') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Password Saat Ini</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Simpan Password</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="col-md-6">
            <div class="card h-100">
            <div class="card-header fw-semibold fs-5 bg-light">Ubah Email</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('settings.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email Baru</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Simpan Email</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="col-md-6">
            <div class="card h-100">
            <div class="card-header fw-semibold fs-5 bg-light">Ubah Nama</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('settings.name') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Baru</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 text-white">Simpan Nama</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection