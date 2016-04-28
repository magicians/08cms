<?php
!defined('M_COM') && exit('No Permission');

$cuid = 32;
$commu = cls_cache::Read('commu',$cuid);
$page = !empty($page) ? max(1, intval($page)) : 1;
submitcheck('bfilter') && $page = 1;
$keyword = empty($keyword) ? '' : $keyword;
$state = isset($state) ? $state : '-1';

$selectsql = "SELECT cu.*";
$wheresql = " WHERE cu.tomid='$memberid'"; 
$fromsql = "FROM {$tblprefix}$commu[tbl] cu";

$keyword && $wheresql .= " AND cu.xingming LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";
$state==0 && $wheresql .= ' AND cu.state=0';
$state==1 && $wheresql .= ' AND cu.state=1';
$state==2 && $wheresql .= ' AND cu.state=2';

$filterstr = '';
foreach(array('keyword','state') as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
$cid = empty($cid) ? 0 : max(0,intval($cid));

if($cid){
	if(!($row = $db->fetch_one("SELECT c.* FROM {$tblprefix}$commu[tbl] c WHERE c.cid='$cid'"))) cls_message::show('ָ����¼�����ڡ�');
	$row['mspacehome'] = cls_Mspace::IndexUrl($row);
	$fields = cls_cache::Read('cufields',$cuid);
	if(!submitcheck('bsubmit')){
		tabheader("�����¼ ֻ�ܴ���״̬",'newform',"?action=$action&cid=$cid",2,1,1);
		$a_field = new cls_field;
		foreach($fields as $k => $v){
			$a_field->init($v,isset($row[$k]) ? $row[$k] : '');
			$a_field->trfield('fmdata');
		}
		unset($a_field);
		trbasic('����״̬','',makeradio('fmdata[state]',array('0'=>'δ����','1'=>'����ʧ��','2'=>'����ɹ�'),$row['state']),'');
		tabfooter('bsubmit');
	}else{//���ݴ���
		$c_upload = new cls_upload;
		$db->query("UPDATE {$tblprefix}$commu[tbl] SET state='$fmdata[state]' WHERE cid='$cid'");
		$c_upload->closure(1,$cid,"commu$cuid");
		$c_upload->saveuptotal(1);
		cls_message::show('��¼�༭���',axaction(6,M_REFERER));
	}
} else {
	if(!submitcheck('bsubmit')){
		echo form_str('newform',"?action=$action&page=$page");
		tabheader_e();
		echo "<tr><td class=\"item2\">";
		echo "<div class='filter'><input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"20\" placeholder=\"���������\" style=\"vertical-align: middle;\" title=\"�����û���\">&nbsp; ";
		echo "<select style=\"vertical-align: middle;\" name=\"state\">".makeoption(array('-1' => '����״̬','0' => 'δ����','1' => '����ʧ��','2' => '����ɹ�'),$state)."</select>&nbsp; ";
		echo strbutton('bfilter','ɸѡ');
		echo "</div></td></tr>";
		tabfooter();
		tabheader("�����б�",'','',9);
		$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",array('����','left'),);
		$cy_arr[] = '�ֻ�';
		$cy_arr[] = 'С��';
		$cy_arr[] = '״̬';
		$cy_arr[] = '���';
		$cy_arr[] = '��������';
		$cy_arr[] = '��ϸ';
		trcategory($cy_arr);

		$pagetmp = $page;
		do{
			$query = $db->query("$selectsql $fromsql $wheresql ORDER BY cu.cid DESC LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
			$pagetmp--;
		} while(!$db->num_rows($query) && $pagetmp);
		$itemstr = '';
		while($r = $db->fetch_array($query)){
			$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$r[cid]]\" value=\"$r[cid]\">";
			$r['state']==0 && $statestr = 'δ����';
			$r['state']==1 && $statestr = '����ʧ��';
			$r['state']==2 && $statestr = '����ɹ�';
			$checkstr = $r['checked'] ? 'Y' : '-';
			$adddatestr = date('Y-m-d',$r['createdate']);
			$editstr = "<a href=\"?action=$action&cid=$r[cid]\" onclick=\"return floatwin('open_arcexit',this)\">�༭</a>";
			$itemstr .= "<tr><td class=\"item\" width=\"40\">$selectstr</td><td class=\"item2\">$r[xingming]</td>\n";
			$itemstr .= "<td class=\"item\">$r[tel]</td>\n";
			$itemstr .= "<td class=\"item\">$r[xqname]</td>\n";
			$itemstr .= "<td class=\"item\">$statestr</td>\n";
			$itemstr .= "<td class=\"item\">$checkstr</td>\n";
			$itemstr .= "<td class=\"item\" width=\"100\">$adddatestr</td>\n";
			$itemstr .= "<td class=\"item\" width=\"100\">$editstr</td>\n";
			$itemstr .= "</tr>\n";
		}
		echo $itemstr;
		tabfooter();
		echo multi($db->result_one("SELECT count(*) $fromsql $wheresql"),$mrowpp,$page, "?action=$action$filterstr");

		tabheader('��������');
		trbasic("<label><input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[delete]\" value=\"1\"> ɾ������</label>",'','��ѡ�м�¼���б������','');
		tabfooter('bsubmit');
	}else{
		if(empty($arcdeal)) cls_message::show('��ѡ�������Ŀ��',axaction(1,M_REFERER));
		if(empty($selectid)) cls_message::show('��ѡ����Ŀ��',axaction(1,M_REFERER));
		foreach($selectid as $k){
			$k = empty($k) ? 0 : max(0, intval($k));
			if(!empty($arcdeal['delete'])){
				$db->query("DELETE FROM {$tblprefix}$commu[tbl] WHERE cid='$k'",'UNBUFFERED');
				continue;
			}
		}
		cls_message::show('�������������ɹ���',"?action=$action&page=$page$filterstr");
	}
}

?>