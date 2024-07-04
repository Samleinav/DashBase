{!! Theme::partial('header') !!}
<body id="page-top" >
{!! apply_filters(THEME_FRONT_BODY, null) !!}
     <!-- Page Wrapper -->
     <div id="wrapper">
        <!--- Page Menu -->
        {!! Theme::partial('sidebar') !!}
    <!-- Page Content -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">
            <!--- Topbar -->
            {!! Theme::partial('topbar') !!}
            
            <div class="container-fluid">
                    <!--- Content -->
                    @yield('content')
                    
            </div>
            
        </div>

        <!--- Footer -->
        {!! Theme::partial('footer') !!}

    </div>

</body>


