<ul class="nav navbar-nav">
	<li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>

	<li class="dropdown">
		<a href="{{ url('pembelian/create') }}" class="text-bold bg-success">
			<i class="icon-cart-add2"></i> Beli
		</a>
	</li>

	<li class="dropdown">
		<a href="{{ url('keuangan/penerimaan-pembayaran/create') }}" class="text-bold bg-info">
			<i class="icon-cash2"></i> Penerimaan Pembayaran
		</a>
	</li>

	<li class="dropdown">
		<a href="{{ url('keuangan/pembayaran/create') }}" class="text-bold bg-primary">
			<i class="icon-cash2"></i> Pembayaran
		</a>
	</li>

	<li class="dropdown">
		<a href="{{ url('kasir/penjualan/create') }}" class="text-bold bg-indigo-300">
			<i class="icon-store2"></i> Halaman Kasir
		</a>
	</li>
</ul>


<ul class="nav navbar-nav navbar-right">

    <p class="navbar-text" id="info-recta"><span class="label bg-danger"><i class="icon-printer2"></i>  Print Not Connected</span></p>

    <li class="dropdown" id="container-stock-limit">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <i class="icon-bubble-notification" id="icon-notif"></i>
            <span class="visible-xs-inline-block position-right"></span>
            <span class="badge bg-danger" id="count-notif">0</span>
        </a>

        <div class="dropdown-menu dropdown-content width-350" id="container-dropdown">
            <div class="dropdown-content-heading bg-success" style="margin-bottom: 10px; padding-top:5px; padding-bottom:5px;">
                <h5 class="text-center">
                    Stock Minimun
                </h5>
            </div>
            <ul class="media-list dropdown-content-body">
            </ul>
            <div class="dropdown-content-footer">
                <a href="{{ url('laporan/stock-item') }}" data-popup="tooltip" title="" data-original-title="Laporan Stock Item"><i class="icon-menu display-block"></i></a>
            </div>
        </div>
    </li>

	<li class="dropdown dropdown-user">
		<a class="dropdown-toggle" data-toggle="dropdown">
			<img src="{{ asset('user.jpg') }}" alt="">
			<span>{{Auth::user()->name}}</span>
			<i class="caret"></i>
		</a>

		<ul class="dropdown-menu dropdown-menu-right">
			<li>
				<a href="{{ route('logout') }}"
					onclick="event.preventDefault();
					document.getElementById('logout-form').submit();"
				><i class="icon-exit2"></i> Keluar</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
			</li>
		</ul>
	</li>
</ul>
