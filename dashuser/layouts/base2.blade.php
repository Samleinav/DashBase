{!! Theme::partial('header') !!}

<body class="bg-gradient-primary" >

{!! apply_filters(THEME_FRONT_BODY, null) !!}
   
        <!-- Main Content -->
        
            <!--- Content -->
            {!! Theme::content() !!}

        <!--- Footer -->
        {!! Theme::partial('footer') !!}


</body>


