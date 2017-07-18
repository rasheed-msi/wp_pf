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
                'name' => 'agency_id',
                'options' => Dot::get_user_id_role('adoption_agency'),
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
                'name' => 'agency_attorney_name',
            ],
            [
                'type' => 'text',
                'label' => 'Agency Website',
                'name' => 'agency_website',
            ],
            [
                'type' => 'text',
                'label' => 'Phone Number',
                'name' => 'phone',
            ],
            [
                'type' => 'text',
                'label' => 'Street Address',
                'name' => 'street_address',
            ],
            [
                'type' => 'text',
                'label' => 'City',
                'name' => 'city',
            ],
            [
                'type' => 'text',
                'label' => 'State',
                'name' => 'state',
            ],
            [
                'type' => 'text',
                'label' => 'Postal / Zip Code',
                'name' => 'zip',
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
        ];
    }

    // CREATE FORMS

    public static function adoptive_family() {
        $form = [
            'form_name' => 'form_adoptive_family',
            'submit' => false,
            'fields' => [
                'user_type',
                'first_name',
                'last_name',
                'agency_id',
                'gender',
                'marital_status',
            ]
        ];

        $form['fields'] = self::get_required_fields($form['fields']);
        $form['fields']['user_type']['value'] = 'adoptive_family';
        return $form;
    }

    public static function adoption_agency() {
        $form = [
            'form_name' => 'form_adoption_agency',
            'submit' => false,
            'fields' => [
                'user_type',
                'agency_attorney_name',
                'agency_website',
                'first_name',
                'last_name',
                'phone',
                'street_address',
                'city',
                'state',
                'zip',
            ]
        ];

        $form['fields'] = self::get_required_fields($form['fields']);
        $form['fields']['user_type']['value'] = 'adoption_agency';
        return $form;
    }

    public static function birth_mother() {
        $form = [
            'form_name' => 'form_birth_mother',
            'submit' => false,
            'fields' => [
                'user_type',
                'first_name',
                'last_name',
                'agency_id',
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
