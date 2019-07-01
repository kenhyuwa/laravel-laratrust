<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ auth()->user()->avatar }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ ucwords(auth()->user()->name) ?? 'Ken' }}</p>
                <a><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <form class="sidebar-form">
            <div class="input-group">
                <input id="filterNavigateMenu" type="text" class="form-control white-color" autocomplete="off">
                <span class="input-group-btn">
                    <button type="button" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
        <ul id="sidebar-menu" class="sidebar-menu" data-widget="tree">
            {{-- <div class="header main-navigation">MAIN NAVIGATION</div> --}}
            {{ $navigation }}
            <li class="developer">
                <a href="mailto:wahyu.dhiraashandy8@gmail.com">
                    <i class="fa fa-star-o"></i>
                    <span>Developer</span>
                </a>
            </li>
        </ul>
    </section>
</aside>