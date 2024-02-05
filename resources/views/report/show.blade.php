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
    </div>

    <div class="row ">


        <div class="col-12 mt-0">
            <div class="card m-0">
                @foreach ($data as $d)
                    <ul class="listview image-listview">
                        <li>
                            <div class="item">
                                <div class="in">
                                    <div class="col-6">
                                        <span>Tanggal</span>
                                    </div>
                                    <div class="col-6">
                                        <b>: {{ date('d-m-Y', strtotime($d->tanggal)) }}</b>
                                    </div>

                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item">
                                <div class="in">
                                    <div class="col-6">
                                        <span>No PO Masuk</span>
                                    </div>
                                    <div class="col-6">
                                        <b>: {{ $d->po_in_id }}</b>
                                    </div>


                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item">
                                <div class="in">
                                    <div class="col-6">
                                        <span>Customer</span>
                                    </div>
                                    <div class="col-6">
                                        <b>: {{ $d->customer_nama }}</b>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item">
                                <div class="in">
                                    <div class="col-6">
                                        <span>Harga Jual Seharusnya</span>
                                    </div>
                                    <div class="col-6">
                                        <b>: Rp.{{ number_format($d->seharusnya, 2, ',', '.') }}</b>
                                    </div>


                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item">
                                <div class="in">
                                    <div class="col-6">
                                        <span>Pembayaran Seharusnya Hasil QC</span>
                                    </div>
                                    <div class="col-6">
                                        <b>: Rp.{{ number_format($d->hargaqc, 2, ',', '.') }}</b>
                                    </div>


                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item">
                                <div class="in">
                                    <div class="col-6">
                                        <span>Pembayaran Aktual</span>
                                    </div>
                                    <div class="col-6">
                                        <b>: Rp.{{ number_format($d->harga, 2, ',', '.') }}</b>
                                    </div>


                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item">
                                <div class="in">
                                    <div class="col-6">
                                        <span>Pesanan</span>
                                    </div>
                                    <div class="col-6">
                                        <b>: {{ $d->order }}</b>
                                    </div>


                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item">
                                <div class="in">
                                    <div class="col-6">
                                        <span>Jumlah</span>
                                    </div>
                                    <div class="col-6">
                                        <b>: {{ $d->qty }}</b>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li>
                            <div class="item">
                                <div class="in">
                                    <div class="col-6">
                                        <span>No PO Keluar</span>
                                    </div>
                                    <div class="col-6">
                                        <b>: {{ $d->po_out_id }}</b>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item">
                                <div class="in">
                                    <div class="col-6">
                                        <span>Pengrajin</span>
                                    </div>
                                    <div class="col-6">
                                        <b>: {{ $d->pengrajin_nama }}</b>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item">
                                <div class="in">
                                    <div class="col-6">
                                        <span>Harga Beli</span>
                                    </div>
                                    <div class="col-6">
                                        <b>: Rp.{{ number_format($d->harga_beli, 2, ',', '.') }}</b>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item">
                                <div class="in">
                                    <div class="col-6">
                                        <span>Transportasi</span>
                                    </div>
                                    <div class="col-6">
                                        <b>: {{ $d->expedisi_nama }}</b>
                                    </div>


                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item">
                                <div class="in">
                                    <div class="col-6">
                                        <span>Ongkos Kirim</span>
                                    </div>
                                    <div class="col-6">
                                        <b>: Rp.{{ number_format($d->transport, 2, ',', '.') }}</b>
                                    </div>


                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item">
                                <div class="in">
                                    <div class="col-6">
                                        <span>Biaya Oprational</span>
                                    </div>
                                    <div class="col-6">
                                        <b>: Rp.{{ number_format($d->oprational, 2, ',', '.') }}</b>
                                    </div>


                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item">
                                <div class="in">
                                    <div class="col-6">
                                        <span>Hasil Bersih</span>
                                    </div>
                                    <div class="col-6">
                                        <b>: Rp.{{ number_format($d->hasil, 2, ',', '.') }}</b>
                                    </div>


                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item">
                                <div class="in">
                                    <div class="col-6">
                                        <span>Untuk Yatim</span>
                                    </div>
                                    <div class="col-6">
                                        <b>: Rp.{{ number_format($d->yatim, 2, ',', '.') }}</b>
                                    </div>


                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item">
                                <div class="in">
                                    <div class="col-6">
                                        <span>Untuk Karyawan </span>
                                    </div>
                                    <div class="col-6">
                                        <b>: Rp.{{ number_format($d->laba, 2, ',', '.') }}</b>
                                    </div>


                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item">
                                <div class="in">
                                    <div class="col-6">
                                        <span>Bagi {{ $karyawan }}</span>
                                    </div>
                                    <div class="col-6">
                                        <b>: Rp.{{ number_format($d->laba / $karyawan, 2, ',', '.') }}</b>
                                    </div>


                                </div>
                            </div>
                        </li>
                    </ul>
                @endforeach


            </div>
        </div>

    </div>
    <div style="margin-bottom: 8rem">
    </div>
@endsection
