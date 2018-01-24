@if (Auth::check())
    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
          <div class="pull-left image">
            {{-- <img src="https://placehold.it/160x160/00a65a/ffffff/&text={{ mb_substr(Auth::user()->name, 0, 1) }}" class="img-circle" alt="User Image"> --}}
            <img src="{{ asset('images/logo.png') }}" class="img-circle" alt="User Image" />
          </div>
          <div class="pull-left info">
            <p>{{ Auth::user()->name }}</p>
            <a href="#">
              <i class="fa fa-circle text-success"></i> 
              <span>
              {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
              @if(isset(Auth::user()->position))
              ( {{  ucfirst(Auth::user()->position) }} )
              @endif
              </span>
            </a>
          </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
          <li class="header">Navigation</li>
          <!-- ================================================ -->
          <!-- ==== Recommended place for admin menu items ==== -->
          <!-- ================================================ -->
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>

          @if( Auth::user()->access == 0 || Auth::user()->access == 1 || Auth::user()->access == 2 )

          @if(Auth::user()->access == 0)

          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/backup') }}"><i class="fa fa-hdd-o"></i> <span>Backups</span></a></li>

          @else

          <li><a href="{{ url('receipt') }}"><i class="fa fa-files-o" aria-hidden="true"></i> <span> Receipt </span></a></li>

          <li><a href="{{ url('sale') }}"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <span> Sales </span></a></li>

          <li><a href="{{ url('inventory/supply') }}"><i class="fa fa-list-alt" aria-hidden="true"></i> <span> Inventory </span></a></li>

          @endif

          @if(Auth::user()->access == 1)

          <li class="header">Information System</li>

          <li><a href="{{ url('maintenance/unit') }}"><i class="fa fa-balance-scale" aria-hidden="true"></i> <span> Unit </span></a></li>

          <li><a href="{{ url('maintenance/category') }}"><i class="fa fa-tags" aria-hidden="true"></i> <span> Category </span></a></li>

          <li><a href="{{ url('maintenance/supply') }}"><i class="fa fa-cubes" aria-hidden="true"></i> <span> Supply </span></a></li>
                    
          <li><a href="{{ url('maintenance/product') }}"><i class="fa fa-database" aria-hidden="true"></i> <span> Product</span></a></li>

          <li><a href="{{ url('maintenance/reference') }}"><i class="fa fa-truck" aria-hidden="true"></i> <span> Reference </span></a></li>

          @endif

          @endif

          @if(Auth::user()->access == 0)
          <!-- ======================================= -->
          <li class="header">Utilities</li>

          <li><a href="{{ url('account') }}"><i class="fa fa-users" aria-hidden="true"></i> Accounts</span></a></li>

          <li><a href="{{ url('audittrail') }}"><i class="fa fa-history" aria-hidden="true"></i> <span>Audit Trail</span></a></li>
          
          @endif

          <!-- ======================================= -->
          <li class="header">{{ trans('backpack::base.user') }}</li>

          <li><a href="{{ url('settings') }}"><i class="fa fa-user-o" aria-hidden="true"></i> <span> Settings</span></a></li>

          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/logout') }}"><i class="fa fa-sign-out"></i> <span>{{ trans('backpack::base.logout') }}</span></a></li>
        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>
@endif
