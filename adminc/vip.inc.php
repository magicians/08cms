<?php
!defined('M_COM') && exit('No Permission');
$type = empty($type) ? 'vipgs' : $type;
if($type == 'vipgs'){
	$ngtid = 31;$nchid = 11;$nugid = 102;
	$mchid = $curuser->info['mchid'];
	if($mchid != $nchid) cls_message::show('����Ҫ��ע��Ϊװ�޹�˾��Ա�ſ���������');
	$exconfigs = cls_cache::cacRead('exconfigs',_08_EXTEND_SYSCACHE_PATH);
	if(!($rules = @$exconfigs[$type])) cls_message::show('ϵͳû�ж���װ�޹�˾��������');
	if(!submitcheck('bsubmit')){
		tabheader('װ�޹�˾����','gtexchagne',"?action=$action&type=$type");
		trbasic('��Ŀǰ������','',($curuser->info["grouptype$ngtid"] == $nugid ? 'VIP��˾' : '��ͨ��˾').' &nbsp;����ʱ�䣺'.($curuser->info["grouptype{$ngtid}date"] ? date('Y-m-d H:i',$curuser->info["grouptype{$ngtid}date"]) : '����'),'');
		trbasic('ˢ�´������','',$curuser->info['freerefnum'].' ��','');
		trbasic('�ֽ��ʻ����','',$curuser->info['currency0']."Ԫ &nbsp; &nbsp;<a href=\"?action=payonline\" target=\"_blank\">>>����֧��</a>",'');
		if(($curuser->info["grouptype$ngtid"] == $nugid) && !$curuser->info["grouptype{$ngtid}date"]){
			trbasic('����˵��','','�������õ�VIP��˾������Ҫ������','');
			tabfooter();
		}else{
			$str = '';foreach($rules as $k => $v) $v['available'] && $str .= "<input class=\"radio\" type=\"radio\" name=\"exchangekey\" value=\"$k\" checked> &nbsp;$v[title] &nbsp;�۸�<b>$v[price]</b> Ԫ &nbsp;��Ч�ڣ�<b>$v[month]</b> ���� &nbsp;���� <b>$v[refnum]</b> ��ˢ������<br>";
			trbasic('����������','',"<br>$str<br>",'');
			tabfooter('bsubmit');
			$mgdes = @$exconfigs['upmemberhelp'][$ngtid];
			$mgdes['des'] = implode('<p>',explode("\r\n",$mgdes['des']));
			empty($mgdes) ? '' : m_guide($mgdes['des'],'fix');
		}
	}else{
		$exchangekey = max(0,intval($exchangekey));
		if(!($rule = @$rules[$exchangekey])) cls_message::show('��ָ������Ϊ����VIP��˾��',M_REFERER);
		if($curuser->info['currency0'] < $rule['price']) cls_message::show('�����ֽ��ʻ����㣬���ֵ��',M_REFERER);
		$curuser->updatefield('freerefnum',$curuser->info['freerefnum'] + $rule['refnum']);
		$curuser->updatefield("grouptype{$ngtid}date",($curuser->info["grouptype$ngtid"] == $nugid ? $curuser->info["grouptype{$ngtid}date"] : $timestamp) + $rule['month'] * 30 * 86400);
		$curuser->updatefield("grouptype$ngtid",$nugid);
		$curuser->updatecrids(array(0 => -$rule['price']),1,'װ�޹�˾��Ա������');
		cls_message::show('VIP��˾�����ɹ���',M_REFERER);
	
	}
}elseif($type == 'vipsj'){
	$ngtid = 32;$nchid = 12;$nugid = 104;
	$mchid = $curuser->info['mchid'];
	if($mchid != $nchid) cls_message::show('����Ҫ��ע��ΪƷ���̼һ�Ա�ſ���������');
	$exconfigs = cls_cache::cacRead('exconfigs',_08_EXTEND_SYSCACHE_PATH);
	if(!($rules = @$exconfigs[$type])) cls_message::show('ϵͳû�ж���Ʒ���̼���������');
	if(!submitcheck('bsubmit')){
		tabheader('Ʒ���̼�����','gtexchagne',"?action=$action&type=$type");
		trbasic('��Ŀǰ������','',($curuser->info["grouptype$ngtid"] == $nugid ? 'VIP�̼�' : '��ͨ�̼�').' &nbsp;����ʱ�䣺'.($curuser->info["grouptype{$ngtid}date"] ? date('Y-m-d H:i',$curuser->info["grouptype{$ngtid}date"]) : '����'),'');
		trbasic('ˢ�´������','',$curuser->info['freerefnum'].' ��','');
		trbasic('�ֽ��ʻ����','',$curuser->info['currency0']."Ԫ &nbsp; &nbsp;<a href=\"?action=payonline\" target=\"_blank\">>>����֧��</a>",'');
		if(($curuser->info["grouptype$ngtid"] == $nugid) && !$curuser->info["grouptype{$ngtid}date"]){
			trbasic('����˵��','','�������õ�VIP�̼ң�����Ҫ������','');
			tabfooter();
		}else{
			$str = '';foreach($rules as $k => $v) $v['available'] && $str .= "<input class=\"radio\" type=\"radio\" name=\"exchangekey\" value=\"$k\" checked> &nbsp;$v[title] &nbsp;�۸�<b>$v[price]</b> Ԫ &nbsp;��Ч�ڣ�<b>$v[month]</b> ���� &nbsp;���� <b>$v[refnum]</b> ��ˢ������<br>";
			trbasic('����������','',"<br>$str<br>",'');
			tabfooter('bsubmit');
			$mgdes = @$exconfigs['upmemberhelp'][$ngtid];
			$mgdes['des'] = implode('<p>',explode("\r\n",$mgdes['des']));
			empty($mgdes) ? '' : m_guide($mgdes['des'],'fix');
		}
	}else{
		$exchangekey = max(0,intval($exchangekey));
		if(!($rule = @$rules[$exchangekey])) cls_message::show('��ָ������Ϊ����VIP�̼ҡ�',M_REFERER);
		if($curuser->info['currency0'] < $rule['price']) cls_message::show('�����ֽ��ʻ����㣬���ֵ��',M_REFERER);
		$curuser->updatefield('freerefnum',$curuser->info['freerefnum'] + $rule['refnum']);
		$curuser->updatefield("grouptype{$ngtid}date",($curuser->info["grouptype$ngtid"] == $nugid ? $curuser->info["grouptype{$ngtid}date"] : $timestamp) + $rule['month'] * 30 * 86400);
		$curuser->updatefield("grouptype$ngtid",$nugid);
		$curuser->updatecrids(array(0 => -$rule['price']),1,'Ʒ���̼һ�Ա������');
		cls_message::show('VIP�̼������ɹ���',M_REFERER);
	
	}	
}
?>