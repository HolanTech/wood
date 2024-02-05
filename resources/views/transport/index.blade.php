@extends('layouts.presensi')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"> </ion-icon>
        </a>
        <div class="pagetitle">Data Transport</div>
        <div class="right"></div>
    </div>
</div>
@section('content')
    <div style="margin-top: 4rem">
        <div class="col">
            @php
                $messageSuccess = Session::get('success');
                $messageError = Session::get('error');
            @endphp
            @if ($messageSuccess)
                <div class="alert alert-success">
                    {{ $messageSuccess }}
                </div>
            @elseif ($messageError)
                <div class="alert alert-danger">
                    {{ $messageError }}
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-12 m-1">
            <div class="card p-3">
                @foreach ($transport as $transportItem)
                    <ul class="listview image-listview">
                        <li>
                            <div class="item">
                                <div class="in">
                                    <div class="">
                                        <div class="image-container">
                                            @php
                                                $path = Storage::url("uploads/transport/{$transportItem->file}");
                                            @endphp
                                            @if (empty($transportItem->file))
                                                <img src="{{ asset('assets/img/avatar.png') }}" class="imaged w64 rounded"
                                                    alt="">
                                            @else
                                                <a href="javascript:void(0)" class="text-info" id="file"
                                                    onclick="viewFile('{{ $transportItem->file }}')" data-toggle="modal"
                                                    data-target="#modalFile">
                                                    <img src="{{ url($path) }}" class="imaged w64 rounded"
                                                        alt="">
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="content">
                                            <span>{{ $transportItem->tanggal }}</span><br>
                                            <strong>{{ $transportItem->po_out_id }}</strong><br>
                                            <span>{{ $transportItem->expedisi_nama }}</span><br>
                                            <span>{{ $transportItem->customer_nama }}</span><br>
                                            <span>{{ $transportItem->qty }}</span><br>
                                            <span>{{ $transportItem->biaya }}</span><br>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="btn-group d-flex flex-column">
                                            <a href="/transport.{{ $transportItem->id }}.edit"
                                                class="edit btn btn-info btn-sm">
                                                <ion-icon name="create-outline"></ion-icon>
                                            </a>
                                            <form action="/transport.{{ $transportItem->id }}.delete" method="post"
                                                style="margin-top: 5px;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm delete-confirm">
                                                    <ion-icon name="trash-outline"></ion-icon>
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
        <a href="/transport.create" class="fab"><ion-icon name="add-outline"></ion-icon></a>
    </div>

    <!-- Modal File In -->
    <div class="modal fade" id="modalFile" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="max-width: max-content;">
                <div class="modal-header">
                    <h4 class="modal-title" id="vcenter">Transport</h4>
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
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#Table_ID').DataTable({
                responsive: true
            });
        });

        function viewFile(data) {
            let url = window.location.origin + '/storage/uploads/transport/' + data;
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
            });
        });
    </script>
@endpush
