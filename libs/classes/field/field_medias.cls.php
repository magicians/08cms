<?php
/* 
** ��ͬ���͵��ֶε����ã�ʹ�÷�������
** ���cls_fieldconfig��ͬ����������չ���� ��public static function ex_demo()
*/
!defined('M_COM') && exit('No Permission');
class cls_field_medias extends cls_fieldconfig{
	
	# ����֮��ͬ�����ֶ���ϱ༭����
    public static function _fm_custom_region(){
		
		self::_fm_notnull();
		self::_fm_min_max();
		self::_fm_guide();
		self::_fm_mode();
		self::_fm_rpid();
		self::_fm_cfgs();
	}
	# ����֮��ͬ�����ֶε����ݴ���
    public static function _sv_custom_region(){
		self::$newField['mode'] = empty(self::$fmdata['mode']) ? 0 : 1;
		foreach(array('min','max') as $key){
			self::$newField[$key] = max(0,intval(self::$fmdata[$key]));
			self::$newField[$key] = empty(self::$newField[$key]) ? '' : self::$newField[$key];
		}
	}
	# ����֮�����ؼ���ʾ�������б�
    protected static function _fm_mode(){
		$Value = empty(self::$oldField['mode']) ? 0 : 1;
		trbasic('�����ؼ���ʾ�������б�','fmdata[mode]',$Value,'radio');
	}
	
	# ����֮������������
    protected static function _fm_min_max(){
		$ValueMin = empty(self::$oldField['min']) ? '' : self::$oldField['min'];
		$ValueMax = empty(self::$oldField['max']) ? '' : self::$oldField['max'];
		trrange('������������', array('fmdata[min]',$ValueMin,'','&nbsp; -&nbsp; ',5, 'validate' => makesubmitstr('fmdata[min]',0,'int')),
								array('fmdata[max]',$ValueMax,'','',5, 'validate' => makesubmitstr('fmdata[max]',0,'int')));
	}
}