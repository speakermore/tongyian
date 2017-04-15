<?php
namespace Topxia\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Topxia\Component\OAuthClient\OAuthClientFactory;

class SchoolAuthController extends BaseController
{
    public function addSchoolAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $userLogin = $this -> getCurrentUser();
            $school = $request->request->get('schools');
            $schoolOld = $this->getSchoolsService()->getSchoolByCName($school['chineseName']);
            if(false != $schoolOld){
                $this->setFlashMessage('error', $this->getServiceKernel()->trans('该学校已经注册，请登录进学校管理后台更新学校信息!!'));
                return $this->redirect($this->generateUrl('newadmin')); 
            }
            $userName = $request->request->get('userName');
            if(0 == $userLogin['id']){          
                $user = $this->getUserService()->getUserByNickname($userName);
                if(null != $user){
                    $school = $this->getSchoolsService()->addSchool(0, $school);
                    $newUser = $this->getUserService()->getUser($user['id']);
                    $newUser['schoolId'] = $school['id'];
                    $this->getUserService()->updateUser($newUser['id'], $newUser);
                    //$birthday = date("Y-m-d", $student['birthday']);
          
                    $this->setFlashMessage('success', $this->getServiceKernel()->trans('基础信息保存成功。'));
             
                    return $this->redirect($this->generateUrl('schoolAuth_add', array('id' => $school['id'])));
                }else{
                    $this->setFlashMessage('error', $this->getServiceKernel()->trans('该用户没有注册，请注册!!'));
                    return $this->redirect($this->generateUrl('register'));
                }
            }else{
                 $school = $this->getSchoolsService()->addSchool(0, $school);
                 $newUser = $this->getUserService()->getUser($userLogin['id']);
                 $newUser['schoolId'] = $school['id'];
                 $this->getUserService()->updateUser($newUser['id'], $newUser);
                //$birthday = date("Y-m-d", $student['birthday']);
          
                 $this->setFlashMessage('success', $this->getServiceKernel()->trans('基础信息保存成功。'));
             
                 return $this->redirect($this->generateUrl('schoolAuth_add', array('id' => $school['id'])));
            }
            
        }
        $provinces = $this->getProvinceService()->findAll();

        $citys = $this->getCityService()->findAll();

        //$school = $this->getSchoolsService()->getSchools($id);

        //$schools = $this->getSchoolsService()->findAll(0);

        return $this->render('TopxiaWebBundle:School:add-school.html.twig', array(
            'provinces' => $provinces,
            'citys'     => $citys
            ));
    }

    public function addSchoolAuthAction(Request $request, $id)
    {
        $user = $this->getCurrentUser();
        //$id = $user->getSchoolId();
       
        $flag = 1;
        $name = "";
        $schoolAuth = $this->getSchoolAuthService()->getSchoolAuthBySchoolId((int)$id);
         if(null != $id){
             $school = $this->getSchoolsService()->getSchool((int)$id);
        }else if(null != $schoolAuth['school_id']){
             $school = $this->getSchoolsService()->getSchool($schoolAuth['school_id']);
        }
        if(null != $school['institutionsType'] && $school['institutionsType'] == 0){
            $flag = 1;
            $name = "学校";
        }else if(null != $school['institutionsType'] && $school['institutionsType'] == 0){
            $flag = 2;
            $name = "培训机构";
        }
       
       
        return $this->render('TopxiaWebBundle:SchoolAuth:add-schoolAuth.html.twig', array(
                'school_id' => $id,
                'name'      => $name,
                // 'user' => $user,
                'flag'      => $flag
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

    protected function getUserService()
    {
        return $this->getServiceKernel()->createService('User.UserService');
    }

    protected function getSchoolsService()
    {
        return $this->getServiceKernel()->createService('Schools.SchoolsService');
    }

    protected function getSchoolAuthService()
    {
        return $this->getServiceKernel()->createService('SchoolAuth.SchoolAuthService');
    }

    protected function getProvinceService()
    {
        return $this->getServiceKernel()->createService('Province.ProvinceService');
    }

    protected function getCityService()
    {
        return $this->getServiceKernel()->createService('City.CityService');
    }
}