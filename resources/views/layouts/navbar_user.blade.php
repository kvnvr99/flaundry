
<div class="navbar-custom">
    <div class="container-fluid">
        <ul class="list-unstyled topnav-menu float-right mb-0">

            <li class="dropdown d-inline-block d-lg-none">
                <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="fe-search noti-icon"></i>
                </a>
                <div class="dropdown-menu dropdown-lg dropdown-menu-right p-0">
                    <form class="p-3">
                        <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                    </form>
                </div>
            </li>

            <li class="dropdown d-none d-lg-inline-block">
                <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen" href="#">
                    <i class="fe-maximize noti-icon"></i>
                </a>
            </li>

            <li class="dropdown notification-list topbar-dropdown">
                <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="../assets/images/laundry/user.png" alt="user-image" class="rounded-circle">
                    <span class="pro-user-name ml-1">
                        Hi, {{ Auth::user()->name }} <i class="mdi mdi-chevron-down"></i> 
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    <!-- item-->
                    <div class="dropdown-divider"></div>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user"></i>
                        <span>Akun Saya</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <!-- item-->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="dropdown-item notify-item text-white bg-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fe-log-out"></i>
                            <span>Logout</span>
                        </a>
                    </form>

                    <div class="dropdown-divider"></div>

                </div>
            </li>

        </ul>

        <!-- LOGO -->
        <div class="logo-box">
            <a href="{{ route('home') }}" class="logo logo-light text-center">
                <span class="logo-sm">
                    <img src="../assets/images/laundry/fruits.png" alt="" height="40">
                </span>
                <span class="logo-lg">
                    <img src="../assets/images/laundry/fruits.png" alt="" height="40">&nbsp; &nbsp; &nbsp;<label style="color:white;">Fruits Laundry</label>
                </span>
            </a>
        </div>

        <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
            <li>
                <button class="button-menu-mobile waves-effect waves-light">
                    <i class="fe-menu"></i>
                </button>
            </li>

            <li>
                <!-- Mobile menu toggle (Horizontal Layout)-->
                <a class="navbar-toggle nav-link" data-toggle="collapse" data-target="#topnav-menu-content">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
                <!-- End mobile menu toggle-->
            </li>   

            <!-- <li class="dropdown dropdown-mega d-none d-xl-block">
                <a class="nav-link dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    Lihat Promo
                    <i class="mdi mdi-chevron-down"></i> 
                </a>
                <div class="dropdown-menu dropdown-megamenu">
                    <div class="row">
                        <div class="col-sm-8">

                        </div>
                        <div class="col-sm-12">
                            <div class="text-center mt-3">
                                <h3 class="text-dark">Potongan diskon untuk user baru</h3>
                                <h4>potongan harga hingga 50%</h4>
                                <button class="btn btn-primary btn-rounded mt-3">Download Now</button>
                            </div>
                        </div>
                    </div>

                </div>
            </li> -->
        </ul>
        <div class="clearfix"></div>
    </div>
</div>
<!-- end Topbar -->

<div class="topnav" style="border-radius: 0px 0px 25px 25px;">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="{{ route('home') }}" role="button">
                            <i class="fe-home mr-1"></i> Home
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" role="button">
                            <i class="fe-list mr-1"></i> Transaksi <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-apps">

                            <a class="dropdown-item" href="{{ route('permintaan-laundry.create') }}"><i class="fe-message-circle"></i>&ensp;&ensp; Buat Pesanan Baru</a>
                            <a class="dropdown-item" href="{{ route('permintaan-laundry') }}"><i class="fe-file-text"></i>&ensp;&ensp; List Transaksi</a>
                            <a class="dropdown-item" href="{{ route('history-laundry') }}"><i class="fe-clock"></i>&ensp;&ensp; Lihat History Pemesanan</a>
                            
                        </div>
                    </li>

                </ul> <!-- end navbar-->
            </div> <!-- end .collapsed-->
        </nav>
    </div> <!-- end container-fluid -->
</div> <!-- end topnav-->

<!-- ============================================================== -->
<!-- Start Page Content here -->
<!-- ============================================================== -->