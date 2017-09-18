<?php
//header("Access-Control-Allow-Origin: *");
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

if (isset($_POST['file']) && $_POST['file'] != "")
    $json = $_POST['file'];
else
    $json = file_get_contents('php://input');


$postData = json_decode($json, true);
// print_r($postData); exit;
$avatarLog = fopen("logs/filestackWebhookLog.txt", "a+") or print_r(error_get_last());


$key = $postData['text']['key'];
$size = $postData['text']['size'];
$explodedKey = explode('/', $key);
$PForCF = $explodedKey[0];
$profileId = $explodedKey[1];
$mode = $explodedKey[2];
$uri4 = $explodedKey[3];
$uri5 = isset($explodedKey[4]) ? $explodedKey[4] : null;

require_once('FilestackProcessor.php');
$filestackObj = new FilestackProcessor;
$username = $filestackObj->getUsernameByID($profileId);
$url = $postData['text']['url'];
$title = explode('/', str_replace('https://', '', $url))[1];
if ($PForCF == 'testwppf') {
    if ($mode == 'avatar') {
        if ($uri4 == 'original' && $uri5 != 'watermark') {
            if (!isset($postData['text']['status']) || $postData['text']['status'] != 'Stored') {
                if (!$filestackObj->AvatarURLExists($url)) {
                    $log = "Date & time: " . date('m-d-Y h:i:sa') . "\n";
                    fwrite($avatarLog, $log);
                    // $profileId = $filestackObj->getProfileIdByUsername(trim($username));
                    $log = "Username: $username\n";
                    $log .= "Profile ID: $profileId\n";
                    $log .= "Mode: Avatar\n";
                    $log .= "JSON Data: $json\n";
                    fwrite($avatarLog, $log);

                    // resize the images
                    $resizeDetails = array(
                        'thumb' => array(
                            'path' => $PForCF . '/' . $profileId . '/avatar/thumb/' . $username . '.jpg'
                        ),
                        'webview' => array(
                            'path' => $PForCF . '/' . $profileId . '/avatar/webview/' . $username . '.jpg'
                        )
                    );
                    $data = array();

                    //function to update the database field
                    if ($filestackObj->updateProfileAvatar($profileId)) {

                        $data['original'] = array(
                            'cloud_filename' => $key,
                            'title' => $title,
                            'cloud_path' => $url,
                            'view_type' => 'original'
                        );
                        foreach ($resizeDetails as $key => $detail) {

                            // function that calls filstack api for image resolution
                            if ($resizedData = $filestackObj->transformImage($url, $detail['path'], $key)) {

                                if (!empty($resizedData)) {
                                    $data[$key] = $resizedData;
                                }
                                $log = ucfirst($key) . " created: Yes\n";
                                fwrite($avatarLog, $log);
                            }
                        }
                        if ($filestackObj->saveParentFilestackImages($profileId, $data))
                            $log = "Saved images: Yes\n";
                        else
                            $log = "Saved images: No\n";
                    } else
                        $log = "New image status: Not Created\n";

                    fwrite($avatarLog, $log);
                    
                    http_response_code(200);
                    fwrite($avatarLog, 'Upload in avatar completed');
                    $log = "\n\n**********************END**********************\n\n";
                    fwrite($avatarLog, $log);
                } else
                    http_response_code(200);
            } else
                http_response_code(200);
        } else
            http_response_code(200);
    } else if ($mode == 'album') {
        echo "am in album\n";
        $albumId = $uri4;
        $uri5 = $explodedKey[4];
        if ($uri5 == 'original') {
            echo "am original\n";
            echo $url;
            if (!$filestackObj->AlbumURLExists($url) && $filestackObj->iSPending($url)) {
                echo "am not exists\n";
                $filestackObj->changeProcessPhotoStatus($url);
                $blob = array(
                    'url' => $url,
                    'key' => $key,
                    'size' => $size
                );
                $filestackObj->processImage($blob, $albumId, $profileId, $username, $PForCF);
                $filestackObj->removeprocessedEntries($albumId, $avatarLog);
                /*if ($filestackObj->checkIfAllProcessed($albumId)) {
                    $filestackObj->notifyUser($profileId);
                }*/
                $log = "Date & time: " . date('m-d-Y h:i:sa') . "\n";
                $log .= "Username: $username\n";
                $log .= "Profile ID: $profileId\n";
                $log .= "Mode: Album\n";
                $log .= "JSON Data: $json\n";
                fwrite($avatarLog, $log);

                http_response_code(200);
                fwrite($avatarLog, 'Upload in albums completed');
                $log = "\n\n**********************END**********************\n\n";
                fwrite($avatarLog, $log);
            } else
                http_response_code(200);
        } else
            http_response_code(200);
    } else
        http_response_code(200);
} else
    http_response_code(200);

fclose($avatarLog);
