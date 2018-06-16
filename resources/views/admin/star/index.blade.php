@extends('voyager::master')

@section('page_title', @$page_title)

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-people"></i>{{@$page_title}}
        </h1>
        <a href="" class="btn btn-success btn-add-new">
            <i class="voyager-plus"></i> <span>添加</span>
        </a>
        <a class="btn btn-danger" id="bulk_delete_btn"><i class="voyager-trash"></i> <span>删除选中</span></a>

        {{-- Bulk delete modal --}}
        <div class="modal modal-danger fade" tabindex="-1" id="bulk_delete_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">
                            <i class="voyager-trash"></i> {{ __('voyager::generic.are_you_sure_delete') }} <span id="bulk_delete_count"></span> <span id="bulk_delete_display_name"></span>?
                        </h4>
                    </div>
                    <div class="modal-body" id="bulk_delete_modal_body">
                    </div>
                    <div class="modal-footer">
                        <form action="/0" id="bulk_delete_form" method="POST">
                            {{ method_field("DELETE") }}
                            {{ csrf_field() }}
                            <input type="hidden" name="ids" id="bulk_delete_input" value="">
                            <input type="submit" class="btn btn-danger pull-right delete-confirm"
                                   value="">
                        </form>
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">
                            取消
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        @include('voyager::multilingual.language-selector')

    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                <tr role="row">
                                    @foreach( $listing_cols as $col )
                                        <th>{{ $module->fields[$col]['label'] or ucfirst($col) }}</th>
                                    @endforeach
                                    @if($show_actions)
                                        <th id="bread-actions">Actions</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $("#dataTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: "/admin/stars_ajax",
            language: {
                lengthMenu: "_MENU_",
                search: "_INPUT_",
                searchPlaceholder: "Search"
            },
            @if($show_actions)
            columnDefs: [ { orderable: false, targets: [-1] }],
            @endif
        });
    </script>
@stop