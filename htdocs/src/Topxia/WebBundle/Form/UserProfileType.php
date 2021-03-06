<?php

namespace Topxia\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('truename', 'text', array('required' => false));
        $builder->add('gender', 'gender', array('expanded' => true, 'required' => true));
        $builder->add('height', 'text', array('required' => false));
        $builder->add('weight', 'text', array('required' => false));
        $builder->add('graduateSchool', 'text', array('required' => false));
        $builder->add('graduationTestScores', 'text', array('required' => false));
        $builder->add('guardianName', 'text', array('required' => false));
        $builder->add('guardianPhone', 'text', array('required' => false));
        $builder->add('admissionTicketNumber', 'text', array('required' => false));
        $builder->add('reportedSchool', 'text', array('required' => false));
        $builder->add('reportedClass', 'text', array('required' => false));
        $builder->add('company', 'text', array('required' => false));
        $builder->add('nation', 'text', array('required' => false));       
        $builder->add('job', 'text', array('required' => false));
        $builder->add('title', 'text', array('required' => false));
        $builder->add('mobile', 'text', array('required' => false));
        $builder->add('about', 'textarea', array('required' => false));
        $builder->add('signature', 'text', array('required' => false));        
        $builder->add('site', 'text', array('required' => false)); 
        $builder->add('weibo', 'text', array('required' => false)); 
        $builder->add('qq', 'text', array('required' => false));
        $builder->add('weixin', 'text', array('required' => false));
        
        $builder->add('iam', 'choice', array(
            'choices' => array(
                'student' => $this->getServiceKernel()->trans('在校生'),
                'notStudent' => $this->getServiceKernel()->trans('非在校生')
            ),
            'expanded' => true,
            //'required' => true
        ));
        $builder->add('school', 'text', array('required' => false));
        $builder->add('class', 'text', array('required' => false));
    }

    public function getName()
    {
        return 'profile';
    }
        protected function getServiceKernel()
    {
        return ServiceKernel::instance();
    }
}