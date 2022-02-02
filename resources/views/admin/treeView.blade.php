@extends('admin.layout.app')
@section('content')
@section('meta_title','Tree View')
@section('meta_keyword','Tree View')
@section('meta_description','Tree View')

<style>
    /*Now the CSS*/

    * {
        margin: 0;
        padding: 0;
    }

    .row {
        border-bottom: 0px !important;
        border-right: 0px !important;
    }

    .tree ul {
        padding-top: 20px;
        position: relative;
        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;

    }

    .tree li {
        float: left;
        text-align: center;
        list-style-type: none;
        position: relative;
        padding: 20px 1px 0 1px;
        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;

    }

    /*We will use ::before and ::after to draw the connectors*/

    .tree li::before,
    .tree li::after {
        content: '';
        position: absolute;
        top: 0;
        right: 50%;
        border-top: 1px solid #ccc;
        width: 50%;
        height: 20px;

    }

    .tree li::after {
        right: auto;
        left: 50%;
        border-left: 1px solid #ccc;
    }

    /*We need to remove left-right connectors from elements without 
        any siblings*/
    .tree li:only-child::after,
    .tree li:only-child::before {
        display: none;
    }

    /*Remove space from the top of single children*/
    .tree li:only-child {
        padding-top: 0;
    }

    /*Remove left connector from first child and 
        right connector from last child*/
    .tree li:first-child::before,
    .tree li:last-child::after {
        border: 0 none;
    }

    /*Adding back the vertical connector to the last nodes*/
    .tree li:last-child::before {
        border-right: 1px solid #ccc;
        border-radius: 0 5px 0 0;
        -webkit-border-radius: 0 5px 0 0;
        -moz-border-radius: 0 5px 0 0;
    }

    .tree li:first-child::after {
        border-radius: 5px 0 0 0;
        -webkit-border-radius: 5px 0 0 0;
        -moz-border-radius: 5px 0 0 0;
    }

    /*Time to add downward connectors from parents*/
    .tree ul ul::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        border-left: 1px solid #ccc;
        width: 0;
        height: 20px;
    }

    .tree li a {
        /* border: 1px solid #ccc; */
        /* background-color: #c1c1c1; */
        padding: 10px 0px !important;
        text-decoration: none;
        color: #666;
        font-family: arial, verdana, tahoma;
        font-size: 12px !important;
        display: inline-block;
        border-radius: 5px;
        -webkit-border-radius: 15px;
        -moz-border-radius: 5px;
        transition: all 0.5s;
        -webkit-transition: all 0.5s;

        -moz-transition: all 0.5s;
        width: 85px !important;
        height: 80px !important;
        /*border-color:1px solid black;*/
        text-align: center;
    }

    /*Time for some hover effects*/
    /*We will apply the hover effect the the lineage of the element also*/
    .tree li a:hover,
    .tree li a:hover+ul li a {
        /*background: #c8e4f8;*/
        /* background: #428bca; */
        color: #000;
        /* border: 1px solid #94a0b4; */
        position: relative;
    }

    /*Connector styles on hover*/
    .tree li a:hover+ul li::after,
    .tree li a:hover+ul li::before,
    .tree li a:hover+ul::before,
    .tree li a:hover+ul ul::before {
        border-color: #94a0b4;
    }

    /*.hover1 {
        position:relative;
        top:50px;
        left:50px;
        }*/


    .hover1:hover+.tooltip {
        /* display tooltip on hover */

    }

    [role="button"],
    a,
    area,
    button,
    input,
    label,
    select,
    summary,
    textarea {
        -ms-touch-action: manipulation;
        touch-action: manipulation;
    }

    a {
        color: #007bff;
        text-decoration: none;
        background-color: transparent;
        -webkit-text-decoration-skip: objects;
    }

    .card {
        word-wrap: break-word;
    }

    /*Thats all. I hope you enjoyed it.
        Thanks :)*/
    .list-inline {
        display: flex;
        justify-content: center;
    }





    .tooltipme {
        position: relative;
        display: inline-block;
        margin-top: 10px;
        margin-bottom: 0px;

    }

    .tooltipme .tooltipmetext {
        z-index: 888 !important;
        visibility: hidden;
        font-size: 14px;
        font-weight: 100;
        line-height: 18px;
        width: 280px;
        background-color: #000;
        color: #fff;
        text-align: left;
        border-radius: 6px;
        padding: 5px;
        position: absolute;
        top: 90%;
        left: 50%;
        margin-left: -140px;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .tooltipme .tooltipmetext::after {
        content: "";
        position: absolute;
        bottom: 100%;
        left: 50%;
        margin-left: -10px;
        border-width: 10px;
        border-style: solid;
        border-color: transparent transparent #555 transparent;
    }

    .tooltipme:hover .tooltipmetext {
        visibility: visible;
        opacity: 1;
        border: #007bff solid 2px;
    }

    .br-50 {
        border-radius: 50%;
    }
</style>

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex align-items-center">
                        <h5 class="page-title">Dashboard</h5>
                        <ul class="breadcrumb ml-2">
                            <li class="breadcrumb-item"><a href="index.html"><i class="fas
                                        fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">@yield('meta_title')</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">@yield('meta_title')</h5>
                        @if(Session::has('message'))
                        <p class="{{ Session::get('alert-class') }}">
                            {{ Session::get('message') }}
                        </p>
                        @endif
                        <p id="requestMessage" class="text-warning"></p>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-sm btn-primary" onclick="backGenerateTree()" style="float: left; clear: both;">Back</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12" style="width: 100%; overflow-x: auto;">
                                <div>
                                    <input type="text" name="search_id" id="search_id" >
                                    <input type="button" value="Search" onclick="generateTree2()" >
                                </div>
                                <br><br><br>

                                <div class="tree text-center">
                                    <div id="getTree" style="width: max-content !important; padding: 0 100px 100px 100px;"> <!--width: max-content !important;-->
                                        <button class="btn btn btn-primary" onclick="generateTree('{{ $user->id }}');">
                                            Show Tree
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<script>
    function generateTree(id) {
        // alert(id);
        var url = "<?php echo e(url('tree_generate')); ?>";
        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            type: "POST",
            data: {
                'id': id
            },
            context: this,
            success: function(result) {
                console.log(result);
                // alert(result);
                jQuery('#getTree').html(result);
            },
            error: function(error) {
                console.log(error.responseText);
            }
        });
    }

    function backGenerateTree() {
        var url = "<?php echo e(url('tree_generate')); ?>";
        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            type: "POST",
            data: {
                'array': 'id'
            },
            context: this,
            success: function(result) {
                // alert(result);
                jQuery('#getTree').html(result);
            },
            error: function(error) {
                console.log(error.responseText);
            }
        });
    }

    function generateTree2() {
        var id = document.getElementById('search_id').value;
        // alert(id);
        var url = "<?php echo e(url('tree_generate')); ?>";
        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            type: "POST",
            data: {
                'id': id,
                'user_id': id
            },
            context: this,
            success: function(result) {
                console.log(result);
                // alert(result);
                jQuery('#getTree').html(result);
            },
            error: function(error) {
                console.log(error.responseText);
            }
        });
    }


    // function generateTreeOLD(id) {
    //     $.ajax({
    //         type: "POST",
    //         url: "tree_generate.php",
    //         data: {
    //             member_id: id
    //         },
    //         success: function(data) {
    //             document.getElementById('getTree').innerHTML = data;
    //         }
    //     });
    // }

    // function backGenerateTreeOLD() {
    //     $.ajax({
    //         type: "POST",
    //         url: "tree_generate.php",
    //         data: {
    //             array: 'id'
    //         },
    //         success: function(data) {
    //             document.getElementById('getTree').innerHTML = data;
    //         }
    //     });
    // }
</script>

@endsection