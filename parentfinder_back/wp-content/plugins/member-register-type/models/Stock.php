<?php

class Stock {

    function __construct() {
        
    }

    function navs() {
        return [
            'approvals' => [
                'label' => 'Approvals',
                'class' => 'approvals',
            ],
            'matching' => [
                'label' => 'Matching',
                'class' => 'approvals',
            ],
            'families' => [
                'label' => 'Families',
                'class' => 'families',
            ],
            'manage_families' => [
                'label' => 'Manage Families',
                'class' => 'manage_families',
            ],
            'public_profile' => [
                'label' => 'Public Profile',
                'class' => 'public_profile',
            ],
            'blog' => [
                'label' => 'Blog',
                'class' => 'blog',
            ],
        ];
    }

    

}
