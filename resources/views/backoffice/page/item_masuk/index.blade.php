@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Data Transaksi - Item Masuk @endsection
@section('style')
@endsection
@section('content')
<div class="content" id="app">
	<div class="row">
		<div class="col-sm-12">
			<index-item-masuk></index-item-masuk>
		</div>
	</div>
</div>

@endsection
@section('script')
<script>
    
	




</script>
@endsection

