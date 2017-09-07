<?php

class MrtApiTest {

    private $link;

    function __construct() {
        global $wpdb;
        $this->link = $wpdb;
    }

    public function setpass() {
        global $wpdb;
        $user_id = 26228;
        $admin_pass = '0c5bc2f502b73a9d4a0dc621c14c80872215c682';
        $admin_salt = 'pxGYYuJ5';

        $user_pass = '8b889d81b70c2053a1380fedccc638778ab8a403';
        $user_salt = 'sZ!qAtau';
        $admin123 = '$P$B7XDAm8J4pjSZ20sButJPrAJkG1n3b0';

        if (isset($_GET['type']) && $_GET['type'] == 'admin') {

            $wpdb->update($wpdb->users, ['user_pass' => $admin_pass, 'Salt' => $admin_salt], ['ID' => $user_id]);
        } else {
            $wpdb->update($wpdb->users, ['user_pass' => $user_pass, 'Salt' => $user_salt], ['ID' => $user_id]);
        }
    }

    public function curl_request() {
        $request = [
            'Categories' => 'sample',
            'Title' => 'sample test',
            'cloud_filename' => 'sample cloud_filename test',
        ];

        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_HTTPHEADER => ['Token: MjYyMjgxMDM1MTI3MzY4'],
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'http://localhost/parentfinder/wp-json/mrt/v1/285/photos',
            CURLOPT_USERAGENT => 'Codular Sample cURL Request',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $request
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // Close request to clear up some resources
        curl_close($curl);

        echo '<pre>', print_r($resp), '</pre>';
        exit();
    }

    public function upload_file() {
        ?><div class="file-uploader" data-profileId="93" data-albumId="163">Upload</div><?php
    }

    public function find_images() {

        $base_url = 'https://www.parentfinder.com/modules/boonex/photos/data/files';
        $photos = $this->get_photos();


        $return = [];
        foreach ($photos as $key => $value) {
            $url = $base_url . '/' . $value;
            $httpCode = $this->curl_file_exist($url);
            $return[$httpCode][] = $value;
        }

        echo '<pre>', print_r($return), '</pre>';
        exit();
    }

    public function curl_file_exist($url) {

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_SSL_VERIFYPEER => 0
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //echo curl_error($ch);
        curl_close($ch);
        return $httpCode;
    }

    public function get_photos() {
//        return [
//            '34635.jpg',
//            '34635_t.jpg',
//            '3463525.jpg',
//            '236.jpg',
//        ];

        return $this->link->get_col("SELECT CONCAT(old_photo_id, '.', Ext) FROM pf_photos LIMIT 10");
    }

    public function find_youtub_id() {

        $urls = $this->link->get_results("SELECT pf_video_id, video_Uri, Video FROM pf_videofiles", ARRAY_A);

        $non_youtube_ids = [];
        $youtube_ids = [];
        $suspected_youtube_ids = [];

        foreach ($urls as $key => $value) {
            $subject = $value['video_Uri'];
            $id = $value['pf_video_id'];
            if (strpos($subject, ' ') === false) {
                // Not found space
                if (strpos($subject, ':') === false) {
                    if (strlen($subject) == 11) {
                        $youtube_ids[$id]['yid'] = $subject;
                        $youtube_ids[$id]['Video'] = $value['Video'];
                    } else {
                        $suspected_youtube_ids[$id] = $subject;
                    }
                } else {
                    $non_youtube_ids[] = $subject;
                }
            } else {
                $non_youtube_ids[] = $subject;
            }
        }

        $return = [
            'youtube_ids' => $youtube_ids,
            'suspected_youtube_ids' => $suspected_youtube_ids,
            'non_youtube_ids' => $non_youtube_ids,
        ];

        return $return;
    }

    public function update_youtube_id() {

        $youtube_ids = $this->find_youtub_id()['youtube_ids'];

        $updated_ids = [];

        foreach ($youtube_ids as $key => $value) {
            if (isset($value['Video']) || $value['Video'] == '') {
                $update = [
                    'Video' => $value['yid'],
                    'YoutubeLink' => 1
                ];

                $where = [
                    'pf_video_id' => $key
                ];
                $this->link->update('pf_videofiles', $update, $where);
                $updated_ids[] = $key;
            }
        }

        echo '<pre>', print_r($updated_ids), '</pre>';
        exit();
    }

    public function setYoutubeLinkOne() {

        $videos = $this->link->get_results("SELECT pf_video_id, video, YoutubeLink FROM pf_videofiles", ARRAY_A);
        $records = [];
        foreach ($videos as $key => $video) {
            if (preg_match('/^[\w-]{11}$/', $video['video']) && $video['YoutubeLink'] == 0) {
                $update = ['YoutubeLink' => 1];
                $where = ['pf_video_id' => $video['pf_video_id']];
                $this->link->update('pf_videofiles', $update, $where);
                $records[] = $video['pf_video_id'];
            }
        }

        echo '<pre>', print_r($records), '</pre>';
        exit();
    }

    public function list_states() {
        ?>
        <div class="ui-widget">
            <label for="birds">Birds: </label>
            <input id="birds">
        </div>
        <?php
    }

    public function get_letter() {
        $mrtuser = get_user_meta(32857, 'last_name', true);
        echo '<pre>', print_r($mrtuser), '</pre>';
        exit();
    }

    

}
