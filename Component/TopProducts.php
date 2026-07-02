<?php



namespace TopProducts\Component;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;


#[AsLiveComponent(name: 'TopProducts:TopProducts', template: '@TopProductsModule/components/TopProducts.html.twig')]
class TopProducts extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;
}
