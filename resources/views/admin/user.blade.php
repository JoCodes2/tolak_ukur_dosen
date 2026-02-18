@extends('Layouts.Base')

@section('content')
    <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h3 class="m-0 font-weight-bold">
                <i class="fa-solid fa-book pr-2"></i> Galeri
            </h3>
            <button class="btn btn-primary btn-sm" id="myBtn"><i class="fas fa-plus"></i> Tambah Galeri</button>
        </div>

        <div class="card-body py-2">
            <div class="py-3">
                <h6>Daftar Galeri</h6>
                <table id="dataGaleri" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Gambar</th>
                            <th>Tanggal Upload</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <tr>
                            <td colspan="5" class="text-center">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit -->
    <div class="modal fade" id="upsertDataModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="upsertDataForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Galeri</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id">
                        <input type="hidden" id="category" name="category" value="galeri">

                        <div class="mb-3">
                            <label for="title" class="form-label">Judul</label>
                            <input type="text" id="title" name="title" class="form-control">
                            <small id="title-error" class="text-danger"></small>
                        </div>

                        <div class="mb-3">
                            <label for="date_upload" class="form-label">Tanggal Upload</label>
                            <input type="datetime-local" id="date_upload" name="date_upload" class="form-control">
                            <small id="date_upload-error" class="text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label for="image">Gambar</label>
                            <input type="file" class="form-control-file" name="image" id="image">
                            <small id="image-error" class="text-danger"></small>
                            <div id="imagePreview" class="pt-2"></div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="simpanData" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            function getData() {
                $.ajax({
                    url: `/v1/content/galeri`,
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        let tableBody = "";
                        $.each(response.data, function(index, item) {
                            tableBody += "<tr>";
                            tableBody += "<td>" + (index + 1) + "</td>";
                            tableBody += "<td>" + item.title + "</td>";
                            tableBody += "<td><img src='/uploads/img-content/" + item.image +
                                "' width='100' height='100' alt='tidak ada'></td>";
                            tableBody += "<td>" + item.date_upload + "</td>";
                            tableBody += "<td>";
                            tableBody +=
                                "<button type='button' class='btn btn-outline-primary btn-sm edit-btn' data-id='" +
                                item.id + "'><i class='fas fa-edit'></i></button> ";
                            tableBody +=
                                "<button type='button' class='btn btn-outline-danger btn-sm delete-confirm' data-id='" +
                                item.id + "'><i class='fas fa-trash'></i></button>";
                            tableBody += "</td>";
                            tableBody += "</tr>";
                        });
                        $("#dataGaleri tbody").html(tableBody);

                        $('#dataGaleri').DataTable({
                            destroy: true,
                            paging: true,
                            searching: true,
                            ordering: true,
                            info: true,
                            order: []
                        });
                    },
                    error: function() {
                        console.log("Gagal mengambil data dari server");
                    }
                });
            }

            getData();

            $(document).on('click', '#simpanData', function(e) {
                $('.text-danger').text('');
                e.preventDefault();

                let id = $('#id').val();
                let formData = new FormData($('#upsertDataForm')[0]);
                let url = id ? `/v1/content/update/${id}` : '/v1/content/create';

                loadingAllert();

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.close();
                        if (response.code === 422) {
                            $.each(response.errors, function(key, value) {
                                $('#' + key + '-error').text(value[0]);
                            });
                        } else if (response.code === 200 || response.status === "success") {
                            successAlert('Data berhasil disimpan!');
                            reloadBrowsers();
                        } else {
                            errorAlert();
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.close();
                        errorAlert();
                    }
                });
            });

            $(document).on('click', '.edit-btn', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: `/v1/content/get/${id}`,
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        $('#upsertDataModal').modal('show');
                        $('#id').val(response.data.id);
                        $('#title').val(response.data.title);
                        $('#date_upload').val(response.data.date_upload);
                        if (response.data.image) {
                            $('#imagePreview').html(
                                `<img src="/uploads/img-content/${response.data.image}" width="200" height="200" alt="content Image">`
                            );
                        } else {
                            $('#imagePreview').html('<p>Tidak ada gambar</p>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data for edit:', error);
                    }
                });
            });

            $(document).on('click', '.delete-confirm', function() {
                let id = $(this).data('id');

                function deleteData() {
                    $.ajax({
                        type: 'DELETE',
                        url: `/v1/content/delete/${id}`,
                        dataType: 'json',
                        success: function(response) {
                            if (response.code === 200 || response.status === "success") {
                                successAlert('Data berhasil dihapus!');
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            } else {
                                errorAlert();
                            }
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr.responseText);
                            errorAlert();
                        }
                    });
                }

                confirmAlert('Apakah Anda yakin ingin menghapus data?', deleteData);
            });

            function successAlert(message) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: message,
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1000,
                });
            }

            function errorAlert() {
                Swal.fire({
                    title: 'Error',
                    text: 'Terjadi kesalahan!',
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 1000,
                });
            }

            function reloadBrowsers() {
                setTimeout(function() {
                    location.reload();
                }, 1500);
            }

            function confirmAlert(message, callback) {
                Swal.fire({
                    title: '<span style="font-size: 22px"> Konfirmasi!</span>',
                    html: message,
                    showCancelButton: true,
                    showConfirmButton: true,
                    cancelButtonText: 'Tidak',
                    confirmButtonText: 'Ya',
                    reverseButtons: true,
                    confirmButtonColor: '#48ABF7',
                    cancelButtonColor: '#EFEFEF',
                    customClass: {
                        cancelButton: 'text-dark'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        callback();
                    }
                });
            }

            function loadingAllert() {
                Swal.fire({
                    title: 'Loading...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }

            $(document).on('click', '#myBtn', function() {
                $('#upsertDataForm')[0].reset();
                $('#id').val('');
                $('#imagePreview').html('');
                $('#upsertDataModal').modal('show');
                $('.text-danger').text('');

            });

            $('#upsertDataModal').on('hidden.bs.modal', function() {
                $('#upsertDataForm')[0].reset();
                $('#id').val('');
                $('#imagePreview').html('');
                $('.text-danger').text('');

            });
        });
    </script>
@endsection
