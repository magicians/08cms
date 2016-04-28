<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
if($re = $curuser->NoBackFunc('catalog')) cls_message::show($re);
foreach(array('cotypes','channels','grouptypes','permissions','vcps','rprojects','cnrels','ca_tpl_cfgs','arc_tpls',) as $k) $$k = cls_cache::Read($k);
include_once M_ROOT."include/fields.fun.php";
$c_upload = cls_upload::OneInstance();	
if($action == 'catalogadd'){
	echo "<title>������Ŀ</title>";
	$catalogs = cls_catalog::InitialInfoArray(0);
	$cafields = cls_cache::Read('cnfields',0);
	if(!submitcheck('bcatalogadd')){
		!cls_channel::chidsarr() && cls_message::show('����������Ч��ģ��');
		$pid = empty($pid) ? 0 : $pid;
		if($pid){
			$pmsg = @$catalogs[$pid];
			$pmsg['tid'] = empty($ca_tpl_cfgs[$pid]) ? 0 : $ca_tpl_cfgs[$pid];
		}
		tabheader('������Ŀ - ��������','catalogadd',"?entry=$entry&action=$action",2,1,1);
		trbasic('��Ŀ����','catalognew[title]','','text',array('validate' => ' onfocus="initPinyin(\'catalognew[dirname]\')"' . makesubmitstr('catalognew[title]',1,0,0,30)));
		trbasic('��̬�ļ�����Ŀ¼','','<input type="text" value="" name="catalognew[dirname]" id="catalognew[dirname]" size="25" ' . makesubmitstr('catalognew[dirname]',1,'tagtype',0,30) . ' offset="2">&nbsp;&nbsp;<input type="button" value="�������" onclick="check_repeat(\'catalognew[dirname]\',\'dirname\');">&nbsp;&nbsp;<input type="button" value="�Զ�ƴ��" onclick="autoPinyin(\'catalognew[title]\',\'catalognew[dirname]\')" />','');
		trbasic('����Ŀ','catalognew[pid]',makeoption(array('0' => '������Ŀ') + cls_catalog::ccidsarr(0), $pid),'select');
		trbasic('�ṹ��Ŀ(��������Ŀ)','catalognew[isframe]','','radio');
		trbasic('������������ģ�͵��ĵ�','',makecheckbox('catalognew[chids][]',cls_channel::chidsarr(0),!empty($pmsg['chids']) ? explode(',',$pmsg['chids']) : array(),5),'');
		tabfooter();
		tabheader("ģ������");
		$na = array(0 => '��ģ��Ĭ��',);foreach($arc_tpls as $k => $v) $na[$k] = $v['cname']."($k)";
		trbasic('�ĵ�ģ�巽��','new_tid',makeoption($na,@$pmsg['tid']),'select',array('guide' => '�������ĵ�����ģ�͵�������á�<br>ģ�巽��ָ��������ҳ�������б����õ�ģ��,����������ģ������->�ĵ�ģ��->�ĵ�ģ�巽��'));
		trbasic('�ĵ�ҳ��̬�����ʽ','catalognew[customurl]',@$pmsg['customurl'],'text',array('guide'=>'����ΪĬ�ϸ�ʽ��{$topdir}������ĿĿ¼��{$cadir}������ĿĿ¼��{$y}�� {$m}�� {$d}�� {$h}ʱ {$i}�� {$s}�� {$chid}ģ��id  {$aid}�ĵ�id {$page}��ҳҳ�� {$addno}����ҳid��id֮�佨���÷ָ���_��-���ӡ�','w'=>50));
		tabfooter();
		tabheader("Ȩ������");
		setPermBar('��������Ȩ������', 'catalognew[dpmid]',@$pmsg['dpmid'], $source='down', $soext='open', '');
        trbasic('���ػ򲥷Ÿ����۳�����','catalognew[ftaxcp]',makeoption(array('' => '���') + $vcps['ftax'],@$pmsg['ftaxcp']),'select');
		tabfooter();
		tabheader("������Ϣ");
		$a_field = new cls_field;
		foreach($cafields as $field){
			$a_field->init($field);
			$a_field->isadd = 1;
			$a_field->trfield('catalognew');
		}
		trbasic('������ĺ�������','',makeradio('needtip',array('��ʾ����һ����ʲô','��������','�رմ���'),empty($m_cookie["np_add_0"]) ? 0 : $m_cookie["np_add_0"]),'');
		tabfooter('bcatalogadd','����');
		a_guide('catalogadd');
	} else {
		$catalognew['title'] = trim(strip_tags($catalognew['title']));
		if(!$catalognew['title'] || !$catalognew['dirname']) cls_message::show('��Ŀ���ϲ���ȫ',M_REFERER);
		if(preg_match("/[^a-zA-Z_0-9]+/",$catalognew['dirname'])) cls_message::show('��Ŀ��ʶ���Ϲ淶',M_REFERER);
		$catalognew['dirname'] = strtolower($catalognew['dirname']);
		if(in_array($catalognew['dirname'],cls_cache::Read('cn_dirnames'))) cls_message::show('��Ŀ��ʶ�ظ�',M_REFERER);
		$catalognew['chids'] = !empty($catalognew['chids']) ? implode(',',$catalognew['chids']) : '';
		$catalognew['level'] = !$catalognew['pid'] ? 0 : $catalogs[$catalognew['pid']]['level'] + 1;
		$catalognew['customurl'] = preg_replace("/^\/+/",'',trim($catalognew['customurl']));

		$a_field = new cls_field;
		$sqlstr = "";
		foreach($cafields as $k => $v){
			$a_field->init($v);
			$a_field->deal('catalognew','cls_message::show',"?entry=$entry&action=catalogadd");
			$sqlstr .= ','.$k."='".$a_field->newvalue."'";
			if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $sqlstr .= ','.$k.'_'.$x."='$y'";
		}
		$c_upload->saveuptotal(1);
		$catalognew['letter'] = empty($ca_autoletter) || empty($catalognew[$ca_autoletter]) ? '' : autoletter($catalognew[$ca_autoletter]);
		$db->query("INSERT INTO {$tblprefix}catalogs SET 
				   	caid = ".auto_insert_id('catalogs').",
					title='$catalognew[title]', 
					dirname='$catalognew[dirname]', 
					letter='$catalognew[letter]', 
					level='$catalognew[level]', 
					chids='$catalognew[chids]', 
					isframe='$catalognew[isframe]',
					dpmid='$catalognew[dpmid]',
					ftaxcp='$catalognew[ftaxcp]',
					customurl='$catalognew[customurl]',
					pid='$catalognew[pid]'
					$sqlstr
					");
		if($caid = $db->insert_id()){
			$c_upload->closure(1, $caid, 'catalogs');
			if($new_tid = empty($arc_tpls[$new_tid]) ? 0 : $new_tid) $ca_tpl_cfgs[$caid] = $new_tid;
			cls_CacheFile::Save($ca_tpl_cfgs,'ca_tpl_cfgs','ca_tpl_cfgs');
		}
		unset($a_field);
		
		adminlog('������Ŀ');
		cls_catalog::DbTrueOrder(0);
		cls_CacheFile::Update('catalogs');
		
		
		$needtip = min(2,max(0,intval($needtip)));
		$needtip ? msetcookie("np_add_0",$needtip,31536000) : mclearcookie("np_add_0");
		$na = array(array('��Ŀ���ӳɹ�',36,"follow"),array('����������һ��',36,$action),array('�����رմ���',6,'catalogedit'),);
		cls_message::show('��Ŀ���ӳɹ���'.$na[$needtip][0], axaction($na[$needtip][1],"?entry=$entry&action=".$na[$needtip][2]));
	}
}elseif($action == 'follow'){
	echo "<title>��������</title>";
	$cnrels = cls_cache::Read('cnrels');
	tabheader('������Ŀ֮��ĺ�������');
	trbasic('��̨�����ڵ�','',"<a href=\"?entry=amconfigs&action=amconfigablock\"  onclick=\"return floatwin('open_fnodes',this)\">>>����</a>�����ø���Ŀ�ڹ�����̨��������еĽڵ�",'');
	foreach($cnrels as $k => $v){
		if(in_array(0,array($v['coid'],$v['coid1']))){
			trbasic("$k.��Ŀ����",'',"<a href=\"?entry=cnrels&action=cnreldetail&rid=$k&isframe=1\" target=\"_blank\">>>����</a>������ [$v[cname]] �еĹ���",'');
		}
	}
	$str = "<a href=\"?entry=cnodes&action=cnconfigs&ncoid=0&arcdeal=newupdate&isframe=1\" target=\"_blank\">>>��ʽ1</a>��ѡ����صĽڵ���ɷ�������ȫ�����еĽڵ㡣�˷�ʽ���ֶ�ѡ��";
	$str .= "<br><a href=\"?entry=cnodes&action=patchupdate&coid=0\" onclick=\"return floatwin('open_fnodes',this)\">>>��ʽ2</a>���Զ���ȫ��������Ŀ��صĽڵ���ɷ������˷�ʽһ����ɡ�";
	trbasic('������Ŀ�ڵ�','',$str,'');
	trbasic('���ӻ�Ա�ڵ�','',"<a href=\"?entry=mcnodes&action=mcnodeadd&isframe=1\" target=\"_blank\">>>����</a>���ֶ�������Ҫ�Ļ�Ա�ڵ�",'');
	$str = "<a href=\"?entry=o_cnodes&action=cnconfigs&ncoid=0&arcdeal=newupdate&isframe=1\" target=\"_blank\">>>��ʽ1</a>��ѡ����صĽڵ���ɷ�������ȫ�����еĽڵ㡣�˷�ʽ���ֶ�ѡ��";
	$str .= "<br><a href=\"?entry=o_cnodes&action=patchupdate&coid=0\" onclick=\"return floatwin('open_fnodes',this)\">>>��ʽ2</a>���Զ���ȫ��������Ŀ��صĽڵ���ɷ������˷�ʽһ����ɡ�";
	trbasic('�����ֻ���ڵ�','',$str,'');
	tabfooter();
}elseif($action == 'catalogadds'){
	backnav('catalog','adds');
	echo "<title>����������Ŀ</title>";
	cls_channel::chidsarr() || cls_message::show('����������Ч��ģ��');
	$catalogs = cls_catalog::InitialInfoArray(0);
	$cafields = cls_cache::Read('cnfields',0);
	if($pid = empty($pid) ? 0 : $pid){
		$pmsg = @$catalogs[$pid];
		$pmsg['tid'] = empty($ca_tpl_cfgs[$pid]) ? 0 : $ca_tpl_cfgs[$pid];
	}
	$chids = cls_channel::chidsarr(0);
	$_settings = array(
		'pid' => array(
			'type' => 'select',
			'title' => '����Ŀ',
			'value' => makeoption(array('0' => '������Ŀ') + cls_catalog::ccidsarr(0), $pid)
		),
		'isframe' => array(
			'type' => 'radio',
			'title' => '�ṹ��Ŀ(��������Ŀ)',
			'value' => ''
		),
		'chids' => array(
			'type' => '',
			'title' => '������������ģ�͵��ĵ�',
			'value' => makecheckbox('catalogsame[chids][]',$chids,!empty($pmsg['chids']) ? explode(',',$pmsg['chids']) : array(),5)
		),
		'dpmid' => array(
			'type' => 'select',
			'title' => '��������Ȩ������',
			'value' => makeoption(pmidsarr('down'),@$pmsg['dpmid'])
		),
		'ftaxcp' => array(
			'type' => 'select',
			'title' => '���ػ򲥷Ÿ����۳�����',
			'value' => makeoption(array('' => '���') + $vcps['ftax'],@$pmsg['ftaxcp'])
		),
	);
	$_settings['customurl'] = array(
			'type' => 'text',
			'title' => '�ĵ�ҳ��̬�����ʽ',
			'value' => @$pmsg['customurl'],
			'tip' => '����ΪĬ�ϸ�ʽ��{$topdir}������ĿĿ¼��{$cadir}������ĿĿ¼��{$y}�� {$m}�� {$d}�� {$h}ʱ {$i}�� {$s}�� {$chid}ģ��id  {$aid}�ĵ�id {$page}��ҳҳ�� {$addno}����ҳid��id֮�佨���÷ָ���_��-���ӡ�'
			);

	$na = array(0 => '��ģ��Ĭ��',);foreach($arc_tpls as $k => $v) $na[$k] = $v['cname']."($k)";
	$_settings['tid'] = array(
			'type' => 'select',
			'title' => '�ĵ�ģ�巽��',
			'value' => makeoption($na,@$pmsg['tid']),
			'tip' => '�������ĵ�����ģ�͵�������á�<br>ģ�巽��ָ��������ҳ�������б����õ�ģ��,����������ģ������->�ĵ�ģ��->�ĵ�ģ�巽��'
			);
	foreach($cafields as $k => $v){
		$_settings[$k] = array(
			'type' => 'field',
			'title' => $v['cname']
		);
	}
	if(!submitcheck('bcatalogset') && !submitcheck('bcatalogadd')){
		tabheader('����������Ŀ - ��������','catalogadd',"?entry=$entry&action=$action",2,0,1);
		trbasic('����������Ŀ����','batch_count','','text',array('validate'=>' rule="int" must="1" min="1" max="200"'));
		trbasic('��Ҫ�ֱ����õ���','','','');

		trbasic('<input class="checkbox" type="checkbox" checked="checked" disabled="disabled" />', '', '��̬�ļ�����Ŀ¼'.
			' <input class="checkbox" type="checkbox" name="auto_pinyin" id="auto_pinyin" value="1" /><label for="auto_pinyin">�Զ�ƴ��</label>', '');
		foreach($_settings as $k => $v)trbasic('<input class="checkbox" type="checkbox" name="diffitems[]" value="'.$k.'" />', '', $v['title'], '');
		tabfooter('bcatalogset');
	}elseif(!submitcheck('bcatalogadd')){
		$batch_count = max(0, intval($batch_count));
		empty($batch_count) && cls_message::show('����д�������ӵ���Ŀ����', M_REFERER);
		$_diffitems = array(
			'title' => array(
				'type' => 'text',
				'title' => '��Ŀ����',
				'value' => ''
			),
		);
		empty($auto_pinyin) && $_diffitems['dirname'] = array(
			'type' => 'text',
			'title' => '��̬�ļ�����Ŀ¼',
			'value' => ''
		);

		$a_field = new cls_field;
		empty($diffitems) && $diffitems = array();
		tabheader('����������Ŀ - ��ͬ����','catalogadd',"?entry=$entry&action=$action",2,1,1);
		foreach($_settings as $k => $v){
			if(in_array($k, $diffitems)){
				$_diffitems[$k] = $v;
			}elseif($v['type'] == 'field'){
				$a_field->init($cafields[$k]);
				$a_field->isadd = 1;
				$a_field->trfield('catalogsame');
			}else{
				trbasic($v['title'], "catalogsame[$k]", $v['value'], $v['type'], array('guide' => array_key_exists('tip', $v) ? $v['tip'] : ''));
			}
		}
		trbasic('������ĺ�������','',makeradio('needtip',array('��ʾ����һ����ʲô','��������','���ع�������'),empty($m_cookie["np_adds_0"]) ? 0 : $m_cookie["np_adds_0"]),'');
		tabfooter();
		
		echo '<br /><br />'.(empty($auto_pinyin) ? '' : '<input type="hidden" name="auto_pinyin" value="1" />');
		for($i = 0; $i < $batch_count; $i++){
			tabheader('�������� - ��Ŀ'.($i+1));
			foreach($_diffitems as $k => $v){
				if($v['type'] == 'field'){
					$a_field->init($cafields[$k]);
					$a_field->isadd = 1;
					$a_field->trfield("catalogitems[$i]");
				}else{
					trbasic($v['title'], "catalogitems[$i][$k]", $k != 'chids' ? $v['value'] : makecheckbox("catalogitems[$i][chids][]",$chids,!empty($pmsg['chids']) ? explode(',',$pmsg['chids']) : array(),5), $v['type'], array('guide' => array_key_exists('tip', $v) ? $v['tip'] : ''));
				}
			}
			tabfooter();
		}
		echo '<br /><input class="btn" type="submit" name="bcatalogadd" value="�ύ">';
		a_guide('catalogadd');
	}else{
		$enamearr = cls_cache::Read('cn_dirnames');
		$ok = 0;
		$a_field = new cls_field;
		foreach($catalogitems as $item){
			$catalognew = $catalogsame;
			foreach($item as $k => $v){
				if(is_array($v)){
					foreach($v as $a => $b)$catalognew[$k][$a] = $b;
				}else{
					$catalognew[$k] = $v;
				}
			}

			$catalognew['title'] = trim(strip_tags($catalognew['title']));
			empty($auto_pinyin) || $catalognew['dirname'] = cls_string::Pinyin($catalognew['title']);
			if(!$catalognew['title'] || !$catalognew['dirname'])continue;
			if(preg_match("/\W/",$catalognew['dirname']))continue;
			$catalognew['dirname'] = strtolower($catalognew['dirname']);
			if(empty($auto_pinyin)){
				if(in_array($catalognew['dirname'], $enamearr))continue;
			}else{
				$i = 1;
				$dirname = $catalognew['dirname'];
				while(in_array($catalognew['dirname'], $enamearr))$catalognew['dirname'] = $dirname.($i++);
			}

			$catalognew['chids'] = !empty($catalognew['chids']) ? implode(',',$catalognew['chids']) : '';
			$catalognew['level'] = !$catalognew['pid'] ? 0 : $catalogs[$catalognew['pid']]['level'] + 1;
			$catalognew['customurl'] = preg_replace("/^\/+/",'',trim($catalognew['customurl']));
	
			$sqlstr = "";
			foreach($cafields as $k => $v){
				$a_field->init($v);
				$a_field->deal('catalognew');
				if(!empty($a_field->error)) break;
				$sqlstr .= ','.$k."='".$a_field->newvalue."'";
				if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $sqlstr .= ','.$k.'_'.$x."='$y'";

			}
			if(!empty($a_field->error))continue;
			$c_upload->saveuptotal(1);
			$catalognew['letter'] = empty($ca_autoletter) || empty($catalognew[$ca_autoletter]) ? '' : autoletter($catalognew[$ca_autoletter]);
			$db->query("INSERT INTO {$tblprefix}catalogs SET 
					    caid = ".auto_insert_id('catalogs').",
						title='$catalognew[title]', 
						dirname='$catalognew[dirname]', 
						letter='$catalognew[letter]', 
						level='$catalognew[level]', 
						chids='$catalognew[chids]', 
						isframe='$catalognew[isframe]',
						dpmid='$catalognew[dpmid]',
						ftaxcp='$catalognew[ftaxcp]',
						customurl='$catalognew[customurl]',
						pid='$catalognew[pid]'
						$sqlstr
						");
			
			if($caid = $db->insert_id()){
				$enamearr[] = $catalognew['dirname'];
				if($new_tid = empty($arc_tpls[$catalognew['tid']]) ? 0 : $catalognew['tid']) $ca_tpl_cfgs[$caid] = $new_tid;
				$ok++;
			}
			$c_upload->closure(1,$caid,'catalogs');
		}
		unset($a_field);
		
		adminlog('����������Ŀ');
		cls_catalog::DbTrueOrder(0);
		cls_CacheFile::Update('catalogs');
		cls_CacheFile::Save($ca_tpl_cfgs,'ca_tpl_cfgs','ca_tpl_cfgs');
		
		$needtip = min(2,max(0,intval($needtip)));
		$needtip ? msetcookie("np_adds_0",$needtip,31536000) : mclearcookie("np_adds_0");
		$na = array(array('�鿴��������',36,"follow"),array('����������һ��',36,$action),array('�������ع�������',6,'catalogedit'),);
		cls_message::show(($ok ? "�ɹ����� $ok ����Ŀ," : '��������ʧ��').$na[$needtip][0], axaction($na[$needtip][1],"?entry=$entry&action=".$na[$needtip][2]));
	}
}elseif($action == 'catalogedit'){
	backnav('catalog','admin');
	$catalogs = cls_catalog::InitialInfoArray(0);
	if(!submitcheck('bcatalogedit')){
		$addfieldstr = "&nbsp; &nbsp;>><a href=\"?entry=$entry&action=catalogadd\" title=\"���ӵ�����Ŀ\" onclick=\"return floatwin('open_catalogedit',this)\">������Ŀ</a>";
		$addfieldstr .= "&nbsp; &nbsp;>><a href=\"?entry=$entry&action=follow\" title=\"������Ŀ֮����������\" onclick=\"return floatwin('open_catalogedit',this)\">������Ŀ��ĺ�������</a>";
		echo form_str('catalogedit', "?entry=$entry&action=catalogedit");
		echo "<div class=\"conlist1\">��Ŀ����$addfieldstr</div>";
		echo '<script type="text/javascript">var cata = [';
		foreach($catalogs as $caid => $catalog)echo "[$catalog[level],$caid,'" . str_replace("'","\\'",mhtmlspecialchars($catalog['title'])) . "',$catalog[vieworder]],";
		empty($treesteps) && $treesteps = '';
		echo <<<DOT
];
document.write(tableTree({data:cata,ckey:'ckey_0_',step:'$treesteps'.split(',')[0],html:{
		head: '<td class="txtC" width="30"><input type="checkbox" name="chkall" class="checkbox" onclick="checkall(this.form,\'selectid\',\'chkall\')"></td>'
			+ '<td class="txtC" width="40">ID</td>'
			+ '<td class="txtL" width="350"%code%>��Ŀ���� %input%</td>'
			+ '<td class="txtC" width="40">����</td>'
			+ '<td class="txtC" width="40">����</td>'
			+ '<td class="txtC" width="40">����</td>'
			+ '<td class="txtC" width="40">ɾ��</td>',
		cell:[2,4],
		rows:'<td class="txtC" width="30"><input class="checkbox" name="selectid[%1%]" value="'
					+ '%1%" type="checkbox" onclick="tableTree.setChildBox()" /></td>'
			+ '<td class="txtC" width="40">%1%</td>'
			+ '<td class="txtL" width="400">%ico%<input name="catalogsnew['
					+ '%1%][title]" value="%2%" size="25" maxlength="30" type="text" /></td>'
			+ '<td class="txtC" width="40"><input name="catalogsnew['
					+ '%1%][vieworder]" value="%3%" type="text" style="width:36px" /></td>'
			+ '<td class="txtC" width="40"><a href="?entry=$entry&action=catalogadd&pid='
					+ '%1%" onclick="return floatwin(\'open_catalogedit\',this)">����</a></td>'
			+ '<td class="txtC" width="40"><a href="?entry=$entry&action=catalogdetail&caid='
					+ '%1%" onclick="return floatwin(\'open_catalogedit\',this)">����</a></td>'
			+ '<td class="txtC" width="40"><a href="?entry=$entry&action=catalogdelete&caid=%1%" onclick="return deltip()">ɾ��</a></td>'
		},
	callback : true
}));
DOT;
		echo '</script>';

		tabheader('������Ŀ'.viewcheck(array('name' => 'viewdetail','value' =>0,'body' =>$actionid.'tbodyfilter',)).' &nbsp;��ʾ��ϸ');
		echo "<tbody id=\"{$actionid}tbodyfilter\" style=\"display:none\">";
		$s_arr = array();
		$s_arr['letter'] = '��������ĸ';
		$s_arr['noletter'] = '�������ĸ';
		if($s_arr){
			$soperatestr = '';$i = 1;
			foreach($s_arr as $k => $v){
				$soperatestr .= "<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[$k]\" value=\"1\">$v &nbsp;";
				if(!($i % 5)) $soperatestr .= '<br>';
				$i ++;
			}
			trbasic('ѡ�������Ŀ','',$soperatestr,'');
		}
		if($paidsarr = cls_pusher::paidsarr('catalogs',0)){ # ����λ
			$soperatestr = '';
			$i = 1;
			foreach($paidsarr as $k => $v){
				$soperatestr .= OneCheckBox("arcdeal[$k]",cls_pusher::AllTitle($k,1,1),0,1)." &nbsp;";
				if(!($i % 5)) $soperatestr .= '<br>';
				$i ++;
			}
			$soperatestr && trbasic('ѡ������λ','',$soperatestr,'');
		}
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[pid]\" value=\"1\">&nbsp;���踸��Ŀ",'arcpid',makeoption(array('0' => '������Ŀ') + cls_catalog::ccidsarr(0)),'select');
		$cnmodearr = array(0 => '�޸�����������',1 => '��ԭ����������',2 => '��ԭ�������Ƴ�',);
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[chids]\" value=\"1\">&nbsp;������������ģ�͵��ĵ�<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallc\" onclick=\"checkall(this.form,'arcchids','chkallc')\">ȫѡ",'',"<select id=\"cnmode\" name=\"cnmode\" style=\"vertical-align: middle;\">".makeoption($cnmodearr)."</select><br>".makecheckbox('arcchids[]',cls_channel::chidsarr(0),array(),5),'');
		$na = array(0 => '��ģ��Ĭ��',);foreach($arc_tpls as $k => $v) $na[$k] = $v['cname']."($k)";
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[tid]\" value=\"1\">&nbsp;�ĵ�ģ�巽��",'arctid',makeoption($na),'select',array('guide' => '�������ĵ�����ģ�͵�������á�<br>ģ�巽��ָ��������ҳ�������б����õ�ģ��,����������ģ������->�ĵ�ģ��->�ĵ�ģ�巽��'));
		setPermBar("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[dpmid]\" value=\"1\">&nbsp;��������Ȩ������", 'arcdpmid','', $source='down', 'open', '');
        trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[ftaxcp]\" value=\"1\">&nbsp;���ػ򲥷Ÿ����۳�����",'arcftaxcp',makeoption(array('' => '���') + $vcps['ftax']),'select');
		echo "</tbody>";
		tabfooter('bcatalogedit');
		a_guide('catalogedit');
	}
	else{
		if(isset($catalogsnew)){
			foreach($catalogsnew as $caid => $catalognew){
				$catalognew['title'] = trim(strip_tags($catalognew['title']));
				$catalognew['title'] = $catalognew['title'] ? $catalognew['title'] : $catalogs[$caid]['title'];
				$catalognew['vieworder'] = max(0,intval($catalognew['vieworder']));
				if(($catalognew['title'] != $catalogs[$caid]['title']) || ($catalognew['vieworder'] != $catalogs[$caid]['vieworder'])){
					$db->query("UPDATE {$tblprefix}catalogs SET 
								title='$catalognew[title]', 
								vieworder='$catalognew[vieworder]' 
								WHERE caid='$caid'
								");
				}
			}
		}
		if(!empty($selectid) && !empty($arcdeal)){
			$sqlstr = '';
			if(!empty($arcdeal['dpmid'])) $sqlstr .= ",dpmid='$arcdpmid'";
			if(!empty($arcdeal['ftaxcp'])) $sqlstr .= ",ftaxcp='$arcftaxcp'";
			$sqlstr = substr($sqlstr,1);
			$sqlstr && $db->query("UPDATE {$tblprefix}catalogs SET $sqlstr WHERE caid ".multi_str($selectid));
			if(!empty($arcdeal['chids'])){
				foreach($selectid as $caid){
					$chidsnew = empty($arcchids) ? array() : $arcchids;
					if(!empty($cnmode)){
						$chids = empty($catalogs[$caid]['chids']) ? array() : explode(',',$catalogs[$caid]['chids']);
						$chidsnew = $cnmode == 1 ? array_unique(array_merge($chids,$chidsnew)) : array_diff($chids,$chidsnew);
					}
					$chidsnew = !empty($chidsnew) ? implode(',',$chidsnew) : '';
					$db->query("UPDATE {$tblprefix}catalogs SET chids='$chidsnew' WHERE caid='$caid'");
				}
			}
			# ����λ
			if($paidsarr = cls_pusher::paidsarr('catalogs',0)){
				foreach($paidsarr as $k => $v){
					if(!empty($arcdeal[$k])){
						foreach($selectid as $caid){
							cls_catalog::push(0,$caid,$k);
						}
					}
				}
			}
			if(!empty($arcdeal['letter']) && $ca_autoletter){
				foreach($selectid as $caid){
					$letter = autoletter(@$catalogs[$caid][$ca_autoletter]);
					$db->query("UPDATE {$tblprefix}catalogs SET letter='$letter' WHERE caid='$caid'");
				}
			}
			//�������ĸ
			if(!empty($arcdeal['noletter'])){
				foreach($selectid as $caid){						
					$db->query("UPDATE {$tblprefix}catalogs SET letter='' WHERE caid='$caid'");
				}
			}
			if(!empty($arcdeal['pid'])){
				foreach($selectid as $caid){
					$sonids = cls_catalog::cnsonids($caid,$catalogs);
					if(in_array($arcpid,$sonids)) continue;
					$newlevel = !$arcpid ? 0 : $catalogs[$arcpid]['level'] + 1;
					$db->query("UPDATE {$tblprefix}catalogs SET pid='$arcpid',level='$newlevel' WHERE caid='$caid'");
					$leveldiff = $newlevel - $catalogs[$caid]['level'];
					foreach($sonids as $sonid) if($sonid != $caid) $db->query("UPDATE {$tblprefix}catalogs SET level=level+".$leveldiff." WHERE caid='$sonid'");
				}
			}
			if(!empty($arcdeal['tid'])){
				$arctid = empty($arc_tpls[$arctid]) ? 0 : $arctid;
				foreach($selectid as $caid){
					if($arctid){
						$ca_tpl_cfgs[$caid] = $arctid;
					}else unset($ca_tpl_cfgs[$caid]);
				}
			}
		}
		cls_catalog::DbTrueOrder(0);
		cls_CacheFile::Update('catalogs');
		cls_CacheFile::Save($ca_tpl_cfgs,'ca_tpl_cfgs','ca_tpl_cfgs');
		adminlog('�༭��Ŀ�����б�');
		cls_message::show('��Ŀ�༭���', "?entry=$entry&action=catalogedit");
	}
}elseif($action =='catalogdetail' && $caid){
	if(!($catalog = cls_catalog::InitialOneInfo(0,$caid))) cls_message::show('��ָ����ȷ����Ŀ��');
	echo "<title>��Ŀ����[$catalog[title]]</title>";
	$catalogs = cls_catalog::InitialInfoArray(0);
	$cafields = cls_cache::Read('cnfields',0);
	if(!submitcheck('bcatalogdetail')){
		tabheader('��Ŀ��������'."&nbsp;&nbsp;[$catalog[title]]",'catalogdetail',"?entry=$entry&action=catalogdetail&caid=$caid",2,1,1);
		trbasic('��̬�ļ�����Ŀ¼','catalognew[dirname]',$catalog['dirname'],'text',array('guide'=>'������������޸ľ�̬Ŀ¼����Ҫ������ҳ���޸���̬���ӻ��������ɾ�̬��'));
		trbasic('����Ŀ','catalognew[pid]',makeoption(array('0' => '������Ŀ') + cls_catalog::ccidsarr(0),$catalog['pid']),'select');
		trbasic('�ṹ��Ŀ(��������Ŀ)','catalognew[isframe]',$catalog['isframe'],'radio');
		trbasic('������������ģ�͵��ĵ�','',makecheckbox('catalognew[chids][]',cls_channel::chidsarr(0),!empty($catalog['chids']) ? explode(',',$catalog['chids']) : array(),5),'');
		tabfooter();
		tabheader("ģ������");
		$tid = empty($ca_tpl_cfgs[$caid]) ? 0 : $ca_tpl_cfgs[$caid];
		$na = array(0 => '��ģ��Ĭ��',);foreach($arc_tpls as $k => $v) $na[$k] = $v['cname']."($k)";
		trbasic('�ĵ�ģ�巽��','new_tid',makeoption($na,$tid),'select',array('guide' => '�������ĵ�����ģ�͵�������á�<br>ģ�巽��ָ��������ҳ�������б����õ�ģ��,����������ģ������->�ĵ�ģ��->�ĵ�ģ�巽��'));
		trbasic('�ĵ�ҳ��̬�����ʽ','catalognew[customurl]',$catalog['customurl'],'text',array('guide'=>'����ΪĬ�ϸ�ʽ��{$topdir}������ĿĿ¼��{$cadir}������ĿĿ¼��{$y}�� {$m}�� {$d}�� {$h}ʱ {$i}�� {$s}�� {$chid}ģ��id  {$aid}�ĵ�id {$page}��ҳҳ�� {$addno}����ҳid��id֮�佨���÷ָ���_��-���ӡ�','w'=>50));
		tabfooter();
		tabheader("Ȩ������");
		setPermBar('��������Ȩ������', 'catalognew[dpmid]', @$catalog['dpmid'] , $source='down', $soext='open', '');
        trbasic('���ػ򲥷Ÿ����۳�����','catalognew[ftaxcp]',makeoption(array('' => '���') + $vcps['ftax'],$catalog['ftaxcp']),'select');
		tabfooter();
		$a_field = new cls_field;
		$addfieldstr = "&nbsp; &nbsp; >><a href=\"?entry=$entry&action=cafieldsedit\">��Ŀ�ֶ�</a>";
		tabheader("������Ϣ");
		foreach($cafields as $field){
			$a_field->init($field,isset($catalog[$field['ename']]) ? $catalog[$field['ename']] : '');
			$a_field->trfield('catalognew');
		}
		tabfooter('bcatalogdetail');
		a_guide('catalogdetail');
	}else{
		$catalognew['dirname'] = strtolower($catalognew['dirname']);
		if($catalognew['dirname'] != $catalog['dirname']){
			if(preg_match("/[^a-zA-Z_0-9]+/",$catalognew['dirname'])) cls_message::show('��Ŀ��ʶ���Ϲ淶',M_REFERER);
			if(in_array($catalognew['dirname'],cls_cache::Read('cn_dirnames'))) cls_message::show('��Ŀ��ʶ�ظ�',M_REFERER);
		}
		$sonids = cls_catalog::cnsonids($caid,$catalogs);
		in_array($catalognew['pid'],$sonids) && cls_message::show('��Ŀ����ת��ԭ��Ŀ��������Ŀ��',"?entry=$entry&action=catalogdetail&caid=$caid");
		$catalognew['chids'] = !empty($catalognew['chids']) ? implode(',',$catalognew['chids']) : '';
		$catalognew['level'] = !$catalognew['pid'] ? 0 : $catalogs[$catalognew['pid']]['level'] + 1;
		$catalognew['customurl'] = preg_replace("/^\/+/",'',trim($catalognew['customurl']));

		$a_field = new cls_field;
		$sqlstr = "";
		foreach($cafields as $k => $v){
			$a_field->init($v,isset($catalog[$k]) ? $catalog[$k] : '');
			$a_field->deal('catalognew','cls_message::show',"?entry=$entry&action=catalogdetail&caid=$caid");
			$sqlstr .= ','.$k."='".$a_field->newvalue."'";
			if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $sqlstr .= ','.$k.'_'.$x."='$y'";
		}
		$c_upload->closure(1, $caid, 'catalogs');
		$c_upload->saveuptotal(1);
		unset($a_field);

		$leveldiff = $catalognew['level'] - $catalog['level'];
		foreach($sonids as $sonid) $db->query("UPDATE {$tblprefix}catalogs SET level=level+".$leveldiff." WHERE caid='$sonid'");
		if(!empty($ca_autoletter) && ($LetterSource = empty($catalognew[$ca_autoletter]) ? @$catalog[$ca_autoletter] : $catalognew[$ca_autoletter])){
			$catalognew['letter'] = autoletter($LetterSource);
		}else $catalognew['letter'] = '';
		$db->query("UPDATE {$tblprefix}catalogs SET
			dirname='$catalognew[dirname]',
			letter='$catalognew[letter]',
			pid='$catalognew[pid]',
			chids='$catalognew[chids]', 
			level='$catalognew[level]',
			isframe='$catalognew[isframe]',
			dpmid='$catalognew[dpmid]',
			ftaxcp='$catalognew[ftaxcp]',
			customurl='$catalognew[customurl]'
			$sqlstr
			WHERE caid='$caid'");
		adminlog('��ϸ�޸���Ŀ');
		cls_catalog::DbTrueOrder(0);
		cls_CacheFile::Update('catalogs');
		
		$new_tid = empty($arc_tpls[$new_tid]) ? 0 : $new_tid;
		if($new_tid){
			$ca_tpl_cfgs[$caid] = $new_tid;
		}else unset($ca_tpl_cfgs[$caid]);
		cls_CacheFile::Save($ca_tpl_cfgs,'ca_tpl_cfgs','ca_tpl_cfgs');
		
		cls_message::show('��Ŀ�������', axaction(6,"?entry=$entry&action=catalogedit"));
	}

}elseif($action == 'catalogdelete' && $caid){
	backnav('catalog','admin');
	deep_allow($no_deepmode && in_array($caid,@explode(',',$deep_caids)),"?entry=$entry&action=catalogedit");
	if(!($catalog = cls_catalog::InitialOneInfo(0,$caid))) cls_message::show('��ָ����ȷ����Ŀ��',"?entry=$entry&action=catalogedit");
	if(!submitcheck('confirm')){
		$message = "ɾ�����ָܻ���ȷ��ɾ����ѡ��Ŀ?<br><br>";
		$message .= "ȷ������>><a href=?entry=$entry&action=catalogdelete&caid=$caid&confirm=ok>ɾ��</a><br>";
		$message .= "��������>><a href=?entry=$entry&action=catalogedit>����</a>";
		cls_message::show($message);
	}
	if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}catalogs WHERE pid='$caid'")) {
		cls_message::show('��Ŀû�������������Ŀ����ɾ��', "?entry=$entry&action=catalogedit");
	}
	$na = stidsarr(1);
	foreach($na as $k => $v){
		$db->result_one("SELECT COUNT(*) FROM {$tblprefix}".atbl($k,1)." WHERE caid='$caid'") && cls_message::show('ɾ����Ŀ���������Ŀ�ڵ��ĵ�', "?entry=$entry&action=catalogedit");
	}

	//ɾ����صĽڵ�
	$db->query("DELETE FROM {$tblprefix}cnodes WHERE caid='$caid'");
	cls_CacheFile::Update('cnodes');
	$db->query("DELETE FROM {$tblprefix}o_cnodes WHERE caid='$caid'");
	cls_CacheFile::Update('o_cnodes');
	$db->query("DELETE FROM {$tblprefix}mcnodes WHERE mcnvar='caid' AND mcnid='$caid'");
	cls_CacheFile::Update('mcnodes');

	$db->query("DELETE FROM {$tblprefix}catalogs WHERE caid='$caid'");
	adminlog('ɾ����Ŀ');
	cls_CacheFile::Update('catalogs');
	//������Ŀ����
	$cnrels = cls_cache::Read('cnrels');
	foreach($cnrels as $k => $v){
		$alter = false;
		if(!$v['coid'] && isset($v['cfgs'][$caid])){
			unset($v['cfgs'][$caid]);
			$alter = true;
		}
		if(!$v['coid1']){
			foreach($v['cfgs'] as $x => $y){
				$a = empty($y) ? array() : array_filter(explode(',',$y));
				if(in_array($caid,$a)){
					$a = array_filter($a,"clearsameid");
					$v['cfgs'][$x] = implode(',',$a);
					$alter = true;
				}
			}
		}
		$alter && $db->query("UPDATE {$tblprefix}cnrels SET cfgs='".(empty($v['cfgs']) ? '' : addslashes(var_export($v['cfgs'],TRUE)))."' WHERE rid='$k'",'SILENT');
	}
	cls_CacheFile::Update('cnrels');
	cls_message::show('��Ŀɾ�����', "?entry=$entry&action=catalogedit");
}elseif($action == 'cafieldsedit'){
	backnav('catalog','fields');
	echo "<title>��Ŀ�ֶ�</title>";
	$fields = cls_fieldconfig::InitialFieldArray('catalog',0);
	if(!submitcheck('bcafieldsedit')){
		$addfieldstr = "&nbsp; &nbsp; >><a href=\"?entry=$entry&action=fieldone\" onclick=\"return floatwin('open_{$actionid}_cafieldadd',this);\">�����ֶ�</a>";
		tabheader('��Ŀ��Ϣ�ֶι���'.$addfieldstr,'cafieldsedit',"?entry=$entry&action=cafieldsedit",'5');
		trcategory(array('����',array('�ֶ�����','txtL'),'����',array('�ֶα�ʶ','txtL'),array('���ݱ�','txtL'),'�ֶ�����','ɾ��','�༭'));
		foreach($fields as $k => $v) {
			echo "<tr class=\"txt\">\n".
				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"fieldsnew[$k][available]\" value=\"1\"".($v['available'] ? ' checked' : '')."></td>\n".
				"<td class=\"txtL\"><input type=\"text\" size=\"25\" name=\"fieldsnew[$k][cname]\" value=\"".mhtmlspecialchars($v['cname'])."\"></td>\n".
				"<td class=\"txtC w60\"><input type=\"text\" size=\"4\" name=\"fieldsnew[$k][vieworder]\" value=\"$v[vieworder]\"></td>\n".
				"<td class=\"txtL\">".mhtmlspecialchars($k)."</td>\n".
				"<td class=\"txtL\">$v[tbl]</td>\n".
				"<td class=\"txtC w100\">".cls_fieldconfig::datatype($v['datatype'])."</td>\n".
				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$k]\" value=\"$k\" onclick=\"deltip()\"></td>\n".
				"<td class=\"txtC w50\"><a href=\"?entry=$entry&action=fieldone&fieldname=$k\" onclick=\"return floatwin('open_fielddetail',this)\">����</a></td>\n".
				"</tr>";
		}
		tabfooter('bcafieldsedit');
		a_guide('cafieldsedit');
	}else{
		if(!empty($delete)){
			$deleteds = cls_fieldconfig::DeleteField('catalog',0,$delete);
			foreach($deleteds as $k){
				unset($fieldsnew[$k]);
			}
		}
		if(!empty($fieldsnew)){
			foreach($fieldsnew as $k => $v){
				$v['cname'] = trim(strip_tags($v['cname']));
				$v['cname'] = $v['cname'] ? $v['cname'] : $fields[$k]['cname'];
				$v['available'] = $fields[$k]['issystem'] || !empty($v['available']) ? 1 : 0;
				$v['vieworder'] = max(0,intval($v['vieworder']));
				cls_fieldconfig::ModifyOneConfig('catalog',0,$v,$k);
			}
		}
		cls_fieldconfig::UpdateCache('catalog',0);
		adminlog('�༭��Ŀ��Ϣ�ֶι����б�');
		cls_message::show('�ֶ��޸����',"?entry=$entry&action=cafieldsedit");
	}
}elseif($action == 'fieldone'){
	cls_FieldConfig::EditOne('catalog',0,@$fieldname);

}elseif($action == 'mconfigs'){
	backnav('catalog','mconfigs');
	echo "<title>��Ŀ����</title>";
	$treestep = empty($mconfigs['treesteps']) ? '' : explode(',', $mconfigs['treesteps']);
	if(!submitcheck('bmconfigs')){
		tabheader('��Ŀ��������','cfview',"?entry=$entry&action=$action");
		is_array($treestep) && $treestep = $treestep[0];#��0��Ϊ��Ŀ��
		$ca_vmodearr = array('0' => '��ͨѡ���б�','1' => '��ѡ��ť','2' => '�༶����','3' => '�༶����(ajax)',);
		trbasic('��Ŀѡ��ʱ�б���ʽ','',makeradio('mconfigsnew[ca_vmode]',$ca_vmodearr,empty($mconfigs['ca_vmode']) ? 0 : $mconfigs['ca_vmode']),'');
		trbasic('ѡ���б����ز���ѡ��','mconfigsnew[catahidden]',$mconfigs['catahidden'],'radio');
		trbasic('�����������η�ҳ��ʾ', 'mconfigsnew[treesteps]', $treestep, 'text', array('guide'=>'������ÿҳ����������Ϊ����ҳ������Ŀ��������ʱ��������Ϊ10-30���������'));
		$fields = cls_fieldconfig::InitialFieldArray('catalog',0);
		$arr = array('' => '������','title' => '��Ŀ����','dirname' => '��̬Ŀ¼',);
		foreach($fields as $k => $v) $v['datatype'] == 'text' && $arr[$k] = $v['cname'];
		trbasic('�Զ�����ĸ��Դ�ֶ�','mconfigsnew[ca_autoletter]',makeoption($arr,@$mconfigs['ca_autoletter']),'select');
		tabfooter('bmconfigs');
	}else{
		$treestep || $treestep = array();
		$treestep[0] = empty($mconfigsnew['treesteps']) ? '' : max(10, $mconfigsnew['treesteps']);#��0��Ϊ��Ŀ��
		$mconfigsnew['treesteps'] = implode(',', $treestep);
		saveconfig('view');
		adminlog('��Ŀ��������');
		cls_message::show('��Ŀ�����������',axaction(6,"?entry=$entry&action=catalogedit"));
	}
}else cls_message::show('������ļ�����');


function clearsameid($var){
	global $caid;
	return $var == $caid ? false : true;
}
