<?php
namespace Topxia\Service\Students;

interface StudentsService
{
   public function addStudent($id, $student);

   public function getStudent($id);

   public function updateStudent($id, $student);

   public function deleteStudent($id);
}