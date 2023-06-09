<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-4" style="background-color: #BEE5EB">
    @php
        $id_lokasi = Request::get('acc');
        
    @endphp

    <!-- Brand Logo -->
    <a href="{{ route('dashboard', ['acc' => $id_lokasi]) }}" class="brand-link">
        <img src="{{ asset('assets') }}{{ $id_lokasi == 1 ? '/menu/img/Takemori.svg' : '/menu/img/soondobu.jpg' }}"
            alt="AdminLTE Logo" class="brand-image image-center elevation-3" style="opacity: .8">
        <h5 class="text-block text-info text-md">{{ $id_lokasi == 1 ? 'Accounting Takemori' : 'Accounting Soondobu' }}
        </h5>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @php
                    $url = Str::remove('http://127.0.0.1:8000/', Request::url());
                @endphp
                @if ($url == 'dashboard')
                @else
                    @php
                        $sub = DB::table('tb_acc_sub_menu')
                            ->where('url', $url)
                            ->first();
                        $id_user = Auth::user()->id;
                        
                    @endphp
                    @if (empty($sub))
                    @else
                    @php
                        $per = DB::table('tb_acc_permission')
                            ->where([['id_user', $id_user], ['permission', $sub->id_sub_menu]])
                            ->first();
                    @endphp
                    @if (empty($per))
                    <script>
                        window.location.href = '{{ route('error') }}';
                    </script>
                    @endif      
                    @endif
                @endif
                @php
                    $menu = DB::table('tb_acc_menu')->orderBy('urutan', 'ASC')->get();
                    $sub_menu = DB::table('tb_acc_sub_menu')->get();
                    
                @endphp
                <?php 
                $id_user = Auth::user()->id;
                $sub = DB::table('tb_acc_sub_menu')
                        ->where('url', Route::current()->getName())
                        ->first();
                if(empty($sub->id_sub_menu)) {

                } else {
                    $permission =  DB::selectOne("SELECT a.id_user, a.permission, b.sub_menu, b.url, b.id_menu
                            FROM tb_acc_permission AS a
                            LEFT JOIN tb_acc_sub_menu AS b ON b.id_sub_menu = a.permission
                            WHERE a.id_user ='$id_user' AND a.permission = '$sub->id_sub_menu'");
                }
                
                  
                    ?>
                @foreach ($menu as $m)
                    @if (in_array($m->id_menu, Session::get('dt_menu')))
                        @if (empty($permission->id_menu))
                        <li class="nav-item {{ $m->id_menu == 1 ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ $m->id_menu == 1 ? 'active' : '' }}">
                        @else
                        <li class="nav-item {{ $m->id_menu == $permission->id_menu ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ $m->id_menu == $permission->id_menu ? 'active' : '' }}">
                        @endif
                        
                                <i class="nav-icon {{ $m->icon }}"></i>
                                <p>
                                    {{ $m->menu }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview"">
                                @foreach ($sub_menu as $s)
                                    @if (in_array($s->id_sub_menu, Session::get('permission')))
                                        @if ($m->id_menu == $s->id_menu)
                                            <li class="nav-item">
                                                @php
                                                    
                                                @endphp
                                                <a href="{{ route($s->url, ['acc' => $id_lokasi]) }}" id_menu="{{ $s->id_menu }}"
                                                    class="nav-link {{ Request::is($s->url) ? 'active' : '' }} url">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>{{ $s->sub_menu }}</p>
                                                </a>
                                            </li>
                                        @endif
                                    @endif
                                @endforeach
                            </ul>
                           
                            
                    @endif
                @endforeach
           
                    <li class="nav-item">
                        <a href="{{ route('logoutAdm') }}" class="nav-link text-dark btn btn-outline-danger"><i class="fa fa-power-off nav-icon"></i> Logout</a>
                    </li>
              
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

