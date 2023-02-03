<?php
	header("Content-type: application/xml");
	$srv = 'https://limehd.tv/api/v4/playlist?limit=472';
	$srvtxt = 'https://limehd.tv/api/v4/channel/';
	$arx = playlist_lime($srv);
	$afk = json_decode($arx, true);
echo '<?xml version="1.0" encoding="utf-8"?>'.'
	<tv generator-info-name="Формировщик EPG LIME PHP">';
foreach ($afk['channels'] as $parsfreelime) 
{
	$icon_channel = $parsfreelime['image'];
	$name_channel = $parsfreelime['name_ru'];
	$id_channel = $parsfreelime['address'];
	echo '
		<channel id="'.$id_channel.'">';
		echo '
			<display-name lang="ru">'.$name_channel.'</display-name>'.'
			<icon src="'.$icon_channel.'"/>'.'
		</channel>';
		$epg_trx = channel_epg($srvtxt.$id_channel."?epg=1");
		$epg_ddx = json_decode($epg_trx, true);
		/*$epg_trx = file_get_contents($srvtxt.$id_channel."?epg=1");
		$epg_ddx = json_decode($epg_trx, true);*/
		if(isset($epg_ddx['epg']))
		{
			foreach($epg_ddx['epg'] as $parsepg)
			{
				if(isset($parsepg['data']))
				{
					foreach($parsepg['data'] as $epgs1)
					{
						$start_epg = $epgs1['timestart'];
						$end_epg = $epgs1['timestop'];
						$name_epg = str_replace("&", "&amp;", $epgs1['title']);
						$format = '%d%m%Y%H%M%S';
						$start_epg_xml = strftime($format, $start_epg);
						$end_epg_xml = strftime($format, $end_epg);
			echo '
			<programme start="'.$start_epg_xml.' +0300" stop="'.$end_epg_xml.' +0300" channel="'.$id_channel.'">'.'
				<title lang="ru">'.$name_epg.'</title>
			</programme>';
					}
				}
				else
				{
						$start_epg = $parsepg['timestart'];
						$end_epg = $parsepg['timestop'];
						$name_epg = str_replace("&", "&amp;", $parsepg['title']);
						$format = '%d%m%Y%H%M%S';
						$start_epg_xml = strftime($format, $start_epg);
						$end_epg_xml = strftime($format, $end_epg);
			echo '
			<programme start="'.$start_epg_xml.' +0300" stop="'.$end_epg_xml.' +0300" channel="'.$id_channel.'">'.'
				<title lang="ru">'.$name_epg.'</title>
			</programme>';
				}
			}
		}
}
echo '
	</tv>';
	function playlist_lime($link)
	{
		$link = $link;
		$ip = $_SERVER['REMOTE_ADDR'];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $link);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$headers = array();
		$headers[] = 'Connection: keep-alive';
		$headers[] = 'Cache-Control: no-cache';
		$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:82.0) Gecko/20100101 Firefox/82.0';
		$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8';
		$headers[] = "REMOTE_ADDR $ip";
		$headers[] = "X-Forwarded-For: $ip";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		return $result;
		if(curl_errno($ch))
		{
			echo 'Ошибка выполнения: '.curl_error($ch);
		}
	}
	function channel_epg($link)
	{
		$link = $link;
		$ip = $_SERVER['REMOTE_ADDR'];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $link);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$headers = array();
		$headers[] = 'Connection: keep-alive';
		$headers[] = 'Cache-Control: no-cache';
		$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:82.0) Gecko/20100101 Firefox/82.0';
		$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8';
		$headers[] = "REMOTE_ADDR $ip";
		$headers[] = "X-Forwarded-For: $ip";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		return $result;
		if(curl_errno($ch))
		{
			echo 'Ошибка выполнения: '.curl_error($ch);
		}
	}
?>