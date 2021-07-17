@extends('crudbooster::admin_template')

@section('content')
<div class="col-sm-12">
    <form method="GET">
        <div class="col-sm-3">
            <div class="form-group form-datepicker header-group-0 " id="form-group-start_date" style="">
                <label class="control-label col-sm-2">Select Year</label>
                <div class="col-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon open-datetimepicker"><a><i
                                    class="fa fa-calendar "></i></a></span>
                        <input type="text" title="Start Year" readonly="" class="form-control notfocus input_date"
                            name="year_select" id="year_select" value="{{$params['year_select']}}">
                    </div>
                    <div class="text-danger"></div>
                    <p class="help-block"></p>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <input type="submit" name="submit" value="Search" class="btn btn-success">
        </div>
    </form>
</div>
<br>
<?php $month = 1; ?>
            <div class="tabs">
                <div class="tab-button-outer">
                    <ul id="tab-button">
                    <li><a href="#tab01">Normal</a></li>
                    <li><a href="#tab02">Table</a></li>
                    </ul>
                </div>
                
                <div id="tab01" class="tab-contents">
                    <div class="col-sm-12">
                        <br>
                        @while ($month <= 12)
                        <div class="col-sm-4 text-center">
                            <div class="month-item">
                                <h3 class="">{{date('M',strtotime($year.'-'.$month.'-01'))}}</h3>
                                <h4> Total Order: {{number_format($orders[$month]->total_order)}}</h4>
                                <h4> Total Sale: {{number_format($orders[$month]->sum_grand_total,2)}}</h4>
                            </div>
                        </div>
                        <?php $month++?>
                        
                        @endwhile
                    </div>
                </div>
                <div id="tab02" class="tab-contents">
                    <table class="table table-light datatable">
                        <thead>
                            <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Month</th>
                            <th class="text-center">Total Order</th>
                            <th class="text-center">Total Sale</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $month = 1;?>
                        @while ($month <= 12)
                        <tr class="text-center">
                            <td class="text-center">{{$month}}</td>
                            <td>{{date('M',strtotime($year.'-'.$month.'-01'))}}</td>
                            <td>{{number_format($orders[$month]->total_order)}}</td>
                            <td>{{number_format($orders[$month]->sum_grand_total, 2)}}</td>
                        <?php $month++?>
                        </tr>
                        @endwhile
                        </tbody>
                    </table>
                </div>
                </div>
@endsection

@push('head')
<link rel='stylesheet' href='<?php echo asset("vendor/crudbooster/assets/select2/dist/css/select2.min.css")?>' />
<style type="text/css">
    .month-item {
        border: 1px solid green;
        margin: 5px;
    }
    .select2-container--default .select2-selection--single {
        border-radius: 0px !important
    }

    .select2-container .select2-selection--single {
        height: 35px
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #3c8dbc !important;
        border-color: #367fa9 !important;
        color: #fff !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff !important;
    }

    .at-l {
        text-align: left;
    }

    .bl li,
    .nav {
        font-size: 18px;
    }


    td.bl {
        border-left: 1px solid rgba(0, 0, 0, .125);
        padding-left: 5px;
    }

    td.w40 {
        width: 40%;
    }

    td.w30 {
        width: 30%;
    }

    .card {
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, .125);
        border-radius: .25rem;
        text-align: center;
    }

    .card-header {
        padding: .75rem 1.25rem;
        margin-bottom: 0;
        background-color: rgba(0, 0, 0, .03);
        border-bottom: 1px solid rgba(0, 0, 0, .125);
    }

    .font-weight-normal {
        font-weight: 400 !important;
    }

    .card-deck .card {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-flex: 1;
        -ms-flex: 1 0 0%;
        flex: 1 0 0%;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        margin-right: 15px;
        margin-bottom: 0;
        margin-left: 15px;
    }

    .box-shadow {
        box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, .05);
    }

    .mb-4,
    .my-4 {
        margin-bottom: 1.5rem !important;
    }
    .col-sm-7 {
        width: 14.28% ;
    }
    .tabs {
    max-width: 100%;
    margin: 0 auto;
    }
    #tab-button {
    display: table;
    table-layout: fixed;
    width: 100%;
    margin: 0;
    padding: 0;
    list-style: none;
    }
    #tab-button li {
    display: table-cell;
    width: 20%;
    }
    #tab-button li a {
    display: block;
    padding: .5em;
    background: #eee;
    border: 1px solid #ddd;
    text-align: center;
    color: #000;
    text-decoration: none;
    }
    #tab-button li:not(:first-child) a {
    border-left: none;
    }
    #tab-button li a:hover,
    #tab-button .is-active a {
    border-bottom-color: transparent;
    background: #fff;
    }
    .tab-contents {
    padding: .5em 2em 1em;
    border: 1px solid #ddd;
    }
    
    .tab-button-outer {
    display: none;
    }
    .tab-contents {
    margin-top: 20px;
    }
    @media screen and (min-width: 768px) {
        .tab-button-outer {
            position: relative;
            z-index: 2;
            display: block;
        }
        .tab-select-outer {
            display: none;
        }
        .tab-contents {
            position: relative;
            top: -1px;
            margin-top: 0;
        }
    }
</style>
@endpush
@push('bottom')
<script src='<?php echo asset("vendor/crudbooster/assets/select2/dist/js/select2.full.min.js")?>'></script>
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
<script>
    jQuery.extend( jQuery.fn.dataTableExt.oSort, {
        "numeric-comma-pre": function ( a ) {
            var x = (a == "-") ? 0 : a.replace( /,/, "." );
            return parseFloat( x );
        },
    
        "numeric-comma-asc": function ( a, b ) {
            return ((a < b) ? -1 : ((a > b) ? 1 : 0));
        },
    
        "numeric-comma-desc": function ( a, b ) {
            return ((a < b) ? 1 : ((a > b) ? -1 : 0));
        }
    } );
    $(function() {
        $('.datatable').DataTable({
            "pageLength": 50,
            "columnDefs": [
                {
                "defaultContent": "-",
                "targets": "_all"
                },
                { type: 'numeric-comma', targets: 0 }
            ],
            dom: 'Bfrtip',  
            "buttons": [
            'csv', 'excel',
        ]
        });

        var $tabButtonItem = $('#tab-button li'),
            $tabSelect = $('#tab-select'),
            $tabContents = $('.tab-contents'),
            activeClass = 'is-active';
      
        $tabButtonItem.first().addClass(activeClass);
        $tabContents.not(':first').hide();
      
        $tabButtonItem.find('a').on('click', function(e) {
          var target = $(this).attr('href');
      
          $tabButtonItem.removeClass(activeClass);
          $(this).parent().addClass(activeClass);
          $tabSelect.val(target);
          $tabContents.hide();
          $(target).show();
          e.preventDefault();
        });
      
        $tabSelect.on('change', function() {
          var target = $(this).val(),
              targetSelectNum = $(this).prop('selectedIndex');
      
          $tabButtonItem.removeClass(activeClass);
          $tabButtonItem.eq(targetSelectNum).addClass(activeClass);
          $tabContents.hide();
          $(target).show();
        });
      });
</script>
@endpush