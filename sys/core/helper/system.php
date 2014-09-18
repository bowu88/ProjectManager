<?php

/**
 * 获取网站地址，依据路由表
 * @param string|array $param
 * @return string
 */
function get_url($param = ''){
	$rt = URL_WEB;
	if(is_array($param)){
		$rt .= implode(ROUTER_SPLIT_CHAR, $param);
		if(func_num_args() > 1){
			$rt .= func_get_arg(1);
		}
	} else{
		$rt .= implode(ROUTER_SPLIT_CHAR, func_get_args());
	}
	return $rt;
}

/**
 * 获取文件绝对路径
 * @param string|array $param
 * @return string
 */
function get_file_url($param = ''){
	$rt = URL_FILE;
	if(is_array($param)){
		$rt .= implode('/', $param);
		if(func_num_args() > 1){
			$rt .= func_get_arg(1);
		}
	} else{
		$rt .= implode('/', func_get_args());
	}
	return $rt;
}

/**
 * 对网页进行重定向，该操作将导致程序结束运行
 * @param string|array $uri
 * @param string $method
 * @param int $http_response_code
 */
function redirect($uri = '', $method = 'refresh', $http_response_code = 302){
	if(is_array($uri) || !preg_match('#^https?://#i', $uri)){
		$uri = get_url($uri);
	}
	switch($method){
		case 'refresh'    :
			header("Refresh:0;url=" . $uri);
			break;
		default            :
			header("Location: " . $uri, true, $http_response_code);
			break;
	}
	exit;
}

/**
 * 获取客户端IP地址
 * @return string
 */
function get_client_ip(){
	if(getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")){
		$ip = getenv("HTTP_CLIENT_IP");
	} else if(getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")){
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	} else if(getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")){
		$ip = getenv("REMOTE_ADDR");
	} else if(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")){
		$ip = $_SERVER['REMOTE_ADDR'];
	} else{
		$ip = "unknown";
	}
	return ($ip);
}

/**
 * 获取文件后缀的Mime类型
 *
 * @return array|bool|string
 */
function mime_get(){
	$mimes = array(
		'3gp' => 'video/3gpp',
		'aab' => 'application/x-authoware-bin',
		'aam' => 'application/x-authoware-map',
		'aas' => 'application/x-authoware-seg',
		'ai' => 'application/postscript',
		'aif' => 'audio/x-aiff',
		'aifc' => 'audio/x-aiff',
		'aiff' => 'audio/x-aiff',
		'als' => 'audio/X-Alpha5',
		'amc' => 'application/x-mpeg',
		'ani' => 'application/octet-stream',
		'asc' => 'text/plain',
		'asd' => 'application/astound',
		'asf' => 'video/x-ms-asf',
		'asn' => 'application/astound',
		'asp' => 'application/x-asap',
		'asx' => 'video/x-ms-asf',
		'au' => 'audio/basic',
		'avb' => 'application/octet-stream',
		'avi' => 'video/x-msvideo',
		'awb' => 'audio/amr-wb',
		'bcpio' => 'application/x-bcpio',
		'bin' => 'application/octet-stream',
		'bld' => 'application/bld',
		'bld2' => 'application/bld2',
		'bmp' => 'application/x-MS-bmp',
		'bpk' => 'application/octet-stream',
		'bz2' => 'application/x-bzip2',
		'cal' => 'image/x-cals',
		'ccn' => 'application/x-cnc',
		'cco' => 'application/x-cocoa',
		'cdf' => 'application/x-netcdf',
		'cgi' => 'magnus-internal/cgi',
		'chat' => 'application/x-chat',
		'class' => 'application/octet-stream',
		'clp' => 'application/x-msclip',
		'cmx' => 'application/x-cmx',
		'co' => 'application/x-cult3d-object',
		'cod' => 'image/cis-cod',
		'cpio' => 'application/x-cpio',
		'cpt' => 'application/mac-compactpro',
		'crd' => 'application/x-mscardfile',
		'csh' => 'application/x-csh',
		'csm' => 'chemical/x-csml',
		'csml' => 'chemical/x-csml',
		'css' => 'text/css',
		'cur' => 'application/octet-stream',
		'dcm' => 'x-lml/x-evm',
		'dcr' => 'application/x-director',
		'dcx' => 'image/x-dcx',
		'dhtml' => 'text/html',
		'dir' => 'application/x-director',
		'dll' => 'application/octet-stream',
		'dmg' => 'application/octet-stream',
		'dms' => 'application/octet-stream',
		'doc' => 'application/msword',
		'dot' => 'application/x-dot',
		'dvi' => 'application/x-dvi',
		'dwf' => 'drawing/x-dwf',
		'dwg' => 'application/x-autocad',
		'dxf' => 'application/x-autocad',
		'dxr' => 'application/x-director',
		'ebk' => 'application/x-expandedbook',
		'emb' => 'chemical/x-embl-dl-nucleotide',
		'embl' => 'chemical/x-embl-dl-nucleotide',
		'eps' => 'application/postscript',
		'eri' => 'image/x-eri',
		'es' => 'audio/echospeech',
		'esl' => 'audio/echospeech',
		'etc' => 'application/x-earthtime',
		'etx' => 'text/x-setext',
		'evm' => 'x-lml/x-evm',
		'evy' => 'application/x-envoy',
		'exe' => 'application/octet-stream',
		'fh4' => 'image/x-freehand',
		'fh5' => 'image/x-freehand',
		'fhc' => 'image/x-freehand',
		'fif' => 'image/fif',
		'fm' => 'application/x-maker',
		'fpx' => 'image/x-fpx',
		'fvi' => 'video/isivideo',
		'gau' => 'chemical/x-gaussian-input',
		'gca' => 'application/x-gca-compressed',
		'gdb' => 'x-lml/x-gdb',
		'gif' => 'image/gif',
		'gps' => 'application/x-gps',
		'gtar' => 'application/x-gtar',
		'gz' => 'application/x-gzip',
		'hdf' => 'application/x-hdf',
		'hdm' => 'text/x-hdml',
		'hdml' => 'text/x-hdml',
		'hlp' => 'application/winhlp',
		'hqx' => 'application/mac-binhex40',
		'htm' => 'text/html',
		'html' => 'text/html',
		'hts' => 'text/html',
		'ice' => 'x-conference/x-cooltalk',
		'ico' => 'application/octet-stream',
		'ief' => 'image/ief',
		'ifm' => 'image/gif',
		'ifs' => 'image/ifs',
		'imy' => 'audio/melody',
		'ins' => 'application/x-NET-Install',
		'ips' => 'application/x-ipscript',
		'ipx' => 'application/x-ipix',
		'it' => 'audio/x-mod',
		'itz' => 'audio/x-mod',
		'ivr' => 'i-world/i-vrml',
		'j2k' => 'image/j2k',
		'jad' => 'text/vnd.sun.j2me.app-descriptor',
		'jam' => 'application/x-jam',
		'jar' => 'application/java-archive',
		'jnlp' => 'application/x-java-jnlp-file',
		'jpe' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'jpz' => 'image/jpeg',
		'js' => 'application/x-javascript',
		'jwc' => 'application/jwc',
		'kjx' => 'application/x-kjx',
		'lak' => 'x-lml/x-lak',
		'latex' => 'application/x-latex',
		'lcc' => 'application/fastman',
		'lcl' => 'application/x-digitalloca',
		'lcr' => 'application/x-digitalloca',
		'lgh' => 'application/lgh',
		'lha' => 'application/octet-stream',
		'lml' => 'x-lml/x-lml',
		'lmlpack' => 'x-lml/x-lmlpack',
		'lsf' => 'video/x-ms-asf',
		'lsx' => 'video/x-ms-asf',
		'lzh' => 'application/x-lzh',
		'm13' => 'application/x-msmediaview',
		'm14' => 'application/x-msmediaview',
		'm15' => 'audio/x-mod',
		'm3u' => 'audio/x-mpegurl',
		'm3url' => 'audio/x-mpegurl',
		'ma1' => 'audio/ma1',
		'ma2' => 'audio/ma2',
		'ma3' => 'audio/ma3',
		'ma5' => 'audio/ma5',
		'man' => 'application/x-troff-man',
		'map' => 'magnus-internal/imagemap',
		'mbd' => 'application/mbedlet',
		'mct' => 'application/x-mascot',
		'mdb' => 'application/x-msaccess',
		'mdz' => 'audio/x-mod',
		'me' => 'application/x-troff-me',
		'mel' => 'text/x-vmel',
		'mi' => 'application/x-mif',
		'mid' => 'audio/midi',
		'midi' => 'audio/midi',
		'mif' => 'application/x-mif',
		'mil' => 'image/x-cals',
		'mio' => 'audio/x-mio',
		'mmf' => 'application/x-skt-lbs',
		'mng' => 'video/x-mng',
		'mny' => 'application/x-msmoney',
		'moc' => 'application/x-mocha',
		'mocha' => 'application/x-mocha',
		'mod' => 'audio/x-mod',
		'mof' => 'application/x-yumekara',
		'mol' => 'chemical/x-mdl-molfile',
		'mop' => 'chemical/x-mopac-input',
		'mov' => 'video/quicktime',
		'movie' => 'video/x-sgi-movie',
		'mp2' => 'audio/x-mpeg',
		'mp3' => array(
			'audio/mpeg',
			'audio/x-mpeg'
		),
		'mp4' => 'video/mp4',
		'mpc' => 'application/vnd.mpohun.certificate',
		'mpe' => 'video/mpeg',
		'mpeg' => 'video/mpeg',
		'mpg' => 'video/mpeg',
		'mpg4' => 'video/mp4',
		'mpga' => 'audio/mpeg',
		'mpn' => 'application/vnd.mophun.application',
		'mpp' => 'application/vnd.ms-project',
		'mps' => 'application/x-mapserver',
		'mrl' => 'text/x-mrml',
		'mrm' => 'application/x-mrm',
		'ms' => 'application/x-troff-ms',
		'mts' => 'application/metastream',
		'mtx' => 'application/metastream',
		'mtz' => 'application/metastream',
		'mzv' => 'application/metastream',
		'nar' => 'application/zip',
		'nbmp' => 'image/nbmp',
		'nc' => 'application/x-netcdf',
		'ndb' => 'x-lml/x-ndb',
		'ndwn' => 'application/ndwn',
		'nif' => 'application/x-nif',
		'nmz' => 'application/x-scream',
		'nokia-op-logo' => 'image/vnd.nok-oplogo-color',
		'npx' => 'application/x-netfpx',
		'nsnd' => 'audio/nsnd',
		'nva' => 'application/x-neva1',
		'oda' => 'application/oda',
		'oom' => 'application/x-AtlasMate-Plugin',
		'pac' => 'audio/x-pac',
		'pae' => 'audio/x-epac',
		'pan' => 'application/x-pan',
		'pbm' => 'image/x-portable-bitmap',
		'pcx' => 'image/x-pcx',
		'pda' => 'image/x-pda',
		'pdb' => 'chemical/x-pdb',
		'pdf' => 'application/pdf',
		'pfr' => 'application/font-tdpfr',
		'pgm' => 'image/x-portable-graymap',
		'pict' => 'image/x-pict',
		'pm' => 'application/x-perl',
		'pmd' => 'application/x-pmd',
		'png' => 'image/png',
		'pnm' => 'image/x-portable-anymap',
		'pnz' => 'image/png',
		'pot' => 'application/vnd.ms-powerpoint',
		'ppm' => 'image/x-portable-pixmap',
		'pps' => 'application/vnd.ms-powerpoint',
		'ppt' => 'application/vnd.ms-powerpoint',
		'pqf' => 'application/x-cprplayer',
		'pqi' => 'application/cprplayer',
		'prc' => 'application/x-prc',
		'proxy' => 'application/x-ns-proxy-autoconfig',
		'ps' => 'application/postscript',
		'ptlk' => 'application/listenup',
		'pub' => 'application/x-mspublisher',
		'pvx' => 'video/x-pv-pvx',
		'qcp' => 'audio/vnd.qcelp',
		'qt' => 'video/quicktime',
		'qti' => 'image/x-quicktime',
		'qtif' => 'image/x-quicktime',
		'r3t' => 'text/vnd.rn-realtext3d',
		'ra' => 'audio/x-pn-realaudio',
		'ram' => 'audio/x-pn-realaudio',
		'rar' => 'application/x-rar-compressed',
		'ras' => 'image/x-cmu-raster',
		'rdf' => 'application/rdf+xml',
		'rf' => 'image/vnd.rn-realflash',
		'rgb' => 'image/x-rgb',
		'rlf' => 'application/x-richlink',
		'rm' => 'audio/x-pn-realaudio',
		'rmf' => 'audio/x-rmf',
		'rmm' => 'audio/x-pn-realaudio',
		'rmvb' => 'audio/x-pn-realaudio',
		'rnx' => 'application/vnd.rn-realplayer',
		'roff' => 'application/x-troff',
		'rp' => 'image/vnd.rn-realpix',
		'rpm' => 'audio/x-pn-realaudio-plugin',
		'rt' => 'text/vnd.rn-realtext',
		'rte' => 'x-lml/x-gps',
		'rtf' => 'application/rtf',
		'rtg' => 'application/metastream',
		'rtx' => 'text/richtext',
		'rv' => 'video/vnd.rn-realvideo',
		'rwc' => 'application/x-rogerwilco',
		's3m' => 'audio/x-mod',
		's3z' => 'audio/x-mod',
		'sca' => 'application/x-supercard',
		'scd' => 'application/x-msschedule',
		'sdf' => 'application/e-score',
		'sea' => 'application/x-stuffit',
		'sgm' => 'text/x-sgml',
		'sgml' => 'text/x-sgml',
		'sh' => 'application/x-sh',
		'shar' => 'application/x-shar',
		'shtml' => 'magnus-internal/parsed-html',
		'shw' => 'application/presentations',
		'si6' => 'image/si6',
		'si7' => 'image/vnd.stiwap.sis',
		'si9' => 'image/vnd.lgtwap.sis',
		'sis' => 'application/vnd.symbian.install',
		'sit' => 'application/x-stuffit',
		'skd' => 'application/x-Koan',
		'skm' => 'application/x-Koan',
		'skp' => 'application/x-Koan',
		'skt' => 'application/x-Koan',
		'slc' => 'application/x-salsa',
		'smd' => 'audio/x-smd',
		'smi' => 'application/smil',
		'smil' => 'application/smil',
		'smp' => 'application/studiom',
		'smz' => 'audio/x-smd',
		'snd' => 'audio/basic',
		'spc' => 'text/x-speech',
		'spl' => 'application/futuresplash',
		'spr' => 'application/x-sprite',
		'sprite' => 'application/x-sprite',
		'spt' => 'application/x-spt',
		'src' => 'application/x-wais-source',
		'stk' => 'application/hyperstudio',
		'stm' => 'audio/x-mod',
		'sv4cpio' => 'application/x-sv4cpio',
		'sv4crc' => 'application/x-sv4crc',
		'svf' => 'image/vnd',
		'svg' => 'image/svg-xml',
		'svh' => 'image/svh',
		'svr' => 'x-world/x-svr',
		'swf' => 'application/x-shockwave-flash',
		'swfl' => 'application/x-shockwave-flash',
		't' => 'application/x-troff',
		'tad' => 'application/octet-stream',
		'talk' => 'text/x-speech',
		'tar' => 'application/x-tar',
		'taz' => 'application/x-tar',
		'tbp' => 'application/x-timbuktu',
		'tbt' => 'application/x-timbuktu',
		'tcl' => 'application/x-tcl',
		'tex' => 'application/x-tex',
		'texi' => 'application/x-texinfo',
		'texinfo' => 'application/x-texinfo',
		'tgz' => 'application/x-tar',
		'thm' => 'application/vnd.eri.thm',
		'tif' => 'image/tiff',
		'tiff' => 'image/tiff',
		'tki' => 'application/x-tkined',
		'tkined' => 'application/x-tkined',
		'toc' => 'application/toc',
		'toy' => 'image/toy',
		'tr' => 'application/x-troff',
		'trk' => 'x-lml/x-gps',
		'trm' => 'application/x-msterminal',
		'tsi' => 'audio/tsplayer',
		'ts' => 'video/MP2T',
		'tsp' => 'application/dsptype',
		'tsv' => 'text/tab-separated-values',
		'tsv' => 'text/tab-separated-values',
		'ttf' => 'application/octet-stream',
		'ttz' => 'application/t-time',
		'txt' => 'text/plain',
		'ult' => 'audio/x-mod',
		'ustar' => 'application/x-ustar',
		'uu' => 'application/x-uuencode',
		'uue' => 'application/x-uuencode',
		'vcd' => 'application/x-cdlink',
		'vcf' => 'text/x-vcard',
		'vdo' => 'video/vdo',
		'vob' => 'application/x-vob',
		'vib' => 'audio/vib',
		'viv' => 'video/vivo',
		'vivo' => 'video/vivo',
		'vmd' => 'application/vocaltec-media-desc',
		'vmf' => 'application/vocaltec-media-file',
		'vmi' => 'application/x-dreamcast-vms-info',
		'vms' => 'application/x-dreamcast-vms',
		'vox' => 'audio/voxware',
		'vqe' => 'audio/x-twinvq-plugin',
		'vqf' => 'audio/x-twinvq',
		'vql' => 'audio/x-twinvq',
		'vre' => 'x-world/x-vream',
		'vrml' => 'x-world/x-vrml',
		'vrt' => 'x-world/x-vrt',
		'vrw' => 'x-world/x-vream',
		'vts' => 'workbook/formulaone',
		'wav' => 'audio/x-wav',
		'wax' => 'audio/x-ms-wax',
		'wbmp' => 'image/vnd.wap.wbmp',
		'web' => 'application/vnd.xara',
		'wi' => 'image/wavelet',
		'wis' => 'application/x-InstallShield',
		'wm' => 'video/x-ms-wm',
		'wma' => 'audio/x-ms-wma',
		'wmd' => 'application/x-ms-wmd',
		'wmf' => 'application/x-msmetafile',
		'wml' => 'text/vnd.wap.wml',
		'wmlc' => 'application/vnd.wap.wmlc',
		'wmls' => 'text/vnd.wap.wmlscript',
		'wmlsc' => 'application/vnd.wap.wmlscriptc',
		'wmlscript' => 'text/vnd.wap.wmlscript',
		'wmv' => 'video/x-ms-wmv',
		'wmx' => 'video/x-ms-wmx',
		'wmz' => 'application/x-ms-wmz',
		'wpng' => 'image/x-up-wpng',
		'wpt' => 'x-lml/x-gps',
		'wri' => 'application/x-mswrite',
		'wrl' => 'x-world/x-vrml',
		'wrz' => 'x-world/x-vrml',
		'ws' => 'text/vnd.wap.wmlscript',
		'wsc' => 'application/vnd.wap.wmlscriptc',
		'wv' => 'video/wavelet',
		'wvx' => 'video/x-ms-wvx',
		'wxl' => 'application/x-wxl',
		'x-gzip' => 'application/x-gzip',
		'xar' => 'application/vnd.xara',
		'xbm' => 'image/x-xbitmap',
		'xdm' => 'application/x-xdma',
		'xdma' => 'application/x-xdma',
		'xdw' => 'application/vnd.fujixerox.docuworks',
		'xht' => 'application/xhtml+xml',
		'xhtm' => 'application/xhtml+xml',
		'xhtml' => 'application/xhtml+xml',
		'xla' => 'application/vnd.ms-excel',
		'xlc' => 'application/vnd.ms-excel',
		'xll' => 'application/x-excel',
		'xlm' => 'application/vnd.ms-excel',
		'xls' => 'application/vnd.ms-excel',
		'xlt' => 'application/vnd.ms-excel',
		'xlw' => 'application/vnd.ms-excel',
		'xm' => 'audio/x-mod',
		'xml' => 'text/xml',
		'xmz' => 'audio/x-mod',
		'xpi' => 'application/x-xpinstall',
		'xpm' => 'image/x-xpixmap',
		'xsit' => 'text/xml',
		'xsl' => 'text/xml',
		'xul' => 'text/xul',
		'xwd' => 'image/x-xwindowdump',
		'xyz' => 'chemical/x-pdb',
		'yz1' => 'application/x-yz1',
		'z' => 'application/x-compress',
		'zac' => 'application/x-zaurus-zac',
		'zip' => 'application/zip'
	);
	$args = func_get_args();
	if(count($args) <= 0){
		return $mimes;
	}
	$rt = array();
	foreach($args as $v){
		if(isset($mimes[$v])){
			array_push($rt, $mimes[$v]);
		}
	}
	if(count($rt) == 1){
		return $rt[0];
	}
	if(count($rt) == 0){
		return false;
	}
	return $rt;
}