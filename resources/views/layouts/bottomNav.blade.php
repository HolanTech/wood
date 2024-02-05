<div class="appBottomMenu">
    <a href="/home" class="item {{ request()->is('home') ? 'active' : '' }}">
        <div class="col">

            <ion-icon name="bar-chart-outline" role="img" class="md hydrated" aria-label="home-outline"></ion-icon>
            <strong>Dashboard</strong>
        </div>
    </a>
    <a href="/kasyatim" class="item {{ request()->is('kasyatim') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="document-attach-outline"></ion-icon>
            <strong>Kas Yatim</strong>
        </div>
    </a>
    {{-- <a href="/dashboard" class="item {{ request()->is('dashboard') ? 'active' : '' }}">
        <div class="col">
            <div class="action-button large">
                <ion-icon name="home-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
            </div>
        </div>
    </a> --}}
    <a href="/dashboard" class="item {{ request()->is('dashboard') ? 'active' : '' }}">
        <div class="col">
            <div class="action-button large">
                <ion-icon name="home-outline">Home</ion-icon>
            </div>
        </div>
    </a>
    <a href="/kas" class="item {{ request()->is('kas') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="document-text-outline"></ion-icon>
            <strong>Kas Karyawan</strong>
        </div>
    </a>
    <a href="/editprofile" class="item {{ request()->is('editprofile') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="people-outline" role="img" class="md hydrated" aria-label="people outline"></ion-icon>
            <strong>Profile</strong>
        </div>
    </a>
</div>
