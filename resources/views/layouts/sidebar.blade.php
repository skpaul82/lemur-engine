<aside class="main-sidebar" id="sidebar-wrapper">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{!! Avatar::create(Auth::user()->name)->toBase64() !!}"
                     class="user-image" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p data-test="sidebar-user-name">{{ Auth::user()->name}}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> {{ (Auth::user()->hasRole('admin')?'Admin':'Member')}}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->

        <ul class="sidebar-menu" data-widget="tree">
            @include('layouts.menu')
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
