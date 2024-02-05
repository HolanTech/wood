@extends('layouts.presensi')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"> </ion-icon>
        </a>
        <div class="pagetitle">Pengarajin</div>
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
                @foreach ($pengrajin as $d)
                    <ul class="listview image-listview">
                        <li>
                            <div class="item">
                                <div class="in">

                                    <div class="">
                                        <div class="image-container">
                                            @php
                                                $path = Storage::url('uploads/pengrajin/' . $d->foto);
                                            @endphp
                                            @if (empty($d->foto))
                                                <img src="{{ asset('assets/img/avatar.png') }}" class="imaged w64 rounded"
                                                    alt="">
                                            @else
                                                <img src="{{ url($path) }}" class="imaged w64 rounded" alt="">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="content">

                                            <small
                                                class="text-muted">{{ $d->badan_hukum == 0 ? 'Perusahaan' : 'Perorangan' }}</small><br>
                                            <strong>{{ $d->nama }}</strong><br>
                                            <span>{{ $d->email }}</span><br>
                                            <span>{{ $d->no_hp }}</span><br>
                                            <small class="text-muted">{{ $d->alamat }}</small>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="btn-group d-flex flex-column">
                                            <a href="/pengrajin.{{ $d->id }}.edit" class="edit btn btn-info btn-sm"
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
                                            <form action="/pengrajin.{{ $d->id }}.delete" method="post"
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
        <a href="/pengrajin.create" class="fab"><ion-icon name="add-outline"></ion-icon></a>
    </div>
    </div>
    {{-- modal edit --}}
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#Table_ID').DataTable({
                responsive: true
            });
        });

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
