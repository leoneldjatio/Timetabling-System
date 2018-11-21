
    <aside id="s-main-menu" class="sidebar">
        <div class="smm-header">
            <i class="zmdi zmdi-long-arrow-left" data-ma-action="sidebar-close"></i>
        </div>

        <ul class="smm-alerts">
            <li data-user-alert="sua-messages" data-ma-action="sidebar-open" data-ma-target="user-alerts">
                <i class="zmdi zmdi-email"></i>
            </li>
            <li data-user-alert="sua-notifications" data-ma-action="sidebar-open" data-ma-target="user-alerts">
                <i class="zmdi zmdi-notifications"></i>
            </li>
            <li data-user-alert="sua-tasks" data-ma-action="sidebar-open" data-ma-target="user-alerts">
                <i class="zmdi zmdi-view-list-alt"></i>
            </li>
        </ul>

        <ul class="main-menu">
            <li>
                <a href="/home"><i class="zmdi zmdi-home"></i>Home</a>
            </li>
            <li class="sub-menu">
                <a href="" data-ma-action="submenu-toggle"><i class="zmdi zmdi-collection-plus" style="font-size: large"></i>Timetabling System</a>

                <ul>
                    @foreach(Auth::user()->userRole()->get() as $role)
                        @foreach($role->privileges()->get() as $privilege)
                            <li><a href="{{ $privilege->url }}">{{ ( $privilege->privilege_name) }}</a></li>   @endforeach
                    @endforeach
                </ul>
            </li>
        </ul>
    </aside>
