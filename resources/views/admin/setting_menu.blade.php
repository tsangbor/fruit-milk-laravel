@extends('admin.layouts.global')

@section('custom_css')
<link href="assets/css/scrollspyNav.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="plugins/bootstrap-select/bootstrap-select.min.css">
<link href="plugins/drag-and-drop/dragula/dragula.css" rel="stylesheet" type="text/css" />
<link href="plugins/drag-and-drop/dragula/example.css" rel="stylesheet" type="text/css" />

@endsection

@section('custom_js')
<script src="assets/js/scrollspyNav.js"></script>
<script src="plugins/drag-and-drop/dragula/dragula.min.js"></script>
<script>
    var _token = '{{csrf_token()}}';


    $(document).ready(function() {

        $("a.btn-save").click(function(event){
            $("#submitfrm").submit();
        });
        $("a.btn-add-link").on('click', function(event){
            event.preventDefault();
            var parentElement = $(this).parent().attr("id");
            var group = $(this).data("group");
            $html = '<div class="media d-md-flex d-block text-sm-left text-center">'+
                        '<div class="media-body">'+
                            '<div class="d-xl-flex d-block justify-content-between">'+
                                '<div class="form-row col-md-12 ">'+
                                    '<div class="form-group col-md-3">'+
                                        '<input name="menu['+group+'][title][]" type="text" class="form-control" placeholder="名稱">'+
                                    '</div>'+
                                    '<div class="form-group col-md-6">'+
                                        '<input name="menu['+group+'][link][] type="text" class="form-control" placeholder="連結">'+
                                    '</div>'+
                                    '<div class="form-group col-md-2">'+
                                        '<select name="menu['+group+'][target][]" class="form-control">'+
                                            '<option value="_self" selected>原視窗</option>'+
                                            '<option value="_blank">新視窗</option>'+
                                        '</select>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>';
            $('#'+parentElement+' .dragula').append($html);
            $_linkSortable();
        });
        function $_linkSortable() {
            $('[data-sortable="true"]').sortable({
                items: ".media",
                cursor: 'move',
                placeholder: "ui-state-highlight",
                refreshPosition: true,
                dropOnEmpty: false,
                stop: function( event, ui ) {
                    var parent_ui = ui.item.parent().attr('data-section');
                },
                update: function( event, ui ) {
                    getParentElement = $(this).parents('[data-connect="sorting"]').attr('data-section');
                    var html = $('div[data-section="'+getParentElement+'"] .connect-sorting-content').html();
                    console.log(html);
                }
            });
        }

        $_linkSortable();

        @if (session('status'))
        fnNotifications('{{ session('status') }}');
        @endif

    });
</script>

@endsection



@section('content_html')
    @parent

    <div id="content" class="main-content">
        <form method="post" enctype="multipart/form-data" action="{{ route('admin.setting.menu.update') }}" id="submitfrm">
            @csrf
        <div class="layout-px-spacing">
            <div class="row layout-spacing layout-top-spacing">

                <div class="col-xl-12 col-lg-12 col-sm-12 ">
                    <div class="col-lg-12 layout-spacing layout-top-spacing">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <h4> Header/Footer 選單</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="widget-content widget-content-area border-top-tab">
                                <ul class="nav nav-tabs mb-3 mt-3" id="borderTop" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="menu-header-1" data-toggle="tab" href="#menu-header-link-1" role="tab" aria-controls="border-top-home" aria-selected="true">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-link"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                                        Header 主選單</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="menu_footer-1" data-toggle="tab" href="#menu_footer-link-1" role="tab" aria-controls="border-top-profile" aria-selected="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-link"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                                             Footer第一區選單</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="menu_footer-2" data-toggle="tab" href="#menu_footer-link-2" role="tab" aria-controls="border-top-contact" aria-selected="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-link"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                                            Footer第二區選單</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="menu_footer-3" data-toggle="tab" href="#menu_footer-link-3" role="tab" aria-controls="border-top-setting" aria-selected="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-link"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                                            Footer第三區選單</a>
                                    </li>
                                </ul>
                                <div class="tab-content parent ex-1" id="borderTopContent">
                                    <div class="tab-pane fade show active" id="menu-header-link-1" role="tabpanel">
                                        <a class="btn btn-success btn-add-link" data-group="header" >新增選單</a>
                                        <div class="dragula" data-sortable="true" style="padding: 5px;">
                                            @if( isset($navi['header']) )
                                                @foreach( $navi['header'] as $header )
                                                <div class="media d-md-flex d-block text-sm-left text-center">
                                                    <div class="media-body">
                                                        <div class="d-xl-flex d-block justify-content-between">
                                                            <div class="form-row col-md-12 ">
                                                                <div class="form-group col-md-3">
                                                                    <input value="{{$header->title}}" name="menu[header][title][]" type="text" class="form-control" placeholder="名稱">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <input value="{{$header->link}}" name="menu[header][link][] type="text" class="form-control" placeholder="連結">
                                                                </div>
                                                                <div class="form-group col-md-2">
                                                                    <select name="menu[header][target][]" class="form-control">
                                                                        <option value="_self" @if( $header->target == '_self' ) selected @endif >原視窗</option>
                                                                        <option value="_blank" @if( $header->target == '_blank' ) selected @endif >新視窗</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            @endif
                                            <!--
                                            <div class="media d-md-flex d-block text-sm-left text-center">
                                                <div class="media-body">
                                                    <div class="d-xl-flex d-block justify-content-between">
                                                        <div class="form-row col-md-12 ">
                                                            <div class="form-group col-md-3">
                                                                <input name="menu[header][title][]" type="text" class="form-control" placeholder="名稱">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <input name="menu[header][link][] type="text" class="form-control" placeholder="連結">
                                                            </div>
                                                            <div class="form-group col-md-2">
                                                                <select name="menu[header][target][]" class="form-control">
                                                                    <option value="_self" selected>原視窗</option>
                                                                    <option value="_blank">新視窗</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>-->
                                        </div>

                                    </div>
                                    <div class="tab-pane fade" id="menu_footer-link-1" role="tabpanel" >
                                        <a class="btn btn-success btn-add-link" data-group="footer-1" >新增選單 </a>
                                        <div class="dragula" data-sortable="true" style="padding: 5px;">
                                            @if( isset($navi['footer-1']) )
                                                @foreach( $navi['footer-1'] as $footer1 )
                                                <div class="media d-md-flex d-block text-sm-left text-center">
                                                    <div class="media-body">
                                                        <div class="d-xl-flex d-block justify-content-between">
                                                            <div class="form-row col-md-12 ">
                                                                <div class="form-group col-md-3">
                                                                    <input value="{{$footer1->title}}" name="menu[footer-1][title][]" type="text" class="form-control" placeholder="名稱">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <input value="{{$footer1->link}}" name="menu[footer-1][link][] type="text" class="form-control" placeholder="連結">
                                                                </div>
                                                                <div class="form-group col-md-2">
                                                                    <select name="menu[footer-1][target][]" class="form-control">
                                                                        <option value="_self" @if( $footer1->target == '_self' ) selected @endif >原視窗</option>
                                                                        <option value="_blank" @if( $footer1->target == '_blank' ) selected @endif >新視窗</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            @endif</div>
                                    </div>
                                    <div class="tab-pane fade" id="menu_footer-link-2" role="tabpanel" >
                                        <a class="btn btn-success btn-add-link" data-group="footer-2"  >新增選單 </a>
                                        <div class="dragula" data-sortable="true" style="padding: 5px;">
                                            @if( isset($navi['footer-2']) )
                                                @foreach( $navi['footer-2'] as $footer2 )
                                                <div class="media d-md-flex d-block text-sm-left text-center">
                                                    <div class="media-body">
                                                        <div class="d-xl-flex d-block justify-content-between">
                                                            <div class="form-row col-md-12 ">
                                                                <div class="form-group col-md-3">
                                                                    <input value="{{$footer2->title}}" name="menu[footer-2][title][]" type="text" class="form-control" placeholder="名稱">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <input value="{{$footer2->link}}" name="menu[footer-2][link][] type="text" class="form-control" placeholder="連結">
                                                                </div>
                                                                <div class="form-group col-md-2">
                                                                    <select name="menu[footer-2][target][]" class="form-control">
                                                                        <option value="_self" @if( $footer2->target == '_self' ) selected @endif >原視窗</option>
                                                                        <option value="_blank" @if( $footer2->target == '_blank' ) selected @endif >新視窗</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            @endif</div>

                                    </div>
                                    <div class="tab-pane fade" id="menu_footer-link-3" role="tabpanel" >
                                        <a class="btn btn-success btn-add-link" data-group="footer-3"  >新增選單 </a>
                                        <div class="dragula" data-sortable="true" style="padding: 5px;">
                                            @if( isset($navi['footer-3']) )
                                                @foreach( $navi['footer-3'] as $footer3 )
                                                <div class="media d-md-flex d-block text-sm-left text-center">
                                                    <div class="media-body">
                                                        <div class="d-xl-flex d-block justify-content-between">
                                                            <div class="form-row col-md-12 ">
                                                                <div class="form-group col-md-3">
                                                                    <input value="{{$footer3->title}}" name="menu[footer-3][title][]" type="text" class="form-control" placeholder="名稱">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <input value="{{$footer3->link}}" name="menu[footer-3][link][] type="text" class="form-control" placeholder="連結">
                                                                </div>
                                                                <div class="form-group col-md-2">
                                                                    <select name="menu[footer-3][target][]" class="form-control">
                                                                        <option value="_self" @if( $footer3->target == '_self' ) selected @endif >原視窗</option>
                                                                        <option value="_blank" @if( $footer3->target == '_blank' ) selected @endif >新視窗</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            @endif</div>

                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>



                <div class="wedget-footer ">
                    <div class="col-xl-12 col-lg-12 col-sm-12 ">
                        <div class="col-lg-12">
                            <a class="btn btn-primary btn-save" href="javascript:void(0);">儲存</a>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </form>
    </div>

@endsection
