@extends('app')

@section('content')

<div class="container mt-3">
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
        <!-- Judul & Search Bar -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="page-title mb-0">Daftar Kolam</h4>

            <input type="text" id="searchPond" class="form-control rounded-pill w-50 ms-auto"
                placeholder="Cari nama kolam..." style="max-width: 300px;">
        </div>

        <!-- Pesan jika tidak ditemukan -->
        <div id="noResult" class="text-center text-muted d-none">Kolam tidak ditemukan</div>

        <!-- Grid Kolam -->
        <div class="row row-cols-1 row-cols-md-3 g-4" id="pondGrid">

            <!-- Add New Pond Card -->
            <div class="col">
                <div class="card text-center border-dashed" style="height: 250px;" role="button" data-bs-toggle="modal" data-bs-target="#addPondModal">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h1 class="text-primary m-0">+</h1>
                        <h5 class="card-title m-0">Tambah Kolam</h5>
                    </div>
                </div>
            </div>

            <!-- Loop Through Ponds -->
            @foreach($ponds as $pond)
            <div class="col pond-card" data-name="{{ strtolower($pond->name_pond) }}">
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h4>{{ $pond->name_pond }}</h4>
                        <button class="btn btn-outline-primary" onclick="window.location.href='{{ route('kolam.show', $pond->id_pond) }}'">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="mb-3 fs-5">
                            <p class="card-text">Umur Ikan: {{ $pond->formatted_age }}</p>
                            <p class="card-text">Jumlah Ikan: {{ $pond->total_fish }}</p>
                        </div>

                        <div class="mt-auto d-flex justify-content-end">
                            <button class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editPondModal{{ $pond->id_pond }}">Edit</button>
                            <form method="POST" action="{{ route('kolam.deactivate', $pond->id_pond) }}" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="button" class="btn btn-danger btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#confirmDeleteModal{{ $pond->id_pond }}">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
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

        <!-- Script Live Search -->
        <script>
            document.getElementById('searchPond').addEventListener('keyup', function () {
                const keyword = this.value.toLowerCase();
                const cards = document.querySelectorAll('.pond-card');
                let visible = 0;

                cards.forEach(card => {
                    const name = card.getAttribute('data-name');
                    // Cek jika nama kolam diawali dengan keyword
                    if (name.startsWith(keyword)) {
                        card.style.display = '';
                        visible++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                document.getElementById('noResult').classList.toggle('d-none', visible > 0);
            });
        </script>
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
                            <label>Umur Ikan</label>
                            <input type="date" class="form-control" name="birth_fish" required>
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

    <!-- Delete (deactivate) Pond Modal -->
    @foreach($ponds as $pond)
        <div class="modal fade" id="confirmDeleteModal{{ $pond->id_pond }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('kolam.deactivate', $pond->id_pond) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Konfirmasi Hapus Kolam</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Yakin ingin menghapus kolam <strong>{{ $pond->name_pond }}</strong>?</p>
                            <div class="mb-3">
                                <label>Alasan Penghapusan (opsional)</label>
                                <textarea name="deact_reason" class="form-control" rows="3" placeholder="Contoh: Kolam bocor, kurang memadai, dll."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

  
</div>



@endsection