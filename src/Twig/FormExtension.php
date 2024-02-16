<?php
namespace App\Twig;

use Symfony\Component\Form\FormFactoryInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FormExtension extends AbstractExtension
{
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('render_global_form', [$this, 'renderGlobalForm'], ['is_safe' => ['html']]),
        ];
    }

    public function renderGlobalForm($type, $data = null, array $options = [])
    {
        $form = $this->formFactory->create($type, $data, $options);
        return $form->createView();
    }
}