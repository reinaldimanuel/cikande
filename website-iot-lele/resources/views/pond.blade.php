@extends('app')

@section('content')

<div class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success shadow-sm border-theme-white-2 position-relative" role="alert">
            <div class="d-inline-flex justify-content-center align-items-center thumb-xs bg-success rounded-circle mx-auto me-1">
                <i class="fas fa-check align-self-center mb-0 text-white"></i>
            </div>
            {{ session('success') }}
            <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div> 
    @endif

    <div class="card-body pt-0">
                                    

    <h4 class="page-title">Daftar Kolam</h4>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        
        <!-- Add New Pond Card -->
        <div class="col">
            <div class="card text-center border-dashed" role="button" data-bs-toggle="modal" data-bs-target="#addPondModal">
                <div class="card-body">
                    <h1 class="text-primary">+</h1>
                    <h5 class="card-title">Tambah Kolam</h5>
                </div>
            </div>
        </div>

        <!-- Loop Through Ponds -->
        @foreach($ponds as $pond)
        <div class="col">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h4>{{ $pond->name_pond }}</h4>
                    <button class="btn btn-outline-primary" onclick="window.location.href='{{ route('kolam.show', $pond->id_pond) }}'">
                    <i class="fas fa-eye"></i> Lihat
                    </button>
                </div>
                <div class="card-body d-flex flex-column">
                    <!-- Fish Details (stacked properly) -->
                    <div class="mb-3 fs-5">
                        <p class="card-text">Umur Ikan: {{ $pond->age_fish }} bulan</p>
                        <p class="card-text">Jumlah Ikan: {{ $pond->total_fish }}</p>
                    </div>

                    <!-- Buttons aligned to the right -->
                    <div class="mt-auto d-flex justify-content-end">
                        <button class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editPondModal{{ $pond->id_pond }}">Edit</button>
                        <form method="POST" action="{{ route('kolam.deactivate', $pond->id_pond) }}" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin menghapus kolam ini?')">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Pond Modal -->
        <div class="modal fade" id="editPondModal{{ $pond->id_pond }}" tabindex="-1" aria-labelledby="editPondLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('kolam.update', $pond->id_pond) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Kolam</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Nama Kolam</label>
                                <input type="text" class="form-control" name="name_pond" value="{{ $pond->name_pond }}" required>
                            </div>
                            <div class="mb-3">
                                <label>Umur Ikan (bulan)</label>
                                <input type="number" class="form-control" name="age_fish" value="{{ $pond->age_fish }}" required>
                            </div>
                            <div class="mb-3">
                                <label>Jumlah Ikan</label>
                                <input type="number" class="form-control" name="total_fish" value="{{ $pond->total_fish }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endforeach

    </div>
</div>

<!-- Add Pond Modal -->
<div class="modal fade" id="addPondModal" tabindex="-1" aria-labelledby="addPondLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('kolam.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kolam</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Kolam</label>
                        <input type="text" class="form-control" name="name_pond" required>
                    </div>
                    <div class="mb-3">
                        <label>Umur Ikan (bulan)</label>
                        <input type="number" class="form-control" name="age_fish" required>
                    </div>
                    <div class="mb-3">
                        <label>Jumlah Ikan</label>
                        <input type="number" class="form-control" name="total_fish" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>


@endsection