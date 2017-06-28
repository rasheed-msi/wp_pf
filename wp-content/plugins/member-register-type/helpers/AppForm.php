<?php

class AppForm {

    public static function get_required_fields($fields) {
        $all_fields = self::set_array_key(self::fields());
        $return = [];
        foreach ($fields as $key => $value) {
            if (is_array($value)) {
                $return[] = $value;
                continue;
            }
            if (isset($all_fields[$value])) {
                $return[$value] = $all_fields[$value];
            }
        }
        return $return;
    }

    public static function set_array_key($array) {
        $return = [];
        foreach ($array as $key => $value) {
            $return[$value['name']] = $value;
        }
        return $return;
    }

    public static function fields() {
        return [
            [
                'type' => 'hidden',
                'name' => 'user_type',
                'value' => 'adoptive_family',
            ],
            [
                'type' => 'hidden',
                'name' => 'action',
                'value' => 'sample_action_name',
            ],
            [
                'type' => 'email',
                'label' => 'Email',
                'name' => 'email',
            ],
            [
                'type' => 'text',
                'label' => 'First Name',
                'name' => 'first_name',
            ],
            [
                'type' => 'text',
                'label' => 'Last Name',
                'name' => 'last_name',
            ],
            [
                'type' => 'select',
                'label' => 'Adoption Agency',
                'name' => 'pf_agency_id',
                'options' => Dot::get_user_id_role('adoption_agency'),
                'options' => Dot::get_table_select_option('pf_agencies', 'pf_agency_id', 'title'),
            ],
            [
                'type' => 'radio',
                'label' => 'Gender',
                'name' => 'gender',
                'options' => [
                    'male' => 'Man',
                    'female' => 'Women',
                ],
            ],
            [
                'type' => 'radio',
                'label' => 'Marital Status',
                'name' => 'marital_status',
                'options' => [
                    'single' => 'Single',
                    'couple' => 'Couple',
                ],
            ],
            [
                'type' => 'text',
                'label' => 'Agency or Attorney Name',
                'name' => 'title',
            ],
            [
                'type' => 'text',
                'label' => 'Agency Website',
                'name' => 'uri',
            ],
            [
                'type' => 'text',
                'label' => 'Phone Number',
                'name' => 'mobile_num',
            ],
            [
                'type' => 'text',
                'label' => 'Street Address',
                'name' => 'StreetAddress',
            ],
            [
                'type' => 'text',
                'label' => 'City',
                'name' => 'City',
            ],
            [
                'type' => 'select',
                'label' => 'State',
                'name' => 'State',
                'class' => 'select_state',
                'options' => [],
            ],
            [
                'type' => 'select',
                'label' => 'Country',
                'name' => 'Country',
                'class' => 'select_country',
                'options' => Dot::get_table_select_option('pf_countries', 'country_id', 'country', true),
            ],
            [
                'type' => 'text',
                'label' => 'Postal / Zip Code',
                'name' => 'Zip',
            ],
            [
                'type' => 'select',
                'label' => 'Roles',
                'name' => 'roles',
                'options' => Dot::get_roles_select_option(),
            ],
            [
                'type' => 'select',
                'label' => 'Membership',
                'name' => 'membership',
                'options' => Dot::get_posts_select_option('memberpressproduct'),
            ],
            [
                'type' => 'select',
                'label' => 'Locations',
                'name' => 'location',
                'required' => true,
                'options' => Dot::get_groups_select_option('Location'),
            ],
            [
                'type' => 'select',
                'label' => 'Religion',
                'name' => 'religion_id',
                'options' => Dot::get_table_select_option('pf_religions', 'ReligionId', 'Religion'),
            ],
            [
                'type' => 'select',
                'label' => 'Fith',
                'name' => 'faith_id',
                'options' => Dot::get_table_select_option('pf_faith', 'faith_id', 'faith'),
            ],
            [
                'type' => 'select',
                'label' => 'Ethnicity',
                'name' => 'ethnicity_id',
                'options' => Dot::get_table_select_option('pf_ethnicity', 'ethnicity_id', 'ethnicity'),
            ],
            [
                'type' => 'select',
                'label' => 'Education',
                'name' => 'education_id',
                'options' => Dot::get_table_select_option('pf_education', 'education_id', 'education_text'),
            ],
        ];
    }

    // CREATE FORMS

    public static function adoptive_family_register() {
        $form = [
            'form_name' => 'adoptive_family_register',
            'submit' => false,
            'fields' => [
                'user_type',
                'first_name',
                'last_name',
                'pf_agency_id',
                'gender',
                'marital_status',
            ]
        ];

        $form['fields'] = self::get_required_fields($form['fields']);
        $form['fields']['user_type']['value'] = 'adoptive_family';
        return $form;
    }

    public static function adoptive_family_couple() {
        $form = [
            'form_name' => 'adoptive_family_couple',
            'submit' => 'Save',
            'fields' => [
                'first_name',
                'last_name',
                'gender',
                'religion_id',
                'ethnicity_id',
                'education_id',
                'action',
            ]
        ];

        $form['fields'] = self::get_required_fields($form['fields']);
        $form['fields']['action']['value'] = 'adoptive_family_couple';
        return $form;
    }

    public static function general_user_contact($param) {
        $form = [
            'form_name' => 'adoptive_family_contact',
            'submit' => 'Submit',
            'fields' => [
                'action',
                'StreetAddress',
                'City',
                'Country',
                'State',
                'Zip',
                'mobile_num',
            ]
        ];

        $form['fields'] = self::get_required_fields($form['fields']);
        $form['fields']['action']['value'] = 'edit_contact';

        if (isset($param['country_id'])) {
            $states = Dot::get_states($param['country_id']);
            $results = Dot::set_array_key_value($states, 'state_id', 'State');
            $form['fields']['State']['options'] = $results;
        }

        return $form;
    }

    public static function adoptive_family_edit() {
        $form = [
            'form_name' => 'adoptive_family_edit',
            'submit' => false,
            'fields' => [
                'first_name',
                'last_name',
                'pf_agency_id',
                'gender',
                'marital_status',
                'religion_id',
                'ethnicity_id',
                'education_id',
                'action',
            ]
        ];

        $form['fields'] = self::get_required_fields($form['fields']);
        $form['fields']['action']['value'] = 'edit_profile';
        return $form;
    }

    public static function adoption_agency_register() {
        $form = [
            'form_name' => 'adoption_agency_register',
            'submit' => false,
            'fields' => [
                'user_type',
                'title',
                'uri',
                'first_name',
                'last_name',
                'mobile_num',
                'StreetAddress',
                'City',
                'Country',
                'State',
                'Zip',
            ]
        ];

        $form['fields'] = self::get_required_fields($form['fields']);
        $form['fields']['user_type']['value'] = 'adoption_agency';
        return $form;
    }

    public static function adoption_agency_edit() {
        $form = [
            'form_name' => 'adoption_agency_edit',
            'submit' => false,
            'fields' => [
                'first_name',
                'last_name',
                'action',
            ]
        ];

        $form['fields'] = self::get_required_fields($form['fields']);
        $form['fields']['action']['value'] = 'edit_profile';
        return $form;
    }

    public static function birth_mother_register() {
        $form = [
            'form_name' => 'form_birth_mother',
            'submit' => false,
            'fields' => [
                'user_type',
                'first_name',
                'last_name',
                'pf_agency_id',
                'gender',
                'marital_status',
            ]
        ];

        $form['fields'] = self::get_required_fields($form['fields']);
        $form['fields']['user_type']['value'] = 'birth_mother';
        return $form;
    }

    public static function user_filter() {

        $form = [
            'form_name' => 'user_filter',
            'submit' => false,
            'fields' => [
                'roles',
                'membership',
                'agency_id',
                'location',
            ],
        ];

        $form['fields'] = self::get_required_fields($form['fields']);
        return $form;
    }

}
