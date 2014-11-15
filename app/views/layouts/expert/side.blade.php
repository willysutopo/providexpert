<ul class="page-sidebar-menu " data-auto-scroll="true" data-slide-speed="200">
	<!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
	<li class="sidebar-toggler-wrapper">
		<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
		<div class="sidebar-toggler">
		</div>
		<!-- END SIDEBAR TOGGLER BUTTON -->
	</li>
	<!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
	<!--
	<li class="sidebar-search-wrapper">
		<!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
		<!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
		<!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box -->
		<!--
		<form class="sidebar-search " action="extra_search.html" method="POST">
			<a href="javascript:;" class="remove">
			<i class="icon-close"></i>
			</a>
			<div class="input-group">
				<input type="text" class="form-control" placeholder="Search...">
				<span class="input-group-btn">
				<a href="javascript:;" class="btn submit"><i class="icon-magnifier"></i></a>
				</span>
			</div>
		</form>
		<!-- END RESPONSIVE QUICK SEARCH FORM -->
	<!--</li>-->
	<li>
		<div class="input-group" style="padding:0px">&nbsp;
		</div>
	</li>
	<li class="{{ ( ( $menu_active == 'dashboard' ) ? 'active open' : '' ) }}">
		<a href="{{ route('expert.dashboard') }}">
		<i class="fa fa-question-circle"></i>
		<span class="title">Questions From Users</span>
		</a>
	</li>
	
	<!--
	<li class="{{ ( ( $menu_active == 'expert' ) ? 'active open' : '' ) }}">
		<a href="javascript:">
		<i class="fa fa-group"></i>
		<span class="title">Expert</span>
		<span class="arrow "></span>
		</a>
		<ul class="sub-menu">
			<li class="{{ ( ( $sub_menu_active == 'health' ) ? 'active' : '' ) }}">
				<a href="/expert/health"><i class="icon-list"></i> Health</a>
			</li>
			<li class="{{ ( ( $sub_menu_active == 'property' ) ? 'active' : '' ) }}">
				<a href="/expert/property"><i class="icon-list"></i> Property</a>
			</li>
			<li class="{{ ( ( $sub_menu_active == 'food' ) ? 'active' : '' ) }}">
				<a href="/expert/food"><i class="icon-list"></i> Food</a>
			</li>
			<li class="{{ ( ( $sub_menu_active == 'love' ) ? 'active' : '' ) }}">
				<a href="/expert/love"><i class="icon-list"></i> Love</a>
			</li>
			<li class="{{ ( ( $sub_menu_active == 'education' ) ? 'active' : '' ) }}">
				<a href="/expert/education"><i class="icon-list"></i> Education</a>
			</li>
		</ul>
	</li>
	-->
</ul>
