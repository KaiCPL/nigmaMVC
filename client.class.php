<?php

Class Client {
	public static function IP() {
		$ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
		foreach ($ip_keys as $key) {
			if (array_key_exists($key, $_SERVER) === true) {
				foreach (explode(',', $_SERVER[$key]) as $ip) {
					// trim for safety measures
					$ip = trim($ip);
					// attempt to validate IP
					if (Client::IPvalid($ip)) return $ip;
				}
			}
		}
		return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
	}
	public static function IPvalid($ip) { return (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false); }
		
	public static function Browser() {
		$agent = $_SERVER['HTTP_USER_AGENT']; if(empty($agent)) return '(hidden browser)';
		//$browser = get_browser(null, true);
		$browsers = [
			'12345'				=> '12345 Browser',
			'Anonymizied'		=> 'Anonymizied',
			'T-Online'			=> 'T-Online',
			'ibisBrowser'		=> 'ibisBrowser',
			'HistoryHound'		=> 'HistoryHound',
			'CryptoAPI'			=> 'Microsoft CryptoAPI',
			'Openwave'			=> 'Openwave Browser',
			'Netscape'			=> 'Netscape',
			'Lunascape'			=> 'Lunascape',
			'Handspring'		=> 'Handspring Blazer',
			'TinyBrowser'		=> 'TinyBrowser',
			'NetPositive'		=> 'NetPositive',
			'Lycoris'			=> 'Lycoris Desktop/LX',
			'fantomas'			=> 'fantomas shadowMaker Browser',
			'Chimera'			=> 'Chimera',
			'BIRD'				=> 'BIRD',
			'ELinks'			=> 'ELinks',
			'Minefield'			=> 'Minefield',
			'Haier'				=> 'Haier',
			'Icedove'			=> 'Icedove',
			'Maxthon'			=> 'Maxthon',
			'PERFECT'			=> 'PERFECT Browser',
			'Shiira'			=> 'Shiira',
			'Songbird'			=> 'Songbird',
			'SeaMonkey'			=> 'SeaMonkey',
			'OmniWeb'			=> 'OmniWeb',
			'Camino'			=> 'Camino',
			'Chimera'			=> 'Chimera',
			'Epiphany'			=> 'Epiphany',
			'Konqueror'			=> 'Konqueror',
			'Iceweasel'			=> 'Iceweasel',
			'K-Meleon'			=> 'K-Meleon',
			'Slim'				=> 'Slim Browser',
			'Lynx'				=> 'Lynx',
			'Links'				=> 'Links',
			'libcurl'			=> 'cURL',
			'midori'			=> 'Midori',
			'Blackberry'		=> 'BlackBerry Browser',
			'Firefox'			=> 'Firefox',
			'OPR'				=> 'Opera Blink', # Opera 15+ (the version running Blink)
			'Vivaldi'			=> 'Vivaldi Blink',
			'Otter'				=> 'Otter Qt',
			'Palemoon'			=> 'Palemoon',
			'Waterfox'			=> 'Waterfox',
			'KDFX'				=> 'KDFX',
			'Presto'			=> 'OperaTor (Opera-based; Presto)',
			'Opera'				=> 'Opera',
			'Chrome'			=> 'Chrome',
			'Safari'			=> 'Safari',
			'chromeframe'		=> 'Chrome Frame',
			'x-clock'			=> 'Chrome Frame',
			'MSIE'				=> 'Internet Explorer',
			'Trident'			=> 'Internet Explorer',
			'Shiretoko'			=> 'Firefox (Experimental)',
			'Minefield'			=> 'Firefox (Experimental)',
			'GranParadiso'		=> 'Firefox (Experimental)',
			'Namoroka'			=> 'Firefox (Experimental)',
			'AppleWebKit'		=> 'WebKit',
			'Mozilla'			=> 'Mozilla'
			/*
			'WWW-Mechanize'		=> 'Perl',
			'Wget'				=> 'Wget',
			'BTWebClient'		=> 'µTorrent',
			'Transmission'		=> 'Transmission',
			'Java'				=> 'Java',
			'RSS'				=> 'RSS Downloader'
			*/
		];
		
		$R = '(unknown browser)';
		foreach($browsers as $string => $browser) {
			if(strpos($agent, $string)!==false) {
				$R = $browser;
				if(preg_match('/(Device|Mobi|Mini|webOS)/i', $agent)) $R .= ' (mobile)';
				if(preg_match('/('.$string.').([0-9a-zA-Z.]+)/i', $agent, $found)) $R .= ' &lt;'.$found[2].'&gt;';
				break;
			}
		}
		return $R;
	}

	public static function OS() {
		$agent = $_SERVER['HTTP_USER_AGENT']; if(empty($agent)) return '(hidden os type)';

		$os_array = [
			'/windows nt (6.4|10.0)/i'		=>	'Windows 10',
			'/windows nt 6.3/i'				=>	'Windows 8.1',
			'/windows nt 6.2/i'				=>	'Windows 8',
			'/windows nt 6.1/i'				=>	'Windows 7',
			'/windows nt 6.0/i'				=>	'Windows Vista',
			'/windows nt 5.2/i'				=>	'Windows Server 2003/XP x64',
			'/windows (nt 5.1|xp)/i'		=>	'Windows XP',
			'/windows nt 5.01/i'			=>	'Windows 2000 SP1',
			'/windows nt 5.0/i'				=>	'Windows 2000',
			'/win 9x 4.90|windows me/i'		=>	'Windows ME',
			'/win98/i'						=>	'Windows 98 SE',
			'/Windows (98|4\.10)/i'			=>	'Windows 98',
			'/win95/i'						=>	'Windows 95',
			'/windows nt 4.0/i'				=>	'Windows NT4.0',
			'/winnt4.0/i'					=>	'Windows NT4.0a',
			'/win(dows )?nt ?3.51/i'		=>	'Windows NT3.51',
			'/win(dows )?3.11|win16/i'		=>	'Windows 3.11',
			'/winnt3.51/i'					=>	'Windows 3.11a',
			'/windows 3.1/i'				=>	'Windows 3.1',
			'/dos/i'						=>	'DOS',
			'/windows phone/i'				=>	'Windows Phone',
			'/zunewp7|wp7/i'				=>	'Windows Phone WP7',
			'/wpdesktop/i'					=>	'Windows Phone DesktopWP7',
			'/windows .+mobile/i'			=>	'Windows CE (M)',
			'/windows ce/i'					=>	'Windows CE',
			'/wm5/i'						=>	'Windows Mobile 5',
			'/(win)([0-9.]+)/i'				=>	'Windows (Unknown)',


			'/macintosh|mac os x/i'			=>	'Mac OS X',
			'/mac_powerpc/i'				=>	'Mac OS 9',
			'/iphone/i'						=>	'iPhone',
			'/ipod/i'						=>	'iPod',
			'/ipad/i'						=>	'iPad',
			'/iwatch/i'						=>	'iWatch',
			'/irmc/i'						=>	'iRemote',
			'/ifridge/i'					=>	'iFridge',
			'/icar/i'						=>	'iCar',
			'/imouse/i'						=>	'iMouse',

			'/java/i'						=>	'Java',
			'/solaris/i'					=>	'Solaris',
			
			'/aix/i'						=>	'AIX',
			'/amiga(-aweb)?/i'				=>	'AmigaOS',
			'/[^A-Za-z]Arch/i'				=>	'Arch GNU/Linux',
			'/ASPLinux/i'					=>	'ASPLinux',
			'/AvantGo/i'					=>	'PalmOS',
			'/beos/i'						=>	'BeOS',
			'/CentOS/i'						=>	'CentOS GNU/Linux',
			'/.el([.0-9a-zA-Z]+).centos/i'	=>	'CentOS GNU/Linux (packed)',
			'/Chakra/i'						=>	'Chakra GNU/Linux',
			'/Crunchbang/i'					=>	'Crunchbang',
			'/Debian/i'						=>	'Debian GNU/Linux',
			'/Dreamcast/i'					=>	'Dreamcast OS',
			'/Dropline/i'					=>	'Slackware GNU/Linux (Dropline GNOME)',
			'/Edubuntu/i'					=>	'Edubuntu',
			'/Fedora/i'						=>	'Fedora GNU/Linux',
			'/.fc([.0-9a-zA-Z]+)/i'			=>	'Fedora GNU/Linux (packed)',
			'/Foresight\ Linux/i'			=>	'Foresight Linux',
			'/freebsd/i'					=>	'FreeBSD',
			'/Gentoo/i'						=>	'Gentoo',
			'/hurd/i'						=>	'GNU Hurd',
			'/Kanotix/i'					=>	'Kanotix',
			'/Knoppix/i'					=>	'Knoppix',
			'/Kubuntu/i'					=>	'Kubuntu GNU/Linux',
			'/libwww-perl/i'				=>	'Unix',
			'/LindowsOS/i'					=>	'LindowsOS',
			'/Linspire/i'					=>	'Linspire/LindowsOS',
			'/Linux\ Mint/i'				=>	'Linux Mint',
			'/Lubuntu/i'					=>	'Lubuntu',
			'/Mageia/i'						=>	'Mageia',
			'/Mandriva/i'					=>	'Mandriva GNU/Linux',
			'/moonOS/i'						=>	'MoonOS',
			'/netbsd/i'						=>	'NetBSD',
			'/Nova/i'						=>	'Nova',
			'/irix/i'						=>	'IRIX',
			'/Oracle/i'						=>	'Oracle',
			'/openbsd/i'					=>	'OpenBSD',
			'/os\/2/i'						=>	'OS/2',
			'/osf/i'						=>	'OSF',
			'/Pardus/i'						=>	'Pardus',
			'/plan9/i'						=>	'Plan9',
			'/red.?hat/i'					=>	'RedHat GNU/Linux',
			'/Slackware/i'					=>	'Slackware GNU/Linux',
			'/sunos/i'						=>	'SunOS',
			'/suse/i'						=>	'OpenSUSE GNU/Linux',
			'/xubuntu/i'					=>	'Xubuntu',
			'/zenwalk/i'					=>	'Zenwalk',
			'/ubuntu/i'						=>	'Ubuntu GNU/Linux',
			'/linux/i'						=>	'GNU/Linux',
			'/risc os/i'					=>	'RISC OS',
			
			'/Android|ADR /i'				=>	'Android',
			'/AmigaOS/i'					=>	'AmigaOS',
			'/blackberry/i'					=>	'BlackBerry',
			'/webos/i'						=>	'Mobile'
		];

		foreach($os_array as $regex => $value) {
			if(preg_match($regex, $agent)) {
				//if(preg_match(pattern, $agent))
				return $value;
			}
		}
		return '(unknown os)';
	}
	public static function Signature() { return md5($_SERVER['HTTP_USER_AGENT']."###".self::IP()); }
}