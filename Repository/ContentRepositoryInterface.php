<?php

namespace Theodo\RogerCmsBundle\Repository;

interface ContentRepositoryInterface
{
    public function getSourceByNameAndType($name, $type = 'page');
    public function getFirstTwoLevelPages();
    public function getPageBySlug($slug);
    public function getHomePage();
    public function remove($object = null);
    public function create($object = null);
    public function save($object = null);
    public function findOneById($id, $type = 'page');
    public function findOneByName($id, $type);
    public function findAll($type = 'page');
}