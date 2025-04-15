<div class="container">
    <ul>






        {{-- @if (Auth::user()->divisi == 'admin')
            <li class="menu-item  has-sub {{ request()->is('master/*') ? 'active' : '' }}">
                <a href="#" class='menu-link'>
                    <span><i class="bi bi-stack"></i> Master</span>
                </a>
                <div class="submenu ">
                    <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
                    <div class="submenu-group-wrapper">


                        <ul class="submenu-group ">

                            <li class="submenu-item menu-item {{ request()->is('master/barang') ? 'active' : '' }} ">
                                <a href="{{ route('barang') }}" class='submenu-link'>Master Barang</a>
                            </li>
                            <li class="submenu-item  {{ request()->is('master/bahan') ? 'active' : '' }}">
                                <a href="{{ route('bahan') }}" class='submenu-link'>Master Bahan</a>
                            </li>
                            <li class="submenu-item  {{ request()->is('master/bahanOlah') ? 'active' : '' }}">
                                <a href="{{ route('bahanOlah') }}" class='submenu-link'>Master Bahan Olah</a>
                            </li>
                            <li class="submenu-item  {{ request()->is('master/supplier') ? 'active' : '' }}">
                                <a href="{{ route('supplier') }}" class='submenu-link'>Master Supplier</a>
                            </li>



                            <li class="submenu-item  has-sub">
                                <a href="#" class='submenu-link'>Extra Components</a>


                                <!-- 3 Level Submenu -->
                                <ul class="subsubmenu">

                                    <li class="subsubmenu-item ">
                                        <a href="extra-component-avatar.html" class="subsubmenu-link">Avatar</a>
                                    </li>

                                    <li class="subsubmenu-item ">
                                        <a href="extra-component-comment.html" class="subsubmenu-link">Comment</a>
                                    </li>

                                    <li class="subsubmenu-item ">
                                        <a href="extra-component-sweetalert.html" class="subsubmenu-link">Sweet
                                            Alert</a>
                                    </li>

                                    <li class="subsubmenu-item ">
                                        <a href="extra-component-toastify.html" class="subsubmenu-link">Toastify</a>
                                    </li>

                                    <li class="subsubmenu-item ">
                                        <a href="extra-component-rating.html" class="subsubmenu-link">Rating</a>
                                    </li>

                                    <li class="subsubmenu-item ">
                                        <a href="extra-component-divider.html" class="subsubmenu-link">Divider</a>
                                    </li>

                                </ul>

                            </li>

                        </ul>


                    </div>
                </div>
            </li>
        @endif --}}




    </ul>
</div>
