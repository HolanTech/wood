@extends('layouts.presensi')
@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">

    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"> </ion-icon>
            </a>
            <div class="pagetitle">Edit Pengrajin</div>
            <div class="right"></div>
        </div>
    </div>
@endsection

@section('content')
    <div style="margin-top: 5rem">
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
    <form action="/pengrajin.{{ $pengrajin->id }}.update" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-1">
                <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-scan"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M10 9a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                            <path d="M4 8v-2a2 2 0 0 1 2 -2h2" />
                            <path d="M4 16v2a2 2 0 0 0 2 2h2" />
                            <path d="M16 4h2a2 2 0 0 1 2 2v2" />
                            <path d="M16 20h2a2 2 0 0 0 2 -2v-2" />
                            <path d="M8 16a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="col-11">
                <select type="text" name="badan_hukum" id="badan_hukum" class="form-select select2" required>
                    <option value="0" {{ $pengrajin->badan_hukum == 0 ? 'selected' : '' }}>Perusahaan</option>
                    <option value="1" {{ $pengrajin->badan_hukum == 1 ? 'selected' : '' }}>Perorangan</option>
                </select>

            </div>
        </div>
        <div class="row">
            <div class="col-1">
                <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-scan"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M10 9a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                            <path d="M4 8v-2a2 2 0 0 1 2 -2h2" />
                            <path d="M4 16v2a2 2 0 0 0 2 2h2" />
                            <path d="M16 4h2a2 2 0 0 1 2 2v2" />
                            <path d="M16 20h2a2 2 0 0 0 2 -2v-2" />
                            <path d="M8 16a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="col-11">
                <input type="text" value="{{ $pengrajin->nama }}" class="form-control" name="nama"
                    placeholder="Nama Lengkap" required>
            </div>
        </div>
        <div class="row">
            <div class="col-1">
                <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-id" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                            <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M15 8l2 0" />
                            <path d="M15 12l2 0" />
                            <path d="M7 16l10 0" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="col-11">
                <input type="text" value="{{ $pengrajin->email }}" class="form-control" name="email"
                    placeholder="E-mail">
            </div>
        </div>

        <div class="row">
            <div class="col-1">
                <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-phone" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="col-11">
                <input type="text" value="{{ $pengrajin->no_hp }}" class="form-control" name="no_hp"
                    placeholder="Nomor Handphone" required>
            </div>
        </div>
        </div>

        <div class="row">
            <div class="col-1">
                <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-hexagon"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 13a3 3 0 1 0 0 -6a3 3 0 0 0 0 6z" />
                            <path d="M6.201 18.744a4 4 0 0 1 3.799 -2.744h4a4 4 0 0 1 3.798 2.741" />
                            <path
                                d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="col-11">
                <input type="text" value="{{ $pengrajin->alamat }}" class="form-control" name="alamat"
                    placeholder="Alamat" required>
            </div>

        </div>
        </div>

        <div class="row">
            <div class="col-1">
                <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-photo-scan"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M15 8h.01" />
                            <path d="M6 13l2.644 -2.644a1.21 1.21 0 0 1 1.712 0l3.644 3.644" />
                            <path d="M13 13l1.644 -1.644a1.21 1.21 0 0 1 1.712 0l1.644 1.644" />
                            <path d="M4 8v-2a2 2 0 0 1 2 -2h2" />
                            <path d="M4 16v2a2 2 0 0 0 2 2h2" />
                            <path d="M16 4h2a2 2 0 0 1 2 2v2" />
                            <path d="M16 20h2a2 2 0 0 0 2 -2v-2" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="col-11">
                <input type="file" value="" class="form-control" name="foto">
                <input type="hidden" value="{{ $pengrajin->foto }}" class="form-control" name="old_foto">

            </div>
        </div>

        <div class="col-12">
            <div class="modal-footer">
                {{-- <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button> --}}
                <button type="submit" class="btn btn-primary w-100">Simpan</button>
            </div>
        </div>
        </div>
    </form>
@endsection
