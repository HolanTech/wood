@extends('layouts.presensi')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"> </ion-icon>
        </a>
        <div class="pagetitle">PO_Keluar</div>
        <div class="right"></div>
    </div>
</div>
@section('content')
    <div style="margin-top: 4rem">
        <div class="col">
            @php
                $massagesuccess = Session::get('success');
                $massageerror = Session::get('error');
            @endphp
            @if ($massagesuccess)
                <div class="alert alert-success">
                    {{ $massagesuccess }}
                </div>
            @elseif ($massageerror)
                <div class="alert alert-danger">
                    {{ $massageerror }}
                </div>
            @endif
        </div>
    </div>


    <div class="row">
        <div class="col-12 m-1">
            <div class="card p-3">
                @foreach ($po_out as $d)
                    <ul class="listview image-listview">
                        <li>
                            <div class="item">
                                <div class="in">

                                    <div class="">
                                        <div class="image-container">
                                            @php
                                                $path = Storage::url('uploads/po_out/' . $d->file);
                                            @endphp
                                            @if (empty($d->file))
                                                <img src="{{ asset('assets/img/avatar.png') }}" class="imaged w64 rounded"
                                                    alt="">
                                            @else
                                                <a href="javascript:void(0)" class="text-info" id="file"
                                                    onclick="viewFile('{{ $d->file }}')" data-toggle="modal"
                                                    data-target="#modalFile">
                                                    <img src="{{ url($path) }}" class="imaged w64 " alt="">
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="content">
                                            <strong>{{ $d->po_out }}</strong><br>
                                            <span>{{ $d->nama }}</span><br>
                                            <span>{{ $d->order }}</span><br>
                                            <span>{{ $d->qty }}</span><br>
                                            <span>{{ $d->harga }}</span><br>
                                            {{-- <small class="text-muted">{{ $d->alamat }}</small> --}}
                                        </div>

                                    </div>
                                    <div class="">
                                        <div class="btn-group d-flex flex-column">
                                            <a href="/po_out.{{ $d->po_out }}.edit" class="edit btn btn-info btn-sm"
                                                nik="">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-edit" width="24" height="24"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                    <path
                                                        d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                    <path d="M16 5l3 3" />
                                                </svg>
                                            </a>
                                            <form action="/po_out.{{ $d->po_out }}.delete" method="post"
                                                style="margin-top: 5px;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm delete-confirm">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-trash" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M4 7l16 0" />
                                                        <path d="M10 11l0 6" />
                                                        <path d="M14 11l0 6" />
                                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </li>
                    </ul>
                @endforeach

            </div>
        </div>
    </div>


    <div class="fab-button bottom-right" style="margin-bottom: 70px;">
        <a href="/po_out.create" class="fab"><ion-icon name="add-outline"></ion-icon></a>
    </div>
    {{-- modal  --}}
    <div class="modal fade" id="modalFile" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="max-width: max-content; ">
                <div class="modal-header">
                    <h4 class="modal-title" id="vcenter">Po Keluar</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <embed src="" id="embed-file" width="100%" height="100%" alt="pdf" />
                </div>
            </div>
        </div>
    </div>
    {{-- modal  --}}
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#Table_ID').DataTable({
                responsive: true
            });
        });

        function viewFile(data) {
            let url = window.location.origin + '/storage/uploads/po_out/' + data;
            $('#embed-file').attr('src', url);
        }
        $(function() {

            $(".delete-confirm").click(function(e) {
                var form = $(this).closest('form');
                e.preventDefault();
                console.log("Button clicked!");
                Swal.fire({
                    title: "Apakah Anda Yakin?",
                    text: "Data Ini Akan Terhapus Dari Database Secara Permanen!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Hapus !"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                        console.log("Form submitted!");
                        Swal.fire({
                            title: "Deleted!",
                            text: "Data Berhasil Di Hapus",
                            icon: "Berhasil"
                        });
                    }
                });
            })
        });
    </script>
@endpush
