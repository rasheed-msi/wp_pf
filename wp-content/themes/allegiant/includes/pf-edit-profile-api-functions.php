<?php

/**
 * Edit Profile API's
 */
class PFEditApi {

    //global vars
    private $wpdb;
    private $user_ID;
    private $userdata;
    private $profile_info;
    //tables
    //user core & personal
    private $tbl_profile_info = 'pf_profiles';
    //user master info
    private $tbl_gender = 'pf_gender';
    private $tbl_ethnicity = 'pf_ethnicity';
    private $tbl_education = 'pf_education';
    private $tbl_religion = 'pf_religions';
    private $tbl_faith = 'pf_faith';
    private $tbl_waiting = 'pf_waiting';
    private $tbl_children = 'pf_kids_in_family';
    private $tbl_pet = 'pf_pet';
    private $tbl_family_structure = 'pf_family_structure';
    private $tbl_residence = 'pf_residency';
    private $tbl_neighborhood = 'pf_neighborhood';
    private $tbl_special_need = 'pf_special_need';
    private $tbl_birthfather_status = 'pf_birthfather_status';
    private $tbl_adoption_type = 'pf_adoption_type';
    private $tbl_age_group = 'pf_age_group';
    private $tbl_childdesired = 'pf_childdesired';
    //user preference [transaction table]
    private $tbl_home = 'pf_home';
    private $tbl_child = 'pf_child';
    private $tbl_adoption_type_preference = 'pf_adoption_type_preference';
    private $tbl_age_group_preference = 'pf_age_group_preference';
    private $tbl_birthfather_pref = 'pf_birthfather_pref';
    private $tbl_ethnicity_pref = 'pf_ethnicity_pref';
    private $tbl_faith_pref = 'pf_faith_pref';
    private $tbl_gender_preference = 'pf_gender_preference';
    private $tbl_special_need_pref = 'pf_special_need_pref';
    private $tbl_desired_child_pref = 'pf_desired_child_pref';
    private $tbl_agencies = 'pf_agencies';
    private $tbl_agency_users = 'pf_agency_users';
    //contact details 
    private $tbl_contact_details = 'pf_contact_details';
    //contact master tbls
    private $tbl_countries = 'pf_countries';
    private $tbl_states = 'pf_states';
    private $tbl_regions = 'pf_regions';
    //api endpoint
    private $namespace = 'pf/api/v1';

    function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;

        //api rules
        add_action('rest_api_init', array($this, 'pf_rest_routes'));
    }

    function pf_get_requests() {
        return [
            'aboutus' => [
                'route' => '/aboutus/(?P<id>\d+)',
                'parm' => [
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => [$this, 'pfedAboutFunc'],
                    'permission_callback' => [$this, 'perm_callback'],
                //'args' => [ 'id' => ['validate_callback' => 'is_numeric']],
                ]
            ],
            'childpreference' => [
                'route' => '/childpreference/(?P<id>\d+)',
                'parm' => [
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => [$this, 'pfedChildprefFunc'],
                    'permission_callback' => [$this, 'perm_callback'],
                //'args' => [ 'id' => ['validate_callback' => 'is_numeric']],
                ]
            ],
            'contactus' => [
                'route' => '/contactus/(?P<id>\d+)',
                'parm' => [
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => [$this, 'pfedContactFunc'],
                    'permission_callback' => [$this, 'perm_callback'],
                // 'args' => [ 'id' => ['validate_callback' => 'is_numeric']],
                ]
            ],
            'states' => [
                'route' => '/states',
                'parm' => [
                    'methods' => 'POST',
                    'callback' => [$this, 'pfedStateFunc'],
                    'permission_callback' => [$this, 'perm_callback'],
                // 'args' => [ 'id' => ['validate_callback' => 'is_numeric']],
                ]
            ],
            'saboutus' => [
                'route' => '/saboutus',
                'parm' => [
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'pfedsAboutFunc'],
                    'permission_callback' => [$this, 'perm_callback'],
                //'args' => [ 'id' => ['validate_callback' => 'is_numeric']],
                ]
            ],
            'schildpreference' => [
                'route' => '/schildpreference',
                'parm' => [
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'pfedsChildprefFunc'],
                    'permission_callback' => [$this, 'perm_callback'],
                //'args' => [ 'id' => ['validate_callback' => 'is_numeric']],
                ]
            ],
            'scontactus' => [
                'route' => '/scontactus',
                'parm' => [
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'pfedsContactFunc'],
                    'permission_callback' => [$this, 'perm_callback'],
                //'args' => [ 'id' => ['validate_callback' => 'is_numeric']],
                ]
            ],
            'agencylist' => [
                'route' => '/agencylist/(?P<id>\d+)',
                'parm' => [
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => [$this, 'pfedAgencyListFunc'],
                    'permission_callback' => [$this, 'perm_callback'],
                //'args' => [ 'id' => ['validate_callback' => 'is_numeric']],
                ]
            ],
            'sagencylist' => [
                'route' => '/sagencylist',
                'parm' => [
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'pfedsAgencyListFunc'],
                    'permission_callback' => [$this, 'perm_callback'],
                //'args' => [ 'id' => ['validate_callback' => 'is_numeric']],
                ]
            ],
            'dagencylist' => [
                'route' => '/dagencylist',
                'parm' => [
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'pfedDelAgencyListFunc'],
                    'permission_callback' => [$this, 'perm_callback'],
                //'args' => [ 'id' => ['validate_callback' => 'is_numeric']],
                ]
            ]
        ];
    }

    function pf_rest_routes() {
        $requests = $this->pf_get_requests();
        foreach ($requests as $key => $request) {
            register_rest_route($this->namespace, $request['route'], $request['parm']);
        }
    }

    function perm_callback() {
        return true;
//        return current_user_can('edit_others_posts');
    }

    /* pref start */

    function getGender() {
        return $this->wpdb->get_results("SELECT `gender_id` AS selectVal, `gender_text` AS selectText FROM " . $this->tbl_gender . " ORDER BY selectText", ARRAY_A);
    }

    function getChildDesired() {
        $childDesired = $this->wpdb->get_results("SELECT `ChildDesired_id` AS selectVal, `ChildDesired_text` AS selectText FROM " . $this->tbl_childdesired . " ORDER BY selectText", ARRAY_A);
        array_unshift($childDesired, array('selectVal' => '-1001', 'selectText' => 'All'));
        return $childDesired;
    }

    function getAgeGroup() {
        $ageGroup = $this->wpdb->get_results("SELECT `Age_group_id` AS selectVal, `Age_group` AS selectText FROM " . $this->tbl_age_group . " ORDER BY selectText", ARRAY_A);
        array_unshift($ageGroup, array('selectVal' => '-1001', 'selectText' => 'All'));
        return $ageGroup;
    }

    function getAdoptionType() {
        $adoptionType = $this->wpdb->get_results("SELECT `adoption_type_id` AS selectVal, `adoption_type` AS selectText FROM " . $this->tbl_adoption_type . " ORDER BY selectText", ARRAY_A);
        array_unshift($adoptionType, array('selectVal' => '-1001', 'selectText' => 'All'));
        return $adoptionType;
    }

    function getEthnicityPref() {
        $ethnicityPref = $this->wpdb->get_results("SELECT `ethnicity_id` AS selectVal, `ethnicity` AS selectText FROM " . $this->tbl_ethnicity . " ORDER BY selectText", ARRAY_A);
        array_unshift($ethnicityPref, array('selectVal' => '-1001', 'selectText' => 'All'));
        return $ethnicityPref;
    }

    function getBirthFatherStatus() {
        $birthFatherStatus = $this->wpdb->get_results("SELECT `Birthfather_status_id` AS selectVal, `Birthfather_status` AS selectText FROM " . $this->tbl_birthfather_status . " ORDER BY selectText", ARRAY_A);
        array_unshift($birthFatherStatus, array('selectVal' => '-1001', 'selectText' => 'All'));
        return $birthFatherStatus;
    }

    function getSpecialNeed() {
        return $this->wpdb->get_results("SELECT `special_need_id` AS selectVal, `special_need` AS selectText FROM " . $this->tbl_special_need . " ORDER BY selectText", ARRAY_A);
    }

    function getChildDesiredById() {
        return wp_list_pluck($this->wpdb->get_results("SELECT `desired_child_id` FROM " . $this->tbl_desired_child_pref . " WHERE `user_id`=" . $this->user_ID, ARRAY_A), 'desired_child_id');
    }

    function getAdoptionTypePrefById() {
        return wp_list_pluck($this->wpdb->get_results("SELECT `adoption_type_id` FROM " . $this->tbl_adoption_type_preference . " WHERE `user_id`=" . $this->user_ID, ARRAY_A), 'adoption_type_id');
    }

    function getAgeGroupPrefById() {
        return wp_list_pluck($this->wpdb->get_results("SELECT `age_group_id` FROM " . $this->tbl_age_group_preference . " WHERE `user_id`=" . $this->user_ID, ARRAY_A), 'age_group_id');
    }

    function getGenderPrefById() {
        return $this->wpdb->get_var("SELECT `gender` FROM " . $this->tbl_gender_preference . " WHERE `user_id`=" . $this->user_ID);
    }

        
    function getSpecialNeedsById() {
        return wp_list_pluck($this->wpdb->get_results("SELECT `special_need_id` FROM " . $this->tbl_special_need_pref . " WHERE `user_id`=" . $this->user_ID, ARRAY_A), 'special_need_id');
    }

    function getBirthfatherPrefById() {
        return wp_list_pluck($this->wpdb->get_results("SELECT `birthfather_status_id` FROM " . $this->tbl_birthfather_pref . " WHERE `user_id`=" . $this->user_ID, ARRAY_A), 'birthfather_status_id');
    }

    function getEthnicityPrefById() {
        return wp_list_pluck($this->wpdb->get_results("SELECT `ethnicity_id` FROM " . $this->tbl_ethnicity_pref . " WHERE `user_id`=" . $this->user_ID, ARRAY_A), 'ethnicity_id');
    }

    /* pref end */


    /* for about API start */

    function getEthinicity() {
        return $this->wpdb->get_results("SELECT `ethnicity_id` AS selectVal, `ethnicity` AS selectText FROM " . $this->tbl_ethnicity . " ORDER BY selectText", ARRAY_A);
    }

    function getEducation() {
        return $this->wpdb->get_results("SELECT `education_id` AS selectVal, `education_text` AS selectText FROM " . $this->tbl_education . " ORDER BY selectText", ARRAY_A);
    }

    function getReligion() {
        return $this->wpdb->get_results("SELECT `ReligionId` AS selectVal, `Religion` AS selectText FROM " . $this->tbl_religion . " ORDER BY selectText", ARRAY_A);
    }

    function getFaith() {
        return $this->wpdb->get_results("SELECT `faith_id` AS selectVal, `faith` AS selectText FROM " . $this->tbl_faith . " ORDER BY selectText", ARRAY_A);
    }

    function getWaiting() {
        return $this->wpdb->get_results("SELECT `waiting_id` AS selectVal, `waiting_text` AS selectText FROM " . $this->tbl_waiting . " ORDER BY waiting_order", ARRAY_A);
    }

    function getChildren() {
        return $this->wpdb->get_results("SELECT `no_of_kids` AS selectVal, `description` AS selectText FROM " . $this->tbl_children . " ORDER BY sort_order", ARRAY_A);
    }

    function getPets() {
        return $this->wpdb->get_results("SELECT `Pet_id` AS selectVal, `Pet_text` AS selectText FROM " . $this->tbl_pet . " ORDER BY selectText", ARRAY_A);
    }

    function getFamilyStructure() {
        return $this->wpdb->get_results("SELECT `family_structure_id` AS selectVal, `family_structure` AS selectText FROM " . $this->tbl_family_structure . " ORDER BY selectText", ARRAY_A);
    }

    function getResidency() {
        return $this->wpdb->get_results("SELECT `Residency_id` AS selectVal, `Residency_text` AS selectText FROM " . $this->tbl_residence . " ORDER BY selectText", ARRAY_A);
    }

    function getNeighborhood() {
        return $this->wpdb->get_results("SELECT `Neighborhood_id` AS selectVal, `Neighborhood_text` AS selectText FROM " . $this->tbl_neighborhood . " ORDER BY selectText", ARRAY_A);
    }

    /* for about API end */

    /** Contact Start * */
    function getContactInfo() {
        $contactInfo = $this->wpdb->get_row("SELECT u.user_email ,c.* FROM " . $this->wpdb->users . " u LEFT JOIN  " . $this->tbl_contact_details . " c ON c.user_id = u.ID WHERE u.ID=" . $this->user_ID);
//echo $this->wpdb->last_query;exit;
        $data = array();
        $data['user_id'] = $this->user_ID;
        $data['City'] = $contactInfo->City;
        $data['Country'] = $contactInfo->Country;
        $data['DefaultContact'] = $contactInfo->DefaultContact;
        $data['emailid'] = $contactInfo->user_email;
        $data['fax_num'] = $contactInfo->fax_num;
        $data['home_num'] = $contactInfo->home_num;
        $data['mobile_num'] = $contactInfo->mobile_num;
        $data['office_num'] = $contactInfo->office_num;
        $data['Region'] = $contactInfo->Region;
        $data['State'] = $contactInfo->State;
        $data['StreetAddress'] = $contactInfo->StreetAddress;
        $data['Zip'] = $contactInfo->Zip;
        return $data;
    }

    function getCountries() {
        $countries = $this->wpdb->get_results("SELECT `country_id` AS selectVal, `country` AS selectText FROM " . $this->tbl_countries . " ORDER BY selectText", ARRAY_A);
        return $countries;
    }

    function getRegions() {
        $regions = $this->wpdb->get_results("SELECT `RegionId` AS selectVal, `Region` AS selectText FROM " . $this->tbl_regions . " ORDER BY selectText", ARRAY_A);
        return $regions;
    }

    function getStates($countryId = '') {
        if (empty($countryId)) {
            $countryId = $this->wpdb->get_var("SELECT Country FROM " . $this->tbl_contact_details . " WHERE user_id=" . $this->user_ID);
            $countryId = !empty($countryId) ? $countryId : '183';
        }
        $states = $this->wpdb->get_results("SELECT `state_id` AS selectVal, `State` AS selectText FROM " . $this->tbl_states . " WHERE country_id = '" . $countryId . "' ORDER BY selectText", ARRAY_A);
        return $states;
    }

    /** Contact End * */

    /**
     * PF Edit Api Path:-v1/editprofile/aboutus/
     */
    function pfedAboutFunc($request) {
        $params = $request->get_params();

        global $user_ID, $userdata;
        //user id
//        $this->user_ID = !empty($user_ID) ? $user_ID : get_current_user_id();
        $this->user_ID = $params['id'];
        //user details
        $this->userdata = !empty($userdata) ? $userdata : get_userdata($this->user_ID);

        //profile info
        $this->profile_info = $this->wpdb->get_row($this->wpdb->prepare(
                        "SELECT p.*,c.website, h.pets,h.relationship_status_id,"
                        . "h.family_structure_id, h.Neighborhood, h.style, "
                        . "ch.Number_of_childern, ch.Type FROM $this->tbl_profile_info p "
                        . "LEFT JOIN $this->tbl_contact_details c ON p.wp_user_id = c.user_id "
                        . "LEFT JOIN $this->tbl_home h ON p.wp_user_id = h.user_id "
                        . "LEFT JOIN $this->tbl_child ch ON p.wp_user_id = ch.user_id "
                        . "WHERE p.wp_user_id=%d", array($this->user_ID)));

        //userDetails
        $uInfo = array();
        $uInfo['name'] = $this->profile_info->first_name;
        $uInfo['dob'] = $this->profile_info->dob;
        $uInfo['gender'] = $this->profile_info->gender;
        $uInfo['education'] = (string) $this->profile_info->education_id;
        $uInfo['ethnicity'] = (string) $this->profile_info->ethnicity_id;
        $uInfo['religion'] = (string) $this->profile_info->religion_id;
        $uInfo['waiting_id'] = $this->profile_info->waiting_id;
        $uInfo['occupation'] = $this->profile_info->occupation;

        //coupleDetails
        $cInfo = array();
        $cInfo['name'] = $this->profile_info->spouse_first_name;
        $cInfo['dob'] = $this->profile_info->spouse_dob;
        $cInfo['gender'] = $this->profile_info->spouse_gender;
        $cInfo['education'] = (string) $this->profile_info->spouse_education_id;
        $cInfo['ethnicity'] = (string) $this->profile_info->spouse_ethnicity_id;
        $cInfo['religion'] = (string) $this->profile_info->spouse_religion_id;
        //$cInfo['waiting_id'] = $this->profile_info->waiting_id;
        $cInfo['occupation'] = $this->profile_info->spouse_occupation;

        $data = array();
        $data['user_id'] = $this->user_ID;
        $data['profile_number'] = (( $this->profile_info->profile_year < 0 ) ? "" : (strlen($this->profile_info->profile_no) > 0 && $this->profile_info->profile_no > 0) ? $this->profile_info->profile_year . "_" . str_pad($this->profile_info->profile_no, STR_PAD_LEFT) : $this->profile_info->profile_year);
        $data['profile_type'] = !empty($this->profile_info->spouse_first_name) ? 'couple' : 'single';
        $data['waiting'] = $this->profile_info->waiting_id;
        $data['profiles'] = array($uInfo, $cInfo);
        $data['autoapprove'] = true;
        $data['user_login'] = $this->userdata->data->user_login; //nickname
        $data['children'] = $this->profile_info->Number_of_childern;
        $data['children_type'] = $this->profile_info->Type;
        $data['family_structure'] = $this->profile_info->family_structure_id;
        $data['house_style'] = $this->profile_info->style;
        $data['neighborhood'] = $this->profile_info->Neighborhood;
        $data['pets'] = explode(',', $this->profile_info->pets);
        $data['relationship_status'] = $this->profile_info->relationship_status_id;
        $data['website'] = $this->profile_info->website;

        $dataOpt = array();
        $dataOpt['gender'] = array(
            array('selectVal' => 'male', 'selectText' => 'Male'),
            array('selectVal' => 'female', 'selectText' => 'Female')
        );

        $dataOpt['ethnicity'] = $this->getEthinicity();
        $dataOpt['education'] = $this->getEducation();
        $dataOpt['religion'] = $this->getReligion();
        $dataOpt['faith'] = $this->getFaith();
        $dataOpt['waiting'] = $this->getWaiting();
        $dataOpt['children'] = $this->getChildren();
        $dataOpt['pets'] = $this->getPets();
        $dataOpt['family_structure'] = $this->getFamilyStructure();
        $dataOpt['residency'] = $this->getResidency();
        $dataOpt['neighborhood'] = $this->getNeighborhood();

        return new WP_REST_Response(array('data' => $data, 'data_options' => $dataOpt), 200);
    }

    /**
     * PF Edit Api Path:-v1/editprofile/contactus/
     */
    function pfedContactFunc($request) {
        $params = $request->get_params();

        global $user_ID, $userdata;
        //user id
        $this->user_ID = $params['id'];
        $data = $this->getContactInfo();

        $dataOpt = array();
        $dataOpt['countries'] = $this->getCountries();
        $dataOpt['Regions'] = $this->getRegions();
        $dataOpt['states'] = $this->getStates();

        return new WP_REST_Response(array('data' => $data, 'data_options' => $dataOpt), 200);
    }

    /**
     * PF Edit Api Path:-v1/editprofile/childpreference/
     */
    function pfedChildprefFunc($request) {
        $params = $request->get_params();

        global $user_ID, $userdata;
        //user id
        $this->user_ID = $params['id'];

        $dataOpt = array();
        $dataOpt['adoption_type'] = $this->getAdoptionType();
        $dataOpt['age_group'] = $this->getAgeGroup();
        $dataOpt['ethnicity'] = $this->getEthnicityPref();
        $dataOpt['birthfather_status'] = $this->getBirthFatherStatus();
        $dataOpt['child_desired'] = $this->getChildDesired();
        $dataOpt['gender'] = $this->getGender();
        $dataOpt['special_need'] = $this->getSpecialNeed();

        $data = array();
        $data['user_id'] = $this->user_ID;
        $data['child_desired'] = $this->getChildDesiredById();
        $data['adoption_type_pref'] = $this->getAdoptionTypePrefById();
        $data['age_group_pref'] = $this->getAgeGroupPrefById();
        $data['genderPref'] = $this->getGenderPrefById();
        $data['ethnicityprefs'] = $this->getEthnicityPrefById();
        $data['birthfatherPrefs'] = $this->getBirthfatherPrefById();
        $data['special_needs'] = $this->getSpecialNeedsById(); //nickname
        $data['special_need'] = (!empty($data['special_needs']) && count($data['special_needs']) > 0) ? 'yes': 'no';

        if (isset($data['child_desired'][0]) && $data['child_desired'][0] == "-1001") {
            array_unshift($dataOpt['child_desired'], array('selectVal' => '-1002', 'selectText' => 'None'));
        }
        if (isset($data['age_group_pref'][0]) && $data['age_group_pref'][0] == "-1001") {
            array_unshift($dataOpt['age_group'], array('selectVal' => '-1002', 'selectText' => 'None'));
        }
        if (isset($data['adoption_type_pref'][0]) && $data['adoption_type_pref'][0] == "-1001") {
            array_unshift($dataOpt['adoption_type'], array('selectVal' => '-1002', 'selectText' => 'None'));
        }
        if (isset($data['birthfatherPrefs'][0]) && $data['birthfatherPrefs'][0] == "-1001") {
            array_unshift($dataOpt['birthfather_status'], array('selectVal' => '-1002', 'selectText' => 'None'));
        }
        if (isset($data['ethnicityprefs'][0]) && $data['ethnicityprefs'][0] == "-1001") {
            array_unshift($dataOpt['ethnicity'], array('selectVal' => '-1002', 'selectText' => 'None'));
        }


        return new WP_REST_Response(array('data' => $data, 'data_options' => $dataOpt), 200);
    }

    /**
     * PF states
     */
    function pfedStateFunc($request) {

        $params = $request->get_params();
        $countryId = $params['data']['Country'];
        $dataOpt = array();
        $dataOpt['states'] = $this->getStates($countryId);
        return new WP_REST_Response(array('data_options' => $dataOpt), 200);
    }

    /**
     * PF Edit Api Path:-v1/editprofile/save/aboutus/
     */
    function pfedsAboutFunc($request) {
        $params = $request->get_params();
        $postData = $params['data'];

        try {
            $pExist = $this->wpdb->get_var("SELECT count(*) as count FROM " . $this->tbl_profile_info . " WHERE wp_user_id=" . $postData['user_id']);
            $cExist = $this->wpdb->get_var("SELECT count(*) as count FROM " . $this->tbl_contact_details . " WHERE user_id=" . $postData['user_id']);
            $hExist = $this->wpdb->get_var("SELECT count(*) as count FROM " . $this->tbl_home . " WHERE user_id=" . $postData['user_id']);
            $chExist = $this->wpdb->get_var("SELECT count(*) as count FROM " . $this->tbl_child . " WHERE user_id=" . $postData['user_id']);


            $data = array();
            //below commented unused cols
            //$data['pf_old_id'] = $postData[''];$data['last_name'] = $postData[''];$data['account_id'] = $postData[''];$data['spouse_last_name'] = $postData[''];$data['wp_user_id'] = $postData['user_id'];$data['faith_id'] = $postData[''];$data['Status_id'] = $postData[''];$data['role_id'] = $postData[''];$profileNo = explode('_', $postData['profile_number']);$data['profile_no'] = $profileNo[1];$data['profile_year'] = $profileNo[2];$data['zoho_id'] = $postData[''];$data['pf_agency_id'] = $postData[''];$data['avatar'] = $postData[''];
            $data['first_name'] = $postData['profiles'][0]['name'];
            $data['dob'] = $postData['profiles'][0]['dob'];
            $data['gender'] = $postData['profiles'][0]['gender'];
            $data['education_id'] = $postData['profiles'][0]['education'];
            $data['ethnicity_id'] = $postData['profiles'][0]['ethnicity'];
            $data['religion_id'] = $postData['profiles'][0]['religion'];
            $data['waiting_id'] = $postData['profiles'][0]['waiting_id'];
            $data['occupation'] = $postData['profiles'][0]['occupation'];
            $data['spouse_first_name'] = $postData['profiles'][1]['name'];
            $data['spouse_dob'] = $postData['profiles'][1]['dob'];
            $data['spouse_gender'] = $postData['profiles'][1]['gender'];
            $data['spouse_education_id'] = $postData['profiles'][1]['education'];
            $data['spouse_ethnicity_id'] = $postData['profiles'][1]['ethnicity'];
            $data['spouse_religion_id'] = $postData['profiles'][1]['religion'];
            $data['spouse_occupation'] = $postData['profiles'][1]['occupation'];
            //$data['website'] = $postData['website']


            if ($pExist > 0) {
                //update to profiles
                $whereCond = array('wp_user_id' => $postData['user_id']);
                $dataFormat = array('%s', '%s', '%s', '%d', '%d', '%d', '%d', '%s');
                $this->wpdb->update($this->tbl_profile_info, $data, $whereCond, $dataFormat, array('%d'));
            } else {
                $dataFormat = array('%s', '%s', '%s', '%d', '%d', '%d', '%d', '%s', '%d');
                $data['wp_user_id'] = $postData['user_id'];
                $this->wpdb->insert($this->tbl_profile_info, $data, $dataFormat);
            }

            //update to contact details
            // update to home 
            $dataContact = array('website' => $postData['website']);
            if ($cExist > 0) {
                $dataWhere = array('user_id' => $postData['user_id']);
                $this->wpdb->update($this->tbl_contact_details, $dataContact, $dataWhere, array('%s'), array('%d'));
            } else {
                $dataContact['wp_user_id'] = $postData['user_id'];
                $this->wpdb->insert($this->tbl_contact_details, $dataContact, array('%s', '%d'));
            }

            $dataHome = array(
                'style' => $postData['house_style'],
                'Neighborhood' => $postData['neighborhood'],
                'pets' => (is_array($postData['pets']) && count($postData['pets']) > 0 ) ? join(",", $postData['pets']) : '',
                'relationship_status_id' => (int) $postData['relationship_status'],
                'family_structure_id' => (int) $postData['family_structure'],
            );

            if ($hExist > 0) {
                $whereCond = array('user_id' => $postData['user_id']);
                $dataFormat = array('%s', '%s', '%s', '%d', '%d');
                $this->wpdb->update($this->tbl_home, $dataHome, $whereCond, $dataFormat, array('%d'));
            } else {
                $dataHome['user_id'] = $postData['user_id'];
                $dataFormat = array('%s', '%s', '%s', '%d', '%d', '%d');
                $this->wpdb->insert($this->tbl_home, $dataHome, $dataFormat);
            }


                $dataChild = array(
                    'Number_of_childern' => $postData['children'],
                    'Type' => $postData['children_type'],
                );
            $dataFormat = array('%d', '%s');
            if ($chExist > 0) {
                $whereCond = array('user_id' => $postData['user_id']);
                $this->wpdb->update($this->tbl_child, $dataChild, $whereCond, $dataFormat, array('%d'));
            } else {
                $dataChild['user_id'] = $postData['user_id'];
                array_push($dataFormat, '%d');
                $this->wpdb->insert($this->tbl_child, $dataChild, $dataFormat);
            }

            return new WP_REST_Response(array('code' => 200, 'message' => 'Profile Saved'), 200);
        } catch (Exception $exc) {
            return new WP_REST_Response(array('code' => $exc->getCode(), 'message' => $exc->getTraceAsString()), $exc->getCode());
        }
    }

    /**
     * PF Edit Api Path:-v1/editprofile/save/contactus/
     */
    function pfedsContactFunc($request) {
        $params = $request->get_params();
        $postData = $params['data'];

        try {
            $this->user_ID = $postData['user_id'];

            $cExist = $this->wpdb->get_var("SELECT count(*) as count FROM " . $this->tbl_contact_details . " WHERE user_id=" . $this->user_ID);

            $dataCon = array(
            //  'user_id'               => $postData['user_id'],
                'StreetAddress'         => $postData['address1'] . ',' . $postData['address2'],
                'City'                  => $postData['City'],
                'State'                 => (int)$postData['State'],
                'Country'               => (int)$postData['Country'],
                'Region'                => (int)$postData['Region'],
                'Zip'                   => $postData['Zip'],
                'mobile_num'            => $postData['mobile_num'],
                'home_num'              => $postData['home_num'],
                'office_num'            => $postData['office_num'],
                'fax_num'               => $postData['fax_num'],
                'DefaultContact'        => $postData['DefaultContact'],
            //  'AllowDefaultContact'   => $postData['DefaultContacts_form'],
            );

            $dataFormat = array('%s', '%s', '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%d');

            if ($cExist > 0) {
                $whereCond = array('user_id' => $this->user_ID);
                $this->wpdb->update($this->tbl_contact_details, $dataCon, $whereCond, $dataFormat, array('%d'));
            } else {
                $dataCon['user_id'] = $this->user_ID;
                array_push($dataFormat, '%d');
                $this->wpdb->insert($this->tbl_contact_details, $dataCon, $dataFormat);
            }

            return new WP_REST_Response(array('code' => 200, 'message' => 'Profile Saved'), 200);
        } catch (Exception $exc) {
            return new WP_REST_Response(array('code' => $exc->getCode(), 'message' => $exc->getTraceAsString()), $exc->getCode());
        }
    }

    /**
     * PF Edit Api Path:-v1/editprofile/save/childpreference/
     */
    function pfedsChildprefFunc($request) {
        $params = $request->get_params();
        $postData = $params['data'];

        try {
            $this->user_ID = $postData['user_id'];
            //preffs
            $childDesired = $postData['child_desired'];
            $genderPref = $postData['genderPref'];
            $ageGroupPref = $postData['age_group_pref'];
            $ethnicityPrefs = $postData['ethnicityprefs'];
            $adoptionTypePref = $postData['adoption_type_pref'];
            $birthfatherPrefs = $postData['birthfatherPrefs'];
            $special_needs = $postData['special_needs'];
            $special_need = $postData['special_need'];

            if (!empty($childDesired) && is_array($childDesired)) {
                $this->wpdb->delete($this->tbl_desired_child_pref, array('user_id' => $this->user_ID));
                foreach ($childDesired as $cdesired) {
                    if ($cdesired == '-1002')
                        $cdesired = -1001;

                    $this->wpdb->insert($this->tbl_desired_child_pref, array('desired_child_id' => $cdesired, 'user_id' => $this->user_ID), array('%d', '%d'));
                }
            }

            if (!empty($genderPref)) {
                $checkGnderPref = $this->wpdb->get_var("SELECT count(*) as count FROM " . $this->tbl_gender_preference . " WHERE user_id=" . $this->user_ID);
                if ($checkGnderPref > 0)
                    $this->wpdb->update($this->tbl_gender_preference, array('gender' => $genderPref), array('user_id' => $this->user_ID), array('%s'), array('%d'));
                else
                    $this->wpdb->insert($this->tbl_gender_preference, array('gender' => $genderPref, 'user_id' => $this->user_ID), array('%s', '%d'));
            }

            if (!empty($ageGroupPref) && is_array($ageGroupPref)) {
                $this->wpdb->delete($this->tbl_age_group_preference, array('user_id' => $this->user_ID));
                foreach ($ageGroupPref as $agp) {
                    if ($agp == '-1002')
                        $agp = -1001;

                    $this->wpdb->insert($this->tbl_age_group_preference, array('age_group_id' => $agp, 'user_id' => $this->user_ID), array('%d', '%d'));
                }
            }

            if (!empty($ethnicityPrefs) && is_array($ethnicityPrefs)) {
                $this->wpdb->delete($this->tbl_ethnicity_pref, array('user_id' => $this->user_ID));
                foreach ($ethnicityPrefs as $ep) {
                    if ($ep == '-1002')
                        $ep = -1001;

                    $this->wpdb->insert($this->tbl_ethnicity_pref, array('ethnicity_id' => $ep, 'user_id' => $this->user_ID), array('%d', '%d'));
                }
            }

            if (!empty($adoptionTypePref) && is_array($adoptionTypePref)) {
                $this->wpdb->delete($this->tbl_adoption_type_preference, array('user_id' => $this->user_ID));
                foreach ($adoptionTypePref as $atp) {
                    if ($atp == '-1002')
                        $atp = -1001;

                    $this->wpdb->insert($this->tbl_adoption_type_preference, array('adoption_type_id' => $atp, 'user_id' => $this->user_ID), array('%d', '%d'));
                }
            }

            if (!empty($birthfatherPrefs) && is_array($birthfatherPrefs)) {
                $this->wpdb->delete($this->tbl_birthfather_pref, array('user_id' => $this->user_ID));
                foreach ($birthfatherPrefs as $bf) {
                    if ($bf == '-1002')
                        $bf = -1001;

                    $this->wpdb->insert($this->tbl_birthfather_pref, array('birthfather_status_id' => $bf, 'user_id' => $this->user_ID), array('%d', '%d'));
                }
            }

            if (!empty($special_need) && $special_need == 'yes') {
                $this->wpdb->delete($this->tbl_special_need_pref, array('user_id' => $this->user_ID));
                foreach ($special_needs as $sn) {
                    if ($sn == '-1002')
                        $sn = -1001;

                    $this->wpdb->insert($this->tbl_special_need_pref, array('special_need_id' => $sn, 'user_id' => $this->user_ID), array('%d', '%d'));
                }
            }elseif($special_need == 'no'){
                $this->wpdb->delete($this->tbl_special_need_pref, array('user_id' => $this->user_ID));
            }

            return new WP_REST_Response(array('code' => 200, 'message' => 'Profile Saved'), 200);
        } catch (Exception $exc) {
            return new WP_REST_Response(array('code' => $exc->getCode(), 'message' => $exc->getTraceAsString()), $exc->getCode());
        }
    }

    function pfGetAgencyUserInfoByUid() {
        $agencies = $this->wpdb->get_results($this->wpdb->prepare("SELECT a.pf_agency_id as agencyId, a.title as agencyName, "
                        . "au.pf_agency_user_id, au.user_status, au.created_date, au.approved_date, au.deleted_date, au.is_contact as isContact "
                        . "FROM $this->tbl_agencies a LEFT JOIN $this->tbl_agency_users au "
                        . "ON a.pf_agency_id = au.pf_agency_id AND au.wp_user_id=%d ORDER BY agencyName", array($this->user_ID)), ARRAY_A);
        $agencyList = array();
        $selectedAgencyList = array();
        if (!empty($agencies)) {
            foreach ($agencies as $key => $agency) {
                if (!empty($agency['pf_agency_user_id'])) {
                    array_push($agencyList, $agency);
                } else {
                    array_push($selectedAgencyList, $agency);
                }
            }
        }

        return array('agencyList' => $selectedAgencyList, 'userId' => $this->user_ID, 'data_options' => $agencyList);
    }

    function pfedAgencyListFunc($request = null) {

        global $user_ID, $userdata;
        $params = $request->get_params();
        $this->user_ID = $params['id'];

        try {
            $agencyUsersInfo = $this->pfGetAgencyUserInfoByUid();
            return new WP_REST_Response($agencyUsersInfo, 200);
        } catch (Exception $exc) {
            return new WP_REST_Response(array('code' => $exc->getCode(), 'message' => $exc->getTraceAsString()), $exc->getCode());
        }
    }

    function pfedsAgencyListFunc($request) {
        $params = $request->get_params();
        //echo "<pre>";print_r($params);exit;
        //selected agency list
        $agencyList = $params['data']['agencyList'];
        $this->user_ID = $params['data']['userId'];
        

        if (is_numeric($this->user_ID) && $this->user_ID != 0) {
            try {
                if (!empty($agencyList)) {
                    foreach ($agencyList as $agency) {
                        $agencyUserId = $agency['pf_agency_user_id'];
                        $agencyId = $agency['agencyId'];
                        $isContact = !empty($agency['isContact']) ?  $agency['isContact'] : 0;
                        if (!empty($agencyUserId)) {
                            $this->wpdb->update($this->tbl_agency_users, array('is_contact' => $isContact), array('pf_agency_user_id' => $agencyUserId, 'wp_user_id' => $this->user_ID), array('%d'), array('%d', '%d'));
                        } else {
                            $this->wpdb->insert($this->tbl_agency_users, array('is_contact' => $isContact, 'pf_agency_id' => $agencyId, 'user_status' => 1, 'created_date' => current_time('mysql'), 'wp_user_id' => $this->user_ID), array('%d', '%d', '%d', '%s', '%d'));
//                            echo $this->wpdb->last_query;exit;
                        }
                    }
                }
                $agencyUsersInfo = $this->pfGetAgencyUserInfoByUid();
                $agencyUsersInfo = $agencyUsersInfo + array('code' => 200);
                return new WP_REST_Response($agencyUsersInfo , 200);
            } catch (Exception $exc) {
                return new WP_REST_Response(array('code' => $exc->getCode(), 'message' => $exc->getTraceAsString()), $exc->getCode());
            }
        } else {
            return new WP_REST_Response(array('code' => 404, 'message' => 'User info is missing'));
        }
    }

    function pfedDelAgencyListFunc($request) {
        $params = $request->get_params();

        $this->user_ID = $params['data']['userId'];
        $agencyUserId = $params['data']['agency']['pf_agency_user_id'];
        if (!empty($agencyUserId) && is_numeric($agencyUserId) && !empty($this->user_ID)) {
            $this->wpdb->delete($this->tbl_agency_users, array('pf_agency_user_id' => $agencyUserId));
            return new WP_REST_Response(array('code' => 200, 'message' => 'Profile Updated'), 200);
        } else {
            return new WP_REST_Response(array('code' => $exc->getCode(), 'message' => $exc->getTraceAsString()), $exc->getCode());
        }
    }

}

new PFEditApi;







