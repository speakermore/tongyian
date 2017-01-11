<?php
namespace Topxia\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Topxia\Component\OAuthClient\OAuthClientFactory;

class SchoolAuthController extends BaseController
{
    public function addSchoolAuthAction(Request $request, $id)
    {
        if ($request->getMethod() == 'POST') {
            $schoolAuth = $request->request->get('schoolAuth');

            $this->getSchoolAuthService()->addSchoolAuth($id, $schoolAuth);

            //$birthday = date("Y-m-d", $student['birthday']);
          
            $this->setFlashMessage('success', $this->getServiceKernel()->trans('基础信息保存成功。'));
             
            return $this->redirect($this->generateUrl('homepage'));
        }

        //$school = $this->getSchoolsService()->getSchools($id);

        //$schools = $this->getSchoolsService()->findAll(0);

        return $this->render('TopxiaWebBundle:SchoolAuth:add-schoolAuth.html.twig', array(
            'school_id' => $id  
            ));
    }

    public function updateSchoolAuthAction(Request $request, $id)
    {
        if ($request->getMethod() == 'POST') {
            $schoolAuth = $request->request->get('schoolAuth');

            $this->getSchoolAuthService()->updateSchoolAuth($id, $schoolAuth);
          
            $this->setFlashMessage('success', $this->getServiceKernel()->trans('基础信息更新成功。'));
             
            return $this->redirect($this->generateUrl('homepage'));
        }

        $schoolAuth = $this->getSchoolAuthService()->getSchoolAuth($id);

        return $this->render('TopxiaWebBundle:SchoolAuth:update-schoolAuth.html.twig', array(
            'schoolAuth' => $schoolAuth,
            'school_id' => $schoolAuth['school_id']
            ));
    }

    protected function getSchoolsService()
    {
        return $this->getServiceKernel()->createService('Schools.SchoolsService');
    }

    protected function getSchoolAuthService()
    {
        return $this->getServiceKernel()->createService('SchoolAuth.SchoolAuthService');
    }
}