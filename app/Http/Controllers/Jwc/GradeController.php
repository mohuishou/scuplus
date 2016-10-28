<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/19 0019
 * Time: 0:14
 */
namespace App\Http\Controllers\Jwc;

use App\Model\Course;
use App\Model\Grade;

class GradeController extends JwcBaseController
{
    protected $_jwc_name = 'Grade';


    /**
     * 更新成绩
     * @author mohuishou<1@lailin.xyz>
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(){
        try{
            $data=$this->_jwc_obj->index();
        }catch (\Exception $e){
            $code=20000;
            if($e->getCode()){
                $code="2".$e->getCode();
            }
            return $this->error("教务处账号密码错误！",$code);
        }
        $grade_data=$this->_user->grade()->orderBy('termId','desc')->get();
        $count=0;
        foreach ($data as $v){
            foreach ($v as $val){

                //从课程信息表当中查找相应课程
                $map['courseId']=$val['courseId'];
                $map['lessonId']=$val['lessonId'];
                $course_data=Course::where($map)->first();
                $cid=$course_data['id'];
                //可能存在本学期课表里面没有的情况
                $cid || $cid=0;
                $val['cid']=$cid;
                $val['uid']=$this->_user->id;


                //和已有的成绩对比，查看是否更新，防止使用firstOrCreate方法导致的查询时间过长的问题
                //判断是否已存在该成绩的标志，0：不存在，1存在
                $flag=0;
                foreach ($grade_data as &$value){
                    //如果已存在该成绩，则更新成绩，只更新平均分，不再添加上课人次
                    if($val['courseId']==$value['courseId']&&$val['lessonId']==$value['lessonId']){
                        $flag=1;
                        if($value['grade']!=$val['grade']){
                            $value->update($val);
                            $count++;
                            if($cid) $course_data->updateAvgGrade($val['grade'],false);
                        }
                        break;
                    }
                }

                //不存在该成绩，新增成绩，并再相应课程更新
                if(!$flag){
                    $grade=Grade::create($val);
                    if($grade){
                        if($cid) $course_data->updateAvgGrade($val['grade']);
                        $count++;
                    }
                }

            }
        }
        $grade_data=$this->_user->grade;
        return $this->success('成绩更新成功,更新成绩'.$count.'门',$grade_data);
    }


    /**
     * 获取成绩
     * @author mohuishou<1@lailin.xyz>
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(){
        $grade_data=$this->_user->grade;
        return $this->success('成绩获取成功！',$grade_data);
    }


}