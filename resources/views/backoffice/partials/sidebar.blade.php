<div class="sidebar-content">
	<!-- User menu -->
	<div class="sidebar-user">
		<div class="category-content">
			<div class="media">
				<a href="#" class="media-left"><img src="{{ asset('user.jpg') }}" class="img-circle img-sm" alt=""></a>
				<div class="media-body">
					<span class="media-heading text-semibold">{{ Auth::user()->name }}</span>
					<div class="text-size-mini text-muted">
						<i class="status-mark border-success position-left"></i> Online
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /user menu -->

	<!-- Main navigation -->
	<div class="sidebar-category sidebar-category-visible">
		<div class="category-content no-padding">
			@include('backoffice.partials.menu')
		</div>
	</div>
	<!-- /main navigation -->
</div>
