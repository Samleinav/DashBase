

{!! Theme::partial('header') !!}
 <!-- END SETTINGS --> 
</head>
<!--
  HOW TO USE: 
  data-theme: default (default), dark, light, colored
  data-layout: fluid (default), boxed
  data-sidebar-position: left (default), right
  data-sidebar-layout: default (default), compact
-->

<body  data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
    <div class="wrapper" >

    <!-- Sidebar -->

    {!! Theme::partial('sidebar') !!}

    <!-- End Sidebar -->
        <div class="main">
           <!-- Topbar -->
            {!! Theme::partial('topbar') !!}
            <!-- END Topbar -->
            <main  id="gjs" class="content">
                <div class="container-fluid p-0">

                    <h1 class="h3 mb-3"></h1>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Empty card</h5>
                                </div>
                                <div class="card-body">
                                {!! Theme::content() !!}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
            <!--- Footer -->
            {!! Theme::partial('footer') !!}
        </div>
    </div> 
</body>

</html>
