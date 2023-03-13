<?php
namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;// 必須トレイト
use Illuminate\Foundation\Bus\DispatchesJobs;// 必須トレイト
use Illuminate\Foundation\Validation\ValidatesRequests;// 必須トレイト
use Illuminate\Routing\Controller as BaseController;
class Controller extends BaseController{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	// Global variables
	public $PROJECT_NAME   = 'DMS';
	public $COUNT_PAR_PAGE = 5;
	// Common function
	// public function sendSlackMsg($msg){
	// 	$ret	= '';
	// 	$query	= User::select('slackid');
	// 	$query->where('kind', 0 );
	// 	$query->groupBy('slackid');
	// 	$urls	= $query->get();
	// 	foreach($urls as $obj) {
	// 		$url	= $obj->slackid;
	// 		$ret	= $this->send_webhook( $msg, $url );
	// 		\Log::debug("Slack送信 send_webhook ret={$ret} url={$url} msg={$msg}");
	// 	}
	// 	return	$ret;
	// }
	// public function send_webhook($msg,$URI){
	// 	$message = [
	//   //  "channel" => "#slack-test",
	// 	    "text" => $msg,
	// 	];
	// 	$ch = curl_init();
	// 	$options = [
	// 	    CURLOPT_URL => $URI,
	// 	    CURLOPT_RETURNTRANSFER => true,
	// 	    CURLOPT_SSL_VERIFYPEER => false,
	// 	    CURLOPT_POST => true,
	// 	    CURLOPT_POSTFIELDS => http_build_query([
	// 	        'payload' => json_encode($message)
	// 	    ])
	// 	];
	// 	curl_setopt_array($ch, $options);
	// 	$result = curl_exec($ch);
	// 	curl_close($ch);
	// 	return $result;
	// }
}/* EOF */
