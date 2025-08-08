<div class="container">

    @php
        $currentCabang = request()->segment(1); // Mengambil segment pertama dari URL
    @endphp
    <ul>



        <li class="menu-item {{ request()->is($currentCabang . '/dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard', ['cabang' => $currentCabang]) }}" class='menu-link'>
                <span><i class="bi bi-grid-fill"></i> Dashboard</span>
            </a>
        </li>



        @if (Auth::user()->divisi == 'admin' || Auth::user()->divisi == 'purchasing')
            <li class="menu-item  has-sub {{ request()->is('master/*') ? 'active' : '' }}">
                <a href="#" class='menu-link'>
                    <span><i class="bi bi-stack"></i> Master</span>
                </a>
                <div class="submenu ">
                    <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
                    <div class="submenu-group-wrapper">


                        <ul class="submenu-group ">

                            <li class="submenu-item menu-item {{ request()->is('master/barang') ? 'active' : '' }} ">
                                <a href="{{ route('barang', ['cabang' => $currentCabang]) }}"
                                    class='submenu-link'>Master
                                    Barang / Menu</a>
                            </li>
                            <li class="submenu-item  {{ request()->is('master/bahan') ? 'active' : '' }}">
                                <a href="{{ route('bahan', ['cabang' => $currentCabang]) }}" class='submenu-link'>Master
                                    Bahan</a>
                            </li>
                            <li class="submenu-item  {{ request()->is('master/bahanOlah') ? 'active' : '' }}">
                                <a href="{{ route('bahanOlah', ['cabang' => $currentCabang]) }}"
                                    class='submenu-link'>Master
                                    Barang Olah / Menu yang diolah</a>
                            </li>
                            <li class="submenu-item  {{ request()->is('master/supplier') ? 'active' : '' }}">
                                <a href="{{ route('supplier', ['cabang' => $currentCabang]) }}"
                                    class='submenu-link'>Master
                                    Supplier</a>
                            </li>





                        </ul>


                    </div>
                </div>
            </li>
        @endif


        @if (Auth::user()->divisi == 'purchasing' || Auth::user()->divisi == 'admin')
            <li class="menu-item  has-sub {{ request()->is($currentCabang . '/purchasing/*') ? 'active' : '' }}">
                <a href="#" class='menu-link'>
                    <span><i class="bi bi-cart"></i> Purchasing</span>
                </a>
                <div class="submenu ">
                    <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
                    <div class="submenu-group-wrapper">


                        <ul class="submenu-group ">

                            <li
                                class="submenu-item {{ request()->is($currentCabang . '/purchasing/pembelian') ? 'active' : '' }} ">
                                <a href="{{ route('pembelian', ['cabang' => $currentCabang]) }}"
                                    class='submenu-link'>Pembelian</a>
                            </li>
                            <li
                                class="submenu-item {{ request()->is($currentCabang . '/purchasing/pengeluaran') ? 'active' : '' }} ">
                                <a href="{{ route('pengeluaran', ['cabang' => $currentCabang]) }}"
                                    class='submenu-link'>Pengeluaran</a>
                            </li>


                            {{-- <li class="submenu-item  has-sub {{ request()->is('transaksi/bar/*') ? 'active' : '' }}">
                                <a href="#" class='submenu-link'>Bar</a>


                                <!-- 3 Level Submenu -->
                                <ul class="subsubmenu">

                                    <li class="subsubmenu-item ">
                                        <a href="extra-component-avatar.html" class="subsubmenu-link">Dashboard -
                                            BAR</a>
                                    </li>

                                    <li class="subsubmenu-item ">
                                        <a href="{{ route('kasir_bar') }}" class="subsubmenu-link">Kasir - BAR</a>
                                    </li>

                                    <li
                                        class="subsubmenu-item {{ request()->is('transaksi/bar/pembelian_bar') ? 'active' : '' }}">
                                        <a href="{{ route('pembelian_bar') }}" class="subsubmenu-link">Order - BAR</a>
                                    </li>
                                    <li
                                        class="subsubmenu-item {{ request()->is('transaksi/bar/pembelian_bar') ? 'active' : '' }}">
                                        <a href="{{ route('pembelian_bar') }}" class="subsubmenu-link">Order - BAR</a>
                                    </li>
                                    <li class="subsubmenu-item ">
                                        <a href="extra-component-comment.html" class="subsubmenu-link">Stock Opname -
                                            BAR</a>
                                    </li>


                                </ul>

                            </li> --}}

                        </ul>


                    </div>
                </div>
            </li>
        @endif




        @if (Auth::user()->divisi == 'kasir' || Auth::user()->divisi == 'admin' || Auth::user()->divisi == 'bar')
            <li class="menu-item  has-sub {{ request()->is('transaksi/*') ? 'active' : '' }}">
                <a href="#" class='menu-link'>
                    <span><i class="bi bi-cash"></i> Kasir</span>
                </a>
                <div class="submenu ">
                    <div class="submenu-group-wrapper">


                        <ul class="submenu-group ">

                            <li
                                class="submenu-item menu-item {{ request()->is($currentCabang . '/transaksi/kasir') ? 'active' : '' }} ">
                                <a href="{{ route('kasir', ['cabang' => $currentCabang]) }}"
                                    class='submenu-link'>Kasir</a>
                            </li>


                        </ul>


                    </div>
                </div>
            </li>
        @endif


        @if (Auth::user()->divisi == 'admin' || Auth::user()->divisi == 'bar' || Auth::user()->divisi == 'purchasing')
            <li class="menu-item  has-sub {{ request()->is($currentCabang . '/bar/*') ? 'active' : '' }}">
                <a href="#" class='menu-link'>
                    <span><i class="bi bi-cup-straw"></i>
                        Bar</span>
                </a>
                <div class="submenu ">
                    <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
                    <div class="submenu-group-wrapper">


                        <ul class="submenu-group ">

                            <li
                                class="submenu-item  {{ request()->is($currentCabang . '/bar/pembelian_bar') ? 'active' : '' }}">
                                <a href="{{ route('pembelian_bar', ['cabang' => $currentCabang]) }}"
                                    class='submenu-link'>Order</a>
                            </li>
                            <li
                                class="submenu-item  {{ request()->is($currentCabang . '/bar/persediaan_bar') ? 'active' : '' }}">
                                <a href="{{ route('persediaan_bar', ['cabang' => $currentCabang]) }}"
                                    class='submenu-link'>Persediaan</a>
                            </li>
                            <li
                                class="submenu-item  {{ request()->is($currentCabang . '/utility/stockOpname') ? 'active' : '' }}">
                                <a href="{{ route('stockOpname', ['cabang' => $currentCabang]) }}"
                                    class='submenu-link'>Stock Opname</a>
                            </li>
                        </ul>


                    </div>
                </div>
            </li>
        @endif


        @if (Auth::user()->divisi == 'admin' || Auth::user()->divisi == 'kasir')
            <li class="menu-item  has-sub {{ request()->is($currentCabang . '/kitchen/*') ? 'active' : '' }}">
                <a href="#" class='menu-link'>
                    <span><i class="bi bi-egg-fried"></i>
                        Kitchen</span>
                </a>
                <div class="submenu ">
                    <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
                    <div class="submenu-group-wrapper">


                        <ul class="submenu-group ">

                            <li
                                class="submenu-item  {{ request()->is($currentCabang . '/kitchen/pembelian_kitchen') ? 'active' : '' }}">
                                <a href="{{ route('pembelian_kitchen', ['cabang' => $currentCabang]) }}"
                                    class='submenu-link'>Order</a>
                            </li>
                            <li
                                class="submenu-item  {{ request()->is($currentCabang . '/kitchen/persediaan_kitchen') ? 'active' : '' }}">
                                <a href="{{ route('persediaan_kitchen', ['cabang' => $currentCabang]) }}"
                                    class='submenu-link'>Persediaan</a>
                            </li>
                            <li
                                class="submenu-item  {{ request()->is($currentCabang . '/utility/stockOpname') ? 'active' : '' }}">
                                <a href="{{ route('stockOpname', ['cabang' => $currentCabang]) }}"
                                    class='submenu-link'>Stock Opname</a>
                            </li>
                        </ul>


                    </div>
                </div>
            </li>
        @endif



        <li class="menu-item  has-sub">
            <a href="#" class='menu-link'>
                <span><i class="bi bi-journal-check"></i> Laporan</span>
            </a>
            <div class="submenu ">
                <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
                <div class="submenu-group-wrapper">


                    <ul class="submenu-group">

                        <li class="submenu-item  ">
                            <a href="https://zuramai.github.io/mazer/docs" class='submenu-link'>Laporan Barang</a>
                        </li>

                        <li class="submenu-item  ">
                            <a href="{{ route('laporanBahan', ['cabang' => $currentCabang]) }}"
                                class='submenu-link'>Laporan Bahan</a>
                        </li>

                    </ul>


                </div>
            </div>
        </li>



        <li class="menu-item  has-sub {{ request()->is('persediaan/*') ? 'active' : '' }}">
            <a href="#" class='menu-link'>
                <span><i class="bi bi-journal-check"></i> Persediaan</span>
            </a>
            <div class="submenu ">
                <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
                <div class="submenu-group-wrapper">
                    <ul class="submenu-group">
                        <li class="submenu-item  {{ request()->is('persediaan/gudang') ? 'active' : '' }}">
                            <a href="{{ route('persediaanGudang', ['cabang' => $currentCabang]) }}"
                                class='submenu-link'>Persediaan</a>
                        </li>
                    </ul>
                </div>
            </div>
        </li>

        <li class="menu-item  has-sub">
            <a href="#" class='menu-link'>
                <span><i class="bi bi-journal-check"></i> Utility</span>
            </a>
            <div class="submenu ">
                <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
                <div class="submenu-group-wrapper">
                    <ul class="submenu-group">
                        <li class="submenu-item ">
                            <a href="{{ route('refreshSaldoStock', ['cabang' => $currentCabang]) }}"
                                class='submenu-link' id="btnRefresh">Refresh</a>
                        </li>
                    </ul>
                </div>
            </div>
        </li>







        {{-- <li class="menu-item  has-sub {{ request()->is('utility/*') ? 'active' : '' }}">
            <a href="#" class='menu-link'>
                <span><i class="bi bi-life-preserver "></i>
                    Utility</span>
            </a>
            <div class="submenu ">
                <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
                <div class="submenu-group-wrapper">


                    <ul class="submenu-group ">

                        <li class="submenu-item  {{ request()->is('utility/stockOpname') ? 'active' : '' }}">
                            <a href="{{ route('stockOpname') }}" class='submenu-link'>Stock Opname</a>


                        </li>



                        <li class="submenu-item  ">
                            <a href="https://github.com/zuramai/mazer/blob/main/CONTRIBUTING.md"
                                class='submenu-link'>Contribute</a>


                        </li>



                        <li class="submenu-item  ">
                            <a href="https://github.com/zuramai/mazer#donation" class='submenu-link'>Donate</a>


                        </li>

                    </ul>


                </div>
            </div>
        </li> --}}


    </ul>
</div>
