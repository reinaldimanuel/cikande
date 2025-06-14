@extends('auth')

@section('auth-content')
    <div class="container-xxl">
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 mx-auto">
                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    {{ session('status') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            <div class="card">
                                <div class="card-body p-0 bg-light auth-header-box rounded-top">
                                    <div class="text-center p-3">
                                        <a href="index.html" class="logo logo-admin">
                                            <img src="{{ asset('images/simko.png') }}" height="50" alt="logo" class="auth-logo">
                                        </a>
                                        <p class="mt-3 text-muted fw-medium mb-0">Buat akun dan mulai memantau secara digital.</p>    
                                    </div>
                                </div>
                                <div class="card-body pt-0">                                    
                                    <form class="my-4" method="POST" action="{{ route('daftar.store') }}"> 
                                        @csrf           
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="name">Name</label>
                                            <input type="text" class="form-control" value="{{ old('username') }}" name="name" placeholder="Masukkan nama" required>                               
                                        </div> 

                                        <div class="form-group mb-2">
                                            <label class="form-label" for="email">Email</label>
                                            <input type="email" class="form-control" value="{{ old('email') }}" name="email" placeholder="Masukkan email" required>                               
                                        </div> 
            
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="password">Password</label>                                            
                                            <input type="password" class="form-control" name="password" placeholder="Masukkan password" required>                            
                                        </div> 

                                        <div class="form-group mb-2">
                                            <label class="form-label" for="password_confirmation">Konfirmasi Password</label>                                            
                                            <input type="password" class="form-control" name="password_confirmation" placeholder="Masukkan kembali password" required>                            
                                        </div> 

                                        @error('email')
                                            <div class="text-red-500 text-sm mb-2">{{ $message }}</div>
                                        @enderror
            
                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <div class="d-grid mt-3">
                                                    <button class="btn btn-primary" type="submit">Daftarkan <i class="fas fa-sign-in-alt ms-1"></i></button>
                                                </div>
                                            </div> 
                                        </div>                           
                                    </form>
                                    <div class="text-center">
                                        <p class="text-muted">Sudah ada akun?  <a href="/login" class="text-primary ms-2">Masuk disini!</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>                                        
    </div>
@endsection
