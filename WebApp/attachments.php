<?php

require_once 'functions.php';
session_start();
security::redirect_if_not_loggedin();

$Guid = costant::desktop_guid();

if (isset($_GET['DeleteAttach'])) {
    attachments::delete_attachment_by_name($_GET['DeleteAttach']);
}

if (isset($_FILES['UploadedAttachments']) && isset($_POST['Attachment_TrId'])) {
    $TrNumber = (int) $_POST['Attachment_TrId'];
    $FileName = $_FILES['UploadedAttachments']['name'];
    $FileExtension = substr($FileName, strpos($FileName, '.') + 1, strlen($FileName));
    $NewFileName = 'Transaction_'.$TrNumber.'_Attach'.(attachments::get_number_of_attachments($TrNumber) + 1).'.'.$FileExtension;
    move_uploaded_file($_FILES['UploadedAttachments']['tmp_name'], 'attachments/'.$NewFileName);
    echo $NewFileName;
}

if (isset($_GET['AttachmentsTable'])) {
    $TrId = $_GET['AttachmentsTable'];
    $Attachments = attachments::get_attachments_filename_array($TrId, true);
    echo "<table class = 'table'>";
    echo '<tbody>';
    for ($i = 0; $i < count($Attachments); $i++) {
        echo '<tr>';
        $File = $Attachments[$i];
        design::table_cell(substr($File, strpos($File, 'Attach'), strlen($File)), '');
        design::table_cell("<a href='services.php?guid=${Guid}&download_attachment=${File}'>
                            <span class='glyphicon glyphicon-download-alt'> </span> Open</a>", 'text_align_right');
        design::table_cell("<a href='#' onclick='attachment_delete(\"${File}\",${TrId});return false;'>
                            <span class='glyphicon glyphicon-remove'> </span> Delete</a>", 'text_align_right');
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}
