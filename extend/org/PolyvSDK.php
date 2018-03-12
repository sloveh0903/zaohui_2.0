<?php
class PolyvSDK {
		
	private $_readtoken;				
	private $_writetoken;
	private $_privatekey;
	private $_sign;
	
	function __construct() {
		$this->_readtoken 		= "9f224fa0-469f-48d5-af54-2c36945bbb76";
		$this->_writetoken 	= "65eaef06-5873-4c77-a557-fedd9e1a913c";
		$this->_privatekey = "RcJed1awOK";
		$this->_sign = true;//提交参数是否需要签名
	}
	private function sanitize_for_xml($input) {
		
	  $output = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', '', $input);
	  
	  return $output;
	}
	private function _processXmlResponse($url, $xml = ''){

		if (extension_loaded('curl')) {
			$ch = curl_init() or die ( curl_error() );
			$timeout = 10;
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);	
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			if(!empty($xml)){
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                       'Content-type: application/xml',
                                       'Content-length: ' . strlen($xml)
                                     ));
			}
			$data = curl_exec( $ch );
			curl_close( $ch );

			if($data){
				return (new SimpleXMLElement($this->sanitize_for_xml($data)));
			}else{
				return false;
			}
		}
		if(!empty($xml))
			throw new Exception('Set xml, but curl does not installed.');

		return (simplexml_load_file($url));	
	}
	private function makeVideo($video){
		return array(
					'vid' => (string)$video->vid, 
					'hlsIndex' => (string)$video->hlsIndex, 
					'swf_link' => (string)$video->swf_link, 
					'ptime' => (string)$video->ptime, 
					'status' => (int) $video->status, 
					'df' => (int) $video->df, 
					'first_image' => (string)$video->first_image, 
					'title' => (string)$video->title, 
					'desc' => (string)$video->context, 
					'duration' => (string)$video->duration, 
					'flv1' => (string)$video->flv1, 
					'flv2' => (string)$video->flv2, 
					'flv3' => (string)$video->flv3, 
					'mp4_1' => (string)$video->mp4_1,
					'mp4_2' => (string)$video->mp4_2,
					'mp4_3' => (string)$video->mp4_3,					
					'hls_1' => (string)$video->hls_1,
					'hls_2' => (string)$video->hls_2,
					'hls_3' => (string)$video->hls_3,
					'seed' => (string)$video->seed
					);
	}
			
	public function getById($vid) {
		
	    $xml = "";
		if($this->_sign){
			$hash = sha1('readtoken='.$this->_readtoken.'&vid='.$vid.$this->_privatekey);
		}
		//echo "-->".'http://beta.polyv.net/uc/services/rest?readtoken='.$this->_readtoken.'&method=getById&vid='.$vid.'&format=xml&sign='.$hash;
		$xml = $this->_processXmlResponse('http://v.polyv.net/uc/services/rest?readtoken='.$this->_readtoken.'&method=getById&vid='.$vid.'&format=xml&sign='.$hash, $xml);
		if($xml) {
			if($xml->error=='0')
					return $this->makeVideo($xml->data->video);
			else
                    return false;
				/*return array(
					'returncode' => $xml->error
					);*/
		}
		else {
			return false;
		}
		
	}
	
					
	/**
	 * 通过视频标题获取信息
	 */
	public function searchByTitle($keyword,$pageNum,$numPerPage) {
		if($this->_sign){
			$hash = sha1('keyword='.$keyword.'&numPerPage='.$numPerPage.'&pageNum='.$pageNum.'&readtoken='.$this->_readtoken.$this->_privatekey);
		}
		//echo 'http://v.polyv.net/uc/services/rest?readtoken='.$this->_readtoken.'&method=searchByTitle&keyword='.$keyword.'&pageNum='.$pageNum.'&numPerPage='.$numPerPage.'&format=xml&sign='.$hash;
		$xml = $this->_processXmlResponse('http://v.polyv.net/uc/services/rest?readtoken='.$this->_readtoken.'&method=searchByTitle&keyword='.$keyword.'&pageNum='.$pageNum.'&numPerPage='.$numPerPage.'&format=xml&sign='.$hash, $xml);
		if($xml) {
			if($xml->error=='0') {
				foreach ($xml->data->video as $video){ 
					
					$videodata = $this->makeVideo($video);
					$result[$num] =$videodata;
					$num++;
				}
				return $result;
			}else{
				return array(
					'returncode' => $xml->error
					);
			}
		}
		else {
			return null;
		}
		
	}
	
	public function uploadfile($title,$desc,$tag,$cataid,$filename) {

		$JSONRPC = '{"title":"'.$title.'","tag":"'.$tag.'","desc":"'.$desc.'"}';
				
		if($this->_sign){
			$hash = sha1('cataid='.$cataid.'&JSONRPC='.$JSONRPC.'&writetoken='.$this->_writetoken.$this->_privatekey);
		}
		//echo 'cataid='.$cataid.'&JSONRPC='.$JSONRPC.'&writetoken='.$this->_writetoken.$this->_privatekey.' hash:'.$hash;


        if (extension_loaded('curl')) {
			$ch = curl_init() or die ( curl_error() );
			$timeout = 360;

			$post = array(
				'JSONRPC' => $JSONRPC,
				'cataid'=>$cataid,
				'writetoken'=>$this->_writetoken,
				'sign'=>$hash,
				'format'=>'xml',
				'Filedata'=>$filename

            );


			curl_setopt( $ch, CURLOPT_URL, "http://v.polyv.net/uc/services/rest?method=uploadfile" );
			curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

			$data = curl_exec( $ch );

			curl_close( $ch );


			if($data){
				$xml = (new SimpleXMLElement($data));
				if($xml) {
					if($xml->error=='0') 
							return $this->makeVideo($xml->data->video);
					else
					    return false;
						/*return array(
							'returncode' => $xml->error
							);*/
				}
				else {
					return false;
				}
				
			}
			else{
				return false;
			}
		}
		
			
			
		
		
	}
	public function getCata($cataid) {
		$xml = ''; 
		$num = 0;
		if($this->_sign){
			$hash = sha1('cataid='.$cataid.'&readtoken='.$this->_readtoken.$this->_privatekey);
		}
		$xml = $this->_processXmlResponse('http://v.polyv.net/uc/services/rest?readtoken='.$this->_readtoken.'&format=xml&method=getCata&sign='.$hash, $xml);
		if($xml) {
			if($xml->error=='0') {
				foreach ($xml->data->video as $video){ 
					$videodata = array(
					'cataid' => $video->cataid, 
					'articles' => $video->articles, 
					'cataname' => $video->cataname
					);
					
					
					$result[$num] =$videodata;
					$num++;
				}
				return $result;
			}else{
				return array(
					'returncode' => $xml->error
					);
			}
		}
		else {
			return null;
		}
		
	}
	public function getNewList($pageNum,$numPerPage,$catatree) {
	
		$xml = ''; 
		$num = 0;
		if($this->_sign){
			$hash = sha1('catatree='.$catatree.'&numPerPage='.$numPerPage.'&pageNum='.$pageNum.'&readtoken='.$this->_readtoken.$this->_privatekey);
		}
		$xml = $this->_processXmlResponse('http://v.polyv.net/uc/services/rest?readtoken='.$this->_readtoken.'&method=getNewList&pageNum='.$pageNum.'&format=xml&numPerPage='.$numPerPage.'&catatree='.$catatree.'&sign='.$hash, $xml);
		if($xml) {
			if($xml->error=='0') {
				foreach ($xml->data->video as $video){ 
					
					$videodata = $this->makeVideo($video);
					$result[$num] =$videodata;
					$num++;
				}
				return $result;
			}else{
				return array(
					'returncode' => $xml->error
					);
			}
		}
		else {
			return null;
		}
		
	}
}
