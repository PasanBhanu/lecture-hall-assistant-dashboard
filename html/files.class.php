<?php
    class DirectoryListing {
        // The top level directory where this script is located, or alternatively one of it's sub-directories
        public $startDirectory = 'files/';

        // An optional title to show in the address bar and at the top of your page (set to null to leave blank)
        public $pageTitle = null;

        // The URL of this script. Optionally set if your server is unable to detect the paths of files
        public $includeUrl = true;

        // If you've enabled the includeUrl parameter above, enter the full url to the directory the index.php file
        // is located in here, followed by a forward slash.
        public $directoryUrl = '';

        // Set to true to list all sub-directories and allow them to be browsed
        public $showSubDirectories = true;

        // Set to true to open all file links in a new browser tab
        public $openLinksInNewTab = true;

        // Set to true to show thumbnail previews of any images
        public $showThumbnails = true;

        // Set to true to allow new directories to be created.
        public $enableDirectoryCreation = true;

        // Set to true to allow file uploads (NOTE: you should set a password if you enable this!)
        public $enableUploads = true;

        // Enable multi-file uploads (NOTE: This makes use of javascript libraries hosted by Google so an internet connection is required.)
        public $enableMultiFileUploads = true;

        // Set to true to overwrite files on the server if they have the same name as a file being uploaded
        public $overwriteOnUpload = false;

        // Set to true to enable file deletion options
        public $enableFileDeletion = true;

        // Set to true to enable directory deletion options (only available when the directory is empty)
        public $enableDirectoryDeletion = true;

        // List of all mime types that can be uploaded. Full list of mime types: http://www.iana.org/assignments/media-types/media-types.xhtml
        public $allowedUploadMimeTypes = array(
            'image/jpeg',
            'image/gif',
            'image/png',
            'image/bmp',
            'audio/mpeg',
            'audio/mp3',
            'audio/mp4',
            'audio/x-aac',
            'audio/x-aiff',
            'audio/x-ms-wma',
            'audio/midi',
            'audio/ogg',
            'video/ogg',
            'video/webm',
            'video/quicktime',
            'video/x-msvideo',
            'video/x-flv',
            'video/h261',
            'video/h263',
            'video/h264',
            'video/jpeg',
            'text/plain',
            'text/html',
            'text/css',
            'text/csv',
            'text/calendar',
            'application/pdf',
            'application/x-pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // MS Word (modern)
            'application/msword',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // MS Excel (modern)
            'application/zip',
            'application/x-tar'
        );

        // Set to true to unzip any zip files that are uploaded (note - will overwrite files of the same name!)
        public $enableUnzipping = true;

        // If you've enabled unzipping, you can optionally delete the original zip file after its uploaded by setting this to true.
        public $deleteZipAfterUploading = false;

        // The Evoluted Directory Listing Script uses Bootstrap. By setting this value to true, a nicer theme will be loaded remotely.
        // Setting this to false will make the directory listing script use the default bootstrap style, loaded locally.
        public $enableTheme = true;

        // Set to true to require a password be entered before being able to use the script
        public $passwordProtect = false;

        // The password to require to use this script (only used if $passwordProtect is set to true)
        public $password = 'password';

        // Optional. Allow restricted access only to whitelisted IP addresses
        public $enableIpWhitelist = false;

        // List of IP's to allow access to the script (only used if $enableIpWhitelist is true)
        public $ipWhitelist = array(
            '127.0.0.1'
        );

        // File extensions to block from showing in the directory listing
        public $ignoredFileExtensions = array(
            'php',
            'ini',
        );

        // File names to block from showing in the directory listing
        public $ignoredFileNames = array(
            '.htaccess',
            '.DS_Store',
            'Thumbs.db',
        );

        // Directories to block from showing in the directory listing
        public $ignoredDirectories = array(

        );

        // Files that begin with a dot are usually hidden files. Set this to false if you wish to show these hiden files.
        public $ignoreDotFiles = true;

        // Works the same way as $ignoreDotFiles but with directories.
        public $ignoreDotDirectories = true;

        /*
        ====================================================================================================
        You shouldn't need to edit anything below this line unless you wish to add functionality to the
        script. You should only edit this area if you know what you are doing!
        ====================================================================================================
        */
        private $__previewMimeTypes = array(
            'image/gif',
            'image/jpeg',
            'image/png',
            'image/bmp'
        );

        private $__currentDirectory = null;

        private $__fileList = array();

        private $__directoryList = array();

        private $__debug = true;

        public $sortBy = 'name';

        public $sortableFields = array(
            'name',
            'size',
            'modified'
        );

        private $__sortOrder = 'asc';

        public function __construct() {
            define('DS', '/');
        }

        public function run() {
            if ($this->enableIpWhitelist) {
                $this->__ipWhitelistCheck();
            }

            $this->__currentDirectory = $this->startDirectory;

            // Sorting
            if (isset($_GET['order']) && in_array($_GET['order'], $this->sortableFields)) {
                $this->sortBy = $_GET['order'];
            }

            if (isset($_GET['sort']) && ($_GET['sort'] == 'asc' || $_GET['sort'] == 'desc')) {
                $this->__sortOrder = $_GET['sort'];
            }

            if (isset($_GET['dir'])) {
                if (isset($_GET['delete']) && $this->enableDirectoryDeletion) {
                    $this->deleteDirectory();
                }

                $this->__currentDirectory = $_GET['dir'];
                return $this->__display();
            } elseif (isset($_GET['preview'])) {
                $this->__generatePreview($_GET['preview']);
            } else {
                return $this->__display();
            }
        }

        public function login() {
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

            if ($password === $this->password) {
                $_SESSION['evdir_loggedin'] = true;
                unset($_SESSION['evdir_loginfail']);
            } else {
                $_SESSION['evdir_loginfail'] = true;
                unset($_SESSION['evdir_loggedin']);

            }
        }

        public function upload() {
            $files = $this->__formatUploadArray($_FILES['upload']);

            if ($this->enableUploads) {
                if ($this->enableMultiFileUploads) {
                    foreach ($files as $file) {
                        $status = $this->__processUpload($file);
                    }
                } else {
                    $file = $files[0];
                    $status = $this->__processUpload($file);
                }

                return $status;
            }
            return false;
        }

        private function __formatUploadArray($files) {
            $fileAry = array();
            $fileCount = count($files['name']);
            $fileKeys = array_keys($files);

            for ($i = 0; $i < $fileCount; $i++) {
                foreach ($fileKeys as $key) {
                    $fileAry[$i][$key] = $files[$key][$i];
                }
            }

            return $fileAry;
        }

        private function __processUpload($file) {
            if (isset($_GET['dir'])) {
                $this->__currentDirectory = $_GET['dir'];
            }

            if (! $this->__currentDirectory) {
                $filePath = realpath($this->startDirectory);
            } else {
                $this->__currentDirectory = str_replace('..', '', $this->__currentDirectory);
                $this->__currentDirectory = ltrim($this->__currentDirectory, "/");
                $filePath = realpath($this->__currentDirectory);
            }

            $filePath = $filePath . DS . $file['name'];

            if (! empty($file)) {

                if (! $this->overwriteOnUpload) {
                    if (file_exists($filePath)) {
                        return 2;
                    }
                }

                if (! in_array(mime_content_type($file['tmp_name']), $this->allowedUploadMimeTypes)) {
                    return 3;
                }

                move_uploaded_file($file['tmp_name'], $filePath);

                if (mime_content_type($filePath) == 'application/zip' && $this->enableUnzipping && class_exists('ZipArchive')) {

                    $zip = new ZipArchive;
                    $result = $zip->open($filePath);
                    $zip->extractTo(realpath($this->__currentDirectory));
                    $zip->close();

                    if ($this->deleteZipAfterUploading) {
                        // Delete the zip file
                        unlink($filePath);
                    }


                }

                return true;
            }
        }

        public function deleteFile() {
            if (isset($_GET['deleteFile'])) {
                $file = $_GET['deleteFile'];

                // Clean file path
                $file = str_replace('..', '', $file);
                $file = ltrim($file, "/");

                // Work out full file path
                $filePath = __DIR__ . $this->__currentDirectory . '/' . $file;

                if (file_exists($filePath) && is_file($filePath)) {
                    return unlink($filePath);
                }
                return false;
            }
        }

        public function deleteDirectory() {
            if (isset($_GET['dir'])) {
                $dir = $_GET['dir'];
                // Clean dir path
                $dir = str_replace('..', '', $dir);
                $dir = ltrim($dir, "/");

                // Work out full directory path
                $dirPath = __DIR__ . '/' . $dir;

                if (file_exists($dirPath) && is_dir($dirPath)) {

                    $iterator = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
                    $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);

                    foreach ($files as $file) {
                        if ($file->isDir()) {
                            rmdir($file->getRealPath());
                        } else {
                            unlink($file->getRealPath());
                        }
                    }
                    return rmdir($dir);
                }
            }
            return false;
        }

        public function createDirectory() {
            if ($this->enableDirectoryCreation) {
                $directoryName = $_POST['directory'];

                // Convert spaces
                $directoryName = str_replace(' ', '_', $directoryName);

                // Clean up formatting
                $directoryName = preg_replace('/[^\w-_]/', '', $directoryName);

                if (isset($_GET['dir'])) {
                    $this->__currentDirectory = $_GET['dir'];
                }

                if (! $this->__currentDirectory) {
                    $filePath = realpath($this->startDirectory);
                } else {
                    $this->__currentDirectory = str_replace('..', '', $this->__currentDirectory);
                    $filePath = realpath($this->__currentDirectory);
                }

                $filePath = $filePath . DS . strtolower($directoryName);

                if (file_exists($filePath)) {
                    return false;
                }

                return mkdir($filePath, 0755);

            }
            return false;
        }

        public function sortUrl($sort) {

            // Get current URL parts
            $urlParts = parse_url($_SERVER['REQUEST_URI']);

            $url = '';

            if (isset($urlParts['scheme'])) {
                $url = $urlParts['scheme'] . '://';
            }

            if (isset($urlParts['host'])) {
                $url .= $urlParts['host'];
            }

            if (isset($urlParts['path'])) {
                $url .= $urlParts['path'];
            }


            // Extract query string
            if (isset($urlParts['query'])) {
                $queryString = $urlParts['query'];

                parse_str($queryString, $queryParts);

                // work out if we're already sorting by the current heading
                if (isset($queryParts['order']) && $queryParts['order'] == $sort) {
                    // Yes we are, just switch the sort option!
                    if (isset($queryParts['sort'])) {
                        if ($queryParts['sort'] == 'asc') {
                            $queryParts['sort'] = 'desc';
                        } else {
                            $queryParts['sort'] = 'asc';
                        }
                    }
                } else {
                    $queryParts['order'] = $sort;
                    $queryParts['sort'] = 'asc';
                }

                // Now convert back to a string
                $queryString = http_build_query($queryParts);

                $url .= '?' . $queryString;
            } else {
                $order = 'asc';
                if ($sort == $this->sortBy) {
                    $order = 'desc';
                }
                $queryString = 'order=' . $sort . '&sort=' . $order;
                $url .= '?' . $queryString;
            }

            return $url;
        }

        public function sortClass($sort) {
            $class = $sort . '_';

            if ($this->sortBy == $sort) {
                if ($this->__sortOrder == 'desc') {
                    $class .= 'desc sort_desc';
                } else {
                    $class .= 'asc sort_asc';
                }
            } else {
                $class = '';
            }
            return $class;
        }

        private function __ipWhitelistCheck() {
            // Get the users ip
            $userIp = $_SERVER['REMOTE_ADDR'];

            if (! in_array($userIp, $this->ipWhitelist)) {
                header('HTTP/1.0 403 Forbidden');
                die('Your IP address (' . $userIp . ') is not authorized to access this file.');
            }
        }

        private function __display() {
            if ($this->__currentDirectory != '.' && !$this->__endsWith($this->__currentDirectory, DS)) {
                $this->__currentDirectory = $this->__currentDirectory . DS;
            }

            return $this->__loadDirectory($this->__currentDirectory);
        }

        private function __loadDirectory($path) {
            $files = $this->__scanDir($path);

            if (! empty($files)) {
                // Strip excludes files, directories and filetypes
                $files = $this->__cleanFileList($files);
                foreach ($files as $file) {
                    $filePath = realpath($this->__currentDirectory . DS . $file);

                    if ($this->__isDirectory($filePath)) {

                        if (! $this->includeUrl) {
                            $urlParts = parse_url($_SERVER['REQUEST_URI']);

                            $dirUrl = '';

                            if (isset($urlParts['scheme'])) {
                                $dirUrl = $urlParts['scheme'] . '://';
                            }

                            if (isset($urlParts['host'])) {
                                $dirUrl .= $urlParts['host'];
                            }

                            if (isset($urlParts['path'])) {
                                $dirUrl .= $urlParts['path'];
                            }
                        } else {
                            $dirUrl = $this->directoryUrl;
                        }

                        if ($this->__currentDirectory != '' && $this->__currentDirectory != '.') {
                            $dirUrl .= '?dir=' . rawurlencode($this->__currentDirectory) . rawurlencode($file);
                        } else {
                            $dirUrl .= '?dir=' . rawurlencode($file);
                        }

                        $this->__directoryList[$file] = array(
                            'name' => rawurldecode($file),
                            'path' => $filePath,
                            'type' => 'dir',
                            'url' => $dirUrl
                        );
                    } else {
                        $this->__fileList[$file] = $this->__getFileType($filePath, $this->__currentDirectory . DS . $file);
                    }
                }
            }

            if (! $this->showSubDirectories) {
                $this->__directoryList = null;
            }

            $data = array(
                'currentPath' => $this->__currentDirectory,
                'directoryTree' => $this->__getDirectoryTree(),
                'files' => $this->__setSorting($this->__fileList),
                'directories' => $this->__directoryList,
                'requirePassword' => $this->passwordProtect,
                'enableUploads' => $this->enableUploads
            );

            return $data;
        }

        private function __setSorting($data) {
            $sortOrder = '';
            $sortBy = '';

            // Sort the files
            if ($this->sortBy == 'name') {
                function compareByName($a, $b) {
                    return strnatcasecmp($a['name'], $b['name']);
                }

                usort($data, 'compareByName');
                $this->soryBy = 'name';
            } elseif ($this->sortBy == 'size') {
                function compareBySize($a, $b) {
                    return strnatcasecmp($a['size_bytes'], $b['size_bytes']);
                }

                usort($data, 'compareBySize');
                $this->soryBy = 'size';
            } elseif ($this->sortBy == 'modified') {
                function compareByModified($a, $b) {
                    return strnatcasecmp($a['modified'], $b['modified']);
                }

                usort($data, 'compareByModified');
                $this->soryBy = 'modified';
            }

            if ($this->__sortOrder == 'desc') {
                $data = array_reverse($data);
            }
            return $data;
        }

        private function __scanDir($dir) {
            // Prevent browsing up the directory path.
            if (strstr($dir, '../')) {
                return false;
            }

            if ($dir == '/') {
                $dir = $this->startDirectory;
                $this->__currentDirectory = $dir;
            }

            $strippedDir = str_replace('/', '', $dir);

            $dir = ltrim($dir, "/");

            // Prevent listing blacklisted directories
            if (in_array($strippedDir, $this->ignoredDirectories)) {
                return false;
            }

            if (! file_exists($dir) || !is_dir($dir)) {
                return false;
            }

            return scandir($dir);
        }

        private function __cleanFileList($files) {
            $this->ignoredDirectories[] = '.';
            $this->ignoredDirectories[] = '..';
            foreach ($files as $key => $file) {

                // Remove unwanted directories
                if ($this->__isDirectory(realpath($file)) && in_array($file, $this->ignoredDirectories)) {
                    unset($files[$key]);
                }

                // Remove dot directories (if enables)
                if ($this->ignoreDotDirectories && substr($file, 0, 1) === '.') {
                    unset($files[$key]);
                }

                // Remove unwanted files
                if (! $this->__isDirectory(realpath($file)) && in_array($file, $this->ignoredFileNames)) {
                    unset($files[$key]);
                }
                // Remove unwanted file extensions
                if (! $this->__isDirectory(realpath($file))) {

                    $info = pathinfo(mb_convert_encoding($file, 'UTF-8', 'UTF-8'));

                    if (isset($info['extension'])) {
                        $extension = $info['extension'];

                        if (in_array($extension, $this->ignoredFileExtensions)) {
                            unset($files[$key]);
                        }
                    }

                    // If dot files want ignoring, do that next
                    if ($this->ignoreDotFiles) {

                        if (substr($file, 0, 1) == '.') {
                            unset($files[$key]);
                        }
                    }
                }
            }
            return $files;
        }

        private function __isDirectory($file) {
            if ($file == $this->__currentDirectory . DS . '.' || $file == $this->__currentDirectory . DS . '..') {
                return true;
            }
            $file = mb_convert_encoding($file, 'UTF-8', 'UTF-8');

            if (filetype($file) == 'dir') {
                return true;
            }

            return false;
        }

        /**
         * __getFileType
         *
         * Returns the formatted array of file data used for thre directory listing.
         *
         * @param  string $filePath Full path to the file
         * @return array   Array of data for the file
         */
        private function __getFileType($filePath, $relativePath = null) {
            $fi = new finfo(FILEINFO_MIME_TYPE);

            if (! file_exists($filePath)) {
                return false;
            }

            $type = $fi->file($filePath);

            $filePathInfo = pathinfo($filePath);

            $fileSize = filesize($filePath);

            $fileModified = filemtime($filePath);

            $filePreview = false;

            // Check if the file type supports previews
            if ($this->__supportsPreviews($type) && $this->showThumbnails) {
                $filePreview = true;
            }

            return array(
                'name' => $filePathInfo['basename'],
                'extension' => (isset($filePathInfo['extension']) ? $filePathInfo['extension'] : null),
                'dir' => $filePathInfo['dirname'],
                'path' => $filePath,
                'relativePath' => $relativePath,
                'size' => $this->__formatSize($fileSize),
                'size_bytes' => $fileSize,
                'modified' => $fileModified,
                'type' => 'file',
                'mime' => $type,
                'url' => $this->__getUrl($filePathInfo['basename']),
                'preview' => $filePreview,
                'target' => ($this->openLinksInNewTab ? '_blank' : '_parent')
            );
        }

        private function __supportsPreviews($type) {
            if (in_array($type, $this->__previewMimeTypes)) {
                return true;
            }
            return false;
        }

        /**
         * __getUrl
         *
         * Returns the url to the file.
         *
         * @param  string $file filename
         * @return string   url of the file
         */
        private function __getUrl($file) {
            if (! $this->includeUrl) {
                $dirUrl = $_SERVER['REQUEST_URI'];

                $urlParts = parse_url($_SERVER['REQUEST_URI']);

                $dirUrl = '';

                if (isset($urlParts['scheme'])) {
                    $dirUrl = $urlParts['scheme'] . '://';
                }

                if (isset($urlParts['host'])) {
                    $dirUrl .= $urlParts['host'];
                }

                if (isset($urlParts['path'])) {
                    $dirUrl .= $urlParts['path'];
                }
            } else {
                $dirUrl = $this->directoryUrl;
            }

            if ($this->__currentDirectory != '.') {
                $dirUrl = $dirUrl . $this->__currentDirectory;
            }
            return $dirUrl . rawurlencode($file);
        }

        private function __getDirectoryTree() {
            $dirString = $this->__currentDirectory;
            $directoryTree = array();

            $directoryTree['./'] = 'Index';

            if (substr_count($dirString, '/') >= 0) {
                $items = explode("/", $dirString);
                $items = array_filter($items);
                $path = '';
                foreach ($items as $item) {
                    if ($item == '.' || $item == '..') {
                        continue;
                    }
                    $path .= rawurlencode($item) . '/';
                    $directoryTree[$path] = $item;
                }
            }

            $directoryTree = array_filter($directoryTree);

            return $directoryTree;
        }

        private function __endsWith($haystack, $needle) {
            return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
        }

        private function __generatePreview($filePath) {
            $file = $this->__getFileType($filePath);

            if ($file['mime'] == 'image/jpeg') {
                $image = imagecreatefromjpeg($file['path']);
            } elseif ($file['mime'] == 'image/png') {
                $image = imagecreatefrompng($file['path']);
            } elseif ($file['mime'] == 'image/gif') {
                $image = imagecreatefromgif($file['path']);
            } else {
                die();
            }

            $oldX = imageSX($image);
            $oldY = imageSY($image);

            $newW = 250;
            $newH = 250;

            if ($oldX > $oldY) {
                $thumbW = $newW;
                $thumbH = $oldY * ($newH / $oldX);
            }
            if ($oldX < $oldY) {
                $thumbW = $oldX * ($newW / $oldY);
                $thumbH = $newH;
            }
            if ($oldX == $oldY) {
                $thumbW = $newW;
                $thumbH = $newW;
            }

            header('Content-Type: ' . $file['mime']);

            $newImg = ImageCreateTrueColor($thumbW, $thumbH);

            imagecopyresampled($newImg, $image, 0, 0, 0, 0, $thumbW, $thumbH, $oldX, $oldY);

            if ($file['mime'] == 'image/jpeg') {
                imagejpeg($newImg);
            } elseif ($file['mime'] == 'image/png') {
                imagepng($newImg);
            } elseif ($file['mime'] == 'image/gif') {
                imagegif($newImg);
            }
            imagedestroy($newImg);
            die();
        }

        private function __formatSize($bytes) {
            $units = array('B', 'KB', 'MB', 'GB', 'TB');

            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);

            $bytes /= pow(1024, $pow);

            return round($bytes, 2) . ' ' . $units[$pow];
        }

    }

?>