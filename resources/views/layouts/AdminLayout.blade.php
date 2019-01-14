<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="Url library">
    <meta name="author" content="Url library">
    <title>Url library</title>
    <!-- Bootstrap core CSS-->
    <link href="{{asset('admin/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Custom fonts for this template-->
    <link href="{{asset('admin/vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    <!-- Page level plugin CSS-->
    <link href="{{asset('admin/vendor/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="{{asset('admin/css/sb-admin.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/style.css')}}" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
<div class="loader"></div>
<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="{{url('urllist')}}" style="font-size: 1rem;font-weight: 400;"> Url library</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                <a class="nav-link" href="{{'/'}}">
                    <i class="fa fa-fw fa-dashboard"></i>
                    <span class="nav-link-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Example Pages">
                <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseExamplePages1" data-parent="#exampleAccordion">
                    <i class="fa fa-fw fa-link"></i>
                    <span class="nav-link-text">URL</span>
                </a>
                <ul class="sidenav-second-level collapse" id="collapseExamplePages1">
                    <li>
                        <a href="{{url('urllist')}}">List</a>
                    </li>
                    <li>
                        <a href="{{url('url-list/create')}}">Add New</a>
                    </li>



                </ul>
            </li>

            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Example Pages">
                <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseExamplePages2" data-parent="#exampleAccordion">
                    <i class="fa fa-fw fa-tasks"></i>
                    <span class="nav-link-text">Category</span>
                </a>
                <ul class="sidenav-second-level collapse" id="collapseExamplePages2">
                    <li>
                        <a href="{{url('category')}}">List</a>
                    </li>
                    <li>
                        <a href="{{url('category/create')}}">Add New</a>
                    </li>



                </ul>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Example Pages">
                <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseExamplePages3" data-parent="#exampleAccordion">
                    <i class="fa fa-fw fa-tags"></i>
                    <span class="nav-link-text">Tags</span>
                </a>
                <ul class="sidenav-second-level collapse" id="collapseExamplePages3">
                    <li>
                        <a href="{{url('tag')}}">List</a>
                    </li>
                    <li>
                        <a href="{{url('tag/create')}}">Add New</a>
                    </li>
                </ul>
            </li>


        </ul>
        <ul class="navbar-nav sidenav-toggler">
            <li class="nav-item">
                <a class="nav-link text-center" id="sidenavToggler">
                    <i class="fa fa-fw fa-angle-left"></i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item" style="padding: 1px;">
                <a href="{{url('csvToexport')}}" class="btn btn-primary btn-sm">Export as CSV</a>
            </li>

            <li class="nav-item" style="padding: 1px;">
                <a href="csv-data" class="btn btn-success btn-sm">Import CSV</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href=""
                   onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
                    <i class="fa fa-fw fa-sign-out"></i>{{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

            </li>


        </ul>
    </div>
</nav>
<div class="content-wrapper">
        <!-- Breadcrumbs-->


        <!-- Icon Cards-->
        @yield('content')


</div>
<!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <footer class="sticky-footer">
        <div class="container">
            <div class="text-center">
                <small></small>
            </div>
        </div>
    </footer>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fa fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login">Logout</a>
                </div>

            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('admin/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- Core plugin JavaScript-->
    <script src="{{asset('admin/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
    <!-- Page level plugin JavaScript-->
    <script src="{{asset('admin/vendor/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('admin/vendor/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('admin/vendor/datatables/dataTables.bootstrap4.js')}}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{asset('admin/js/sb-admin.min.js')}}"></script>
    <!-- Custom scripts for this page-->
    <script src="{{asset('admin/js/sb-admin-datatables.min.js')}}"></script>
  {{--  <script src="{{asset('admin/js/sb-admin-charts.min.js')}}"></script>--}}


</div>
<script>
 function ShowDetail(ths) {
 var id = $(ths).attr('id');
$.ajax({
    type:"GET",
    url:"{{url('dashboard/linkdetail')}}?id="+id,
    success:function(res){
        $('#detail_page').show()
    }
});
 }
function ChangeCategory(ths,linkid) {
    var id = $(ths).val();
    $.ajax({
        type: "GET",
        url: "{{url('dashboard/ChangeCategory')}}?id=" + id + "&linkid=" + linkid,
        success: function (res) {
            $('#review' + res.id).text(res.review);
        }
    });
}
    function ChangeAudit(ths, linkid) {
        var id = $(ths).val();
        $.ajax({
            type: "GET",
            url: "{{url('dashboard/ChangeAudit')}}?id=" + id + "&linkid=" + linkid,
            success: function (res) {
                $('#review' + res.id).text(res.review);
            }
        });
    }

    $('#new_example').dataTable({
        "pageLength": 1000
    });
    var table = $('#new_example').DataTable();

    $('#myInput').on('keyup', function () {
        table.search(this.value).draw();
    });
    $("#checkAll").click(function () {
		
		localStorage.setItem('checkboxItems',this.checked);
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
	function allcheckBoxItems(){
		var check = localStorage.getItem('checkboxItems');
		if(check == 'true'){
			$('input:checkbox').prop('checked',true);
		}
	}
	allcheckBoxItems();
    function new_check() {
        $(".loader").show();
        var arr = [];
        $('input.yourClass:checkbox:checked').each(function () {
            arr.push($(this).val());
        });
        var myJSONText = JSON.stringify(arr);
        //alert(myJSONText);
        $.ajax({
            type: "GET",
            data: "select=" + myJSONText,
            url: "{{url('new_check')}}",
            success: function (res) {
                console.log(res);
                $(".loader").hide();
               // location.reload();
            }
        });
    }
	
	var interval = null;
	function new_check_background() {	
		interval=setInterval(get_session, 5000);	
        var arr = [];
        $('input.yourClass:checkbox:checked').each(function () {
            arr.push($(this).val());
        });
        var myJSONText = JSON.stringify(arr); 
        $.ajax({
            type: "GET",
            data: "select=" + myJSONText,
            url: "{{url('new_check_background')}}",
            success: function (res) {
                console.log(res);  
            }
        });
    }
	function get_session(){
		$.ajax({
			type: "GET",
			data: "select=check",
			url: "{{url('new_check_background_result')}}",
			success: function (res) {
				console.log(res);
				$('.percenatge_div').text(res+'%'); 
				if(res==='100') clearInterval(interval);
			}
		});
	}
    function checkHttp() {
        $(".loader").show();
        var arr = [];
        $('input.yourClass:checkbox:checked').each(function () {
            arr.push($(this).val());
        });
        var myJSONText = JSON.stringify(arr);
        $.ajax({
            type: "GET",
            data: "select=" + myJSONText,
            url: "{{url('checkHttp')}}",
            success: function (res) {
                console.log(res);
                $(".loader").hide();
                //location.reload();
            }
        });

    }

    function check_ip() {
    $(".loader").show();
    var myJSONText;
	var selectall='';
	if ($("#checkAll").is(":checked")) {
		 selectall='1';
	}else{
		var arr = [];
		$('input.yourClass:checkbox:checked').each(function () {
			arr.push($(this).val());
		});
		myJSONText = JSON.stringify(arr);
	} 
        $.ajax({
            type: "GET",
            data: "select=" + myJSONText+"&selectall="+selectall,
            url: "{{url('checkIp')}}",
            success: function (res) {
                console.log(res);
                $(".loader").hide();
                //location.reload();
            }
        });

    }
	function Check_Title(){
    $(".loader").show();
	var myJSONText;
	var selectall='';
	if ($("#checkAll").is(":checked")) {
		 selectall='1';
	}else{
		var arr = [];
		$('input.yourClass:checkbox:checked').each(function () {
			arr.push($(this).val());
		});
		myJSONText = JSON.stringify(arr);
	} 
    $.ajax({
        type: "GET",
        data: "select=" + myJSONText+"&selectall="+selectall,
        url: "{{url('CheckTitle')}}",
        success: function (res) {
            $(".loader").hide();
            console.log(res);
          // location.reload();
        }
    });

}
    function changeaudit_new(data, table, column, id) {
        var that=$(data);
        $.ajax({
            type:"GET",
            url:"{{url('audit/changeaudit_new')}}?table="+table+"&column="+column+"&id="+id,
            success:function(res){
                if(res.status=='1'){
                    that.text('Yes');
                   $('#review'+id).text(res.date);
                   $('#review'+id).removeClass('status_no');
                   $('#review'+id).addClass('status_yes');
                    that.removeClass('status_no');
                    that.addClass('status_yes');
                }else{
                    that.text('No');
                    $('#review'+id).text(res.date);
                    that.removeClass('status_yes');
                    that.addClass('status_no');
                }
            }
        });
    }

    function Change_review(data, table, column, id) {
        var date = new Date();
        var that = $(data);
        $.ajax({
            type: "GET",
            url: "{{url('audit/change-flag')}}?table=" + table + "&column=" + column + "&id=" + id,
            success: function (res) {
                if (res.status == '1') {
                    that.text(date);
                    that.removeClass('status_no');
                    that.addClass('status_yes');
                } else {
                    that.text('No');
                    that.removeClass('status_yes');
                    that.addClass('status_no');
                }
            }
        });
    }

    function open_url(ths, url) {
        window.open(url);
    }
	function Change_review(ths,id) {
            $.ajax({
                type: "GET",
                url: "{{url('dashboard/Change_review')}}?id="+id,
                success: function (res) {
                    console.log(res);
                    $('#review'+id).html(res.status);
                    $('#review'+id).removeClass('status_no');
                    $('#review'+id).addClass('status_yes');
                }
            });

    }
	

</script>
@yield("scripts")
</body>
</html>