<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>Aplikasi Work Report</title>
    <!-- [Meta] -->
    <x-meta></x-meta>

    <!-- Scripts -->

    <style>
        /* Slimmer sidebar without clipping the brand/title */
        :root {
            --wr-sidebar-width: 180px;
        }

        /* Apply desktop layout sizing only (mobile sidebar uses slide-in behavior) */
        @media (min-width: 1025px) {

            .pc-sidebar,
            .pc-sidebar .navbar-wrapper {
                width: var(--wr-sidebar-width) !important;
            }

            /* Header must start where sidebar ends */
            .pc-header {
                left: var(--wr-sidebar-width) !important;
            }

            /* Header left block width must match sidebar width */
            .pc-header .m-header {
                width: var(--wr-sidebar-width) !important;
                padding-left: 12px !important;
                padding-right: 10px !important;
            }

            .pc-container,
            .pc-footer {
                margin-left: var(--wr-sidebar-width) !important;
            }

            /* Breadcrumb/page header alignment */
            .page-header {
                left: var(--wr-sidebar-width) !important;
            }
        }

        /* Reduce link padding slightly to match slimmer sidebar */
        .pc-sidebar .pc-link {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }

        /* Ensure sidebar title is never cut; allow wrapping */
        .pc-sidebar .m-header .b-brand span {
            display: block;
            white-space: normal;
            overflow: visible;
            text-overflow: unset;
            line-height: 1.2;
        }

        .pc-item.active>.pc-link {
            background-color: #1890ff !important;
            color: #fff !important;
            border-radius: 6px;
        }

        .dashboard-card {
            border: none;
            border-radius: 12px;
            transition: .25s;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
        }

        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, .12);
        }

        .dashboard-icon {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 22px;
        }

        .dashboard-number {
            font-size: 34px;
            font-weight: bold;
        }

        .dashboard-title {
            font-size: 14px;
            color: #888;
        }

        .dashboard-footer {
            color: #999;
            font-size: 13px;
        }
    </style>

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <!-- [ Sidebar Menu ] start -->
    <x-sidebar></x-sidebar>
    <!-- [ Sidebar Menu ] end --> <!-- [ Header Topbar ] start -->
    <x-header></x-header>
    <!-- [ Header ] end -->



    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <x-breadcrumbs></x-breadcrumbs>
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            <div class="row">
                @if (session('success'))
                    <div class="">
                        <div class="alert alert-success" id="success-alert" role="alert">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
    <x-footer></x-footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.datatables.net/2.3.7/js/dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            let table = new DataTable('#table', {
                searching: false
            });

            $("#success-alert").fadeTo(2000, 500).slideUp(500, function() {
                $("#success-alert").slideUp(500);
            });

        });
    </script>

    <!-- [Page Specific JS] start -->
    <script src="{{ asset('template/dist') }}/assets/js/plugins/apexcharts.min.js"></script>
    <script src="{{ asset('template/dist') }}/assets/js/pages/dashboard-default.js"></script>
    <!-- [Page Specific JS] end -->
    <!-- Required Js -->
    <script src="{{ asset('template/dist') }}/assets/js/plugins/popper.min.js"></script>
    <script src="{{ asset('template/dist') }}/assets/js/plugins/simplebar.min.js"></script>
    <script src="{{ asset('template/dist') }}/assets/js/plugins/bootstrap.min.js"></script>
    <script src="{{ asset('template/dist') }}/assets/js/fonts/custom-font.js"></script>
    <script src="{{ asset('template/dist') }}/assets/js/pcoded.js"></script>
    <script src="{{ asset('template/dist') }}/assets/js/plugins/feather.min.js"></script>





    <script>
        layout_change('light');
    </script>




    <script>
        change_box_container('false');
    </script>



    <script>
        layout_rtl_change('false');
    </script>


    <script>
        preset_change("preset-1");
    </script>


    <script>
        font_change("Public-Sans");
    </script>

    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}

    <script src="{{ asset('js/multi-image-preview.js') }}"></script>

    <script src="{{ asset('js/work_report_continue_note.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

    @stack('scripts')

</body>
<!-- [Body] end -->

</html>
