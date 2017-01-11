<?php
namespace Topxia\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Topxia\Component\OAuthClient\OAuthClientFactory;

class StudentsController extends BaseController
{
    public function addStudentAction(Request $request, $id)
    {
        if ($request->getMethod() == 'POST') {
            $student = $request->request->get('student');

            $this->getStudentsService()->addStudent($id, $student);

            //$birthday = date("Y-m-d", $student['birthday']);
          
            $this->setFlashMessage('success', $this->getServiceKernel()->trans('基础信息保存成功。'));
             
            return $this->redirect($this->generateUrl('homepage'));
        }

        $school = $this->getSchoolsService()->getSchool($id);

        //$schools = $this->getSchoolsService()->findAll(0);

        return $this->render('TopxiaWebBundle:Student:add-student.html.twig', array(
            'school_id' => $id,
            'school' => $school
            ));
    }

    public function updateStudentAction(Request $request, $id)
    {
        if ($request->getMethod() == 'POST') {
            $student = $request->request->get('student');

            $this->getStudentsService()->updateStudent($id, $student);
          
            $this->setFlashMessage('success', $this->getServiceKernel()->trans('基础信息更新成功。'));
             
            return $this->redirect($this->generateUrl('homepage'));
        }

        $student = $this->getStudentsService()->getStudent($id);
        if($student['city_id'] != null){
            //城市
            $city = $this->getCityService()->getCityById($school['city_id']);
        }
        if($student['birthday']!=null){
            $birthday =  date("Y-m-d", $student['birthday']);
        }

       
        return $this->render('TopxiaWebBundle:Student:update-student.html.twig', array(
            'city' => $city,
            'student' => $student,
            'birthday' => $birthday
            ));
    }


    protected function getSchoolsService()
    {
        return $this->getServiceKernel()->createService('Schools.SchoolsService');
    }

    protected function getStudentsService()
    {
        return $this->getServiceKernel()->createService('Students.StudentsService');
    }

}
