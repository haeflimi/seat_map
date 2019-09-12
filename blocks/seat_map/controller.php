<?php
namespace Concrete\Package\SeatMap\Block\SeatMap;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Editor\LinkAbstractor;
use Concrete\Core\User\Group\GroupList;
use File;
use Package;
use Concrete\Core\Attribute\Key\UserKey;
use Concrete\Core\Attribute\StandardSetManager;
use Concrete\Core\Attribute\SetFactory;
use UserList;
use User;
use Group;

class Controller extends BlockController
{
    protected $btTable = 'btSeatMap';
    protected $btInterfaceWidth = "1024";
    protected $btInterfaceHeight = "768";
    protected $btCacheBlockRecord = false;
    protected $btCacheBlockOutput = false;
    protected $btCacheBlockOutputLifetime = 0;
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
        $this->prepFormData();
    }

    public function edit()
    {
        $this->prepFormData();
    }

    public function claim_seat()
    {
        $currentUser = new User();
        $ui = $currentUser->getUserInfoObject();
        $this->validateAjax('claim_seat');
        if(!empty($newSeatId = $this->post('claim_seat_id')) && $this->reservationAllowed() && $this->post('event_class')){
            $ui->setAttribute($this->post('event_class').'_reservation', $newSeatId);
            echo t('Seat %s was claimed for you.',[$newSeatId]);
        }
        die;
    }

    public function save($args)
    {
        $pkg = Package::getByHandle('seat_map');
        $em = \ORM::entityManager();
        if(empty($args['showList']))$args['showList']=false;
        if(empty($args['showSearch']))$args['showSearch']=false;
        parent::save($args);
        if($args['class']){
            $service = $this->app->make('Concrete\Core\Attribute\Category\CategoryService');
            $categoryEntity = $service->getByHandle('user');
            $category = $categoryEntity->getController();
            $akHandle = $args['class'].'_reservation';
            $ak = $category->getByHandle($akHandle);
            if($ak){
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
                    $ak->setAttributeKeyName(t('Seat Reservation Attribute for Map width Class: "%s"',$akHandle));
                    $ak = $category->add('text', $ak, null, $pkg);
                    $sm->addKey($set, $ak);
                }
            }
        }
    }

    public function view()
    {
        $this->requireAsset('javascript', 'bootstrap/tooltip');
        $this->requireAsset('javascript', 'bootstrap/popover');
        $this->requireAsset('selectize');
        // Load the SVG File content
        $f = File::getByID($this->fID);
        $svgMap = $f->getFileContents();
        $reservations = array();
        $temporary = array();
        $currentUser = new User();
        $g = Group::getByID($this->gID);
        // Check if the Attribute for the map exists
        if(!empty(UserKey::getByHandle($this->class.'_reservation'))){
            // Filter the User List for that attribute
            $ul = new UserList;
            $ul->filterByGroup($g, true);
            $ul->sortBy('uName', 'ASC');
            foreach($ul->getResults() as $u){
                $reservations[$u->getAttribute($this->class.'_reservation')] = $u;
                if($friends = $u->getAttribute($this->class.'_reservation_friends')) {
                    $friends = trim($friends);
                    if(strpos($friends,',') === false && is_numeric($friends) && !array_key_exists($friends,$reservations)){
                        $reservations[$friends] = $u;
                        $temporary[] = $friends;
                    } else {
                        $friendsArr = explode(',',$friends);
                        foreach($friendsArr as $cf){
                            if(is_numeric($cf) && !array_key_exists($cf,$reservations)){
                                $reservations[$cf] = $u;
                                $temporary[] = $cf;
                            }
                        }
                    }
                }
            }
            // Check the current User for that attribute to determine users seat
            if($currentUser->isLoggedIn()){
                $ui = $currentUser->getUserInfoObject();
                $mySeat = $ui->getAttribute($this->class.'_reservation');
                $this->set('mySeat', $mySeat);
            }
        }

        $this->set('reservations', $reservations);
        $this->set('temporary', $temporary);
        $this->set('svgMap', $svgMap);
        $this->set('allowReservation', $this->reservationAllowed());
        $this->set('showSearch', $this->showSearch);
        $this->set('showList', $this->showList);
        $this->set('class', $this->class);
        $this->set('fID', $this->fID);
        $this->set('gID', $this->gID);
        $this->set('bID', $this->bID);
    }

    private function prepFormData()
    {
        $gl = new GroupList;
        $allGroups = [];
        $allGroups[0] = t('All');
        foreach($gl->getResults() as $group){
            $allGroups[$group->getGroupID()] = $group->getGroupPath();
        }
        $this->set('allGroups', $allGroups);
        $this->set('showSearch', $this->showSearch);
        $this->set('showList', $this->showList);
        $this->set('gID', $this->gID);
        $this->set('fID', $this->fID);
        $this->set('class', $this->class);
    }

    private function reservationAllowed()
    {
        $currentUser = new User();
        $group = Group::getByID($this->gID);
        $ui = $currentUser->getUserInfoObject();
        if( $currentUser->isLoggedIn() &&
            ($this->gID == 0 || $currentUser->inGroup($group))){
            return true;
        } else {
            return false;
        }
    }

    public function validateAjax($token_string){
        $u = new User();
        $token = \Core::make("token");
        if(!$u->isRegistered()){
            throw Exception('Invalid Request, user must be logged in.');
        } elseif(!$token->validate($token_string)){
            throw Exception('Invalid Request, token must be valid.');
        }
        return true;
    }
}