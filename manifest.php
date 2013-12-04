<?php
$manifest = array(
    'acceptable_sugar_versions' => array('6.7.3','7.0.0'),
    'acceptable_sugar_flavors' => array('PRO','ENT','ULT'),
    'readme' => '',
    'key' => 'PSI',
    'author' => 'Profiling Solutions',
    'description' => ' Provide example manifest with layoutfields array ',
    'icon' => '',
    'is_uninstallable' => true,
    'name' => 'Layoutfields Example',
    'published_date' => '2013-09-24 7:24:17',
    'type' => 'module',
    'version' => '1.0',
);
$installdefs = array(
    'id' => '2013Q2Customizations005',
    'layoutfields'=> array(
        array(
            'additional_fields'=> array(
                'Contacts' => 'send_intro_email_c',
            ),
        ),
    ),
    'custom_fields' => array(
        'Contactssend_intro_email_c' => array(
            'id' => 'Contactssend_intro_email_c',
            'name' => 'send_intro_email_c',
            'label' => 'LBL_SEND_INTRO_EMAIL',
            'comments' => '',
            'help' => '',
            'module' => 'Contacts',
            'type' => 'bool',
            'max_size' => '255',
            'require_option' => '0',
            'default_value' => '',
            'date_modified' => '2013-08-21 08:27:00',
            'deleted' => '0',
            'audited' => '1',
            'mass_update' => '1',
            'duplicate_merge' => '0',
            'reportable' => '1',
            'importable' => 'true',
            'ext1' => '',
            'ext2' => '',
            'ext3' => '',
            'ext4' => '',
        ),
    ),
);