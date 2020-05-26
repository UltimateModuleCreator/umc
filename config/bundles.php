<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class => ['all' => true],
    App\Umc\CoreBundle\UmcCoreBundle::class => ['all' => true],
    App\Umc\MagentoBundle\UmcMagentoBundle::class => ['all' => true],
    App\Umc\ShopwareBundle\UmcShopwareBundle::class => ['all' => true],
    App\Umc\SyliusBundle\UmcSyliusBundle::class => ['all' => true],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class => ['dev' => true, 'test' => true],
];
