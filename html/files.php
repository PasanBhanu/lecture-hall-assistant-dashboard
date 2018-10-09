<?php require_once 'codeblocks/session.php' ; ?>
<?php require_once 'codeblocks/login.php' ; ?>
<?php require_once 'codeblocks/database.php' ; ?>
<?php require_once 'files.class.php' ; ?>
<?php
    $listing = new DirectoryListing();
    $successMsg = null;
    $errorMsg = null;
    
    if (!isset($_GET['dir'])){
        $dir = "files";
        $previous = "";
    }else{
        $dir = urldecode($_GET['dir']);
        $dir_arr = explode("/", $dir);
        array_pop($dir_arr);
        $previous = join("/",$dir_arr);
    }

    if (isset($_GET['msg'])){
        switch($_GET['msg']){
            case '1':
                $successMsg = "Folder deleted!";
                break;
            case '2':
                $successMsg = "Files uploaded!";
                break;
        }
        unset($_GET['msg']);
    }

    if (isset($_POST['password'])) {
        $listing->login();

        if (isset($_SESSION['evdir_loginfail'])) {
            $errorMsg = 'Login Failed! Please check you entered the correct password an try again.';
            unset($_SESSION['evdir_loginfail']);
        }

    } elseif (isset($_FILES['upload'])) {
        $uploadStatus = $listing->upload();
        if ($uploadStatus == 1) {
            $successMsg = 'Your file was successfully uploaded!';
        } elseif ($uploadStatus == 2) {
            $errorMsg = 'Your file could not be uploaded. A file with that name already exists.';
        } elseif ($uploadStatus == 3) {
            $errorMsg = 'Your file could not be uploaded as the file type is blocked.';
        }
    } elseif (isset($_POST['directory'])) {
        if ($listing->createDirectory()) {
            $successMsg = "Folder created!";
        } else {
            $errorMsg = "Folder exists!";
        }
    } elseif (isset($_GET['deleteFile']) && $listing->enableFileDeletion) {
        if ($listing->deleteFile()) {
            $successMsg = "File deleted!";
        } else {
            $errorMsg = "File delete failed. Check server permissions.";
        }
    } elseif (isset($_GET['dir']) && isset($_GET['delete']) && $listing->enableDirectoryDeletion) {
        if ($listing->deleteDirectory()) {
            header ("location: files.php?msg=1&dir=" . urlencode($previous));
        } else {
            $errorMsg = "Folder delete failed. Check server permissions.";
        }
    }

    $data = $listing->run();

    function pr($data, $die = false) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';

        if ($die) {
            die();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'codeblocks/header.php'; ?>
    <link href="css/dropzone.css" type="text/css" rel="stylesheet" />
    <title>File Manager | Lecture Hall Assistant</title>
</head>

<body class="fix-header">
    <?php require_once 'codeblocks/preloader.php'; ?>
    <div id="wrapper">
        <?php require_once 'codeblocks/navbar.php'; ?>

        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">File Manager</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li class="active">File Manager</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box">
                            <h3 class="box-title">Create Folder</h3> 
                            <form class="form-inline form-material" method="post">
                                <div class="form-group">
                                    <label for="directory">Directory Name : </label>
                                    <input type="text" name="directory" id="directory" class="form-control margin-20">
                                    <button type="submit" class="btn btn-primary margin-20" name="submit">Create Directory</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box">
                            <h3 class="box-title">Folders <a href="?dir=<?php echo urlencode($dir);?>" class="pull-right"><i class="fa fa-refresh fa-fw" aria-hidden="true"></i></a></h3>
                            <table class="table table-bordered">
									<tbody>
                                        <?php
                                            if ($previous != ""){
                                                echo '<tr><td><a href="?dir=' . urlencode($previous) . '" class="folder"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i>Back</a></td></tr>';
                                            }
                                        ?>
                                        <?php if (! empty($data['directories'])): ?>
                                            <?php foreach ($data['directories'] as $directory): ?>
                                                <tr>
                                                    <td>
                                                        <a href="<?php echo $directory['url']; ?>" class="folder"><i class="fa fa-folder fa-fw" aria-hidden="true"></i>
                                                            <?php echo $directory['name']; ?>
                                                        </a>
                                                        <?php if ($listing->enableDirectoryDeletion): ?>
                                                            <span class="pull-right folder-delete">
                                                                <a href="<?php echo $directory['url']; ?>&delete=true" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?')">Delete</a>
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif;?>
									</tbody>
                            </table>
                        </div>
                    </div>
                </div>
				<div class="row">
					<div class="col-md-12">
						<div class="white-box">
                            <h3 class="box-title">Files <a href="?dir=<?php echo urlencode($dir);?>" class="pull-right"><i class="fa fa-refresh fa-fw" aria-hidden="true"></i></a></h3>
							<table class="table table-striped table-bordered">
								<thead>
									<tr>
										<th class="padding-10">
											<a href="<?php echo $listing->sortUrl('name'); ?>">Document <span class="<?php echo $listing->sortClass('name'); ?>"></span></a>
										</th>
										<th class="text-right xs-hidden padding-10">
											<a href="<?php echo $listing->sortUrl('size'); ?>">Size <span class="<?php echo $listing->sortClass('size'); ?>"></span></a>
										</th>
										<th class="text-right sm-hidden padding-10">
											<a href="<?php echo $listing->sortUrl('modified'); ?>">Last Modified <span class="<?php echo $listing->sortClass('modified'); ?>"></span></a>
										</th>
									</tr>
								</thead>
								<tbody>
                                <?php if (! empty($data['files'])): ?>
                                    <?php foreach ($data['files'] as $file): ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo $file['url']; ?>" target="<?php echo $file['target']; ?>" class="item _blank folder <?php echo $file['extension']; ?>">
                                                    <i class="fa fa-file fa-fw" aria-hidden="true"></i>
                                                    <?php echo $file['name']; ?>
                                                </a>
                                                <?php if ($listing->enableFileDeletion == true): ?>
                                                    <a href="?deleteFile=<?php echo urlencode($file['relativePath']); ?>&dir=<?php echo urlencode($dir); ?>" class="pull-right btn btn-danger btn-xs folder-delete" onclick="return confirm('Are you sure?')">Delete</a>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-right xs-hidden padding-10"><?php echo $file['size']; ?></td>
                                            <td class="text-right sm-hidden padding-10"><?php echo date('M jS Y \a\t g:ia', $file['modified']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="3">No Files</td></tr>
                                    <?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box">
                            <h3 class="box-title">Upload Files</h3> 
                            <form action="upload.php" method="post" enctype="multipart/form-data" class="dropzone" id="filedrop">
                                <input type="file" name="file" id="file" class="inputfile" />
                                <input type="text" name="dir" value="<?php echo $dir; ?>" hidden>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php require_once 'codeblocks/footer.php'; ?>
        </div>
    </div>
    <?php require_once 'codeblocks/js.php'; ?>
    <script src="js/dropzone.js"></script>
    <script type="text/javascript">
        Dropzone.options.filedrop = {
            init: function () {
                this.on("complete", function (file) {
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                        window.location.href = "files.php?msg=2&dir=<?php echo urlencode($dir); ?>";
                    }
                });
            }
        };
    </script>
    <?php
        if ($successMsg != ""){
            echo '<script type="text/javascript">window.onload = function(){toastr.success("' . $successMsg .  '", "Success");}; </script>';
        }
        if ($errorMsg != ""){
            echo '<script type="text/javascript">window.onload = function(){toastr.error("' . $errorMsg .  '", "Error");}; </script>';
        }
    ?>
</body>

</html>
