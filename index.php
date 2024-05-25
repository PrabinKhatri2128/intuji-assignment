<?php

// Include configuration file 
include_once 'include/config.php'; 
 
$postData = ''; 
if(!empty($_SESSION['postData'])){ 
    $postData = $_SESSION['postData']; 
    unset($_SESSION['postData']); 
}

$status = $statusMsg = ''; 
if(!empty($_SESSION['status_response'])){ 
    $status_response = $_SESSION['status_response']; 
    $status = $status_response['status']; 
    $statusMsg = $status_response['status_msg'];
    unset($_SESSION['status_response']);
}

//echo '<pre>';
//print_r($_SESSION);
?>
<div class="bootstrap-wrapper">
<div class="col-md-6">
<span class="align-middle"><h1>Google Calendar Event</h1></span>
<!-- Status message -->
<?php if(!empty($statusMsg)){ ?>
    <div id="msg-container"><?php echo $statusMsg; ?></div>
<?php } ?>

    <div class="right_col" role="main">
        <div class="page-title">
            <div class="title_left">
                <button type="button" class="btn btn-primary" onClick="window.open('<?php echo $googleOauthURL; ?>')">Authorization</button>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link rel="stylesheet" href="css/bootstrap.css" />