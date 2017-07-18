<?php

class PF_FSRepository {

    protected $wpdb; //wp db obj
    protected static $instance; // for creating repo instance
    //filter tables
    protected $profileTable = 'pf_profiles';
    protected $familySizeTable;
    protected $countryTable;
    protected $stateTable;
    protected $religionTable;
    protected $regionTable;
    protected $ethinicityTable;
    //params
    protected $agencyId; //current agency Id

    public static function init() {
        is_null(self::$instance) AND self::$instance = new self();
        return self::$instance;
    }

    public function __construct() {
        global $wpdb; //wp db obj
        //assign values to cls vars
        $this->wpdb = $wpdb;
        $this->profileTable = 'pf_profiles';
        $this->familySizeTable = $this->wpdb->prefix . '';
        $this->countryTable = $this->wpdb->prefix . '';
        $this->stateTable = $this->wpdb->prefix . '';
        $this->religionTable = $this->wpdb->prefix . '';
        $this->regionTable = $this->wpdb->prefix . '';
        $this->ethinicityTable = $this->wpdb->prefix . '';
        //plugin hooks db related
        add_action('wp_ajax_pffs_menuitems', array($this, 'pffsActionHandler'));
        add_action('wp ajax nopriv_pffs_menuitems', array($this, 'pffsActionHandler'));
        add_action('wp_ajax_pffs_getprofiles', array($this, 'pffsActionHandler'));
        add_action('wp ajax nopriv_pffs_getprofiles', array($this, 'pffsActionHandler'));
    }

    public function pffsActionHandler() {
        $this->agencyId = $_POST['agency_id'];
        $action = $_POST['action'];

        $response = array(); //response var to sent
        switch ($action) {
            case 'pffs_menuitems':
                $response = $this->getProfileMenu();
                break;
            case 'getprofiles':
                $response = $this->getProfileMenu();
                break;
        }

        if (defined('DOING_AJAX') && DOING_AJAX) {
            echo json_encode($response);
            wp_die();
        } else {
            return json_encode($response);
        }
    }

    public function getProfileMenu() {
        
    }

    public function getProfiles() {

        //pagination
        //request params
        $filter = $_POST['key'];


        switch ($filter) {
            case 'kids':

                break;
            case 'state':

                break;
            case 'religion':

                break;
            case 'name':

                break;
            case 'child-preference':

                break;
            case 'country':

                break;
            case 'region':

                break;
            case 'sort':

                break;
            default :

                break;
        }

        $sql = 'SELECT * FROM ' . $this->profileTable . 'WHERE ';


        $this->wpdb->get_results('');
        //get Count
        //get records
    }

}

PF_FSRepository::init();
