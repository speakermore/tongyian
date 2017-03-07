<?php
namespace Topxia\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class StudentsController extends BaseController
{
    public function addStudentAction(Request $request, $id)
    {
         if ($request->getMethod() == 'POST') {
            $student = $request->request->get('student');
            $value = $request->request->get('reportedCourse');
            if (!empty($value)) {
                foreach ($value as $key => $va) {
                    $student['reportedCourse'] = (int)$va;
                    $this->getStudentsService()->addStudent($student['school_id'], $student);
                }
            }
            //$birthday = date("Y-m-d", $student['birthday']);
          
            $this->setFlashMessage('success', $this->getServiceKernel()->trans('基础信息保存成功。'));
             
            return $this->redirect($this->generateUrl('homepage'));
        }

        $user = $this->getCurrentUser();

        if ($user->isLogin()) {
             $school = $this->getSchoolsService()->getSchool($id);
             /*查询招生老师*/
             $teacher = $this->getUserService()->findUserBySchoolId($id);
             if($teacher == null)
             {
                 /*查询所有学校招生老师*/
                 $teacher = $this->getUserService()->findUserBySchoolId(0);
             }
             $userId = $user['id'];
             $userProfile = $this->getUserService()->getUserProfile($userId);
             $conditions = array();
             $conditions['status'] = 'published';
             $conditions['parentId'] = 0;
             $conditions['school_id'] = $id;
             $arguments = array();
             $arguments['count'] = 12;
             $arguments['categoryId'] = 0;
             $arguments['school_id'] = $id; 
             $courses = $this->getCourseService()->searchCourses($conditions,'latest', 0, $arguments['count']);

             //$course = $this->getCoursesService()->findCoursesBySchoolId($id);

             //$schools = $this->getSchoolsService()->findAll(0);

            return $this->render('TopxiaWebBundle:Student:add-student.html.twig', array(
                'school_id' => $id,
                'school' => $school,
                'teacher' => $teacher,
                'courses' => $courses,
                'userProfile' => $userProfile,
                'user' => $user
                ));
        }else{
            return $this->redirect($this->generateUrl('login_background'));
        } 
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

    protected function getCourseService()
    {
        return $this->getServiceKernel()->createService('Course.CourseService');
    }

    protected function getUserService()
    {
        return $this->getServiceKernel()->createService('User.UserService');
    }

}
