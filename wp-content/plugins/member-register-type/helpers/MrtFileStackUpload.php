<?php

class MrtFileStackUpload {

    private $image_dimensions = null;
    private $convert_url = null;
    private $input = [];

    function __construct() {

        $this->image_dimensions = [
            'album' => [
                'original' => [],
                'thumb' => [
                    'width' => 220,
                    'height' => 150
                ],
                'webview' => [
                    'width' => 500,
                    'height' => 600
                ]
            ]
        ];
        
        $this->convert_url = "https://process.filestackapi.com/" . MRT_FILESTACK_APIKEY . "/[RESIZE]/store=location:S3,access:public[PATH_FILE]";
        $this->mrt_user = new MrtUser(get_current_user_id());
        $this->mrt_file_stack = new MrtFileStack;
        $this->mrt_photo = new MrtPhoto;
    }

    public function process($input) {

        $this->input = $input;
        //$this->input = $this->sample_input();

        Dot::log("test 01");
        Dot::log($this->input);

        $create_img = $this->image_dimensions[$this->input['mode']];

        $data = [
            'pf_album_id' => $this->input['pf_album_id'],
            'user_id' => $this->mrt_user->user_id,
            'Size' => $this->input['originalFile']['size'],
        ];

        $this->photo_id = $this->mrt_photo->insert($data);


        Dot::log("New photo id {$this->photo_id}");

        $response = [];
        foreach ($create_img as $key => $value) {
            if ($key == 'original') {
                $data = [
                    'pf_photo_id' => $this->photo_id,
                    'cloud_filename' => $this->input['key'],
                    'user_id' => $this->mrt_user->user_id,
                    'cloud_path' => $this->input['url'],
                    'view_type' => $key,
                    'last_updated' => date('Y-m-d H:i:s'),
                ];
                $this->mrt_file_stack->insert($data);
            } else {
                $path = MRT_S3DOMAIN . '/' . $this->mrt_user->user_id . '/' . $this->input['mode'] . '/' . $this->input['pf_album_id'] . '/' . $key . '/';
                $transformed = $this->transformImage($path, $value['width'], $value['height']);
                $data = [
                    'pf_photo_id' => $this->photo_id,
                    'cloud_filename' => $transformed['cloud_filename'],
                    'user_id' => $this->mrt_user->user_id,
                    'cloud_path' => $transformed['cloud_path'],
                    'view_type' => $key,
                    'last_updated' => date('Y-m-d H:i:s'),
                ];
                $this->mrt_file_stack->insert($data);
            }
            $response[$key] = $data;
        }

        return $response;
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
            $replacePath = ",path:\"" . $path . "\"/" . $this->input['url'];
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
                    'last_updated' => Date('Y-m-d h:i:s')
                );
            }
            // print_r($data); exit;
            return $data;
        }
        return null;
    }

    public function sample_input() {
        return Array(
            'filename' => '08bfef43c342258272a26e55260d80a8.jpg',
            'handle' => 'TxhK5OIARP2YdVv0Rag2',
            'mimetype' => 'image/jpeg',
            'originalPath' => '08bfef43c342258272a26e55260d80a8.jpg',
            'size' => '386869',
            'source' => 'local_file_system',
            'url' => 'https://cdn.filestackcontent.com/TxhK5OIARP2YdVv0Rag2',
            'originalFile' => [
                'name' => '08bfef43c342258272a26e55260d80a8.jpg',
                'type' => 'image/jpeg',
                'size' => '386869',
            ],
            'status' => 'Stored',
            'key' => 'testwppf/27779/album/53800/original/W15IqJiKTe2YyHJMzAr3_08bfef43c342258272a26e55260d80a8.jpg',
            'container' => 's3.childconnect.com',
            'pf_album_id' => '53800',
            'mode' => 'album',
        );
    }

}
