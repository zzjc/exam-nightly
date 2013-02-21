<?php
class TempalteModel extends Model
{
    protected $trueTableName = 'template';

    protected $_validate = array(
        array('name','','模板名称已经存在',0,'unique',1),
    );
}