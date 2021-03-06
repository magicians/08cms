<?php
$cachedos = array(
	'channels' => array(
		'tbl' => 'channels',
		'key' => 'chid',
		'orderby' => 'vieworder,chid',
		'varexport' => 'cfgs,searchfields', //,fieldgroup
		'unset' => 'vieworder,cfgs0,remark,content',
		'merge' => 'cfgs',
	),
	'fields' => array(
		'tbl' => 'afields',
		'key' => 'ename',
		'orderby' => 'vieworder,fid',
		'varexport' => 'cfgs',
		'unset' => 'cfgs0',
		'merge' => 'cfgs',
	),
	'commus' => array(
		'tbl' => 'acommus',
		'key' => 'cuid',
		'orderby' => 'vieworder,cuid',
		'varexport' => 'cfgs',
		'unset' => 'cfgs0,content,vieworder',
		'merge' => 'cfgs',
	),
	'abrels' => array(
		'tbl' => 'abrels',
		'key' => 'arid',
		'orderby' => 'vieworder,arid',
		'varexport' => 'cfgs',
		'unset' => 'cfgs0,content,vieworder',
		'merge' => 'cfgs',
	),
	'aurls' => array(
		'tbl' => 'aurls',
		'key' => 'auid',
		'orderby' => 'vieworder',
	),
	'cnrels' => array(
		'tbl' => 'cnrels',
		'key' => 'rid',
		'orderby' => 'vieworder,rid',
		'varexport' => 'cfgs',
		'unset' => 'remark,vieworder',
	),
	'cotypes' => array(
		'tbl' => 'cotypes',
		'key' => 'coid',
		'orderby' => 'vieworder,coid',
		'unset' => 'remark,vieworder',
	),
	'mctypes' => array(
		'tbl' => 'mctypes',
		'key' => 'mctid',
		'orderby' => 'vieworder,mctid',
	),
	'fcatalogs' => array(
		'tbl' => 'fcatalogs',
		'key' => 'fcaid',
		'orderby' => 'vieworder,fcaid',
		'varexport' => 'cfgs',
		'unset' => 'cfgs0,content,vieworder',
		'merge' => 'cfgs',
	),
	'frcatalogs' => array(
		'tbl' => 'frcatalogs',
		'key' => 'frcaid',
		'orderby' => 'vieworder,frcaid',
	),
	'fragments' => array(
		'tbl' => 'fragments',
		'key' => 'ename',
		'orderby' => 'vieworder,ename',
	),
	'fchannels' => array(
		'tbl' => 'fchannels',
		'key' => 'chid',
		'orderby' => 'chid',
	),
	'currencys' => array(
		'tbl' => 'currencys',
		'key' => 'crid',
		'orderby' => 'crid',
		'unserialize' => 'bases',
	),
	'splitbls' => array(
		'tbl' => 'splitbls',
		'key' => 'stid',
		'explode' => 'chids,coids',
		'orderby' => 'vieworder,stid',		
	),
	'static_process' => array(
		'tbl' => 'static_process',
		'key' => 'spid',
		'fieldstr' => 'spid,startdate,finishdate,pause',
		'orderby' => 'spid',
	),
	'mspacepaths' => array(
		'tbl' => 'members',
		'key' => 'mid',
		'fieldstr' => 'mid,mspacepath',
		'where' => "mspacepath<>''",
	),
	'cnconfigs' => array(
		'tbl' => 'cnconfigs',
		'key' => 'cncid',
		'orderby' => 'vieworder',
		'unserialize' => 'configs',
	),
	'bannedips' => array(
		'tbl' => 'bannedips',
		'key' => 'bid',
		'fieldstr' => 'bid,ip1,ip2,ip3,ip4',
	),
	'mcatalogs' => array(
		'tbl' => 'mcatalogs',
		'key' => 'mcaid',
		'orderby' => 'vieworder,mcaid',
	),
	'mchannels' => array(
		'tbl' => 'mchannels',
		'key' => 'mchid',
		'orderby' => 'mchid',
		'varexport' => 'cfgs',
		'unset' => 'cfgs0,content',
		'merge' => 'cfgs',
	),
	'dbsources' => array(
		'tbl' => 'dbsources',
		'key' => 'dsid',
		'orderby' => 'dsid',
	),
	
	'players' => array(
		'tbl' => 'players',
		'key' => 'plid',
		'orderby' => 'vieworder,plid',
	),
	'gmodels' => array(
		'tbl' => 'gmodels',
		'key' => 'gmid',
		'orderby' => 'gmid',
		'unserialize' => 'gfields',
	),
	'gmissions' => array(
		'tbl' => 'gmissions',
		'key' => 'gsid',
		'orderby' => 'gsid',
		'unserialize' => 'fsettings,dvalues',
	),
	'catalogs' => array(
		'tbl' => 'catalogs',
		'key' => 'caid',
		'where' => 'closed=0',
		'orderby' => 'trueorder',
	),
	'grouptypes' => array(
		'tbl' => 'grouptypes',
		'key' => 'gtid',
		'orderby' => 'gtid',
	),
	'usergroups' => array(
		'tbl' => 'usergroups',
		'key' => 'ugid',
		'orderby' => 'currency DESC,prior,ugid',
	),
	'coclasses' => array(
		'tbl' => 'coclass',
		'key' => 'ccid',
		'orderby' => 'trueorder',
		'unserialize' => 'conditions',
	),
	'splangs' => array(
		'tbl' => 'splangs',
		'key' => 'ename',
		'orderby' => 'vieworder,slid',
	),
	'rprojects' => array(
		'tbl' => 'rprojects',
		'key' => 'rpid',
		'orderby' => 'rpid',
		'unserialize' => 'rmfiles',
		'explode' => 'excludes',
	),
	'watermarks' => array(
		'tbl' => 'watermarks',
		'key' => 'wmid',
		'orderby' => 'wmid',
	),
	'uprojects' => array(
		'tbl' => 'uprojects',
		'key' => 'upid',
		'orderby' => 'gtid,upid',
	),
	'permissions' => array(
		'tbl' => 'permissions',
		'key' => 'pmid',
		'orderby' => 'vieworder,pmid',
	),
	'crprojects' => array(
		'tbl' => 'crprojects',
		'key' => 'crpid',
		'orderby' => 'crpid',
	),
	'mtconfigs' => array(
		'tbl' => 'mtconfigs',
		'key' => 'mtcid',
		'orderby' => 'mtcid',
		'unserialize' => 'setting,arctpls',
	),
	'amconfigs' => array(
		'tbl' => 'amconfigs',
		'key' => 'amcid',
		'orderby' => 'vieworder',
	),
	'sitemaps' => array(
		'tbl' => 'sitemaps',
		'key' => 'ename',
		'orderby' => 'vieworder',
	),
	'freeinfos' => array(
		'tbl' => 'freeinfos',
		'key' => 'fid',
		'orderby' => 'fid',
	),
	'mconfigs' => array(
		'tbl' => 'mconfigs',
		'key' => 'varname',
		'where' => "cftype<>''",
		'orderby' => 'cftype',
	),
	'localfiles' => array(
		'tbl' => 'localfiles',
		'key' => 'lfid',
		'orderby' => 'lfid',
	),
	'crprices' => array(
		'tbl' => 'crprices',
		'key' => 'ename',
		'orderby' => 'crid,crvalue',
	),
	'vcatalogs' => array(
		'tbl' => 'vcatalogs',
		'key' => 'caid',
		'orderby' => 'vieworder,caid',
	),
	'usualurls' => array(
		'tbl' => 'usualurls',
		'key' => 'uid',
		'orderby' => 'vieworder',
	),
	'cntpls' => array(
		'tbl' => 'cntpls',
		'key' => 'tid',
		'where' => 'ism=0',
		'orderby' => 'vieworder,tid',
		'unserialize' => 'cfgs',
	),
	'mcntpls' => array(
		'tbl' => 'cntpls',
		'key' => 'tid',
		'where' => 'ism=1',
		'orderby' => 'vieworder,tid',
		'unserialize' => 'cfgs',
	),
	'orderccid' => array(
		'tbl' => 'coclass',
		'key' => 'ccid',
		'orderby' => 'vieworder',
	),
	'ordercaid' => array(
		'tbl' => 'catalogs',
		'key' => 'caid',
		'orderby' => 'vieworder',
	),
	'pagecaches' => array(
		'tbl' => 'pagecaches',
		'key' => 'pcid',
		'where' => 'available=1 AND period>0',
		'orderby' => 'vieworder,pcid',
		'varexport' => 'cfgs',
		'merge' => 'cfgs',
	),
	'pushtypes' => array(
		'tbl' => 'pushtypes',
		'key' => 'ptid',
		'orderby' => 'vieworder,ptid',
		'unset' => 'remark,vieworder',
	),
	'pushareas' => array(
		'tbl' => 'pushareas',
		'key' => 'paid',
		'where' => 'available=1',
		'orderby' => 'vieworder,paid',
		'varexport' => 'sourcefields',
	),	
);
