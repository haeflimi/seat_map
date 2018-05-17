<?php
namespace Concrete\Package\SeatMap\Block\SeatMap;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Editor\LinkAbstractor;
use File;

class Controller extends BlockController
{
    protected $btTable = 'btSeatMap';
    protected $btInterfaceWidth = "850";
    protected $btInterfaceHeight = "680";
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputLifetime = 21600;
    protected $btCacheBlockOutputOnPost = false;
    protected $btCacheBlockOutputForRegisteredUsers = false;

    public function getBlockTypeName()
    {
        return t('Seat Map');
    }

    public function getBlockTypeDescription()
    {
        return t('Concrete5 Block that displays a Seatmap based on a svg vector graphic and allows your community users to pick/ reserve seats.');
    }

    public function add()
    {
        //$this->set('fileObject', $this->getFileObject());
    }

    public function edit()
    {
        //$this->set('fileObject', $this->getFileObject());
    }

    public function save($args)
    {
        parent::save($args);
    }

    public function view()
    {
        //$this->set('fileObject', $this->getFileObject());
    }
}