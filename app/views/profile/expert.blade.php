<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>My Profile | Providexpert</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/global/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="{{ asset('assets/global/css/components.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/global/css/plugins.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/admin/layout/css/layout.css') }}" rel="stylesheet" type="text/css"/>
<link id="style_color" href="{{ asset('assets/admin/layout/css/themes/default.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/admin/layout/css/custom.css') }}" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
<!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
<!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
<!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
<!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
<!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
<!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->
<body class="dashboard page-header-fixed page-quick-sidebar-over-content ">
<!-- BEGIN HEADER -->
@include('layouts.top')
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN SIDEBAR -->
	<div class="page-sidebar-wrapper">
		<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
		<div class="page-sidebar navbar-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->
			@include('layouts.expert.side', array('menu_active' => 'dashboard', 'sub_menu_active' => ''))
			<!-- END SIDEBAR MENU -->
		</div>
	</div>
	<!-- END SIDEBAR -->
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title">Modal title</h4>
						</div>
						<div class="modal-body">
							 Widget settings form goes here
						</div>
						<div class="modal-footer">
							<button type="button" class="btn blue">Save changes</button>
							<button type="button" class="btn default" data-dismiss="modal">Close</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<!-- /.modal -->
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			
			<!-- BEGIN PAGE HEADER-->
			<h3 class="page-title">
			My Profile
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="/">Home</a>
						<i class="fa fa-angle-right"></i>
					</li>					
					<li>
						My Profile
					</li>
				</ul>				
			</div>
			<!-- END PAGE HEADER-->

			@if (Session::has('errormsg'))
				<div class="bg-danger success_padder success_margin pt20 pb20 pl20 pr20 mb20">
					{{{ Session::get('errormsg') }}}
				</div>
			@endif

			@if (Session::has('message'))
				<div class="bg-success success_padder success_margin pt20 pb20 pl20 pr20 mb20">
					{{{ Session::get('message') }}}
				</div>
			@endif

			<div class="row">
				{{ Form::open(array(
					'route' => 'expert.profile.update.me',
					'role' => 'form',
					'class' => 'form-horizontal',
					'method' => 'PUT',
					'files'	=> true
				)) }}

				<div class="col-sm-6">

					<div class="form-group">
						<label class="col-sm-4 control-label" for="expert_name">Name</label>
						<div class="col-sm-8">
							{{ Form::text('expert_name', Input::old('expert_name', $user->expert->expert_name), array('class' => 'form-control')) }}
							{{ $errors->first('expert_name', '<p class="help-block text-danger" style="color:#ff0000">:message</p>') }}
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-4 control-label" for="email">Email</label>
						<div class="col-sm-8">
							{{ Form::input('email', 'email', Input::old('email', $user->email), array('class' => 'form-control', 'readonly' => '')) }}
							{{ $errors->first('email', '<p class="help-block text-danger" style="color:#ff0000">:message</p>') }}
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-4 control-label" for="category_id">Category</label>
						<div class="col-sm-8">
							{{ Form::select(
							'category_id',
							['' => '-- Please select --'] + $categoryList,
							Input::old('category_id', $user->expert->category_id), 
							array('class' => 'form-control required')
						) }}
							{{ $errors->first('category_id', '<p class="help-block text-danger" style="color:#ff0000">:message</p>') }}
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-4 control-label" for="expertises">Expertises</label>
						<div class="col-sm-8">
							{{ Form::text('expertises', Input::old('expertises', $user->expert->expertises), array('class' => 'form-control')) }}
							{{ $errors->first('expertises', '<p class="help-block text-danger" style="color:#ff0000">:message</p>') }}
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-4 control-label" for="address">Address</label>
						<div class="col-sm-8">
							{{ Form::textarea('address', Input::old('address', $user->address), array('class' => 'form-control', 'rows' => 3)) }}
							{{ $errors->first('address', '<p class="help-block text-danger" style="color:#ff0000">:message</p>') }}
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-4 control-label" for="phone">Phone</label>
						<div class="col-sm-8">
							{{ Form::input('tel', 'phone', Input::old('phone', $user->phone), array('class' => 'form-control')) }}
							{{ $errors->first('phone', '<p class="help-block text-danger" style="color:#ff0000">:message</p>') }}
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-4 control-label" for="photo">Photo</label>
						<div class="col-sm-8">
							{{ Form::file('pic_link', array('class' => 'form-control')) }}
						</div>
					</div>

					<div class="row">
						<div class="col-sm-offset-4 col-sm-8">
							{{ Form::submit('Save Changes', array('class' => 'btn blue-madison')) }}
						</div>
					</div>
					
				</div>
				

				{{ Form::close() }}
			</div>
			
			<!-- END PAGE CONTENT-->
		</div>
	</div>
	<!-- END CONTENT -->
	<!-- BEGIN QUICK SIDEBAR -->
	@include('layouts.quicksidebar')
	<!-- END QUICK SIDEBAR -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
@include('layouts.copyright')
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="{{ asset('assets/global/plugins/respond.min.js') }}"></script>
<script src="{{ asset('assets/global/plugins/excanvas.min.js') }}"></script> 
<![endif]-->
<script src="{{ asset('assets/global/plugins/jquery-1.11.0.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-migrate-1.2.1.min.js') }}" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="{{ asset('assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery.cokie.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/uniform/jquery.uniform.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="{{ asset('assets/global/plugins/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js' ) }}"></script>
<script type="text/javascript" src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script src="{{ asset('assets/global/scripts/metronic.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/admin/layout/scripts/layout.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/admin/layout/scripts/quick-sidebar.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/admin/layout/scripts/demo.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/admin/pages/scripts/custom/dashboard-managed.js') }}"></script>
<script src="{{ asset('assets/admin/pages/scripts/custom/providexpert.js') }}"></script>
<script>
jQuery(document).ready(function() {       
 	// initiate layout and plugins
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
	DashboardManaged.init();
});
</script>
</body>
<!-- END BODY -->
</html>