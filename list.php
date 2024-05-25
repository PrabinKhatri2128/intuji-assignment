<?php

// Include Calendar Events handler Class
include_once 'classes/CalendarEvents.php';

// Include database configuration file 
require_once 'include/dbConfig.php'; 

$CalendarEvents = new CalendarEvents($db);

$data  = $CalendarEvents->GetCalendarEvent();

$dataset = array(
            "echo" => 1,
            "totalrecords" => count($data),
            "totaldisplayrecords" => count($data),
            "data" => $data
        );
        
//echo '<pre>';
//print_r($dataset);

?>
<div class="bootstrap-wrapper">
<div class="col-md-12">
<span class="align-middle"><h1>Calendar Events List</h1></span>
<table id="myTable" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Location</th>
                <th>Date</th>
                <th>Time From</th>
                <th>Time To</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
</div>

<div class="modal fade" id="info_container" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" id="form_container" >
        </div>
    </div>
</div>
<link rel="stylesheet" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> 
<script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
<link href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css" rel="stylesheet"> 
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script type="text/javascript">
    new DataTable('#myTable', {
    ajax: 'ajax.php',
    processing: true,
    serverSide: true,
    columns: [
        { data: 'title' },
        { data: 'description' },
        { data: 'location' },
        { data: 'date' },
        { data: 'time_from' },
        { data: 'time_to' },
        { data: 'action' }
    ],
    "columnDefs": [
        {      
            "targets":6,
            "data" : "action",
            "render": function (data, type, row, meta) {                
                var action = '<a data="'+data+'" class="remove_event btn btn-xs btn-success" title="Remove" href="javascript:void(0)" ><i class="fa fa-remove"></i></a>';
                return action;
            }
        },
        {"orderable": false, "targets": [6]}
    ]
});

$(document).ready(function(){
    var $element = $("<div class='event_confirm'><i class='fa fa-lg fa-info-circle'></i> <span id='delete'></span></div>");
    $("#myTable").next().append($element);

    $(".event_confirm").dialog({
        modal: true,
        bgiframe: true,
        minWidth: 500,
        height: "auto",
        autoOpen: false,
        classes: {
            "ui-dialog": "ui-corner-all info_open",
            "ui-dialog-titlebar": "ui-corner-all alert-info",
            "ui-button":"btn-primary",
        },
    });
});

//Delete event
$(document).on('click',".remove_event",function(e) {
        e.preventDefault();
        var id = $(this).attr('data');
        $("#delete").text("Are you sure want to delete this Calendar Event. ?");
        $(".event_confirm").dialog('option','title','Confirm Delete');
        $(".event_confirm").dialog('option', 'buttons', {
            "Confirm": function() {
                $.ajax({
                    type:"post",
                    url:"ajax.php?action=delete",
                    data: {id:id},
                    async:false,
                    success:function (data) {
                        data = $.parseJSON(data);
                        var $msg = '';
                        if( data.status ) {
                            $msg = $('<div class="auto-dismissible alert alert-success alert-dismissible" role="alert">'+
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                                '<strong>Success!</strong> '+data.msg+
                                '</div>');
                        } else {
                            $msg = $('<div class="auto-dismissible alert alert-danger alert-dismissible" role="alert">'+
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                                '<strong>Error!</strong> '+data.msg+
                                '</div>');
                        }
                        $("#msg-container").html($msg);
                    },
                    error:function (err) {
                        var $msg = $('<div class="auto-dismissible alert alert-danger alert-dismissible" role="alert">'+
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                            '<strong>Error!</strong> '+err.responseText+
                            '</div>');
                        $("#msg-container").html($msg);
                    }
                });
                table.ajax.reload();
                $(this).dialog("close");
            },
            "Cancel": function() {
                $(this).dialog("close");
            }
        });
        $(".info_confirm").dialog("open");
        $(".info_open").position({
            of: $(window)
        });

    });
</script>
