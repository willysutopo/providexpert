<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>Top Up Credits | Providexpert</title>
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
<body class="topup_page page-header-fixed page-quick-sidebar-over-content ">
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
			@include('layouts.side', array('menu_active' => 'topup', 'sub_menu_active' => ''))
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
			Top Up Credits
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="/">Home</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						Top Up Credits
					</li>
				</ul>				
			</div>
			<!-- END PAGE HEADER-->
			<div class="row">
				<div class="col-md-12 col-xs-12">
@if (Session::has('done'))
   <div class="alert alert-success">{{ Session::get('done') }}<button type="button" class="close" data-close="alert"></button></div>
@endif
@if (Session::has('fail'))
   <div class="alert alert-danger">{{ Session::get('fail') }}<button type="button" class="close" data-close="alert"></button></div>
@endif

					<h1 class="current_credit_text">Your Current Credits : <span class="current_credit">{{ $user->credits }}</span></h1>
					<div class="pt15 pl20">		
						<h2>Price List</h2>
					</div>
					<form action="{{ route('topup.sale') }}" id="topup" class="form-horizontal" method="post">
					{{Form::token()}}
					<div class="form-group">
						<div class="pl20">
							<div class="radio-list price_option">
								<label>
								<input type="radio" name="price" value="1" checked="" /> <span>$1 For 3 Credits</span></label>
								<label>
								<input type="radio" name="price" value="2" /> <span>$2 For 7 Credits</span></label>
								<label>
								<input type="radio" name="price" value="5" /> <span>$5 For 18 Credits</span></label>	
							</div>
						</div>
					</div>
					</form>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12 col-xs-12">
					<div class="mt10">
						<button class="btn green" type="button" id="btn_pay"><i class="fa fa-credit-card"></i> Pay via Credit Card</button>
						<a href="{{route("dashboard")}}" class="btn default"><i class="fa fa-undo"></i> Cancel</a>
					</div>
				</div>
			</div>
			<hr />
			<h3>Transactions History</h3>
			<div class="row">
				<div class="col-md-12 col-xs-12">
					<div class="mt10">
						<?php 
							$data = $user_->transactions()->orderBy("id", "desc")->paginate(5);
						?>
						@if($data)					
						<table class="table table-hover">
							<thead>
							<tr>
								<th>
									 Trx ID
								</th>
								<th>
									 Trx Date
								</th>
								<th>
									 Status
								</th>
								<th>
									 Amount
								</th>
							</tr>
							</thead>
							<tbody>
							@if($data->count() == 0)
							<tr>
								<td colspan="10" class="text-center">
									<div class="label label-danger">no transaction history</div>
								</td>
							</tr>
							@else
							@foreach($data as $i => $row)
							<tr>
								<td>
									{{$row->trx_id}}
								</td>
								<td>
									{{date("d M Y H:i:s", strtotime($row->created_at))}}
								</td>
								<td>
									 {{$row->status}}
								</td>
								<td>
									 ${{$row->amount}}
								</td>
							</tr>
							@endforeach
							@endif
							</tbody>
						</table>	

						{{$data->links()}}					
						@endif
					</div>
				</div>
			</div>			
			
<!-- Modal -->
<div class="modal fade" id="loading" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="alert alert-danger text-center">Currently Your Credit Card not configured.</div>
      <div class="modal-body">
			<form action="{{ route('profile.paypal.sync') }}" class="form-horizontal" method="post">
				{{Form::token()}}
				<div class="form-body">
					<div class="form-group">
						<label class="col-md-3 control-label">Credit Card</label>
						<div class="col-md-9">
							<div class="input-inline input-medium">
								<div class="input-group">
									<span class="input-group-addon">
									<i class="fa fa-credit-card"></i>
									</span>
									<input name="cc_num" type="text" class="form-control" placeholder="Credit Card Number" autocomplete="off">
								</div>
							</div>
							<span class="help-block"><i class="text text-success">Our system not collect your confident information as CC Number for high security reason.</i></span>
							<span class="text text-danger">{{ $errors->first('cc_num'); }}</span>
						</div>
					</div>						
					<div class="form-group">
						<label class="col-md-3 control-label">Cvv</label>
						<div class="col-md-3">
							<input name="cvv_num" type="text" maxlength="4" size="4" class="form-control">
							<span class="text text-danger">{{ $errors->first('cvv_num'); }}</span>
						</div>
						<span class="help-block"><i class="text text-success">Let see on back your Credit Card, usually 3 Number.</i></span>
						
					</div>
					<div class="form-group">
							<label class="col-md-3 control-label">Expired</label>
							<div class="col-md-3">
								<input name="month" type="text" maxlength="2" size="2" class="form-control" placeholder="Month">
								<span class="text text-danger">{{ $errors->first('month'); }}</span>
							</div>
							<div class="col-md-3">
								<input name="year" type="text" maxlength="4" size="4" class="form-control" placeholder="Year">
								<span class="text text-danger">{{ $errors->first('year'); }}</span>
							</div>


					</div>
					<div class="form-group">
							<label class="col-md-3 control-label"></label>
							<div class="col-md-9">
								<button type="submit" class="btn btn-success">Save</button>
								<button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
							</div>

					</div>
				</div>
			</form>
        
      </div>
      
    </div>
  </div>
</div>

<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="alert alert-success text-center">Are you sure  want to topup?</div>
      <div class="modal-body text-center">
			<button type="submit" id="btn_submit" class="btn btn-success">Submit</button>
			<button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
      </div>
    </div>
  </div>
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

	$("#btn_pay").click(function(){
		@if(! $user_->paypal)
			$("#loading").modal("show");
		@else
			$("#confirm").modal("show");
		@endif
	});

	$("#btn_submit").click(function(){
		$("#topup").submit();
	});

	
});
</script>
</body>
<!-- END BODY -->
</html>