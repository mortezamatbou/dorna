<?php
require_once './inc/auth.inc.php';

$info = [
    'tab_title' => APP_NAME,
    'page_title' => 'Files | <span class="btn  btn-xs">Dorna Simple FileManager</span>'
];

require './inc/header.inc.php';
?>

<?php $route = get_instance()->input->get_route(); ?>
<div class="row" style="padding: 5px; margin-bottom: 15px;">
    <h1><?= $info['page_title']; ?></h1>
    <hr>
    <p>
        <a href='<?= base_url() . 'index.php?route=/&token=' . get_instance()->token ?>'>Home</a>
        <?php
        $rr = explode('/', get_instance()->input->get_route());
        $host = host_url();
        $r3 = '';
        foreach ($rr as $r) {
            if ($r) {
                $r3 .= '/' . $r;
                $h = base_url() . 'index.php?route=' . $r3 . '&token=' . get_instance()->token;
                echo " > <a href='$h'>$r</a>";
            }
        }
        ?>

    </p>
    <br>
    <div class="col-lg-6 col-xs-12" style="margin-bottom: 10px">
        <form id="upload-form" action="<?= base_url() . 'api.php'; ?>" method="POST" enctype="multipart/form-data">
            <p id="upload-form-content">
                <input type="hidden" name="token" id="token" value="<?= get_instance()->token ?>" />
                <input type="hidden" name="level" value="write" />
                <input type="hidden" name="route" value="<?= get_instance()->input->get_route(); ?>" />
                <input type="file" name="uploadfile" id="uploadfile" style="display: none;" />
                <a class="btn btn-success btn-xs" onclick="openFile()">Select file</a> &nbsp; 
                <input class="btn btn-danger btn-xs" type="submit" value="Upload" />
                &nbsp; &nbsp; <b style="margin-top: 5px;" id="upload-message">Select file to upload here!</b>
            </p>
        </form>
        <div class="progress" id="upload-progress" style="width: 200px; display: none;">
            <div id="upload-progress-bar" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"
                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                <span id="upload-progress-bar-d"></span>
            </div>
        </div> 
    </div>
    <div class="col-lg-6 col-xs-12" style="margin-bottom: 10px">
        <a class="btn btn-warning btn-xs" onclick="boxShow('folder')">Add folder</a> &nbsp;
        <a class="btn btn-primary btn-xs">Add file</a> &nbsp;
        <a class="btn btn-danger btn-xs" onclick="deleteFile()">Delete file</a>
    </div>
</div>

<div class="row" style="padding: 5px;">
    <div class="table-responsive" id="files-list">
        <?php include_once './inc/files_list.inc.php'; ?>
    </div>
</div>

<div id="box-add-folder" style="position: absolute; top: 0; left: 0px; width: 100%; height: 100%; background-color: rgba(255,255,255, .95); display: none;">
    &nbsp;
    
    <a style="border: 1px solid black; cursor: pointer; padding: 5px 10px; color: black; position: absolute; right: 50px; top: 10px; " onclick="boxClose('folder')">X</a>
    
    <div style="width: 80%; margin: 0px auto; padding: 10px; position: absolute; height: 100%; top: 40px; left: 10%;">
        <h1>Add new folder</h1>
        <form id="add-folder-form" action="<?= base_url() . 'api.php' ?>" method="POST">
            <input type="hidden" name="token" id="token" value="<?= get_instance()->token ?>" />
            <input type="hidden" name="level" value="mkdir" />
            <input type="hidden" name="route" value="<?= get_instance()->input->get_route(); ?>" />
            <label for="folder-name">Current directory: <?= get_instance()->input->get_route(); ?><br>This is <?= host_url() . get_instance()->input->get_route(TRUE); ?></label>
            <input type="text" class="form-control" name="folder_name" id="folder-name" placeholder="Example: new_folder , new_folder_1/new_folder_2" />
            <br>
            <input type="submit" value="Add" class="btn btn-success btn-sm right"> &nbsp; <span id="add-folder-message"></span>
        </form>
    </div>
</div>

<div id="box-rename" style="position: absolute; padding: 10px; background-color: rgba(255,255,255, .95); display: none;">
    
    <div style="width: 100%; margin: 0px auto;">
        <form id="rename-form" action="<?= base_url() . 'api.php' ?>" method="POST">
            <input type="hidden" name="token" id="token" value="<?= get_instance()->token ?>" />
            <input type="hidden" name="level" value="rename" />
            <input type="hidden" name="rename_file" value="" id="rename-file-name" />
            <input type="hidden" name="route" value="<?= get_instance()->input->get_route(); ?>" />
            <div class="col-xs-8">
                <input type="text" class="form-control" name="rename_to" id="rename-to" />
            </div>
            <div class="col-xs-4" style="padding: 5px;">
                <input type="submit" value="Rename" class="btn btn-primary btn-xs right" id="rename-button"> &nbsp;
                <a class="btn btn-danger btn-xs right" onclick="boxClose('rename')"><i class="glyphicon glyphicon-remove"></i></a>
            </div>
            
        </form>
    </div>
</div>

<?php
require './inc/footer.inc.php';
