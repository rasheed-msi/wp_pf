<?php

	require_once('FilestackProcessor.php');
	// $status = isset($argv[1]) ? 'fromUser' : 'fromCron'; 
	$albumId = isset($argv[1]) ? $argv[1] : null;
	// $where = $albumId ? "album_id = ". $albumId ." AND status = 'pending'" : "created <='". date('Y-m-d h:i:s', strtotime('-10 minutes')) ."'";
	$where = $albumId ? "album_id = ". $albumId ." AND status = 'pending'" : "created <= DATE_SUB( CURRENT_TIME(), INTERVAL 10 MINUTE)";
	// $sql = "SELECT * FROM `pfcomm_mig_new`.`filestack_album_processing` WHERE created <= '" . date('Y-m-d h:i:s', strtotime('-1 hour')) . "'";
	$sql = "SELECT * FROM `pfcomm_mig_new`.`filestack_album_processing` WHERE " . $where;
	$result = mysql_query($sql);
	$count = mysql_num_rows($result);
	if($count > 0) {
		$processingIds = array();
		while($row = mysql_fetch_array($result)) {
			$processingIds[] = $row['id'];
		}
		$sql = "UPDATE `pfcomm_mig_new`.`filestack_album_processing` SET status = 'manual' WHERE id IN (". join(',', $processingIds) .")";
		mysql_query($sql);
		mysql_data_seek($result, 0);

		$adminData = array();
		$filestackObj = new FilestackProcessor;
		$processLog = fopen("filestackAlbumReProcessLog.txt", "a+") or print_r(error_get_last());
		while($row = mysql_fetch_array($result)) {
			$id = $row['id'];
			$url = $row['url'];
			if(!$filestackObj->AlbumURLExists($url)) {
				$cloud_filename = $row['cloud_filename'];
				$size = $row['size'];
				$explodedKey = explode('/', $cloud_filename);
				$PForCF = $explodedKey[0];
				$profileId = $explodedKey[1];
				$mode = $explodedKey[2];
				$albumId = $explodedKey[3];
				if(!$filestackObj->albumExists($albumId)) {
					$filestackObj->removeAlbumProcessingEntries($albumId);
					continue;
				}
				$username = $filestackObj->getUsernameByID($profileId);
				$adminData['users'][$username]['count'] += 1;
				$adminData['users'][$username]['time'] = date('h:ia', strtotime($row['created']));
				$date = $row['created'];
				$blob = array(
					'url' => $url,
					'key' => $cloud_filename,
					'size' => $size
				);
				$log  = "Date & time: ". date('m-d-Y h:i:sa')."\n";
				$log .= "Profile ID: $profileId\n";
				$log .= "Username: $username\n";
				$log .= "Filestack URL: $url\n";
				if($filestackObj->processImage($blob, $albumId, $profileId, $username, $PForCF)) {
					$filestackObj->removeprocessedEntries($albumId, $processLog);
					if($filestackObj->checkIfAllProcessed($albumId)) {
						if($filestackObj->notifyUser($profileId))
							$log .= "Notified User: Yes\n";
						else
							$log .= "Notified User: Failed\n";
					}
					$log .= "Status: Success\n";
				}
				fwrite($processLog, $log);
				$log = "\n********************** END **********************\n\n";
				fwrite($processLog, $log);
			}
		}
		$adminData['date'] = date('F d Y', strtotime($date));
		$adminData['count'] = $count;
		if(!$albumId)
			$filestackObj->notifyAdmin($adminData);
	}

	// Below is the test code
	/*$url = 'https://cdn.filestackcontent.com/rXGFDVKTHKbf8CZv5Qoy';
	$filestackObj = new FilestackProcessor;
	$bla = $filestackObj->removeImage($url);
	echo "<pre>"; print_r($bla); exit;*/