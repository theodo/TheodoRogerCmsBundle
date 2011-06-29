<?php

namespace Theodo\ThothCmsBundle\Repository;

interface ContentRepositoryInterface
{
    public function getSourceByNameAndType($name, $type = 'page');
    public function getFirstTwoLevelPages();
    public function getPageBySlug($slug);
    public function getHomePage();
    public function remove($object = null);
    public function create($object = null);
}