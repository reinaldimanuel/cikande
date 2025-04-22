@extends('auth')

@section('auth-content')
    <div class="container-xxl">
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 mx-auto">
                            <div class="card">
                                <div class="card-body p-0 bg-light auth-header-box rounded-top">
                                    <div class="text-center p-3">
                                        <a href="index.html" class="logo logo-admin">
                                            <img src="{{ asset('images/simko.png') }}" height="50" alt="logo" class="auth-logo">
                                        </a>
                                        <p class="mt-3 text-muted fw-medium mb-0">Isi email Anda dan instruksi akan dikirimkan!</p>  
                                    </div>
                                </div>
                                <div class="card-body pt-0">                                    
                                    <form class="my-4" action="{{ route('password.email') }}">            
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="username">Email</label>
                                            <input type="text" class="form-control" id="userEmail" name="Email" placeholder="Isi email Anda disini..." required>                               
                                        </div><!--end form-group-->             
                                        
                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <div class="d-grid mt-3">
                                                    <button class="btn btn-primary" type="button">Reset <i class="fas fa-sign-in-alt ms-1"></i></button>
                                                </div>
                                            </div><!--end col--> 
                                        </div> <!--end form-group-->                           
                                    </form><!--end form-->
                                    <div class="text-center  mb-2">
                                        <p class="text-muted">Sudah ingat?  <a href="/login" class="text-primary ms-2">Kembali ke login</a></p>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end card-body-->
            </div><!--end col-->
        </div><!--end row-->                                        
    </div><!-- container -->
@endsection