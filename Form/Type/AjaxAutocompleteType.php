<?php

namespace Mawi\AjaxAutocompleteBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Exception\LogicException;
use Mawi\AjaxAutocompleteBundle\Form\DataTransformer\AutocompleteToEntityTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class AjaxAutocompleteType extends AbstractType
{

    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }
    
	public function setDefaultOptions(OptionsResolverInterface $resolver)
    {   
		$resolver->setDefaults(array(
				'class' => null,
				'compound' => true
		));
        $resolver->setRequired(array('entity_alias'));
    }

    public function getName()
    {
        return 'mawi_ajax_autocomplete';
    }

    public function getParent()
    {
        return 'text';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entities = $this->container->getParameter('mawi.autocomplete');

        if (null === $options['entity_alias']) {
            throw new LogicException('You must provide a entity alias "entity_alias" and tune it in config file');
        }

        if (!isset ($entities[$options['entity_alias']])){
            throw new LogicException('There are no entity alias "' . $options['entity_alias'] . '" in your config file');
        }
        
        $config = $entities[$options['entity_alias']];

        $options['class'] = $config['class'];
                
        $transformer = new AutocompleteToEntityTransformer(
            $this->container->get('doctrine')->getManager(),
            $config
        );
        
        $transformer->setStripTags(true);

        $builder->add('id', 'hidden');
        $builder->add('label', 'text');
        
        $builder->addViewTransformer($transformer);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {        
    	$view->vars = array_replace($view->vars, array(
    		'entity_alias' => $options['entity_alias'],
    	));
    }

}