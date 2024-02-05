@extends('layouts.presensi')
@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">

    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"> </ion-icon>
            </a>
            <div class="pagetitle">Edit Data Pembelian</div>
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
    <form action="/beli.{{ $beli->id }}.update" method="POST" enctype="multipart/form-data">
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
                <div class="form-group">
                    <input type="text" id="tanggal" name="tanggal" class="form-control datepicker"
                        value="{{ $beli->tanggal }}">
                </div>
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
                <select name="po_out_id" id="po_out_id" class="form-select select2" required>
                    <option value="">Pilih PO Keluar</option>
                    @foreach ($po_out as $d)
                        <option {{ $beli->po_out_id == $d->po_out ? 'selected' : '' }} value="{{ $d->po_out }}">
                            {{ $d->po_out }}
                        </option>
                    @endforeach
                </select>
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
                <select type="text" name="pengrajin_id" id="pengrajin_id" class="form-select select2" required>
                    <option value="">Pilih Pengrajin</option>
                    @foreach ($pengrajin as $pengrajin)
                        <option {{ $beli->pengrajin_id == $pengrajin->id ? 'selected' : '' }} value="{{ $pengrajin->id }}">
                            {{ $pengrajin->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-1">
                <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shopping-cart"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M17 17h-11v-14h-2" />
                            <path d="M6 5l14 1l-1 7h-13" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="col-11">
                <input type="text" value="{{ $beli->order }}" class="form-control" name="order"
                    placeholder="Pesanan" required>
            </div>
        </div>
        </div>

        <div class="row">
            <div class="col-1">
                <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-box" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                            <path d="M12 12l8 -4.5" />
                            <path d="M12 12l0 9" />
                            <path d="M12 12l-8 -4.5" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="col-11">
                <input type="text" value="{{ $beli->qty }}" class="form-control" name="qty"
                    placeholder="Jumlah" required>
            </div>

        </div>
        <div class="row">
            <div class="col-1">
                <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-coin" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M14.8 9a2 2 0 0 0 -1.8 -1h-2a2 2 0 1 0 0 4h2a2 2 0 1 1 0 4h-2a2 2 0 0 1 -1.8 -1" />
                            <path d="M12 7v10" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="col-11">
                <input type="text" value="{{ $beli->harga }}" class="form-control" name="harga"
                    placeholder="Harga" required>
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
                <input type="file" value="" class="form-control" name="file">
                <input type="hidden" value="{{ $beli->file }}" class="form-control" name="old_file">

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
