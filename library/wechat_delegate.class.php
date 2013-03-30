<?php
abstract class WechatDelegate
{
	abstract public function textMsg($content);
	abstract public function imageMsg($picUrl);
	abstract public function locationMsg($locationX,$locationY,$scale,$label);
	abstract public function linkMsg($title,$description,$url);
	abstract public function eventMsg($event);
	
	public function makeTextMsg($content,$isFlag=false)
	{
		$rVal = array(
			'MsgType'=>'text',
			'Content'=>$content,
			'FuncFlag'=>$isFlag?1:0
		);
		return $rVal;
	}
	
	public function makeMusicMsg($musicUrl,$hqMusicUrl,$title,$description,$isFlag=false)
	{
		$rVal = array(
			'MsgType'=>'music',
			'Title'=>$title,
			'Description'=>$description,
			'MusicUrl'=>$musicUrl,
			'HQMusicUrl'=>$hqMusicUrl,
			'FuncFlag'=>$isFlag?1:0
		);
		return $rVal;
	}
	
	
	public function makeNewsMsg($articles,$isFlag=false)
	{
		$rVal = array(
			'MsgType'=>'news',
			'ArticleCount'=>count($articles),
			'Articles'=>$articles,
			'FuncFlag'=>$isFlag?1:0
		);
		return $rVal;
	}

	public function makeArticle($title,$description,$picUrl,$url)
	{
		$rVal = array(
			'Title'=>$title,
			'Description'=>$description,
			'PicUrl'=>$picUrl,
			'Url'=>$url
		);
		return $rVal;
	}
}