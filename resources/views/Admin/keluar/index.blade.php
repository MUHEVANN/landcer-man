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
                <tr class="container-th">
                    <th><input type="checkbox" name="" id="head-cb"></th>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jenis Pekerjaan</th>
                    <th>No Akta</th>
                    <th>Proses Permohonan </th>
                    <th>Nama Bank</th>
                    <th>Pihak</th>
                    <th>Keterangan</th>
                    <th>Document</th>
                    <th>Penanggung Jawab</th>
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
                                <label for="" class="">Jenis Pekerjaan</label>
                                <select name="jenis_pekerjaan" id="jenis_pekerjaan" class="form-control">
                                    <option value="">Pilih salah satu</option>
                                    <option value="PPAT">PPAT</option>
                                    <option value="NOTARIS">NOTARIS</option>
                                </select>
                                <div id="error-jenis_pekerjaan" class="text-danger"></div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="">Nama Pemohon</label>
                                <input type="text" name="nama_pemohon" id="nama_pemohon"
                                    class="form-control text-capitalize">
                                <div id="error-nama_pemohon" class="text-danger"></div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="">No. Akta</label>
                                <input type="number" name="no_akta" id="no_akta" class="form-control">
                                <div id="error-no_akta" class="text-danger"></div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="">Proses Permohonan</label>
                                <input type="text" name="proses_permohonan" id="proses_permohonan" class="form-control">
                                <div id="error-proses_permohonan" class="text-danger"></div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="">Nama Bank</label>
                                <input type="text" name="bank_name" id="bank_name" class="form-control">
                                <div id="error-bank_name" class="text-danger"></div>
                            </div>

                            <div class="mb-3">
                                <label for="" class="">Document</label>
                                <input type="file" name="document[]" id="document" class="form-control" multiple>
                                <div id="error-document" class="text-danger"></div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="">Nama Penanggung Jawab</label>
                                <select name="" id="penanggung_jawab_id" class="form-control text-capitalize">
                                    <option value="">Pilih Salah Satu</option>
                                    @foreach ($penanggung_jawab as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->nama_penanggung_jawab }}</option>
                                    @endforeach
                                </select>
                                <div id="error-penanggung_jawab_id" class="text-danger"></div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" cols="30" rows="10" class="form-control"></textarea>
                                <div id="error-keterangan" class="text-danger"></div>
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
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                    }, {
                        name: 'created_at',
                        data: 'created_at',
                        render: function(data) {
                            var date = new Date(data)
                            let month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                                "Juli", "Agustus", "September", "Oktober", "November",
                                "Desember"
                            ]
                            let m = month[date.getMonth()];
                            let d = date.getDate();
                            let y = date.getFullYear();

                            return "<span >" + d + " " + m + " " + y + "</span>"
                        }

                    }, {
                        data: 'jenis_pekerjaan',
                        name: 'jenis_pekerjaan',
                    }, {
                        data: 'no_akta',
                        name: 'no_akta',
                    }, {
                        data: 'proses_permohonan',
                        name: 'proses_permohonan',
                    }, {
                        data: 'bank_name',
                        name: 'bank_name',
                    }, {
                        name: 'nama_pemohon',
                        data: 'nama_pemohon',

                    }, {
                        name: 'keterangan',
                        data: 'keterangan',

                    }, {
                        name: 'document',
                        data: 'document',

                    }, {
                        name: 'penanggung_jawab.nama_penanggung_jawab',
                        data: 'penanggung_jawab.nama_penanggung_jawab',

                    },
                    {
                        name: 'proses_sertifikat',
                        data: 'proses_sertifikat',
                        render: function(data) {
                            return "<div class='masuk'><span>" + data + "</span></div>"
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
                table.columns(11).search(val).draw();
            });
            $('#select_penanggung_jawab').change(function(e) {
                var val = $(this).val();
                e.preventDefault();
                table.columns(10).search(val).draw();
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
                        $('#no_akta').val(response.success.no_akta);
                        $('#jenis_pekerjaan').val(response.success
                            .jenis_pekerjaan);
                        $('#proses_permohonan').val(response.success.proses_permohonan);
                        $('#document').val(response.success.document);
                        $('#bank_name').val(response.success.bank_name);
                        $('#keterangan').val(response.success.keterangan);
                    },
                });
                $('.submit').off('click');
                $('.submit').click(function(e) {
                    e.preventDefault();
                    var formData = new FormData();
                    formData.append('penanggung_jawab_id', $('#penanggung_jawab_id').val());
                    formData.append('nama_pemohon', $('#nama_pemohon').val());
                    formData.append('no_akta', $('#no_akta').val());
                    formData.append('jenis_pekerjaan', $('#jenis_pekerjaan').val());
                    formData.append('proses_permohonan', $('#proses_permohonan').val());
                    formData.append('bank_name', $('#bank_name').val());
                    formData.append('keterangan', $('#keterangan').val());
                    var file = $('#document')[0].files;
                    for (i = 0; i < file.length; i++) {
                        formData.append('document[]', $('#document')[0].files[i]);
                    }
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
                                    .penanggung_jawab_id);
                                $('#error-jenis_pekerjaan').text(response[1].gambar);
                                $('#error-document').text(response[1].document);
                            } else {
                                $('#penanggung_jawab_id').val('');
                                $('#nama_pemohon').val('');
                                $('#no_akta').val('');
                                $('#proses_permohonan').val('');
                                $('#jenis_pekerjaan').val("");
                                $('#document').val("");
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
