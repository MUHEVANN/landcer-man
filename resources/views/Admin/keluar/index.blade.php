@extends('layouts.dashboard')
@section('content')
    <div class="container card py-5">

        <div class="mb-3 row">
            <div class="col-3">
                <button type="button" onclick="hapus()" id="hapus" class="btn btn-danger" disabled>Hapus</button>
            </div>
            <div class="col-9 row d-flex justify-content-end ">
                <div class="col-3">
                    <select name="" id="select_penanggung_jawab" class="form-control text-capitalize">
                        <option value="">Penanggung Jawab</option>
                        @foreach ($penanggung_jawab as $item)
                            <option value="{{ $item->nama_penanggung_jawab }}">{{ $item->nama_penanggung_jawab }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <select name="status" id="status" class="form-control">
                        <option value="">Status</option>
                        <option value="keluar">Keluar</option>
                        <option value="masuk">Masuk</option>
                    </select>
                </div>
            </div>
        </div>
        <table class="table table-hover" id="myTable" style="width:100%">
            <thead>
                <tr>
                    <th><input type="checkbox" name="" id="head-cb"></th>
                    <th>Nama Penanggung Jawab</th>
                    <th>Nama Pemohon</th>
                    <th>Domisili</th>
                    <th>No. Sertifikat</th>
                    <th>Desa</th>
                    <th>No. Berkas</th>
                    <th>Document</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
        <div class="modal fade" id="tambah-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                        <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="form-id">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="" class="">Nama Penanggung Jawab</label>
                                <select name="" id="penanggung_jawab_id" class="form-control">
                                    <option value="">Pilih Salah Satu</option>
                                    @foreach ($penanggung_jawab as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_penanggung_jawab }}</option>
                                    @endforeach
                                </select>
                                <div id="error-penanggung_jawab_id" class="text-danger"></div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="">Nama Pemohon</label>
                                <input type="text" name="nama_pemohon" id="nama_pemohon" class="form-control">
                                <div id="error-nama_pemohon" class="text-danger"></div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="">Domisili</label>
                                <input type="text" name="domisili" id="domisili" class="form-control">
                                <div id="error-domisili" class="text-danger"></div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="">No. Sertifikat</label>
                                <input type="text" name="nomor_sertifikat" id="nomor_sertifikat" class="form-control">
                                <div id="error-nomor_sertifikat" class="text-danger"></div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="">Desa</label>
                                <input type="text" name="desa" id="desa" class="form-control">
                                <div id="error-desa" class="text-danger"></div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="">No. Berkas</label>
                                <input type="text" name="no_berkas" id="no_berkas" class="form-control">
                                <div id="error-no_berkas" class="text-danger"></div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="">Document</label>
                                <input type="file" name="document" id="document" class="form-control">
                                <div id="error-document" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" class="close"
                                data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $('#myTable').DataTable({
                fixedHeader: true,
                responsive: true,
                processing: true,
                serverside: true,
                ajax: '/data-user-keluar',
                columns: [{
                        data: 'checkbox',
                        name: 'checkbox',
                    }, {
                        name: 'penanggung_jawab.nama_penanggung_jawab',
                        data: 'penanggung_jawab.nama_penanggung_jawab',
                        render: function(data) {
                            return "<span class='text-capitalize '>" + data + "</span>"
                        }
                    },
                    {
                        name: 'nama_pemohon',
                        data: 'nama_pemohon',
                        render: function(data) {
                            return "<span class='text-capitalize '>" + data + "</span>"
                        }
                    },
                    {
                        name: 'domisili',
                        data: 'domisili',
                        render: function(data) {
                            return "<span class='text-capitalize '>" + data + "</span>"
                        }
                    },
                    {
                        name: 'nomor_sertifikat',
                        data: 'nomor_sertifikat',
                    },
                    {
                        name: 'desa',
                        data: 'desa',
                        render: function(data) {
                            return "<span class='text-capitalize '>" + data + "</span>"
                        }
                    },
                    {
                        name: 'no_berkas',
                        data: 'no_berkas',
                    },
                    {
                        name: 'document',
                        data: 'document',

                    },
                    {
                        name: 'proses_sertifikat',
                        data: 'proses_sertifikat',
                        render: function(data) {
                            return data === 'keluar' ?
                                "<span class='text-capitalize text-danger'>" + data + "</span>" :
                                "<span class='text-capitalize text-info'>" + data + "</span>"
                        }
                    },
                    {
                        name: 'action',
                        data: 'action',
                    },
                ]
            });
            $('#head-cb').on('click', function() {
                if ($('#head-cb').prop('checked') === true) {
                    $('#myTable tbody tr').css('background-color', '#f5f5f5');
                    $('.child-cb').prop('checked', true);
                    $('#hapus').prop('disabled', false);


                } else {
                    $('#hapus').prop('disabled', true);
                    $('.child-cb').prop('checked', false);
                    $('#myTable tbody tr').css('background-color', '');
                }
            });

            $('#myTable tbody').on('click', '.child-cb', function() {
                var uncheckedCheckboxes = $('#myTable tbody .child-cb:not(:checked)');
                uncheckedCheckboxes.closest('tr').css('background-color', '');
                if ($(this).prop('checked') === false) {
                    $('#head-cb').prop('checked', false);
                }
                $('#myTable tbody .child-cb:checked').closest('tr').css('background-color', '#f5f5f5');
                let all_checkbox = $('#myTable tbody .child-cb:checked');
                let active_checkbox = (all_checkbox.length > 0);
                // console.log(all_checkbox.val());
                $('#hapus').prop('disabled', !active_checkbox);
            });
            $('#status').change(function(e) {
                var val = $(this).val();
                e.preventDefault();
                table.columns(8).search(val).draw();
            });
            $('#select_penanggung_jawab').change(function(e) {
                var val = $(this).val();
                e.preventDefault();
                table.columns(1).search(val).draw();
            });
            $('.close').click(function() {
                $('#tambah-modal').modal('hide');
                $('#penanggung_jawab_id').val('');
                $('#nama_pemohon').val('');
                $('#domisili').val('');
                $('#nomor_sertifikat').val('');
                $('#desa').val('');
                $('#no_berkas').val('');
                $('#document').val('');
                $('#error-penanggung_jawab_id').text("");
                $('#error-nama_pemohon').text("");
                $('#error-domisili').text("");
                $('#error-nomor_sertifikat').text("");
                $('#error-desa').text("");
                $('#error-no_berkas').text("");
                $('#error-document').text("");
            });

            $('body').on('click', '.edit', function(e) {
                e.preventDefault();
                var id = $(this).data('id');

                $.ajax({
                    type: "GET",
                    url: "{{ url('purposes') }}" + '/' + id + '/edit',
                    success: function(response) {
                        $('#tambah-modal').modal('show');
                        $('#penanggung_jawab_id').val(response.success
                            .penanggung_jawab_id);
                        $('#nama_pemohon').val(response.success.nama_pemohon);
                        $('#domisili').val(response.success.domisili);
                        $('#nomor_sertifikat').val(response.success
                            .nomor_sertifikat);
                        $('#desa').val(response.success.desa);
                        $('#no_berkas').val(response.success.no_berkas);
                    },
                });
                $('.submit').off('click');
                $('.submit').click(function(e) {
                    e.preventDefault();
                    var formData = new FormData();
                    formData.append('penanggung_jawab_id', $('#penanggung_jawab_id').val());
                    formData.append('nama_pemohon', $('#nama_pemohon').val());
                    formData.append('domisili', $('#domisili').val());
                    formData.append('nomor_sertifikat', $('#nomor_sertifikat').val());
                    formData.append('desa', $('#desa').val());
                    formData.append('no_berkas', $('#no_berkas').val());
                    formData.append('document', $('#document')[0].files[0]);
                    console.log(formData);
                    $.ajax({
                        method: 'POST',
                        url: "{{ url('purposes') }}" + '/' + id,
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            console.log(response);
                            if (response[0] === 'error') {
                                $('#error-penanggung_jawab_id').text(response[1]
                                    .benefit);
                                $('#error-nama_pemohon').text(response[1].name);
                                $('#error-domisili').text(response[1].desc);
                                $('#error-nomor_sertifikat').text(response[1]
                                    .kualifikasi);
                                $('#error-desa').text(response[1].gambar);
                                $('#error-no_berkas').text(response[1].deadline);
                            } else {
                                $('#penanggung_jawab_id').val('');
                                $('#nama_pemohon').val('');
                                $('#domisili').val('');
                                $('#nomor_sertifikat').val('');
                                $('#desa').val("");
                                $('#no_berkas').val("");
                                $('#tambah-modal').modal('hide');
                                const Toast = Swal.mixin({
                                    width: 400,
                                    padding: 18,
                                    toast: true,
                                    position: 'bottom-end',
                                    showConfirmButton: false,
                                    timer: 1500,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter',
                                            Swal.stopTimer)
                                        toast.addEventListener('mouseleave',
                                            Swal.resumeTimer)
                                    }
                                })

                                Toast.fire({

                                    icon: 'success',
                                    title: response.success
                                })
                                table.ajax.reload();
                            }
                        }
                    });
                });
            });

            $('body').on('click', '.hapus', function(e) {
                var id = $(this).data('id');
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Yakin ingin menghapus lowongan?',
                    text: "Semua Apply yang berkaitan juga akan terhapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('purposes') }}" + '/' + id,
                            method: 'DELETE',
                            success: function(response) {
                                const Toast = Swal.mixin({
                                    width: 400,
                                    padding: 15,
                                    toast: true,
                                    position: 'bottom-end',
                                    showConfirmButton: false,
                                    timer: 1500,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter',
                                            Swal.stopTimer)
                                        toast.addEventListener('mouseleave',
                                            Swal.resumeTimer)
                                    }
                                })

                                Toast.fire({

                                    icon: 'success',
                                    title: response.success
                                })
                                table.ajax.reload();
                            }
                        });
                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        $('#tambah-modal').modal('hide');
                        const Toast = Swal.mixin({
                            width: 400,
                            padding: 18,
                            toast: true,
                            position: 'bottom-end',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter',
                                    Swal.stopTimer)
                                toast.addEventListener('mouseleave',
                                    Swal.resumeTimer)
                            }
                        })

                        Toast.fire({

                            icon: 'error',
                            title: 'Tidak jadi menghapus'
                        })
                    }
                })
            });

        });

        function hapus() {
            var checkbox_checked = $('#myTable tbody .child-cb:checked');
            let all_checked = [];
            $.each(checkbox_checked, function(index, value) {
                all_checked.push(value.value);
            });
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    for (var i = 0; i < all_checked.length; i++) {
                        $.ajax({
                            method: 'DELETE',
                            url: "{{ url('purposes') }}" + '/' + all_checked[i],
                        })
                    }
                    const Toast = Swal.mixin({
                        width: 400,
                        padding: 18,
                        toast: true,
                        position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter',
                                Swal.stopTimer)
                            toast.addEventListener('mouseleave',
                                Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'berhasil konfirmasi'
                    });

                    $('#myTable').DataTable().ajax.reload();
                    $('#head-cb').prop('checked', false);
                    $('#hapus').prop('disabled', true);
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Your imaginary file is safe :)',
                        'error'
                    )
                }
            })



        }
    </script>
@endsection
