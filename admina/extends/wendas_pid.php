<?PHP
/*
** �ϼ��ڵ��ĵ��б�������archives_pid.php������ʾ��������������Ͷ����ʽʹ��
** ע�����ֺϼ���ϵ��1���ĵ�����¼pid 2���ϼ���ϵ�����ر�������select��from������ 
** �ϼ��ڵĹ������ٷ����Ƿ������Ŀ����Ȩ��
*/ 
/* ������ʼ������ */
$chid = 106;//�ű��̶����ĳ��ģ��
//$chid = empty($chid) ? 0 : max(0,intval($chid));//�����ⲿ��chid����Ҫ��������

$pid = empty($pid) ? 0 : max(0,intval($pid));//��ʼ���ϼ�id���п���ʹ������id��ʽ����������$hejiid�ȣ�ҪתΪʹ��pid
$arid = 1;

$_init = array(
'chid' => $chid,//ģ��id������
'url' => "?entry=$entry$extend_str",//����url���������Ҫ����chid��pid

'cols' => 0,//Ĭ��Ϊ0����Ϊ����1��Ϊ�����ĵ�ģʽ����ͼƬ�б�(�趨һ��Ԫ�ز���Ҫ������)
//'coids' => array(1),//�ֶ�����������ϵ���ڻ�Ա�����ر���Ҫָ��
//'fields' => array(),//���������װ�����ֶλ���

'isab' => 1,//*** �Ƿ�ϼ��ڹ�����0Ϊ��ͨ�����б���1Ϊ���ڹ����б���2Ϊ���������б�
'pid' => $pid,//�ϼ�id
'arid' => $arid,//*** ָ���ϼ���Ŀid
'orderby' => "b.inorder DESC,a.aid DESC",//�ϼ���ָ������,�ĵ����ϼ���¼��Ϊ"a.inorderxx DESC"��xxΪ�ϼ���Ŀid

);

/******************/

$oL = new cls_archives($_init);

//ͷ���ļ����������
$oL->top_head();

//������Ŀ ****************************
//����������Ŀ��s_additem($key,$cfg)
$oL->s_additem('keyword',array('fields' => array('a.subject' => '����','a.aid' => '�ĵ�ID'),));//fields������Ĭ��Ϊarray('a.subject' => '����','a.mname' => '��Ա','a.aid' => '�ĵ�ID')
$oL->s_additem('caid',array());


//����sql��filter�ִ�����
$oL->s_deal_str();

//����������Ŀ ********************
$oL->o_additem('inclear');



$oL->o_additem('ccid38');

if(!submitcheck('bsubmit')){
	
	//������ʾ���� ****************************
	$oL->s_header();
	$oL->s_view_array();
	$oL->s_footer();

	//�����б��� **************************
	$oL->m_header(" &nbsp;<a style=\"color:#C00\" href=\"?entry=extend&extend=wendas_load&pid=$pid\" onclick=\"return floatwin('open_arcexit',this)\">>>�����ʴ�</a> &nbsp;<a style=\"color:#C00\" href=\"?entry=extend&extend=qa_a&pid=$pid\" onclick=\"return floatwin('open_arcexit',this)\">>>�����ʴ�</a>",1);
	
	//�����б���Ŀ
	//���飬���ȳ��ֵ��������м��룺'group' =>'item,���ݷָ���,�����ָ���',���ݷָ�������ֱ������,�����б���ķָ���������ֻʹ�õ�һ�����
	$oL->m_additem('selectid');
	$oL->m_additem('subject',array('len' => 40,));
	$oL->m_additem('caid');
	$oL->m_additem('createdate',array('type'=>'date','view'=>'S'));
	$oL->m_additem('ccid38',array('view'=>'S'));
	$oL->m_additem('info',array('type'=>'url','title'=>'����','mtitle'=>'�鿴','url'=>"?entry=extend&extend=archiveinfo&aid={aid}",'width'=>40,));
	$oL->m_additem('detail',array('type'=>'url','title'=>'�༭','mtitle'=>'����','url'=>"?entry=extend&extend=qa_a&aid={aid}",'width'=>40,));
	
	//$oL->m_mcols_style();//�����ĵ�ģʽ������ʾ��Ŀ�������ʽ,Ĭ��Ϊ��"{selectid} &nbsp;{subject}"
	
	//��ʾ�����У����ж���չʾ�Ļ�����Ҫ
	$oL->m_view_top();
	
	//ȫ���б��������������Ҫ���ƣ�����ʹ�����е�ϸ�ַ���
	$oL->m_view_main();
	
	//��ʾ�б���β��
	$oL->m_footer();
	
	//��ʾ����������*******************************
	$oL->o_header();
	
	//��ʾ��ѡ��
	$oL->o_view_bools('�ϼ����� ',array('inclear'));
	$oL->o_view_bools();
	
	//��ʾ������
	$oL->o_view_rows();
	
	$oL->o_footer('bsubmit');
	$oL->guide_bm('&nbsp;&nbsp;&nbsp;1.�ϼ�����>>������ò����ǰ������ڱ�ҳ���б���ɾ��������ͨ��"�����ʴ�"�������ؽ�����<br/>&nbsp;<br/>','1');
	
}else{
	//Ԥ������δѡ�����ʾ
	$oL->sv_header();
	
	//�б���������������ݴ���
	$oL->sv_e_additem('clicks',array());
	$oL->sv_e_additem('inorder',array());
//	$oL->sv_e_additem('incheck',array('type' => 'bool'));
	$oL->sv_e_all();
	
	//��������������ݴ���
	$oL->sv_o_all();
	
	//��������
	$oL->sv_footer();
}
?>