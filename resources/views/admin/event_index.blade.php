@extends('admin.layouts.global')

@section('custom_css')
<link rel="stylesheet" type="text/css" href="plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="plugins/table/datatable/custom_dt_html5.css">
<link rel="stylesheet" type="text/css" href="plugins/table/datatable/dt-global_style.css">
<link rel="stylesheet" type="text/css" href="assets/css/forms/switches.css">
<link rel="stylesheet" type="text/css" href="plugins/select2/select2.min.css">
<link rel="stylesheet" type="text/css" href="assets/css/forms/theme-checkbox-radio.css">
@endsection

@section('custom_js')
<script src="plugins/table/datatable/datatables.js"></script>
<!-- NOTE TO Use Copy CSV Excel PDF Print Options You Must Include These Files  -->
<script src="plugins/table/datatable/button-ext/dataTables.buttons.min.js"></script>
<script src="plugins/table/datatable/button-ext/jszip.min.js"></script>
<script src="plugins/table/datatable/button-ext/buttons.html5.min.js"></script>
<script src="plugins/table/datatable/button-ext/buttons.print.min.js"></script>
<script src="plugins/select2/select2.min.js"></script>
<script type="text/javascript">
    var _token = '{{csrf_token()}}';
    var oTable;
    $(function(){


        $(".permission").select2({
            tags: true,
        });

        oTable = $("#datatable-table").DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('admin.event.list') }}",
                    "data": function ( d ) {
                        d.action = "LOADDATAS";
                        d._token = _token;
                    }
                },
                "columns": [
                    { "data": "cpId", "width":"10%"  },
                    { "data": "cpUserID", "width":"30%"  },
                    { "data": "cpLink", "width":"25%"  },
                    { "data": "cpType", "width":"8%"  },
                    { "data": "cpStatus", "width":"8%"  },
                    { "data": "cpTakeTime", "width":"20%"  },
                ],


                "language":{
                    "url": "plugins/table/datatable/language/zh_TW.txt"
                },

                dom: '<"row"<"col-md-12"<"row"<"col-md-6"l><"col-md-6"f> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',

                buttons: {
                    buttons: [
                        { extend: 'csv', className: 'btn' },
                        { extend: 'excel', className: 'btn' },
                    ]
                },
                "columnDefs": [ {
                  "targets": 'nosort',
                  "orderable": false
                } ],
                "iDisplayLength": 50,
                "aaSorting": [[ 0, "desc" ]]
            });


            $('#datatable-table').on( 'draw.dt', function () {

            });

            $("a.btn-event-export").on('click', function(event){
                window.open("{{ route('admin.event.export') }}");
            });





            $('#usersEditModal').on('hidden.bs.modal', function (e) {
                $("#usersFieldsID, #usersFieldsName, #emailFieldsEmail, #passwordFieldsPassword, #passwordFieldsPasswordConfirm ").val('');
                $('input[type="checkbox"][name="permission[]"]').each(function(i, v){
                    $(this).prop("checked", false);
                });

            })

        @if (session('status'))
            fnNotifications('{{ session('status') }}');
        @endif

    });


</script>
@endsection



@section('content_html')
    @parent

    <div class="row layout-spacing layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-sm-12 ">

            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-8 col-md-8 col-sm-12 col-12">
                            <h4> {{ $list['title'] }} </h4>
                        </div>
                        <div class="col-xl-4 col-md-4 col-sm-12 col-12 text-md-right">
                            <h4> <a class="btn btn-primary btn-event-export" href="javascript:void(0);"> <i class="fas fa-edit"></i> 匯出活動資料 </a></h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area ">
                    <div class="table-responsive mb-4 mt-4">
                        <table id="datatable-table" class="table table-hover non-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th name="cpId">編號</th>
                                    <th name="cpUserID">LINE</th>
                                    <th name="cpLink" class="nosort"> LINK </th>
                                    <th name="cpType" class="nosort">獎項</th>
                                    <th name="cpStatus" class="nosort">資格</th>
                                    <th name="cpTakeTime">領取時間</th>
                                    <!--<th name="action" class="nosort">動作</th>-->
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>


        </div>

    </div>




@endsection
