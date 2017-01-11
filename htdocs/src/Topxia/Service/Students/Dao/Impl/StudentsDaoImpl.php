<?php
namespace Topxia\Service\Students\Dao\Impl;

use Topxia\Service\Common\BaseDao;
use Topxia\Service\Students\Dao\StudentsDao;

class StudentsDaoImpl extends BaseDao implements StudentsDao
{
    protected $table = 'students';

    public function addStudent($id, $student)
    {
        $affected = $this->getConnection()->insert($this->table, $student);
        $this->clearCached();

        if ($affected <= 0) {
            throw $this->createDaoException('Insert school error.');
        }

        return $this->getStudent($this->getConnection()->lastInsertId());

    }

    public function updateStudent($id, $student)
    {
        $affected = $this->getConnection()->update($this->table, $student, array('id' => $id));
        $this->clearCached();

        if ($affected <= 0) {
            throw $this->createDaoException('Update school error.');
        }

        return $this->getStudent($id);
    }

    public function getStudent($id)
    {
        $that = $this;
        return $this->fetchCached("id:{$id}", $id, function ($id) use ($that) {
            $sql = "SELECT * FROM {$that->getTable()} WHERE id = ? LIMIT 1";
            return $that->getConnection()->fetchAssoc($sql, array($id)) ?: null;
        }
        );
    }

    public function deleteStudent($id)
    {
        
    }

    public function findStudents($school_id)
    {
        $that = $this;
        return $this->fetchCached("school_id:{$school_id}", $school_id, function ($school_id) use ($that) {
            $sql = "SELECT name,phone,IDcards,reportedCourse,createTime FROM {$this->table} WHERE school_id = ? ORDER BY createTime LIMIT 10";
            return $this->getConnection()->fetchAll($sql, array($school_id)) ? : array();
        }
        );
    }
}
