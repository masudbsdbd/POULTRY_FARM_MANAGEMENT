<!-- ========== Left Sidebar Start ========== -->
<div class="app-menu">

    <div class="logo-box">
        <a href="{{ route('dashboard') }}" class="logo-light">
            <img style="height:80px" src="{{ asset('uploads/logo/' . gs()->logo) }}" alt="logo" class="logo-lg">
            <img style="height:80px" src="{{ asset('uploads/logo/' . gs()->logo) }}" alt="small logo" class="logo-sm">
        </a>
        <a href="{{ route('dashboard') }}" class="logo-dark">
            <img style="height:80px" src="{{ asset('uploads/logo/' . gs()->logo) }}" alt="dark logo" class="logo-lg">
            <img style="height:80px" src="{{ asset('uploads/logo/' . gs()->logo) }}" alt="small logo" class="logo-sm">
        </a>
    </div>

    <div class="scrollbar h-100" data-simplebar>

        <!-- User box -->
        <div class="user-box text-center">
            <img src="/images/users/user-1.jpg" alt="user-img" title="Mat Helme" class="rounded-circle avatar-md">
            <div class="dropdown">
                <a href="javascript: void(0);" class="dropdown-toggle h5 mb-1 d-block" data-bs-toggle="dropdown">Geneva
                    Kennedy</a>
                <div class="dropdown-menu user-pro-dropdown">

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user me-1"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-settings me-1"></i>
                        <span>Settings</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-lock me-1"></i>
                        <span>Lock Screen</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-log-out me-1"></i>
                        <span>Logout</span>
                    </a>

                </div>
            </div>
            <p class="text-muted mb-0">Admin Head</p>
        </div>

        <!--- Sidemenu -->

        <ul id="side-menu" class="menu">

            <li class="menu-title">Navigation</li>

            <li class="menu-item ">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <span class="menu-icon">
                        <i class="mdi mdi-view-dashboard"></i>
                    </span>
                    <span class="menu-text"> Dashboard </span>
                </a>
            </li>

            <li class="menu-item">
                <a href="#manageEmployee" data-bs-toggle="collapse" class="menu-link">
                    <span class="menu-icon"><i class="mdi mdi-account-multiple"></i></span>
                    <span class="menu-text"> Quotations </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="manageEmployee">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('quotation.index') }}"><span class="menu-text">All
                                    Quotations</span></a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="menu-item">
                <a href="#allReports" data-bs-toggle="collapse" class="menu-link">
                    <span class="menu-icon"><i class="mdi mdi-account-multiple"></i></span>
                    <span class="menu-text"> Reports </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="allReports">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('payment.history') }}"><span class="menu-text">Payment Report</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('challan.used_history') }}"><span class="menu-text">Product Used History</span></a>
                        </li>
                    </ul>
                </div>
            </li>

            @can('product-list')
            <li class="menu-item">
                <a href="#manageProducts" data-bs-toggle="collapse" class="menu-link">
                    <span class="menu-icon"><i class="mdi mdi-apps"></i></span>
                    <span class="menu-text"> Products </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="manageProducts">
                    <ul class="sub-menu">
                            <li class="menu-item">
                                <a href="{{ route('product.index') }}" class="menu-link">
                                    <span class="menu-text"> All Products </span>
                                </a>
                            </li>

                        @can('unit-list')

                        <li class="menu-item">
                            <a href="{{ route('unit.index') }}" class="menu-link">
                                <span class="menu-text"> Product Unit </span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="{{ route('building.index') }}" class="menu-link">
                                <span class="menu-text"> Building </span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="{{ route('floor.index') }}" class="menu-link">
                                <span class="menu-text"> Floor </span>
                            </a>
                        </li>
                        @endcan


                    </ul>
                </div>
            </li>
            @endcan


            {{-- pultry management start from here --}}
            {{-- poultry batch management start --}}
            <li class="menu-item">
                <a href="#managePoultryBatch" data-bs-toggle="collapse" class="menu-link">
                    <span class="menu-icon"><i class="mdi mdi-account-multiple"></i></span>
                    <span class="menu-text"> Batches </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="managePoultryBatch">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('poultrybatch.index') }}"><span class="menu-text">All Active Batches</span></a>
                        </li>
                    </ul>
                </div>
            </li>
            {{-- poultry batch management end --}}
            {{-- pultry management end here --}}
            
            

            @can('customer-list')

            <li class="menu-item">
                <a href="#manageCustomers" data-bs-toggle="collapse" class="menu-link">
                    <span class="menu-icon"><i class="mdi mdi-account-multiple"></i></span>
                    <span class="menu-text"> Customers </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="manageCustomers">
                    <ul class="sub-menu">
                        {{--@can('customer-create')
                            <li class="menu-item">
                                <a class="menu-link" href="{{ route('customer.create') }}"><span class="menu-text">Add
                                        Customer</span></a>
                            </li>
                        @endcan--}}

                            <li class="menu-item">
                                <a class="menu-link" href="{{ route('customer.index') }}"><span class="menu-text">All
                                        Customers</span></a>
                            </li>
                        {{-- <li class="menu-item">
                            <a class="menu-link" href="{{ route('customer.advance.index') }}"><span
                            class="menu-text">Advance List</span></a>
                         </li> --}}
                         {{-- @can('customer-type-list')
                         <li class="menu-item">
                            <a class="menu-link" href="{{ route('setting.customer.type') }}"><span
                                    class="menu-text">Customer Type</span></a>
                           @endcan
                        </li> --}}
                    </ul>
                </div>
            </li>
            @endcan

            @can('user-list')
                <li class="menu-item">
                    <a href="#manageRoles" data-bs-toggle="collapse" class="menu-link">
                        <span class="menu-icon"><i class="mdi mdi-database-outline"></i></span>
                        <span class="menu-text"> Users </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="manageRoles">
                        <ul class="sub-menu">
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('users.index') }}"><span class="menu-text">Manage
                                            Users</span></a>
                                </li>
                            @can('role-list')
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('roles.index') }}"><span class="menu-text">Manage
                                            Role</span></a>

                                </li>
                            @endcan

                        </ul>
                    </div>
                </li>
            @endcan
            @can('general-setting-maintain')
            <li class="menu-item">
                <a class="menu-link" href="#settings" data-bs-toggle="collapse">
                    <span class="menu-icon"><i class="mdi mdi-wrench"></i></span>
                    <span class="menu-text"> Settings </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="settings">
                    <ul class="sub-menu">

                        <li class="menu-item">
                            <a href="{{ route('setting.general') }}" class="menu-link">
                                <span class="menu-text"> General Settings</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endcan


            <li class="menu-item"></li>
            <li class="menu-item"></li>
            <li class="menu-item"></li>
            <li class="menu-item"></li>
            <li class="menu-item"></li>
            
        </ul>

        {{-- </div> --}}
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->
