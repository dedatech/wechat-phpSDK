<?php
class WechatServer
{
	public function __construct()
	{
		;
	}
	
	public function run(WechatDelegate $delegate)
	{
		if(isset($_GET["echostr"])){
			  $this->valid();
		}
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		if ($this->checkSignature() && !empty($postStr)){
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			switch($postObj->MsgType){
				case 'text':
					$response = $delegate->textMsg($postObj->Content);
					break;
				case 'image':
					$response = $delegate->imageMsg($postObj->PicUrl);
					break;
				case 'location':
					$response = $delegate->locationMsg($postObj->Location_X, $postObj->Location_Y, $postObj->Scale, $postObj->Label);
					break;
				case 'link':
					$response = $delegate->linkMsg($postObj->Title, $postObj->Description, $postObj->Url);
					break;
				case 'event':
					$response = $delegate->eventMsg($postObj->Event);
					break;
				default:
					return FALSE;
			}
			if(!empty($response)){
				switch($response['MsgType']){
					case 'text':
						$this->responseTextMsg($postObj->FromUserName,$postObj->ToUserName,$response);
						break;
					case 'music':
						$this->responseMusicMsg($postObj->FromUserName,$postObj->ToUserName,$response);
						break;
					case 'news':
						$this->responseNewsMsg($postObj->FromUserName,$postObj->ToUserName,$response);
						break;
					default:;
						return FALSE;
				}
				return TRUE;
			}
			return TRUE;
        }else {
        	return FALSE;
        }
	}
	
	private function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    private function responseTextMsg($toUsername,$fromUsername,$response)
    {
        $time = time();
		$textTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			<FuncFlag>%d</FuncFlag>
			</xml>";
		$resultStr = sprintf($textTpl, $toUsername, $fromUsername, $time, $response['MsgType'], $response['Content'], $response['FuncFlag']);
		echo $resultStr;
    }
	
	private function responseMusicMsg($toUsername,$fromUsername,$response)
    {
        $time = time();
		$textTpl = "<xml>
			 <ToUserName><![CDATA[%s]]></ToUserName>
			 <FromUserName><![CDATA[%s]]></FromUserName>
			 <CreateTime>%s</CreateTime>
			 <MsgType><![CDATA[%s]]></MsgType>
			 <Music>
			 <Title><![CDATA[%s]]></Title>
			 <Description><![CDATA[%s]]></Description>
			 <MusicUrl><![CDATA[%s]]></MusicUrl>
			 <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
			 </Music>
			 <FuncFlag>%d</FuncFlag>
			 </xml>";
		$resultStr = sprintf($textTpl, $toUsername,$fromUsername, $time, $response['MsgType'],
			 $response['Title'],$response['Description'],$response['MusicUrl'],$response['HQMusicUrl'], $response['FuncFlag']);
		echo $resultStr;
    }
    
    private function responseNewsMsg($toUsername,$fromUsername,$response)
    {
        $time = time();
		$textTpl_Head = "<xml>
			 <ToUserName><![CDATA[%s]]></ToUserName>
			 <FromUserName><![CDATA[%s]]></FromUserName>
			 <CreateTime>%s</CreateTime>
			 <MsgType><![CDATA[%s]]></MsgType>
			 <ArticleCount>%d</ArticleCount>
			 <Articles>";
		$textTpl_Body = "
			 <item>
			 <Title><![CDATA[%s]]></Title> 
			 <Description><![CDATA[%s]]></Description>
			 <PicUrl><![CDATA[%s]]></PicUrl>
			 <Url><![CDATA[%s]]></Url>
			 </item>";
		$textTpl_Foot = "</Articles>
			 <FuncFlag>%d</FuncFlag>
			 </xml>";
		
		$resultStr = sprintf($textTpl_Head, $toUsername,$fromUsername, $time, $response['MsgType'], $response['ArticleCount']);
		foreach($response['Articles'] as $article){
			$resultStr .= sprintf($textTpl_Body,  $article['Title'],$article['Description'],$article['PicUrl'],$article['Url']);
		}
		$resultStr .= sprintf($textTpl_Foot, $response['FuncFlag']);
		echo $resultStr;
    }
	
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	

		$token = WECHAT_TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}
