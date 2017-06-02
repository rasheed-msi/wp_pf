<?php
if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');}

class MeprAddonUpdates {
  public $memberpress_active, $slug, $main_file, $options_key, $title, $desc;

  public function __construct($slug, $main_file, $options_key='', $title='', $desc='') {
    $this->slug = $slug;
    $this->main_file = $main_file;
    $this->options_key = $options_key;
    $this->title = $title;
    $this->desc = $desc;

    $priority = mt_rand(900000,999999);

    add_filter('pre_set_site_transient_update_plugins', array( $this, 'queue_update' ));

    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    $this->memberpress_active = is_plugin_active('memberpress/memberpress.php');
  }

  public function site_domain() {
    return preg_replace('#^https?://(www\.)?([^\?\/]*)#','$2',home_url());
  }

  public function queue_update($transient, $first_time_install=false) {
    if(!$first_time_install && empty($transient->checked)) { return $transient; }

    $license = $this->license();
    if(empty($license)) {
      // Just here to query for the current version
      $args = array();
      if( defined( "MEMBERPRESS_EDGE" ) && MEMBERPRESS_EDGE ) { $args['edge'] = 'true'; }

      $version_info = $this->send_mothership_request( "/versions/latest/".$this->slug, $args );
      $curr_version = $version_info['version'];
      $download_url = '';
    }
    else {
      try {
        $domain = urlencode($this->site_domain());
        $args = compact('domain');

        if( defined( "MEMBERPRESS_EDGE" ) && MEMBERPRESS_EDGE ) { $args['edge'] = 'true'; }
        $license_info = $this->send_mothership_request("/versions/info/".$this->slug."/{$license}", $args);
        $curr_version = $license_info['version'];
        $download_url = $license_info['url'];
      }
      catch(Exception $e) {
        try {
          // Just here to query for the current version
          $args = array();
          if( defined( "MEMBERPRESS_EDGE" ) && MEMBERPRESS_EDGE ) {
            $args['edge'] = 'true';
          }

          $version_info = $this->send_mothership_request("/versions/latest/".$this->slug, $args);
          $curr_version = $version_info['version'];
          $download_url = '';
        }
        catch(Exception $e) {
          if(isset($transient->response[$this->main_file])) {
            unset($transient->response[$this->main_file]);
          }

          return $transient;
        }
      }
    }

    $installed_version = (($first_time_install || !isset($transient->checked) || empty($transient->checked) || !isset($transient->checked[$this->main_file])) ? '0.0.0' : $transient->checked[$this->main_file]);

    if(isset($curr_version) && version_compare($curr_version, $installed_version, '>')) {
      $transient->response[$this->main_file] = (object)array(
        'id'          => $curr_version,
        'slug'        => $this->slug,
        'new_version' => $curr_version,
        'url'         => 'http://memberpress.com',
        'package'     => $download_url
      );
    }
    else {
      unset( $transient->response[$this->main_file] );
    }

    return $transient;
  }

  public function send_mothership_request( $endpoint,
                                           $args=array(),
                                           $method='get',
                                           $domain='http://mothership.caseproof.com',
                                           $blocking=true ) {
	return false;
	
    $uri = $domain.$endpoint;

    $arg_array = array(
      'method'    => strtoupper($method),
      'body'      => $args,
      'timeout'   => 15,
      'blocking'  => $blocking,
      'sslverify' => false
    );

    $resp = wp_remote_request($uri, $arg_array);

    // If we're not blocking then the response is irrelevant
    // So we'll just return true.
    if($blocking == false) { return true; }

    if(is_wp_error($resp)) {
      throw new Exception(__('You had an HTTP error connecting to Caseproof\'s Mothership API', 'memberpress'));
    }
    else {
      if(null !== ($json_res = json_decode($resp['body'], true))) {
        if(isset($json_res['error'])) {
          throw new Exception($json_res['error']);
        }
        else {
          return $json_res;
        }
      }
      else {
        throw new Exception(__( 'Your License Key was invalid', 'memberpress'));
      }
    }

    return false;
  }

  public function manually_queue_update($first_time_install=false) {
    $transient = get_site_transient('update_plugins');
    set_site_transient('update_plugins', $this->queue_update($transient, $first_time_install));
  }

  private function license() {
    if( $this->memberpress_active ) {
      $mepr_options = MeprOptions::fetch();
      return $mepr_options->mothership_license;
    }
    else if(!empty($this->options_key)) {
      return get_option($this->options_key);
    }

    return false;
  }
} //End class

