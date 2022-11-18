@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Kelola Menu - Menu @endsection
@section('style')
@endsection
@section('content')
<div class="content" id="app">
	<div class="row">
		<div class="col-sm-12">
			<index-menu></index-menu>
		</div>
	</div>
</div>

@endsection
@section('script')
<script>
    
	




</script>
@endsection

