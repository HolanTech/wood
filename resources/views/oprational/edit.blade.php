@extends('layouts.presensi')
@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
    @style
        <style>
            .datepicker-oprational {
                max-height: 460px !important;
            }

            .datepicker-date-display {
                background-color: rgb(44, 44, 243) !important;
            }

            ::placeholder {
                color: black;
            }
        </style>
    @endstyle
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"> </ion-icon>
            </a>
            <div class="pagetitle">Edit Oprational</div>
            <div class="right"></div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row" style="margin-top:70px;">
        <div class="col">
            <form action="/oprational.{{ $oprational->id }}.update" method="post" id="frmoprational">
                @csrf
                @method('put')
                <div class="form-group">
                    <input type="text" id="tanggal" name="tanggal" class="form-control datepicker"
                        placeholder="Tanggal" value="{{ $oprational->tanggal }}">
                </div>

                <div class="form-group">
                    <select name="kas" id="kas" class="form-control">
                        <option value="">Kas</option>
                        <option value="d" {{ $oprational->debet > 0 ? 'selected' : '' }}>Debet</option>
                        <option value="c" {{ $oprational->credit > 0 ? 'selected' : '' }}>Credit</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" value="{{ $oprational->debet > 0 ? $oprational->debet : $oprational->credit }}"
                        id="nominal" name="nominal" placeholder="Nominal">
                </div>
                <div class="form-group">
                    <input type="text" value="{{ $oprational->po_id }}" name="po_id" id="po_id"
                        placeholder="Nomor PO">
                </div>
                <div class="form-group">
                    <input type="text" value="{{ $oprational->ket }}" name="ket" id="ket"
                        placeholder="Keterangan">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary w-100">Kirim</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
    <script>
        var currYear = (new Date()).getFullYear();

        $(document).ready(function() {
            $(".datepicker").datepicker({
                format: "yyyy/mm/dd"
            });

            $("#tanggal").change(function(e) {
                var tanggal = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '/oprational.store',
                    data: {
                        _token: "{{ csrf_token() }}",
                        tanggal: tanggal
                    },
                    cache: false,
                    success: function(respond) {
                        if (respond == 1) {
                            Swal.fire({
                                title: 'Ooops!',
                                text: 'Anda Sudah Melakukan Input oprational Pada Tanggal tersebut',
                                icon: 'warning',
                            }).then((result) => {
                                $("#tanggal").val("");
                            })
                        }
                    }
                });
            });

            $("#frmoprational").submit(function() {
                var tanggal = $("#tanggal").val();
                var status = $("#status").val();
                var keterangan = $("#keterangan").val();

                if (tanggal == "") {
                    Swal.fire({
                        title: 'Ooops!',
                        text: 'Tanggal Harus Di isi',
                        icon: 'warning',
                    });
                    return false;
                } else if (status == "") {
                    Swal.fire({
                        title: 'Ooops!',
                        text: 'Status Harus Di isi',
                        icon: 'warning',
                    });
                    return false;
                } else if (keterangan == "") {
                    Swal.fire({
                        title: 'Ooops!',
                        text: 'Keterangan Harus Di isi',
                        icon: 'warning',
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
