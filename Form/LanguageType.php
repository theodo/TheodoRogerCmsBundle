<?php

namespace Theodo\RogerCmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class LanguageType extends AbstractType
{

    public function buildForm(FormBuilder $builder, array $options)
    {
        $langs = $options['languages'];
        $langs = array_combine($langs, $langs);
        foreach ($langs as &$lang) {
            $lang = \Locale::getDisplayName($lang);
        }

        $builder->add('language', 'choice', array(
            'choices' => $langs,
            'required' => false,
        ));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'languages' => array('fr_FR', 'en_GB'),
            'csrf_protection' => false,
        );
    }

    public function getName()
    {
        return 'roger_language';
    }
}