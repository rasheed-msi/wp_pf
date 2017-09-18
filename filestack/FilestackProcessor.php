<?php

require_once('../wp-config.php');

class FilestackProcessor {

    private $ApiKey;
    private $SecretKey;
    private $convert_url;
    protected $convert_existing_url;
    private $store_url;
    private $remove_url;
    private $delete_url;
    private $rewrite_url;
    private $watermark_url;
    private $watermarkImages;
    private $imageDimensions;
    private $s3BucketPath;
    private $dbCon;

    public function __construct() {

        $this->dbCon = $this->createConnection();

        global $fileStack, $amazonS3;
        $this->ApiKey = $fileStack['APIKey'];
        $this->SecretKey = $fileStack['SecretKey'];
        $this->s3BucketPath = $amazonS3['s3BucketPath'];
        $this->watermarkImages = $fileStack['watermarkImages'];
        // $this->convert_url = "https://process.filestackapi.com/". $this->ApiKey ."/store=location:S3,access:public";
        $this->convert_url = "https://process.filestackapi.com/" . $this->ApiKey . "/[RESIZE]/store=location:S3,access:public[PATH_FILE]";
        $this->convert_existing_url = "https://process.filestackapi.com/";
        $this->store_url = "https://www.filestackapi.com/api/store/S3?key=" . $this->ApiKey . "&store=access:public&path=[PATH]";
        $this->remove_url = 'https://www.filestackapi.com/api/file/[HANDLE]/remove';
        $this->delete_url = 'https://www.filestackapi.com/api/file/[HANDLE]?key=' . $this->ApiKey . '&policy=[POLICY]=&signature=[SIGNATURE]';
        $this->rewrite_url = 'https://www.filestackapi.com/api/file/[HANDLE]?policy=[POLICY]&signature=[SIGNATURE]';
        $this->watermark_url = 'https://process.filestackapi.com/' . $this->ApiKey . '/[OPTIONS]/store=l:S3,path:"[PATH]"/[IMAGE]';
        // $this->store_url = "https://www.filestackapi.com/api/store/S3?key=AGqZkiQc0SEqFPo81uPNAz&store=access:public";
        $this->imageDimensions = $fileStack['ImageDimensions'];
    }

    private function createConnection() {
        $con = mysqli_connect('localhost', 'parentfi_wp632', 'NZ2(p(S6d2', 'parentfi_wp_parentfinder');
        if (!$con)
            exit('Filed to connect with database');
        return $con;
    }

    public function AvatarURLExists($url) {
        $sql = "SELECT COUNT(*) AS count FROM `pf_parent_filestack_photos` WHERE `cloud_path` = '$url'";
        if (mysqli_fetch_array(mysqli_query($this->dbCon, $sql))['count'] > 0) {
            return true;
        } else {
            $sql = "SELECT COUNT(*) AS count FROM `pf_watermark_filestack_photos` WHERE `cloud_path` = '$url'";
            if (mysqli_fetch_array(mysqli_query($this->dbCon, $sql))['count'] > 0)
                return true;
        }
        return false;
    }

    public function AlbumURLExists($url) {
        $sql = "SELECT COUNT(*) AS count FROM `pf_photos` WHERE `Uri` = '$url'";
        if (mysqli_fetch_array(mysqli_query($this->dbCon, $sql))['count'] > 0)
            return true;
        return false;
    }

    public function iSPending($url) {
        $sql = "SELECT status FROM `pf_filestack_album_processing` WHERE `url` = '$url'";
        $result = mysqli_query($this->dbCon, $sql);
        if (mysqli_num_rows($result) == 0)
            return false;
        else if (mysqli_fetch_array($result)['status'] != 'pending')
            return false;
        return true;
    }

    public function changeProcessPhotoStatus($url) {
        $sql = "UPDATE `pf_filestack_album_processing` SET status = 'auto' WHERE `url` = '$url'";
        mysqli_query($this->dbCon, $sql);
    }

    public function processImage($blob, $albumid, $user_id, $username, $s3Domain) {
        $Uri = $blob['url'];
        $rand = date('Y-m-d H:i:s');
        $status = "pending";
        
        if (!empty($user_id)) {
            /* if (@mysql_num_rows(mysql_query("SELECT ID FROM  Profiles JOIN bx_groups_moderation WHERE Profiles.ID= " . $loggedID['Owner'] . " AND Profiles.AdoptionAgency = bx_groups_moderation.GroupId AND bx_groups_moderation.ApproveStatus= 'on' AND bx_groups_moderation.Type = 'photo'"))) {
              $status = "approved";
              } */
            // $sql = "INSERT INTO pf_photos (`pf_album_id`, `user_id`, `Ext`, `Size`, `Title`, `Uri`, `photo_Desc`, `photo_Date`, `Status`, `Hash`) VALUES ($albumid, $user_id, 'jpg', '" . $blob['size'] . "', '', '$Uri', '', '" . time() . "', '" . $status . "', '" . md5(microtime()) . "')";
            $sql = "INSERT INTO pf_photos (`pf_album_id`, `user_id`, `Ext`, `Size`, `Title`, `Uri`, `photo_Desc`, `photo_Date`, `photo_Hash`) VALUES ($albumid, $user_id, 'jpg', '" . $blob['size'] . "', '', '$Uri', '', '" . time() . "', '" . md5(microtime()) . "')";

            $result = mysqli_query($this->dbCon, $sql);
            $lastId = mysqli_insert_id($this->dbCon);

            // Insert the data to the 'filestack_album_photos' table
            $cloud_filename_org = $blob['key'];
            $cloud_path_org = $blob['url'];
            $title_org = explode('/', str_replace('https://', '', $blob['url']))[1];
            $view_type_org = 'original';
            $last_updated_org = Date('Y-m-d h:i:s');

            $this->processAlbumImage($user_id, $lastId, $cloud_filename_org, $cloud_path_org, $title_org, $view_type_org, $last_updated_org, $status, 'upload', $username, $albumid, $s3Domain);

            /* $album = new BxDolAlbums;
              $album->BxDolAlbums("bx_photos", $loggedID['Owner']);
              $album->addObject($albumid, $lastId);
              $new_order = mysql_fetch_array(mysql_query("SELECT MAX(`obj_order`) as max_order FROM sys_albums_objects WHERE  `id_album` = $albumid"));

              mysql_query("UPDATE sys_albums_objects SET obj_order= " . ($new_order[0] + 1) . " WHERE id_album= $albumid AND id_object= $lastId");

              mysql_query("INSERT INTO `SyncLog` (`ProfileId`, `Area`, `UpdateYN`, `DeleteYN`) VALUES ( '" . $loggedID['Owner'] . "', 'Photos', '0', '0');") or die(mysql_error()); */
        }
        return true;
    }

    public function processAlbumImage($user_id, $lastId, $cloud_filename_org, $cloud_path_org, $title_org, $view_type_org, $last_updated_org, $status, $requestFrom, $username, $albumid, $s3Domain) {
        echo "in process\n";
        // First save the original
        $filestackSql = "INSERT INTO pf_filestack_photos (pf_photo_id, cloud_filename, user_id, title, cloud_path, view_type, last_updated) VALUES "
                . "($lastId, '$cloud_filename_org', $user_id, '$title_org', '$cloud_path_org', '$view_type_org', '$last_updated_org')";
        mysqli_query($this->dbCon, $filestackSql);
        $imageTransforms = array('thumb', 'webview');
        foreach ($imageTransforms as $transform) {
            // Create the filestack tumbnail
            $transformedData = $this->transformImage($cloud_path_org, $s3Domain . '/' . $user_id . '/album/' . $albumid . '/' . $transform . '/', $transform);
            if (!empty($transformedData)) {
                foreach ($transformedData as $key => $val) {
                    $$key = $val;
                }
                $filestackSql = "INSERT INTO `pf_filestack_photos` (pf_photo_id, cloud_filename, user_id, title, cloud_path, view_type, last_updated) VALUES ($lastId, '$cloud_filename', $user_id, '$title', '$cloud_path', '$view_type', '$last_updated_org')";
                mysqli_query($this->dbCon, $filestackSql);
            }
        }
    }

    public function getProfileIdByUsername($username) {
        $query = "SELECT ID FROM wp_users WHERE user_login = '$username'";
        $result = mysqli_query($this->dbCon, $query);
        $data = mysqli_fetch_array($result);
        return $data['ID'];
    }

    public function getUsernameByID($user_id) {
        $query = "SELECT user_login FROM `wp_users` WHERE `ID` = $user_id";
        $result = mysqli_query($this->dbCon, $query);
        $data = mysqli_fetch_array($result);
        return $data['user_login'];
    }

    public function updateProfileAvatar($user_id) {
//        $query = "UPDATE `Profiles` SET isAvatarFilestack  = 1, Avatar = 1 WHERE ID = " . $user_id;
//        return mysql_query($query);
    }

    public function saveParentFilestackImages($user_id, $data, $avatarLog) {
        // $filestackObj = new FilestackPhotosParents();
        $flag = 1;
        foreach ($data as $view_type => $row) {
            $cond = "user_id = $user_id AND view_type = '$view_type'";
            $countRow = mysql_num_rows(mysql_query("SELECT * FROM `pf_parent_filestack_photos` WHERE " . $cond));
            $cloud_filename = $row['cloud_filename'];
            $title = $row['title'];
            $cloud_path = $row['cloud_path'];
            $view_type = $row['view_type'];
            if ($countRow > 0) {
                $updateData = "cloud_filename = '$cloud_filename', title = '$title', cloud_path = '$cloud_path'";
                // $return = $filestackObj->where($cond)->update($updateData);
                if (!mysql_query("UPDATE `pf_parent_filestack_photos` SET " . $updateData . " WHERE " . $cond))
                    $flag = 0;
            } else {
                // $return = $filestackObj->insert($row);
                if (!mysql_query("INSERT INTO `pf_parent_filestack_photos` (cloud_filename, user_id, title, cloud_path, view_type) VALUES ('$cloud_filename', $user_id, '$title', '$cloud_path', '$view_type')")) {
                    $flag = 0;
                    $log = mysql_error() . "\n";
                    fwrite($avatarLog, $log);
                }
            }
        }
        return $flag;
    }

    public function transformImage($url = '', $path, $view_type = 'original') {
        // print_r($this->imageDimensions); exit;
        echo "in transform\n";
        echo $width = $this->imageDimensions[$view_type]['width'];
        echo $height = $this->imageDimensions[$view_type]['height'];
        $message = '';
        $resize_options = array();
        if ($width != null) {
            $resize_options[] = "width:$width";
        }
        if ($height != null) {
            $resize_options[] = "height:$height";
        }
        $data = array();
        $params = array('http' => array(
                'method' => 'POST',
        ));
        $ctx = stream_context_create($params);

        if (!empty($resize_options)) {
            $resize_option = join(",", $resize_options);
            $replacePath = ",path:\"" . $path . "\"/" . $url;
            $convert_url_base = str_replace('[PATH_FILE]', $replacePath, $this->convert_url);
            $resize = "resize=$resize_option";
            $resize_url = str_replace('[RESIZE]', $resize, $convert_url_base);

            $fp = @fopen($resize_url, 'rb', false, $ctx);
            if ($fp) {
                $thumbnail = @stream_get_contents($fp);
                $thumb = json_decode($thumbnail, true);
                $thumb_url = $thumb['url'];
                $http_removed = str_replace('https://', '', $thumb_url);
                $handle = explode('/', $http_removed)[1];
                // print_r($thumb); exit;
                $data = array(
                    'cloud_filename' => $thumb['key'],
                    'cloud_path' => $thumb_url,
                    'title' => $handle,
                    'view_type' => $view_type,
                    'last_updated' => Date('Y-m-d h:i:s')
                );
            }
            // print_r($data); exit;
            return $data;
        }
        return null;
    }

    public function transformExistingImage($url, $view_type) { // not used
        // print_r($this->imageDimensions); exit;
        $width = $this->imageDimensions[$view_type]['width'];
        $height = $this->imageDimensions[$view_type]['height'];
        $handler = explode('/', str_replace('https://', '', $url))[1];
        $resize_options = array();
        if ($width != null) {
            $resize_options[] = "width:$width";
        }
        if ($height != null) {
            $resize_options[] = "height:$height";
        }
        $data = array();
        $params = array('http' => array(
                'method' => 'POST',
        ));
        $ctx = stream_context_create($params);

        if (!empty($resize_options)) {
            $resize_option = join(",", $resize_options);
            $convert_url_base = $this->convert_existing_url;
            echo $resize_url = $convert_url_base . "resize=$resize_option/" . $handler;
            $fp = @fopen($resize_url, 'rb', false, $ctx);
            if ($fp) {
                $thumbnail = @stream_get_contents($fp);
                $thumb = json_decode($thumbnail, true);
                print_r($thumb); //exit;
                /* $thumb_url = $thumb['url'];
                  $http_removed = str_replace('https://', '', $thumb_url);
                  $handle = explode('/', $http_removed)[1];
                  $data = array(
                  'cloud_filename' => $thumb['key'],
                  'cloud_path' => $thumb_url,
                  'title' => $handle,
                  'view_type' => $view_type,
                  'last_updated' => Date('Y-m-d h:i:s')
                  ); */
            }
            // print_r($data); exit;
            // return $data;
        }
        return null;
    }

    public function storeImage($url, $user_id, $s3Domain, $albumId) {
        // echo $url; exit;
        $curl_url = $this->store_url;
        $path = $s3Domain . '/' . $user_id . '/album/' . $albumId . '/original/';
        $curl_url = str_replace('[PATH]', $path, $curl_url);
        /* $options = array(
          CURLOPT_RETURNTRANSFER => true,     // return web page
          CURLOPT_HEADER         => false,    // don't return headers
          CURLOPT_FOLLOWLOCATION => true,     // follow redirects
          CURLOPT_ENCODING       => "",       // handle all encodings
          CURLOPT_USERAGENT      => "spider", // who am i
          CURLOPT_AUTOREFERER    => true,     // set referer on redirect
          CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
          CURLOPT_TIMEOUT        => 120,      // timeout on response
          CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
          CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
          ); */
        $post_fields = array('url' => $url);
        $ch = curl_init($curl_url);
        // curl_setopt_array($ch, $options);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        $content = curl_exec($ch);
        return $content;
        /* $err     = curl_errno( $ch );
          $errmsg  = curl_error( $ch );
          $header  = curl_getinfo( $ch );
          curl_close( $ch );
          echo "err : ".$err."\n";
          echo "content: ".$content;
          echo $errmsg; exit; */
    }

    public function isImageFilestack($imageId) {
        $filestackSql = "SELECT * FROM `pf_photos` WHERE pf_photo_id = $imageId";
        $row = mysql_fetch_array(mysql_query($filestackSql));
        if (strpos($row['Uri'], 'cdn.filestackcontent.com') !== false)
            return true;
        return false;
    }

    public function removeOldFilestackImages($imageId, $newUrl, $albumid, $s3Domain, $user_id) {
        // echo $imageId;
        $filestackSql = "SELECT * FROM `pf_photos` WHERE pf_photo_id = $imageId";
        $row = mysql_fetch_array(mysql_query($filestackSql));
        // $urls = array('originalBase' => $row['Uri']);
        $this->reWriteImage($row['Uri'], $newUrl);

        $filestackSql = "SELECT `pf_filestack_id`, `cloud_path`, `view_type` FROM `pf_filestack_photos` WHERE pf_photo_id = $imageId AND view_type != 'original'";
        $result = mysql_query($filestackSql);
        // echo mysql_num_rows($result); exit;
        while ($row = mysql_fetch_array($result)) {
            $viewType = $row['view_type'];
            // $urls[$viewType] = $row;
            $filestackUrl = $row['cloud_path'];
            $this->reWriteImage($filestackUrl, $newUrl);
            $transformedData = $this->transformImage($filestackUrl, $s3Domain . '/' . $user_id . '/album/' . $albumid . '/' . $viewType . '/', $viewType);
            // $transformedData = $this->transformImage($filestackUrl, 'albums/' . $viewType . '/', $viewType);
            foreach ($transformedData as $key => $val) {
                $$key = $val;
            }
            $updateSql = "UPDATE `pf_filestack_photos` SET cloud_filename  = '$cloud_filename', cloud_path = '$cloud_path', title = '$title', last_updated = '$last_updated' WHERE pf_photo_id = $imageId AND view_type = '$viewType'";
            mysql_query($updateSql);
        }
        // print_r($urls); exit;
    }

    public function reWriteImage($oldUrl, $newUrl) {
        $curl_url = $this->rewrite_url;
        $handle = explode('/', str_replace('https://', '', $oldUrl))[1];
        $policy = $this->getPolicy($handle);
        $signature = $this->getSignature($policy);
        $curl_url = str_replace('[HANDLE]', $handle, $curl_url);
        $curl_url = str_replace('[POLICY]', $policy, $curl_url);
        $curl_url = str_replace('[SIGNATURE]', $signature, $curl_url);
        $post_fields = array('url' => $newUrl);

        $ch = curl_init($curl_url);
        curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        $content = curl_exec($ch);
        // print_r($content); exit;
        // return $content;
    }

    public function removeImage($url) {

        $curl_url = $this->remove_url;
        $handle = strpos($url, 'https://') !== false ? str_replace('https://', '', $url) : str_replace('http://', '', $url);
        $handle = explode('/', str_replace('https://', '', $handle))[1];
        $policy = $this->getPolicy($handle);
        $signature = $this->getSignature($policy);
        $curl_url = str_replace('[HANDLE]', $handle, $curl_url);
        $post_fields = array('key' => $this->ApiKey,
            'policy' => $policy,
            'signature' => $signature
        );
        $ch = curl_init($curl_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        $content = curl_exec($ch); //exit;
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);
        /* $bla[] = "Err No : " . $err;
          $bla[] = "Content: " . $content;
          $bla[] = "Error: " . $errmsg; */
        return $content;
    }

    public function RESTApi($curl_url, $post_fields, $fh) {
        $options = array(
            CURLOPT_RETURNTRANSFER => true, // return web page
            CURLOPT_HEADER => false, // don't return headers
            CURLOPT_FOLLOWLOCATION => true, // follow redirects
            CURLOPT_ENCODING => "", // handle all encodings
            CURLOPT_USERAGENT => "spider", // who am i
            CURLOPT_AUTOREFERER => true, // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
            CURLOPT_TIMEOUT => 120, // timeout on response
            CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
        );

        $ch = curl_init();
        // $ch = curl_init($curl_url);
        curl_setopt_array($ch, $options);
        // curl_setopt($ch, CURLOPT_HEADER, 1);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_URL, $curl_url);
        // curl_setopt($ch, CURLOPT_URL, 'www.google.com');
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 2);


        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        $content = curl_exec($ch);
        // $err     = curl_errno( $ch );
        $errmsg = curl_error($ch);
        // $header  = curl_getinfo( $ch );
        curl_close($ch);
        // echo "err : ".$err."\n";
        // echo "content: " . $content;
        // echo "content: " . http_response_code();
        /* $log = "Content: " . $content . "\n";
          fwrite($fh, $log);
          $log = "Error: " . $errmsg . "\n";
          fwrite($fh, $log); */
        // echo $errmsg;
        // return $errmsg;
    }

    protected function getPolicy($handle) {

        $timestamp = sprintf('%013.0f', microtime(1) * 1000 + (60 * 60));
        $json = '{"handle":"' . $handle . '","expiry":' . $timestamp . '}';
        return base64_encode($json);
    }

    protected function getSignature($policy) {

        $secret = $this->SecretKey;
        return hash_hmac('sha256', $policy, $secret);
    }

    public function insertToProcessing($albumId, $url, $cloud_filename, $size) {
        $date = date('Y-m-d h:i:s');
        echo $sql = "INSERT INTO `pf_filestack_album_processing` (pf_album_id, url, cloud_filename, size, created) VALUES ($albumId, '$url', '$cloud_filename', $size, '$date')";
        if (!mysql_query($sql))
            echo mysql_error();
    }

    public function removeFromProcessing($albumId, $url, $avatarLog) {
        $sql = "DELETE FROM `pf_filestack_album_processing` WHERE  pf_album_id = $albumId AND url = '$url'";
        mysqli_query($this->dbCon, $sql);
        /* $log = "Query: " . $sql . "\n";
          if(mysql_query($sql))
          $log .= "Affected rows: " . mysql_affected_rows() . "\n";
          else
          $log .= "SQL Error: " . mysql_error() . "\n";
          fwrite($avatarLog, $log); */
    }

    public function removeprocessedEntries($albumId, $avatarLog) {
        $sql = "DELETE fp FROM `pf_filestack_album_processing` AS fp JOIN `pf_photos` AS pm on fp.url = pm.Uri WHERE fp.pf_album_id = $albumId";
        mysqli_query($this->dbCon, $sql);
        /* $log = "Query: " . $sql . "\n";
          if(mysql_query($sql))
          $log .= "Affected rows: " . mysql_affected_rows() . "\n";
          else
          $log .= "SQL Error: " . mysql_error() . "\n";
          fwrite($avatarLog, $log); */
    }

    public function checkIfAllProcessed($albumId) {
        $sql = "SELECT count(*) AS count FROM `pf_filestack_album_processing` WHERE  pf_album_id = $albumId";
        $count = mysqli_fetch_array(mysqli_query($this->dbCon, $sql))['count'];
        if ($count > 0)
            return false;
        return true;
    }

    public function albumExists($albumId) {
        $sql = "SELECT * FROM `pf_albums` WHERE pf_album_id = $albumId";
        $result = mysqli_query($this->dbCon, $sql);
        if (mysqli_num_rows($result) > 0)
            return true;
        return false;
    }

    public function removeAlbumProcessingEntries($albumId) {
        $sql = "DELETE FROM `pf_filestack_album_processing` WHERE  pf_album_id = $albumId";
        mysqli_query($this->dbCon, $sql);
    }

    public function notifyAdmin($data) {
        $rEmailTemplate = new BxDolEmailTemplates();
        $adminData = db_arr("SELECT user_email FROM wp_users WHERE user_login = 'admin'");
        $adminEmail = $adminData['Email'];
        $content = '';
        $totalUsers = count($data['users']);
        $totalFiles = $data['count'];
        $date = $data['date'];
        foreach ($data['users'] as $username => $row) {
            $content .= '******** ' . $username . '********<br>';
            $content .= 'Uploaded Time: ' . $row['time'] . '<br>';
            $content .= 'No of Files Impacted: ' . $row['count'] . '<br><br>';
        }
        $adminEmail = 'rafeeque@sparksupport.com';
        // $adminEmail = 'fathimath.sithara@msisoft.in';
        $aTemplate = $rEmailTemplate->getTemplate('t_notifyAdminOnWebhookFails');
        $variables = array('users' => $totalUsers, 'files' => $totalUsers, 'date' => $date, 'content' => $content);
        $aTemplate['Body'] = $this->replaceEmailVariables($aTemplate['Body'], $variables);
        if (sendMail($adminEmail, $aTemplate['Subject'], $aTemplate['Body']))
            return true;
        return false;
    }

    public function notifyUser($user_id) {
        $rEmailTemplate = new BxDolEmailTemplates();
        $userData = db_arr("SELECT * FROM wp_users WHERE ID = $user_id");
        $userEmail = $userData['Email'];
        $userEmail = 'dinoop@sparksupport.com';
        // $userEmail = 'fathimath.sithara@msisoft.in';
        $username = $userData['NickName'];
        $aTemplate = $rEmailTemplate->getTemplate('t_uploadedAlbumImagesProcessed');
        $variables = array('username' => $username, 'numberOfPhotos' => 10);
        $aTemplate['Body'] = $this->replaceEmailVariables($aTemplate['Body'], $variables);
        if (sendMail($userEmail, $aTemplate['Subject'], $aTemplate['Body']))
            return true;
        return false;
    }

    public function replaceEmailVariables($body, $variables) {
        foreach ($variables as $key => $value) {
            $value = trim($value);
            $body = str_replace('[[' . $key . ']]', $value, $body);
        }
        return $body;
    }

    public function watermarkAvatarImage($user_id, $status, $agencyId) {
        if (in_array($status, array('Matched', 'Placed'))) {
            $watermarkedUser = mysql_fetch_assoc(mysql_query("SELECT `author_id` FROM `watermarkimages` where `author_id`='" . $user_id . "'"));
            mysql_query("UPDATE  `Profiles` SET  `Status` =  'Active' WHERE  `Profiles`.`ID` =" . $user_id);
            mysql_query("UPDATE  `Profiles_draft` SET  `Status` =  'Active' WHERE  `Profiles`.`ID` =" . $user_id);

            if (!empty($watermarkedUser))
                mysql_query("DELETE FROM `watermarkimages` where `author_id`=" . $user_id);
            list($avatarData, $isAvatarExists) = $this->getAvatarDetails($user_id);
            if ($isAvatarExists) {
                mysql_query("INSERT INTO `watermarkimages` (`author_id`,`status`) VALUES ('" . $user_id . "', '" . $status . "')");
                $this->backupFilestackAvatars($user_id, $avatarData, $status);
                $this->watermarkAvatar($avatarData, $agencyId, $status);
            }
        } else {
            // Delete the new table entries after restoring the avatars
            list($avatarData, $isAvatarExists) = $this->getAvatarDetails($user_id);
            $sql = "SELECT * FROM `pf_watermark_filestack_photos` WHERE user_id = $user_id";
            $result = mysql_query($sql);
            if ($isAvatarExists && mysql_num_rows($result) > 0) {
                while ($row = mysql_fetch_array($avatarData)) {
                    $this->removeImage($row['cloud_path']);
                }
                $this->restoreAvatarBackup($user_id);
                mysql_query("DELETE FROM `watermarkimages` WHERE author_id = " . $user_id);
            }
        }
    }

    /* public function isAlreadyWatermarked($user_id, $status) {
      $sql = "SELECT COUNT(*) AS count FROM `pf_watermark_filestack_photos` WHERE user_id = $user_id AND status = '". $status ."'";
      if(mysql_fetch_array(mysql_query($sql))['count'] > 0)
      return true;
      return false;
      } */

    public function watermarkAvatar($avatarData, $agencyId, $status) {
        $watermarkUrl = $this->watermark_url;
        $overlayIndex1 = $status == 'Matched' ? 'matched' : 'placed';
        $overlayIndex2 = $status == 'Matched' ? 'matchedHOA' : 'placedHOA';
        $overlayHandle = in_array($agencyId, array(73, 8117)) ? $this->watermarkImages[$overlayIndex2]['handle'] : $this->watermarkImages[$overlayIndex1]['handle'];
        // $options = 'store=location:S3,access:public&path=pf/watermark/&watermark=file:'. $overlayHandle .',position:[bottom,right],size:100';
        $options = 'watermark=file:' . $overlayHandle . ',position:[bottom,right],size:100';
        $watermarkUrl = str_replace('[OPTIONS]', $options, $watermarkUrl);
        $watermarkUrlStatic = $watermarkUrl;
        while ($row = mysql_fetch_array($avatarData)) {
            $image = $this->s3BucketPath . $row['cloud_filename'];
            $watermarkUrl = str_replace('[IMAGE]', $image, $watermarkUrlStatic);
            $cloudFilenameArr = explode('/', $row['cloud_filename']);
            $count = count($cloudFilenameArr);
            if ($count == 5) { // testpf/59/avatar/original/MarkLetty.jpg : 5
                $fileName = $cloudFilenameArr[$count - 1];
                $cloudFilenameArr[$count - 1] = 'watermark';
                $cloudFilenameArr[] = $fileName;
            }
            $path = implode('/', $cloudFilenameArr);
            $watermarkUrl = str_replace('[PATH]', $path, $watermarkUrl);
            $params = array('http' => array(
                    'method' => 'POST',
            ));
            $ctx = stream_context_create($params);
            $fp = @fopen($watermarkUrl, 'rb', false, $ctx);
            if ($fp) {
                $result = @stream_get_contents($fp);
                $decodedData = json_decode($result, true);
                $url = $decodedData['url'];
                $handle = explode('/', str_replace('https://', '', $url))[1];
                $sql = "UPDATE `pf_parent_filestack_photos` SET cloud_filename = '" . $decodedData['key'] . "', title = '" . $handle . "', cloud_path = '" . $url . "' WHERE id = " . $row['id'] . " AND view_type = '" . $row['view_type'] . "'";
                mysql_query($sql);
                echo mysql_error();
            } else {
                print_r(error_get_last());
            }
        }
    }

    public function backupFilestackAvatars($user_id, $avatarData, $status) {
        $cond = "user_id = " . $user_id;
        $sql = "SELECT count(*) AS count, status FROM `pf_watermark_filestack_photos` WHERE " . $cond;
        $result = mysql_fetch_array(mysql_query($sql));
        if ($result['count'] > 0) {
            if ($result['status'] != $status)
                mysql_query("UPDATE `pf_watermark_filestack_photos` SET status = '" . $status . "' WHERE " . $cond);
            // mysql_query("DELETE FROM `pf_watermark_filestack_photos` WHERE " . $cond);
        } else {
            while ($row = mysql_fetch_assoc($avatarData)) {
                $sql = "INSERT INTO `pf_watermark_filestack_photos` VALUES (null, '" . $row['cloud_filename'] . "', " . $row['user_id'] . ", '" . $row['title'] . "', '" . $row['cloud_path'] . "', '" . $row['view_type'] . "', '" . $status . "', '" . date('Y-m-d h:i:s') . "')";
                mysql_query($sql);
            }
            mysql_data_seek($avatarData, 0);
        }
    }

    public function restoreAvatarBackup($user_id) {
        $sql = "SELECT * FROM `pf_watermark_filestack_photos` WHERE user_id = $user_id";
        $result = mysql_query($sql);
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_array($result)) {
                $sql = "UPDATE `pf_parent_filestack_photos` SET cloud_filename = '" . $row['cloud_filename'] . "', title = '" . $row['title'] . "', cloud_path = '" . $row['cloud_path'] . "' WHERE user_id = " . $row['user_id'] . " AND view_type = '" . $row['view_type'] . "'";
                if (mysql_query($sql)) {
                    mysql_query("DELETE FROM `pf_watermark_filestack_photos` WHERE id = " . $row['id']);
                }
            }
        }
    }

    public function getAvatarDetails($user_id) {
        $sql = "SELECT * FROM `pf_parent_filestack_photos` WHERE user_id = $user_id";
        $result = mysql_query($sql);
        $return[] = $result;
        if (mysql_num_rows($result) > 0)
            $return[] = true;
        else
            $return[] = false;
        return $return;
    }

    public function test() {
        print_r($this->imageDimensions);
        exit;
    }

}

/*$test = new FilestackProcessor;
$result = $test->transformImage('https://cdn.filestackcontent.com/3icdgcnRQ39sXYgn30jL', '/test/', 'thumb');
print_r($result);*/