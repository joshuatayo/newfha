<?php namespace JoshuaTayo\Fha;

use System\Classes\PluginBase;
use Backend;
use JoshuaTayo\Fha\Controllers\ServiceForms;
use JoshuaTayo\Fha\Controllers\Messages;


class Plugin extends PluginBase
{
   public function registerComponents()
    {
        return [
            '\JoshuaTayo\Fha\Components\Slider'    => 'slider',
            '\JoshuaTayo\Fha\Components\PropertyPriceList'    => 'propertypricelist',
            '\JoshuaTayo\Fha\Components\ServiceList'    => 'servicelist',
            '\JoshuaTayo\Fha\Components\ServiceForm'    => 'serviceform',
            '\JoshuaTayo\Fha\Components\GalleryList'    => 'gallerylist',
            '\JoshuaTayo\Fha\Components\GalleryImages'    => 'galleryimages',
            '\JoshuaTayo\Fha\Components\VideoList'    => 'videolist',
            '\JoshuaTayo\Fha\Components\ProjectDetails'    => 'projectdetails',
            '\JoshuaTayo\Fha\Components\PartnerList'    => 'partnerlist',
            '\JoshuaTayo\Fha\Components\ContactForm'    => 'contactform',
        ];
    }

    public function registerMailTemplates()
    {
        return [
            'joshuatayo.fha::mail.reply' => 'Contact Form -- reply message',
            'joshuatayo.fha::mail.auto-response' => 'Contact Form -- auto response message',
            'joshuatayo.fha::mail.notification' => 'Contact Form -- notification mail',
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'         => 'FHA Settings',
                'description'   => 'About Fha configuration',
                'category'      => 'FHA Fontend Setting',
                'icon'          => 'icon-cog',
                'class'         => 'JoshuaTayo\Fha\Models\Settings',
                'order'         => 1
            ],
        ];
    }

    public function registerNavigation(){
        return [
            'federalhousingauthority' => [
                'label'       => 'Federal Housing Authority',
                'url'         => Backend::url('joshuatayo/fha/sliders'),
                'icon'        => 'icon-home',
                'sideMenu' => [
                    'slider' => [
                        'label'       => 'Slider',
                        'icon'        => 'oc-icon-sliders',
                        'url'         => Backend::url('joshuatayo/fha/sliders'),
                    ],
                    'galleries' => [
                        'label'       => 'Galleries',
                        'icon'        => 'oc-icon-picture-o',
                        'url'         => Backend::url('joshuatayo/fha/galleries'),
                    ],
                    'videos' => [
                        'label'       => 'Videos',
                        'icon'        => 'oc-icon-video-camera',
                        'url'         => Backend::url('joshuatayo/fha/videos'),
                    ],
                    'propertyprices' => [
                        'label'       => 'Property Prices',
                        'icon'        => 'oc-icon-money',
                        'url'         => Backend::url('joshuatayo/fha/propertyprices'),
                    ],
                    'projects' => [
                        'label'       => 'Projects',
                        'icon'        => 'oc-icon-stumbleupon-circle',
                        'url'         => Backend::url('joshuatayo/fha/projects'),
                    ],
                    'services' => [
                        'label'       => 'Services',
                        'icon'        => 'oc-icon-slack',
                        'url'         => Backend::url('joshuatayo/fha/services'),
                    ],
                    'partners' => [
                        'label'       => 'Partners',
                        'icon'        => 'oc-icon-paragraph',
                        'url'         => Backend::url('joshuatayo/fha/partners'),
                    ]
                ]

            ],
            'messages' => [
                'label'       => 'Messages',
                'url'         => Backend::url('joshuatayo/fha/serviceforms'),
                'icon'        => 'icon-envelope',

                'sideMenu' => [
                    'serviceforms' => [
                        'label'       => 'Service Form',
                        'icon'        => 'icon-inbox',
                        'url'         => Backend::url('joshuatayo/fha/serviceforms'),
                        'counter'     => ServiceForms::countUnreadForm(),
                        'counterLabel' => 'Un-Read Form'
                    ],
                    'contactform' => [
                        'label'       => 'Contact Form',
                        'icon'        => 'icon-inbox',
                        'url'         => Backend::url('joshuatayo/fha/messages'),
                        'counter'     => Messages::countUnreadMessages(),
                        'counterLabel' => 'Un-Read Messages'
                    ]

                ]

            ]
        ];
    }
}
