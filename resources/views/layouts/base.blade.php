<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @yield('title', 'PointOfSaleRBTG')
    </title>

    <link rel="shortcut icon"
        href="data:image/svg+xml,%3csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2033%2034'%20fill-rule='evenodd'%20stroke-linejoin='round'%20stroke-miterlimit='2'%20xmlns:v='https://vecta.io/nano'%3e%3cpath%20d='M3%2027.472c0%204.409%206.18%205.552%2013.5%205.552%207.281%200%2013.5-1.103%2013.5-5.513s-6.179-5.552-13.5-5.552c-7.281%200-13.5%201.103-13.5%205.513z'%20fill='%23435ebe'%20fill-rule='nonzero'/%3e%3ccircle%20cx='16.5'%20cy='8.8'%20r='8.8'%20fill='%2341bbdd'/%3e%3c/svg%3e"
        type="image/x-icon">
    <link rel="shortcut icon"
        href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAiCAYAAADRcLDBAAAEs2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgZXhpZjpQaXhlbFhEaW1lbnNpb249IjMzIgogICBleGlmOlBpeGVsWURpbWVuc2lvbj0iMzQiCiAgIGV4aWY6Q29sb3JTcGFjZT0iMSIKICAgdGlmZjpJbWFnZVdpZHRoPSIzMyIKICAgdGlmZjpJbWFnZUxlbmd0aD0iMzQiCiAgIHRpZmY6UmVzb2x1dGlvblVuaXQ9IjIiCiAgIHRpZmY6WFJlc29sdXRpb249Ijk2LjAiCiAgIHRpZmY6WVJlc29sdXRpb249Ijk2LjAiCiAgIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiCiAgIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIKICAgeG1wOk1vZGlmeURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiCiAgIHhtcDpNZXRhZGF0YURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJwcm9kdWNlZCIKICAgICAgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWZmaW5pdHkgRGVzaWduZXIgMS4xMC4xIgogICAgICBzdEV2dDp3aGVuPSIyMDIyLTAzLTMxVDEwOjUwOjIzKzAyOjAwIi8+CiAgICA8L3JkZjpTZXE+CiAgIDwveG1wTU06SGlzdG9yeT4KICA8L3JkZjpEZXNjcmlwdGlvbj4KIDwvcmRmOlJERj4KPC94OnhtcG1ldGE+Cjw/eHBhY2tldCBlbmQ9InIiPz5V57uAAAABgmlDQ1BzUkdCIElFQzYxOTY2LTIuMQAAKJF1kc8rRFEUxz9maORHo1hYKC9hISNGTWwsRn4VFmOUX5uZZ36oeTOv954kW2WrKLHxa8FfwFZZK0WkZClrYoOe87ypmWTO7dzzud97z+nec8ETzaiaWd4NWtYyIiNhZWZ2TvE946WZSjqoj6mmPjE1HKWkfdxR5sSbgFOr9Ll/rXoxYapQVik8oOqGJTwqPL5i6Q5vCzeo6dii8KlwpyEXFL519LjLLw6nXP5y2IhGBsFTJ6ykijhexGra0ITl5bRqmWU1fx/nJTWJ7PSUxBbxJkwijBBGYYwhBgnRQ7/MIQIE6ZIVJfK7f/MnyUmuKrPOKgZLpEhj0SnqslRPSEyKnpCRYdXp/9++msneoFu9JgwVT7b91ga+LfjetO3PQ9v+PgLvI1xkC/m5A+h7F32zoLXug38dzi4LWnwHzjeg8UGPGbFfySvuSSbh9QRqZ6H+Gqrm3Z7l9zm+h+iafNUV7O5Bu5z3L/wAdthn7QIme0YAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAJTSURBVFiF7Zi9axRBGIefEw2IdxFBRQsLWUTBaywSK4ubdSGVIY1Y6HZql8ZKCGIqwX/AYLmCgVQKfiDn7jZeEQMWfsSAHAiKqPiB5mIgELWYOW5vzc3O7niHhT/YZvY37/swM/vOzJbIqVq9uQ04CYwCI8AhYAlYAB4Dc7HnrOSJWcoJcBS4ARzQ2F4BZ2LPmTeNuykHwEWgkQGAet9QfiMZjUSt3hwD7psGTWgs9pwH1hC1enMYeA7sKwDxBqjGnvNdZzKZjqmCAKh+U1kmEwi3IEBbIsugnY5avTkEtIAtFhBrQCX2nLVehqyRqFoCAAwBh3WGLAhbgCRIYYinwLolwLqKUwwi9pxV4KUlxKKKUwxC6ZElRCPLYAJxGfhSEOCz6m8HEXvOB2CyIMSk6m8HoXQTmMkJcA2YNTHm3congOvATo3tE3A29pxbpnFzQSiQPcB55IFmFNgFfEQeahaAGZMpsIJIAZWAHcDX2HN+2cT6r39GxmvC9aPNwH5gO1BOPFuBVWAZue0vA9+A12EgjPadnhCuH1WAE8ivYAQ4ohKaagV4gvxi5oG7YSA2vApsCOH60WngKrA3R9IsvQUuhIGY00K4flQG7gHH/mLytB4C42EgfrQb0mV7us8AAMeBS8mGNMR4nwHamtBB7B4QRNdaS0M8GxDEog7iyoAguvJ0QYSBuAOcAt71Kfl7wA8DcTvZ2KtOlJEr+ByyQtqqhTyHTIeB+ONeqi3brh+VgIN0fohUgWGggizZFTplu12yW8iy/YLOGWMpDMTPXnl+Az9vj2HERYqPAAAAAElFTkSuQmCC"
        type="image/png">
    <!--<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">-->



    <link rel="stylesheet" crossorigin
        href="{{ asset('mazer/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" crossorigin href="{{ asset('mazer/assets/compiled/css/table-datatable-jquery.css') }}">
    <link rel="stylesheet" crossorigin href="{{ asset('mazer/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" crossorigin href="{{ asset('mazer/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" crossorigin href="{{ asset('mazer/assets/compiled/css/iconly.css') }}">


    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" /> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <style>
        /* .card-body {
            display: flex;
            flex-direction: column;
        } */

        /* .card-body p {
            flex-grow: 10;
        } */
        .selected {
            background-color: #fad85d !important;
            /* Ganti warna sesuai kebutuhan */
        }

        .card-box {
            width: 100px;
            /* Lebar kotak, sesuaikan dengan kebutuhan */
            height: 100px;
            /* Tinggi kotak, sesuaikan dengan kebutuhan */
            background-color: #6c6c6c;
            /* Warna latar belakang kotak, sesuaikan dengan kebutuhan */
            border: 1px solid #0e0ae2;
            /* Border kotak, sesuaikan dengan kebutuhan */
            border-radius: 5px;
            /* Rounding sudut kotak, sesuaikan dengan kebutuhan */

        }
    </style>


</head>

<body class="light">

    <script src="{{ asset('mazer/assets/static/js/initTheme.js') }}"></script>
    <div id="app">
        <div id="main" class="layout-horizontal">
            <header class="mb-5">
                <div class="header-top">
                    <div class="container">
                        <div class="logo">
                            <h3 id="titleNavbar">RBTGTECH</h3>
                        </div>

                        <div class="header-top-right">
                            <small>periode</small>
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input  me-0" type="checkbox" id="btnBypassBahan"
                                    style="cursor: pointer">
                                <label class="form-check-label">Bypass Bahan</label>
                                <input id="tempBypass" style="display: none"></input>

                            </div>
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input  me-0" type="checkbox" id="toggle-dark"
                                    style="cursor: pointer">
                                <label class="form-check-label">Light/Dark Mode</label>
                            </div>
                            <div class="dropdown">
                                <a href="#" id="topbarUserDropdown"
                                    class="user-dropdown d-flex align-items-center dropend dropdown-toggle "
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="avatar avatar-md2">
                                        <img src="{{ asset('mazer/assets/compiled/jpg/1.jpg') }}" alt="Avatar">
                                    </div>
                                    <div class="text">
                                        <h6 class="user-dropdown-name">{{ Auth::user()->name }}</h6>
                                        <p class="user-dropdown-status text-sm text-muted">{{ Auth::user()->divisi }}
                                        </p>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg"
                                    aria-labelledby="topbarUserDropdown">
                                    <li><a class="dropdown-item" href="{{ route('lobby') }}">Lobby / Pilihan Store</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('logout') }}"onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Logout</a></li>
                                </ul>
                            </div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>

                            <!-- Burger button responsive -->
                            <a href="#" class="burger-btn d-block d-xl-none">
                                <i class="bi bi-justify fs-3"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <nav class="main-navbar">
                    @include('layouts.navbar')
                </nav>

            </header>

            <div class="content-wrapper" style="padding: 0 30px">
                @yield('content')
            </div>

            <footer>
                <div class="container">
                    <div class="footer clearfix mb-0 text-muted">
                        <div class="float-start">
                            <p>2024 &copy; RBTGTECH</p>
                        </div>
                        <div class="float-end">
                            <p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by <a
                                    href="https://rbtgtech.my.id">RBTGTECH</a></p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>



    <!--<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>-->
    <!--<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>-->

    <!--{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> --}}-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script src="{{ asset('mazer/assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('mazer/assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('mazer/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('mazer/assets/static/js/pages/datatables.js') }}"></script>


    <script src="{{ asset('mazer/assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('mazer/assets/static/js/pages/horizontal-layout.js') }}"></script>
    <script src="{{ asset('mazer/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

    <script src="{{ asset('mazer/assets/compiled/js/app.js') }}"></script>


    <script src="{{ asset('mazer/assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('mazer/assets/static/js/pages/dashboard.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        $('#tempBypass').val(0)

        ///ambil tanggal sekarang
        var today = new Date();

        // Format tanggal menjadi YYYY-MM-DD
        var day = String(today.getDate()).padStart(2, '0');
        var month = String(today.getMonth() + 1).padStart(2, '0'); // Januari adalah 0
        var year = today.getFullYear();

        var tglsekarang = year + '-' + month + '-' + day;

        var cabang = '{{ request()->segment(1) }}'; // Ini mengasumsikan cabang ada di segment pertama

        $.ajax({
            type: "GET",
            url: "{{ route('getCabang') }}",
            data: {
                id: cabang
            },
            dataType: "json",
            success: function(res) {
                $('#titleNavbar').html(res.cabang[0].stgnama);
                // console.log(res.cabang[0].stgnama)
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error); // Menambahkan penanganan error
            }
        });



        ///hanya bisa input nomor
        $('.nomor').on('input', function() {
            // Remove any non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Alternatively, to also prevent paste of non-numeric characters
        $('.nomor').on('paste', function(e) {
            var clipboardData = e.originalEvent.clipboardData || window.clipboardData;
            var pastedData = clipboardData.getData('Text');

            if (/[^0-9]/.test(pastedData)) {
                e.preventDefault();
            }
            if (this.value === '') {
                this.value = '1';
            }
        });

        $('.nomor').on('blur', function() {
            // Ensure value is not empty on blur
            if (this.value === '') {
                this.value = '1';
                Toast.fire({
                    icon: "error",
                    title: "Jumlah Minimal 1 "
                });

            }
        });


        $('.hitungan').on('input', function() {
            let value = this.value.replace(/[^,\d]/g, '');

            this.value = formatRupiah(value);
        });

        function formatRupiah(angka, prefix) {
            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix === undefined ? rupiah : rupiah;
        }

        $('#btnRefresh').click(function(e) {
            e.preventDefault();
            refreshSaldo();

        });


        function refreshSaldo() {
            $.ajax({
                type: "GET",
                url: "{{ route('refreshSaldo', ['cabang' => '__cabang__']) }}".replace(
                    '__cabang__', cabang),
                data: {
                    'gudang': $('').val(),
                },
                dataType: "json",
                success: function(res) {
                    if (res.status == 200) {
                        Toast.fire({
                            icon: "success",
                            title: "Berhasil refresh saldo, mohon tunggu",
                            timer: 1000, // Menentukan berapa lama toast muncul (dalam milidetik)
                            timerProgressBar: true, // Menambahkan progress bar
                        }).then(() => {
                            // Fungsi yang ingin dipanggil setelah toast selesai
                            refreshSaldoStock()
                        });
                    } else {
                        Toast.fire({
                            icon: "error",
                            title: "Gagal refresh saldo"
                        });
                    }
                }
            });
        }


        function refreshSaldoStock() {
            $.ajax({
                type: "GET",
                url: "{{ route('refreshSaldoStock', ['cabang' => '__cabang__']) }}".replace(
                    '__cabang__', cabang),
                dataType: "json",
                success: function(res) {
                    if (res.status == 200) {
                        Toast.fire({
                            icon: "success",
                            title: "Berhasil menyesuaikan stock gudang bar, mohon tunggu",
                            timer: 1000, // Menentukan berapa lama toast muncul (dalam milidetik)
                            timerProgressBar: true, // Menambahkan progress bar
                        }).then(() => {
                            // Fungsi yang ingin dipanggil setelah toast selesai
                            refreshSaldoStock_besar()
                        });
                    } else {
                        Toast.fire({
                            icon: "error",
                            title: "gagal menyesuaikan saldo gudang bar"
                        });
                    }
                }
            });
        }


        function refreshSaldoStock_besar() {
            $.ajax({
                type: "GET",
                url: "{{ route('refreshSaldoStock_besar', ['cabang' => '__cabang__']) }}".replace(
                    '__cabang__', cabang),
                dataType: "json",
                success: function(res) {
                    if (res.status == 200) {
                        Toast.fire({
                            icon: "success",
                            title: "Berhasil menyesuaikan stock gudang besar, mohon tunggu",
                            timer: 1000, // Menentukan berapa lama toast muncul (dalam milidetik)
                            timerProgressBar: true, // Menambahkan progress bar
                        }).then(() => {
                            refreshSaldoStock_kitchen()
                        });
                    } else {
                        Toast.fire({
                            icon: "error",
                            title: "gagal menyesuaikan saldo gudang besar"
                        });
                    }
                }
            });
        }

        function refreshSaldoStock_kitchen() {
            $.ajax({
                type: "GET",
                url: "{{ route('refreshSaldoStock_kitchen', ['cabang' => '__cabang__']) }}".replace(
                    '__cabang__', cabang),
                dataType: "json",
                success: function(res) {
                    if (res.status == 200) {
                        Toast.fire({
                            icon: "success",
                            title: "BERHASIL ! Stock seluruh gudang sudah dikalkulasi",
                        })
                    } else {
                        Toast.fire({
                            icon: "error",
                            title: "Gagal menyesuaikan saldo gudang kitchen"
                        });
                    }
                }
            });
        }


        $('#btnBypassBahan').change(function() {
            if ($(this).prop('checked')) {
                Toast.fire({
                    icon: "success",
                    title: "bypass pengecekan bahan tidak dilakukan"
                });
                $('#tempBypass').val(1)
                // Tambahkan kode lain jika checkbox tercentang
            } else {
                Toast.fire({
                    icon: "success",
                    title: "pengecekan bahan saat transaksi tetap dilakukan"
                });
                $('#tempBypass').val(0)
            }
        });
    </script>

    @stack('js')

</body>

</html>
