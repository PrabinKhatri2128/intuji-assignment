<?php

// Include Google calendar api handler class 
include_once 'classes/GoogleCalendarApi.php'; 

// Include Calendar Events handler Class
include_once 'classes/CalendarEvents.php';

// Include database configuration file 
require_once 'include/dbConfig.php';

// Initialize Google Calendar API class 
$GoogleCalendarApi = new GoogleCalendarApi(); 
$CalendarEvents = new CalendarEvents($db);

$redirect_host = 'http://' . $_SERVER['HTTP_HOST'];
$path = explode("/",$_SERVER['REQUEST_URI']);
$projectFolderName = '/'.$path[1];
$base_url =  $redirect_host . $projectFolderName;

$status = $statusMsg = $access_token = ''; 
if(!empty($_SESSION['google_access_token'])){ 
    $access_token = $_SESSION['google_access_token'];
    $getUserInfo =  $GoogleCalendarApi->GetUserInfo($access_token);
}
else {
    header("Location:".$base_url);
}

if(!empty($_SESSION['status_response'])){ 
    $status_response = $_SESSION['status_response']; 
    $status = $status_response['status']; 
    $statusMsg = $status_response['status_msg'];
    unset($_SESSION['status_response']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Google Calendar Integration</title>
</head>
<body>
<div class="bootstrap-wrapper">
<div class="col-md-12">
<span class="align-middle"><h1>Calendar Events List</h1></span>

<!-- Status message -->

<?php if(!empty($statusMsg)){ ?>
    <div id="msg-container"><?php echo $statusMsg; ?></div>
<?php }  else { ?>
    <div id="msg-container"></div>
<?php } ?>
    
<?php if(!empty($access_token)){ ?>
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <button type="button" class="btn btn-primary" id="logout">Disconnect Google Account</button>
            <button type="button" class="btn btn-primary" data-title="Add Event" data-toggle="modal" data-target="#addModal">Add Event</button>
        </div>
    </div>
</div>
<?php } ?>

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

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addmodalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addModalLabel">Add Calendar Event</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        <form method="post" action="addEvent.php" class="form">
            <div class="form-group">
                <label>Event Title</label>
                <input type="text" class="form-control" name="title" value="<?php echo !empty($postData['title'])?$postData['title']:''; ?>" required="">
            </div>
            <div class="form-group">
                <label>Event Description</label>
                <textarea name="description" class="form-control"><?php echo !empty($postData['description'])?$postData['description']:''; ?></textarea>
            </div>
            <div class="form-group">
                <label>Location</label>
                <input type="text" name="location" class="form-control" value="<?php echo !empty($postData['location'])?$postData['location']:''; ?>">
            </div>
            <div class="form-group">
                <label>Date</label>
                <input type="date" name="date" class="form-control" value="<?php echo !empty($postData['date'])?$postData['date']:''; ?>" required="">
            </div>
            <div class="form-group time">
                <label>Time</label>
                <input type="time" name="time_from" class="form-control" value="<?php echo !empty($postData['time_from'])?$postData['time_from']:''; ?>">
                <span>TO</span>
                <input type="time" name="time_to" class="form-control" value="<?php echo !empty($postData['time_to'])?$postData['time_to']:''; ?>">
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" name="submit" value="Add Event"/>
            </div>
            </form>       
        </div>
        </div>
        </div>
    </div>

</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">Delete Calendar Event</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            Are you sure want to delete this Calendar Event.?
        </div>
        <div class="modal-footer">
        </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="css/bootstrap.min.css" />
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> 
<script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
<script src="js/bootstrap.js"></script> 
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
<link href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css" rel="stylesheet"> 
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script type="text/javascript">
   let table = new DataTable('#myTable', {
    ajax: 'ajax.php?action=listevent',
    processing: true,
    serverSide: true,
    columns: [
        { data: 'title' },
        { data: 'description' },
        { data: 'location' },
        { data: 'date' },
        { data: 'time_from' },
        { data: 'time_to' },
        { data: 'id' }
    ],
    "columnDefs": [
        {      
            "targets":6,
            "render": function (data, type, row) {                
                var action = '<a data-id="'+data+'" class="remove_event btn btn-xs btn-success" data-title="Delete Event" data-toggle="modal" data-target="#myModal"><i class="fa fa-remove"></i></a>';
                return action;
            }
        },
        {"orderable": false, "targets": [4, 5, 6]}
    ],
    paging: true,
    pagingType: 'simple_numbers',
});

$("#myModal").on('show.bs.modal', function (e) {
    var triggerLink = $(e.relatedTarget);
    var id = triggerLink.data("id");
    var title = triggerLink.data("title");
  
    $("#modalTitle").text(title);
    $(this).find(".modal-footer").html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><button type="button" class="btn btn-primary delete_event" data="'+id+'">Confirm Delete</button>');
});


//Delete event
$(document).on('click',".delete_event",function(e) {
        e.preventDefault();
            let id = $(this).attr('data');
                $.ajax({
                    type:"post",
                    url:"ajax.php",
                    data: {id:id, action:'deleteevent'},
                    async:false,
                    success:function (data) {
                        $('#myModal').modal('toggle');
                        table.ajax.reload();
                        data = $.parseJSON(data);
                        let $msg = '';
                        console.log(data);
                        if( data.status ) {
                            $msg = $('<div class="auto-dismissible alert alert-success alert-dismissible" role="alert">'+
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                                '<strong>Success! </strong> '+data.msg+
                                '</div>');
                                console.log($msg);
                                console.log($("#msg-container"));
                            $("#msg-container").html($msg);
                        } 
                    },
                    error:function (err) {
                        $('#myModal').modal('toggle');
                        let $msg = $('<div class="auto-dismissible alert alert-danger alert-dismissible" role="alert">'+
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                            '<strong>Error! </strong> '+err.responseText+
                            '</div>');
                        $("#msg-container").html($msg);
                    }
                });            

    });

    
    $(document).on('click',"#logout",function(e) {
        let baseUrl = window.location.href;
        let path = baseUrl.split("/");        
        $.ajax({
                type:"post",
                url:"ajax.php",
                data: {action:'logout'},
                async:false,
                success:function (data) {
                    data = $.parseJSON(data);
                    if( data.status ) {
                        window.location.replace("http://localhost/"+path[3]);
                    } 
                }
            });
    });


</script>
</body>
</html>