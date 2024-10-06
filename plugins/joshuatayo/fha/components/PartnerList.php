<?php namespace JoshuaTayo\Fha\Components;

use Str;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use JoshuaTayo\Fha\Models\Partner as PartnerModel;

class PartnerList extends ComponentBase
{
    public  $partners;

    public function componentDetails()
    {
        return [
            'name'        => 'Partner',
            'description' => 'Display Partners on page'
        ];
    }

    public function onRun()
    {
        $this->partners     =   $this->page['partners']   = $this->loadList();
    }

    public function loadList()
    {
        $partners          = PartnerModel::isEnabled()->get();

        return $partners;
    }
}