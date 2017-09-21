<?php

require_once('../wp-load.php');

class ImgFileStackManager {

    private $image_dimensions = null;
    private $convert_url = null;

    function __construct() {
        
        $this->image_dimensions = [
            'album' => [
                'original' => [],
                'thumb' => [
                    'width' => 300,
                    'height' => 360
                ],
                'webview' => [
                    'width' => 500,
                    'height' => 600
                ]
            ]
        ];
        $this->ApiKey = 'A9Ul90L7XRqWxNswfaGOGz';
        $this->s3Domain = 'testwppf';
        $this->convert_url = "https://process.filestackapi.com/" . $this->ApiKey . "/[RESIZE]/store=location:S3,access:public[PATH_FILE]";
        $this->mrt_user = new MrtUser(get_current_user_id());
        $this->mrt_file_stack = new MrtFileStack;
        $this->mrt_photo = new MrtPhoto;
    }

    public function get_params() {
        Dot::log("wp-filestack");
        return $_POST;
    }

    public function process() {

        $input = $this->get_params();
        
        $create_img = $this->image_dimensions[$input['mode']];
        
        $data = [
            'pf_album_id' => $input['pf_album_id'],
            'user_id' => $this->mrt_user->user_id,
            'Title' => $input['Title'],
            'Size' => $input['originalFile']['size'],
        ];

        $this->photo_id = $this->mrt_photo->insert($data);

        foreach ($create_img as $key => $value) {
            if ($key == 'original') {
                $data = [
                    'pf_photo_id' => $this->photo_id,
                    'cloud_filename' => $input['key'],
                    'user_id' => $this->mrt_user->user_id,
                    'title' => $input['title'],
                    'cloud_path' => $input['url'],
                    'view_type' => $key,
                    'last_updated' => date('Y-m-d H:i:s'),
                ];
                $this->mrt_file_stack->insert($data);
            } else {
                $path = $this->s3Domain . '/' . $this->mrt_user->user_id . '/' . $input['mode'] .  '/' . $input['pf_album_id'] . '/' . $key . '/';
                $transformed = $this->transformImage($path, $value['width'], $value['height']);
                $data = [
                    'pf_photo_id' => $this->photo_id,
                    'cloud_filename' => $transformed['key'],
                    'user_id' => $this->mrt_user->user_id,
                    'title' => $input['title'],
                    'cloud_path' => $transformed['url'],
                    'view_type' => $value['view_type'],
                    'last_updated' => date('Y-m-d H:i:s'),
                ];
                $this->mrt_file_stack->insert($data);
            }
        }
    }

    public function transformImage($path, $width, $height) {

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
            $replacePath = ",path:\"" . $path . "\"/" . $input['url'];
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

}

$obj = new ImgFileStackManager;
$obj->process();
