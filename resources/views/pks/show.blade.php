@extends('layouts.presensi')

<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
        <div class="pagetitle">Detail Perjanjian KerjaSama</div>
        <div class="right"></div>
    </div>
</div>

@section('content')
    <div class="row">
        <div class="col-12 m-1">
            <div class="card p-3">
                <h3>{{ $pks->file }}</h3>
                <div class="text-center">
                    <iframe src="{{ Storage::url('uploads/pks/' . $pks->file) }}" width="800" height="600"
                        frameborder="0"></iframe>
                </div>
                <div class="mt-3">
                    <a href="/pks.index" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection
