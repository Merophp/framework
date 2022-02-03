<?php
use Merophp\ObjectManager\ObjectContainer;
use Merophp\ObjectManager\ObjectManager;

$oc = ObjectManager::makeInstance(
    ObjectContainer::class
);
ObjectManager::setObjectContainer($oc);
