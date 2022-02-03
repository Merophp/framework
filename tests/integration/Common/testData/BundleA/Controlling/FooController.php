<?php

namespace OrgaA\BundleA\Controlling;

use Merophp\Framework\RequestControlling\AbstractController;

class FooController extends AbstractController
{

    public function barAction($v1, $v2)
    {
        $this->view->text($v1.' '.$v2.' now');
    }
}
