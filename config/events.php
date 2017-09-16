<?php 
use App\Event\MailerEventListener;
use App\Event\ChargeCardEventListener;
use App\Event\MilestoneEventListener;
use Cake\Event\EventManager;
use App\Event\PeopleHubEventListener;
use App\Event\InstantGiftCouponEventListener;

$chargeCardEventListener = new ChargeCardEventListener();
EventManager::instance()->attach($chargeCardEventListener);


$milestoneEventListener = new MilestoneEventListener();
EventManager::instance()->attach($milestoneEventListener);

$mailerEventListener = new MailerEventListener();
EventManager::instance()->attach($mailerEventListener);

$peopleHubEventListener = new PeopleHubEventListener();
EventManager::instance()->attach($peopleHubEventListener);

$instantGiftCouponEventListener = new InstantGiftCouponEventListener();
EventManager::instance()->attach($instantGiftCouponEventListener);
?>