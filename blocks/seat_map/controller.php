<?php
namespace Concrete\Package\SeatMap\Block\SeatMap;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Editor\LinkAbstractor;
use File;
use Package;
use Concrete\Core\Entity\Attribute\Key\UserKey;
use Concrete\Core\Attribute\StandardSetManager;
use Concrete\Core\Attribute\SetFactory;
use UserList;
use User;

class Controller extends BlockController
{
    protected $btTable = 'btSeatMap';
    protected $btInterfaceWidth = "1024";
    protected $btInterfaceHeight = "768";
    protected $btCacheBlockRecord = false;
    protected $btCacheBlockOutput = false;
    protected $btCacheBlockOutputLifetime = CACHE_LIFETIME;
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

    }

    public function edit()
    {
        $this->set('fID', $this->fID);
        $this->set('prefix', $this->prefix);
    }

    public function save($args)
    {
        $pkg = Package::getByHandle('seat_map');
        $em = \ORM::entityManager();
        parent::save($args);
        if($args['prefix']){
            $service = $this->app->make('Concrete\Core\Attribute\Category\CategoryService');
            $categoryEntity = $service->getByHandle('user');
            $category = $categoryEntity->getController();
            $akHandle = $args['prefix'].'reservation';
            $ak = $category->getByHandle($akHandle);
            $setHandle = 'seat_map_reservations';
            $sf = new SetFactory($em);
            $sm = new StandardSetManager($categoryEntity, $em);
            $set = $sf->getByHandle($setHandle);
            if(!is_object($set)){
                $sm->addSet($setHandle, t('Seat Map Reservations'), $pkg);
                $set = $sf->getByHandle($setHandle);
            }
            if(!is_object($ak)){
                $ak = new UserKey();
                $ak->setAttributeKeyHandle($akHandle);
                $ak->setAttributeKeyName(t('Seat Reservation Attribute for Map width Prefix: "%s"',$akHandle));
                $ak = $category->add('text', $ak, null, $pkg);
                $sm->addKey($set, $ak);
            }
        }
    }

    public function view()
    {
        $this->requireAsset('javascript', 'bootstrap/popover');

        $f = File::getByID($this->fID);
        $svgMap = $f->getFileContents();

        $ul = new UserList;
        $ul->filterByAttribute($this->prefix.'reservation', '', '!=');
        foreach($ul->getResults() as $u){
            $reservations[$u->getAttribute($this->prefix.'reservation')] = $u;
        }

        $u = new User();
        $ui = $u->getUserInfoObject();
        if(!empty($mySeat = $ui->getAttribute($this->prefix.'reservation'))){
            $this->set('mySeat', $mySeat);
        }

        $this->set('reservations', $reservations);
        $this->set('svgMap', $svgMap);
        $this->set('fID', $this->fID);
        $this->set('prefix', $this->prefix);
        $this->set('bID', $this->bID);
    }

}