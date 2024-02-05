@extends('layouts.presensi')
@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"> </ion-icon>
            </a>
            <div class="pagetitle">Pembagian Kas</div>
            <div class="right"></div>
        </div>
    </div>
@endsection
@section('content')
    <div style="margin-top: 4rem">
        @foreach ($data as $d)
            <div class="row ">
                <div class="col-12 mb-0">
                    <div class="card p-3 m-0">
                        <span>Penerimaan {{ $d->ket }}</span>
                        <span>tanggal :{{ date('d-m-Y', strtotime($d->tanggal)) }}</span>
                        <h3>Rp.{{ number_format($d->laba, 2, ',', '.') }}</h3>
                    </div>
                </div>

                <div class="col-12 mt-0">
                    <div class="card m-0">
                        @foreach ($karyawan as $k)
                            <ul class="listview image-listview">
                                <li>
                                    <div class="item">
                                        <div class="in">
                                            <div class="col-6">
                                                <small class="text-muted">{{ $k->nama_lengkap }}</small>
                                                <b> </b>
                                            </div>
                                            <div class="col-6">
                                                <span
                                                    class="badge bg-success text-light">Rp.{{ number_format($d->perorang, 2, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
