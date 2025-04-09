@extends('app')

@section('content')

    <div class="container col-md-12 mt-3">
            <div class="card mb-3">
                <div class="card-header fw-bold">Jadwal Harian Pemberian Makan</div>
                <div class="card-body">
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#feedingModal">Tambah Waktu</button>
                    
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($schedule->feeding_time)->format('H:i') }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $schedule->id }}" data-bs-toggle="modal" data-bs-target="#editfeedingModal">Edit</button>
                                        <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $schedule->id }}">Hapus</button>
                                        <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $schedule->id }}">Beri Pakan Sekarang</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <p>*Status akan diulang setiap harinya pada jam 00:00</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header fw-bold">Riwayat Pemberian Makan</div>
                    <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($histories as $history)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($history->feeding_time)->format('d-m-y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($history->feeding_time)->format('H:i') }}</td>
                                        <td><span class="badge bg-success">{{ $history->status }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                    </div>
            </div>
        </div>

        <!-- Modal for Adding Feeding Time -->
        <div class="modal fade" id="feedingModal" tabindex="-1" aria-labelledby="feedingModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="feedingModalLabel">Tambah Waktu Pemberian Makan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('feeding.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="feeding_time" class="form-label">Waktu Pemberian Makan</label>
                                <input type="time" class="form-control" name="feeding_time" id="feeding_time" required>
                            </div>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

         <!-- Modal for Editing Feeding Time -->
        <div class="modal fade" id="editfeedingModal" tabindex="-1" aria-labelledby="feedingModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="feedingModalLabel">Edit Waktu Pemberian Makan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editFeedingForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="feeding_time" class="form-label">Waktu Pemberian Makan</label>
                                <input type="time" class="form-control" name="feeding_time" id="feeding_time" value="{{ $schedule->feeding_time }}" required>
                            </div>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
            // When Edit button is clicked
            $('.edit-btn').click(function() {
                let id = $(this).data('id'); // Get ID from button
                let time = $(this).data('time'); // Get feeding time

                // Update the modal form action with the correct ID
                $('#editFeedingForm').attr('action', '/feeding-schedule/' + id);

                // Populate the feeding time input field
                $('#feeding_time').val(time);
            });
        });
        </script>


@endsection