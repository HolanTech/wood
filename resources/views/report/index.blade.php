@extends('layouts.presensi')
@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"> </ion-icon>
            </a>
            <div class="pagetitle">Laporan Penjualan</div>
            <div class="right"></div>
        </div>
    </div>
@endsection
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

    <div class="row ">
        <div class="col-12 mb-0">
            <div class="card p-3 m-0">
                <span></span>
            </div>
        </div>

        <div class="col-12 mt-0">
            <div class="card m-0">
                @foreach ($data as $d)
                    <a href="/report.show.{{ $d->po_out_id }}" style="text-decoration: none">

                        <ul class="listview image-listview">
                            <li>
                                <div class="item">
                                    <div class="in">
                                        <div class="col-6">
                                            <b>{{ date('d-m-Y', strtotime($d->tanggal)) }}
                                            </b>
                                        </div>
                                        <div class="col-6">
                                            <b>{{ $d->po_out_id }}
                                            </b>
                                        </div>

                                    </div>
                                </div>
                            </li>
                        </ul>
                    </a>
                @endforeach
            </div>
        </div>

    </div>
    {{-- 
    <div class="fab-button bottom-right" style="margin-bottom: 70px;">
        <a href="/kas.create" class="fab"><ion-icon name="add-outline"></ion-icon></a>
    </div> --}}
@endsection
