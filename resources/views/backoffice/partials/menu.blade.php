<ul class="navigation navigation-main navigation-accordion">
	<li class="navigation-header"><span>Main</span> <i class="icon-menu" title="Main pages"></i></li>
	<li class="{{ (request()->is('/')) ? 'active' : '' }}" ><a href="{{ url('/') }}"><i class="icon-home5"></i><span>Dashboard</span></a></li>
	<li class="{{ (request()->is('/penjualan')) ? 'active' : '' }}" ><a href="{{ url('/penjualan') }}"><i class="icon-cart-remove"></i><span>Transaksi Penjualan</span></a></li>
	<li class="{{ (request()->is('/pembelian')) ? 'active' : '' }}" ><a href="{{ url('/pembelian') }}"><i class="icon-cart-add2"></i><span>Transaksi Pembelian</span></a></li>
	<li class="{{ (request()->is('produksi/*')) ? 'active' : '' }}">
		<a href="#" class="has-ul"><i class="icon-grid"></i> <span>Produksi</span></a>
		<ul class="hidden-ul" style="{{ (request()->is('produksi/*')) ? 'display: block;' : 'display: none;' }}">
			<li class="{{ (request()->is('produksi/bill-of-material*')) ? 'active' : '' }}">
				<a href="{{ url('produksi/bill-of-material') }}" class="text-bold"><i class="icon-folder-plus2"></i>Bill Of Material</a>
			</li>
			<li class="{{ (request()->is('produksi/produksi-bom*')) ? 'active' : '' }}">
				<a href="{{ url('produksi/produksi-bom') }}" class="text-bold"><i class="icon-clipboard6"></i>Produksi B.O.M</a>
			</li>
			{{-- <li class="{{ (request()->is('produksi/laporan-produksi*')) ? 'active' : '' }}">
				<a href="{{ url('produksi/laporan-produksi') }}" class="text-bold"><i class="icon-stack4"></i>Laporan Produksi</a>
			</li> --}}
		</ul>
	</li>
	<li class="{{ (request()->is('data-master/*')) ? 'active' : '' }}">
		<a href="#" class="has-ul"><i class="icon-grid"></i> <span>Data Master</span></a>
		<ul class="hidden-ul" style="{{ (request()->is('data-master/*')) ? 'display: block;' : 'display: none;' }}">
			<li class="{{ (request()->is('data-master/item*')) ? 'active' : '' }}">
				<a href="{{ url('data-master/item') }}" class="text-bold"><i class="icon-cube4"></i>Data Item</a>
			</li>
			<li class="{{ (request()->is('data-master/satuan*')) ? 'active' : '' }}">
				<a href="{{ url('data-master/satuan') }}" class="text-bold"><i class="icon-law"></i>Data Satuan</a>
			</li>
			<li class="{{ (request()->is('data-master/supplier*')) ? 'active' : '' }}">
				<a href="{{ url('data-master/supplier') }}" class="text-bold"><i class="icon-address-book2"></i>Data Supplier</a>
			</li>
			<li class="{{ (request()->is('data-master/costumer*')) ? 'active' : '' }}">
				<a href="{{ url('data-master/costumer') }}" class="text-bold"><i class="icon-address-book2"></i>Data Costumer</a>
			</li>
			<li class="{{ (request()->is('data-master/akun*')) ? 'active' : '' }}">
				<a href="{{ url('data-master/akun') }}" class="text-bold"><i class="icon-bookmark"></i>Data Akun</a>
			</li>
			<li class="{{ (request()->is('data-master/kas*')) ? 'active' : '' }}">
				<a href="{{ url('data-master/kas') }}" class="text-bold"><i class="icon-cash4"></i>Data Kas</a>
			</li>
		</ul>
	</li>

	<li class="{{ (request()->is('keuangan/*')) ? 'active' : '' }}">
		<a href="#" class="has-ul"><i class="icon-coins"></i> <span>Keuangan</span></a>
		<ul class="hidden-ul" style="{{ (request()->is('keuangan/*')) ? 'display: block;' : 'display: none;' }}">
			<li><a href="{{ url('keuangan/penerimaan-pembayaran') }}" class="text-bold"><i class="icon-cash2"></i>Penerimaan Pembayaran</a></li>
			<li><a href="{{ url('keuangan/pembayaran') }}" class="text-bold"><i class="icon-cash2"></i>Pembayaran</a></li>
			<li><a href="{{ url('keuangan/mutasi-kas') }}" class="text-bold"><i class="icon-cash2"></i>Mutasi Kas</a></li>
			<li><a href="{{ url('keuangan/lihat-kas') }}" class="text-bold"><i class="icon-cash2"></i>Lihat Kas</a></li>
			<li><a href="{{ url('keuangan/lihat-piutang') }}" class="text-bold"><i class="icon-cash2"></i>Lihat Piutang</a></li>
			<li><a href="{{ url('keuangan/lihat-hutang') }}" class="text-bold"><i class="icon-cash2"></i>Lihat Hutang</a></li>
		</ul>
	</li>

	<li class="{{ (request()->is('sistem/*')) ? 'active' : '' }}">
		<a href="#" class="has-ul"><i class="icon-equalizer2"></i> <span>Sistem</span></a>
		<ul class="hidden-ul" style="{{ (request()->is('sistem/*')) ? 'display: block;' : 'display: none;' }}">
			<li class="{{ (request()->is('sistem/penyesuaian-stock')) ? 'active' : '' }}"><a href="{{ url('sistem/penyesuaian-stock') }}" class="text-bold"><i class="icon-cube4"></i> Penyesuian Stock</a></li>
			<li class="{{ (request()->is('sistem/stock-opname')) ? 'active' : '' }}"><a href="{{ url('sistem/stock-opname') }}" class="text-bold"><i class="icon-cube4"></i> Stock Opname</a></li>
			<li class="{{ (request()->is('sistem/retur/*')) ? 'active' : '' }}">
				<a href="#" class="has-ul text-bold"><i class="icon-toggle"></i> Retur Item</a>
				<ul class="hidden-ul" style="{{ (request()->is('sistem/retur/*')) ? 'display: block;' : 'display: none;' }}">
					<li class="text-bold"><a href="{{ url('sistem/retur/retur-penjualan') }}"><i class="icon-rotate-cw"></i> Retur Penjualan</a></li>
					<li class="text-bold"><a href="{{ url('sistem/retur/retur-pembelian') }}"><i class="icon-rotate-ccw"></i> Retur Pembelian</a></li>
				</ul>
			</li>
			<li class="{{ (request()->is('sistem/set-*')) ? 'active' : '' }}">
				<a href="#" class="has-ul text-bold"><i class="icon-gear"></i> Set Nilai Awal</a>
				<ul class="hidden-ul" style="{{ (request()->is('sistem/set-*')) ? 'display: block;' : 'display: none;' }}">
					<li class="text-bold"><a href="{{ url('sistem/set-nilai-akun') }}"><i class="icon-book3"></i> Set Nilai Akun</a></li>
					<li class="text-bold"><a href="{{ url('sistem/set-nilai-item') }}"><i class="icon-cube3"></i> Set Nilai Item</a></li>
					<li class="text-bold"><a href="{{ url('sistem/set-nilai-kas') }}"><i class="icon-cash3"></i> Set Nilai Kas</a></li>
					<li class="text-bold"><a href="{{ url('sistem/set-nilai-piutang') }}"><i class="icon-credit-card"></i> Set Nilai Piutang</a></li>
					<li class="text-bold"><a href="{{ url('sistem/set-nilai-hutang') }}"><i class="icon-credit-card2"></i> Set Nilai Hutang</a></li>
				</ul>
			</li>
		</ul>
	</li>
    @if (Auth::user()->tipe_akun == 4 OR Auth::user()->tipe_akun == 1)
	<li class="{{ (request()->is('pengaturan/*')) ? 'active' : '' }}">
		<a href="#" class="has-ul"><i class="icon-equalizer2"></i> <span>Pengaturan</span></a>
		<ul class="hidden-ul" style="{{ (request()->is('pengaturan/*')) ? 'display: block;' : 'display: none;' }}">
			<li><a href="{{ url('pengaturan/pengaturan-akun') }}" class="text-bold"><i class="icon-gear"></i>Manajemen Akun</a></li>
			<li><a href="{{ url('pengaturan/manajemen-user') }}" class="text-bold"><i class="icon-users"></i>Manajemen User</a></li>
			<li><a href="{{ url('pengaturan/pengaturan-sistem') }}" class="text-bold"><i class="icon-cogs"></i>Manajemen Sistem</a></li>
		</ul>
	</li>
    @endif
	<li class="{{ (request()->is('akutansi/*')) ? 'active' : '' }}">
		<a href="#" class="has-ul"><i class="icon-stats-bars3"></i> <span>Akutansi</span></a>
		<ul class="hidden-ul" style="{{ (request()->is('akutansi/*')) ? 'display: block;' : 'display: none;' }}">
			<li class="{{ (request()->is('akutansi/jurnal*')) ? 'active' : '' }}">
				<a href="{{ url('akutansi/jurnal') }}" class="text-bold"><i class="icon-bookmark"></i>Jurnal</a>
			</li>
		</ul>
	</li>
	<li class="{{ (request()->is('laporan/*')) ? 'active' : '' }}">
		<a href="#" class="has-ul"><i class="icon-chart"></i> <span>Laporan</span></a>
		<ul class="hidden-ul" style="{{ (request()->is('laporan/*')) ? 'display: block;' : 'display: none;' }}">
			<li class="{{ (request()->is('laporan/stock-item*')) ? 'active' : '' }}">
				<a href="{{ url('laporan/stock-item') }}" class="text-bold"><i class="icon-file-spreadsheet2"></i>Laporan Stock Item</a>
			</li>
			<li class="{{ (request()->is('laporan/pembelian*')) ? 'active' : '' }}">
				<a href="{{ url('laporan/pembelian') }}"" class="text-bold"><i class="icon-file-spreadsheet2"></i>Laporan Pembelian</a>
			</li>
			<li class="{{ (request()->is('laporan/penjualan*')) ? 'active' : '' }}">
				<a href="{{ url('laporan/penjualan') }}"" class="text-bold"><i class="icon-file-spreadsheet2"></i>Laporan Penjualan</a>
			</li>
			<li class="{{ (request()->is('laporan/laba-rugi*')) ? 'active' : '' }}">
				<a href="{{ url('laporan/laba-rugi') }}"" class="text-bold"><i class="icon-file-spreadsheet2"></i>Laporan Laba&Rugi</a>
			</li>
            <li class="{{ (request()->is('laporan/neraca-saldo*')) ? 'active' : '' }}">
				<a href="{{ url('laporan/neraca-saldo') }}"" class="text-bold"><i class="icon-file-spreadsheet2"></i>Laporan Neraca Saldo</a>
			</li>
		</ul>
	</li>
    <li class="{{ (request()->is('ubah-password')) ? 'active' : '' }}" ><a href="{{ url('ubah-password') }}"><i class="icon-key"></i><span>Ubah Password</span></a></li>
	<li>
		<a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
		<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
			@csrf
		</form>
		<i class="icon-exit2"></i><span>Keluar</span></a>
	</li>

</ul>
