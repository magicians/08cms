<?PHP
$chid = 121;//ָ��chid
$caid = empty($caid) ? 623 : $caid;
$pid = empty($pid) ? 0 : $pid;

cls_env::SetG('chid',$chid);
cls_env::SetG('caid',$caid);

//Ҫָ���ϼ�id������$pidkey���ϼ���Ŀ$arid
if(!empty($pid)){
    //$_pid = @$pid || $fmdata['pid'];
    $_arc = new cls_arcedit; //��ҵ�ز�-�ϼ�����
    $_arc->set_aid($pid,array('au'=>0,'ch'=>0)); 
    $arid = in_array($_arc->archive['chid'],array(2,3)) ? 38 : 36;//ָ���ϼ���Ŀid
}else{
    $arid = 0;
} 
cls_env::SetG('arid',$arid);

$oA = new cls_archive();
$isadd = $oA->isadd; /* 0Ϊ����༭��1Ϊ�ĵ�����ϵ */

$oA->top_head();//�ļ�ͷ��
/* ��ȡ���п������ϣ���ģ�͡��ֶΡ����ĵ� */
$oA->read_data();

if(empty($aid)){
	$pchid = empty($_arc->archive['chid']) ? $oA->message('��������') : $_arc->archive['chid'];//4; //����ʱ-ѡ�������ϼ�(¥��)
}

/* ����������������ϵ������������������ϵ */
$oA->setvar('coids',array(0));

/* ����ǰ�Ĵ���ļ���,�ڲ��ֶ��ƴ����У���ֱ��ʹ���������� */
$chid = &$oA->chid;
$arc = &$oA->arc;
$channel = &$oA->channel;
$fields = &$oA->fields;

#-----------------

if(!submitcheck('bsubmit')){
	
	if($isadd){//���Ӳ���Ҫ
		$fields['thumb']['datatype'] = 'images';
		$oA->fm_pre_cns(); //����ʱԤ������Ŀ
	}
	
	$oA->fm_allow(); //������ǰ��Ա��Ȩ��
	
	$oA->fm_header("","?entry=extend$extend_str&arid=$arid&pid=$pid"); //($title,$url)��url�пɲ�ָ��chid��aid
	
	$oA->fm_album('pid'); //�����ϼ�����ָ���ϼ�id������������Ĭ��Ϊpid
	$oA->fm_caid(array('hidden' => 1)); //������Ŀ��ͨ���������飬��ָ���ر��չʾ������array('topid' => 5,'hidden' => 1)��
	$oA->fm_fields(array('subject'));
	//$oA->fm_chuxing(); // ���� ѡ���ֶ�
	$oA->fm_ccids(array(12)); //($coids)���������࣬$coids��array(3,4,5)
	$oA->fm_fields(array('thumb'),1); //($arr,$noinc)��$arr�ֶα�ʶ���飬Ϊ���������У�$noinc=1�ų�ģʽ
	$oA->fm_fields(array('thumb'));
	//$oA->fm_fields_other(); //����ʣ�����Ч�ֶΣ����Դ����ų��ֶ�$nos
	
	$oA->fm_footer('bsubmit');
	
	$oA->fm_guide_bm('','0');
	
}else{	
	if(!$isadd){ // Edit
		$oA->sv_allow(); //����Ȩ�ޣ�����Ȩ�޻��̨����Ȩ��
		$oA->sv_cns(array()); //��Ŀ�������ɴ�$coids��array(1,2)
		$oA->sv_fields(array()); //�ֶδ������ɴ�$nos��array('ename1','ename2')
		$oA->sv_update();
		$oA->sv_upload(); //�ϴ�����
		$oA->sv_album('pid'); //Ҫָ���ϼ�id������$pidkey���ϼ���Ŀ$arid
		$oA->sv_static(); //�Զ����ɾ�̬
		$oA->sv_finish(); //����ʱ��Ҫ�����񣬰����Զ����ɾ�̬��������¼���ɹ���ʾ
	}else{ // add
		
        $oA->sv_regcode('archive'); 
		$oA->sv_pre_cns(array()); 
		$oA->sv_allow(); 
		if(!empty($fmdata['thumb'])){
			$oA->sv_images2arcs($fmdata,'thumb',array('chid'=>$chid,'caid'=>$caid,'pfield'=>'pid','arid'=>$arid,));
			$oA->message("�ĵ��������",axaction(6,M_REFERER));
		}else{
			$oA->message('���ϴ�ͼƬ��');	
		}	
	}
}
?>