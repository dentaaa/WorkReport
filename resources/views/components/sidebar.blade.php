<div>
    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="../dashboard/index.html" class="b-brand text-primary">
                    <span>Aplikasi Work Report</span>
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">

                    <x-sidebar.links title='Home' icon='ti ti-dashboard' route='home' />
                    <x-sidebar.links title='Work Report' icon='ti ti-users' route='workreport.index' />

                    @if (auth()->user()->isAdmin())
                        <x-sidebar.links title='Manage User' icon='ti ti-users' route='users.index' />
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</div>
