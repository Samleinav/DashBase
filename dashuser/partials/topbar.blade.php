<nav class="navbar navbar-expand navbar-light navbar-bg">
    <a class="sidebar-toggle js-sidebar-toggle">
        <i class="hamburger align-self-center"></i>
    </a>
    <!-- SEARCH FORM -->
    <form class="d-none d-sm-inline-block">
        <div class="input-group input-group-navbar">
            <input type="text" class="form-control" placeholder="Search…" aria-label="Search">
            <button class="btn" type="button">
                <i class="align-middle" data-feather="search"></i>
            </button>
        </div>
    </form>

    <ul class="navbar-nav d-none d-lg-flex">
        
    </ul>

    <div class="navbar-collapse collapse">
        <ul class="navbar-nav navbar-align">
            <!-- ALERTS !-->
            {!! Theme::partial('top.alerts-dropdown') !!}
            <!-- MESSAGES !-->
            {!! Theme::partial('top.messages-dropdown') !!}
            <!-- LANGUAGES !-->
            {!! Theme::partial('top.language-switcher') !!}
            <!-- USER !-->
            {!! Theme::partial('top.user-dropdown') !!}
        </ul>
    </div>
</nav>