@extends('layouts.dashboard')
@section('content')
    <div class="container card py-5">
        <div class="mb-3 d-flex gap-2">
            <div>
                <button type="button" class="btn btn-danger" id="hapus" onclick="hapus()" disabled>Hapus</button>
            </div>
            <button type="button" class="btn btn-primary" id="tambah">Tambah</button>
        </div>
        <table id="myTable" class="table table-hover">
            <thead>
                <tr>
                    <th><input type="checkbox" name="" id="head-cb"></th>
                    <th>Nama</th>
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
                                <label for="" class="">Nama</label>
                                <input type="text" name="nama_penanggung_jawab" id="nama_penanggung_jawab"
                                    class="form-control">
                                <div id="error-nama_penanggung_jawab" class="text-danger"></div>
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
                processing: true,
                serverside: true,
                ajax: '/data-penanggung',
                columns: [{
                    name: 'checkbox',
                    data: 'checkbox'
                }, {
                    name: 'nama_penanggung_jawab',
                    data: 'nama_penanggung_jawab',
                    render: function(data) {
                        return "<span class='text-capitalize '>" + data + "</span>"
                    }
                }, {
                    name: 'action',
                    data: 'action',

                }]
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


            $('#tambah').click(function(e) {
                e.preventDefault();
                $('#tambah-modal').modal('show');
                $('.submit').off('click');
                $('.submit').click(function(e) {
                    e.preventDefault();
                    var formData = new FormData();
                    formData.append('nama_penanggung_jawab', $('#nama_penanggung_jawab').val());
                    $.ajax({
                        url: '/penanggung-jawab',
                        contentType: false,
                        processData: false,
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            if (response.error) {
                                $('#error-nama_penanggung_jawab').text(response.error
                                    .nama_penanggung_jawab);
                            } else {
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
                                $('#nama_penanggung_jawab').val("");
                            }
                        }
                    });
                })
            });

            $('body').on('click', '.edit', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                $.ajax({
                    url: 'penanggung-jawab/' + id,
                    type: 'GET',
                    success: function(response) {
                        $('#tambah-modal').modal('show');
                        $('#nama_penanggung_jawab').val(response.result.nama_penanggung_jawab);
                    }
                })

                $('.submit').off('click');
                $('.submit').click(function(e) {
                    e.preventDefault();
                    var formData = new FormData();
                    formData.append('nama_penanggung_jawab', $('#nama_penanggung_jawab').val());
                    console.log(formData);
                    $.ajax({
                        url: 'penanggung/' + id,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            console.log(response)
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
                            $('#nama_penanggung_jawab').val("");
                        }
                    })
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
                            url: "{{ url('penanggung-jawab') }}" + '/' + id,
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
                            url: "{{ url('penanggung-jawab') }}" + '/' + all_checked[i],
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
