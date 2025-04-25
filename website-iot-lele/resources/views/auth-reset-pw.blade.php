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
                                        <p class="mt-3 text-muted fw-medium mb-0">Silakan masukkan password baru Anda.</p>    
                                    </div>
                                </div>
                                <div class="card-body pt-0">                                    
                                    <form class="my-4" action="{{ route('password.update') }}"> 
                                        @csrf           

                                        <input type="hidden" name="token" value="{{ $token }}">

                                        <div class="form-group mb-2">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control" value="{{ $email ?? old('email') }}" required>
                                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="form-group mb-2">
                                            <label class="form-label"   >Password</label>                                            
                                            <input type="password" class="form-control" name="password" placeholder="Masukkan password">    
                                            @error('password') <small class="text-danger">{{ $message }}</small> @enderror                        
                                        </div><!--end form-group--> 

                                        <div class="form-group mb-2">
                                            <label class="form-label">Konfirmasi Password</label>                                            
                                            <input type="password" class="form-control" name="password_confirmation" placeholder="Masukkan kembali password">                            
                                        </div><!--end form-group--> 
            
                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <div class="d-grid mt-3">
                                                    <button class="btn btn-primary" type="submit">Ubah Password <i class="fas fa-sign-in-alt ms-1"></i></button>
                                                </div>
                                            </div><!--end col--> 
                                        </div> <!--end form-group-->                           
                                    </form><!--end form-->
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end card-body-->
            </div><!--end col-->
        </div><!--end row-->                                        
    </div><!-- container -->

@endsection
