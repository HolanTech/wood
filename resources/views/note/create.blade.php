@extends('layouts.presensi')
@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"> </ion-icon>
            </a>
            <div class="pagetitle">Buat Catatan Baru</div>
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
    <div class="container">

        <form action="/note.store" method="post">
            @csrf
            <div class="form-group">

                <input type="text" name="title" id="title" class="form-control" placeholder="  masukkan judul"
                    required>
            </div>
            <div class="form-group">

                <textarea name="content" id="content" class="form-control" rows="15" placeholder="Silahan tulis catatan"
                    required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Catatan</button>
        </form>
    </div>
@endsection
