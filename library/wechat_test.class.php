<?php
class WechatTest extends WechatDelegate
{
	public function textMsg($content)
	{
		return $this->makeNewsMsg(array(
			$this->makeArticle('Google', 'Google Search', 'https://www.google.com.hk/images/srpr/logo4w.png', 'http://www.google.com.hk'),
			$this->makeArticle('Google 2', 'Google Search 2', 'https://www.google.com.hk/images/srpr/logo4w.png', 'http://www.google.com.hk')
		));
	}
	public function imageMsg($picUrl)
	{
		return $this->makeMusicMsg(
		'http://music.baidu.com/data/music/file?link=http://zhangmenshiting.baidu.com/data2/music/8704091/8692986237600128.mp3?xcode=14c4a2b772662aee0ca177dffe5246f3', 
		'http://music.baidu.com/data/music/file?link=http://zhangmenshiting.baidu.com/data2/music/8704091/8692986237600128.mp3?xcode=14c4a2b772662aee0ca177dffe5246f3', 
		'How Did I Fall In Love With You', 'Backstreet Boys');
	}
	public function locationMsg($locationX,$locationY,$scale,$label)
	{
		return $this->makeTextMsg('Location Msg');
	}
	public function linkMsg($title,$description,$url)
	{
		return $this->makeTextMsg('Link Msg');
	}
	public function eventMsg($event)
	{
		return $this->makeTextMsg('event Msg');
	}
}
