<?php
namespace App\Http\Controllers;

use Goutte\Client;
use App\Search;
use Illuminate\Http\Request;
use App\Download;

use Exception;

class PBController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	public function getIndex(){
		
		
		return $this->getSearch("orange");
	}
	
	
	// https://developer.yahoo.com/yql/console/?q=select%20*%20from%20html%20where%20url%3D%22https%3A%2F%2Fthepiratebay.se%2Fsearch%2Fubuntu%2F0%2F7%2F0%22%20and%0A%20%20%20%20%20%20xpath%3D%27%2F%2Ftr%27
	public function getSearch($search){
		
		$value = Search::firstOrNew(['word' => $search]);
		$value->counter = $value->counter +1;
		$value->save();
		
		$client = new Client();
		//100 -audio , 200 -video, 300 -Applications, 400 -Games, 500 -porn
		//search/orange/0/7/100,200,300,400,600
		//'https://thepiratebay.org/search/'.$search.'/0/7/0'
		
		//'http://pbproxy.maik.rocks/s/?q='.$search.'&page=0&orderby=99'
		
		$pagina_inicio = $client->request('GET', 'https://thepiratebay.org/search/'.$search.'/0/7/0');
		
		$json = [];
		
		$table = $pagina_inicio->filterXPath('//table/tr');
		$row_count=0;
		
		$rows =[];
		//filas
		
		$type="";
		$name="";
		$link="";
		$magnet="https://thepiratebay.org/";
		$seed="";
		$leech="";
		$categoryA="";
		$categoryB="";
		$categoryA_link="";
		$categoryB_link="";
		$torrent_name ="Sorry! We are Out of Service. Try Again in a few minutes.";
		$details ="";
		
		foreach ($table as $i => $tr){
			
			$row_count++;
			//thread, tr -> names
			$tds = array();
				
			$ncol=0;
			//columnas
			foreach ($tr->childNodes as $i => $node) {
				
					// extract the value
					if($ncol==0){
						
						$type =  str_replace (array("\t","\n"), "",trim ( $node->nodeValue ,"\t\n\r\0\x0B"  ));
						
						if(null !== ($node->getElementsByTagName ( 'a' )->item(0))){
							$categoryA_link=$node->getElementsByTagName ( 'a' )->item(0)->getAttribute('href');
							$categoryA=$node->getElementsByTagName ( 'a' )->item(0)->nodeValue;
						}
						if(null !== ($node->getElementsByTagName ( 'a' )->item(1))){
							$categoryB_link=$node->getElementsByTagName ( 'a' )->item(1)->getAttribute('href');
							$categoryB=$node->getElementsByTagName ( 'a' )->item(1)->nodeValue;
						}
						
					}elseif ($ncol==2){
							
						//$name=str_replace (array("\t","\n"), "",trim ($node->nodeValue,"\t\n\r\0\x0B"  ));
						$link=$node->getElementsByTagName ( 'a' )->item(0)->getAttribute('href');
						$magnet=$node->getElementsByTagName ( 'a' )->item(1)->getAttribute('href');
						
						$torrent_name=str_replace (array("\t","\n"), "", $node->getElementsByTagName ( 'div' )->item(0)->nodeValue);
						$details=$node->getElementsByTagName ( 'font' )->item(0)->nodeValue;
	
						$name = $torrent_name . " - " . $details;
						
					}elseif ($ncol==4){
						$seed=$node->nodeValue;
					}elseif ($ncol==6){
						$leech=$node->nodeValue;
					}
					$ncol++;
				
			}
		
			$rows[] = ['row'=> [
					'torrent_name'=> $torrent_name,
					'details'=> $details,
					'type'=> $type,
					'name'=>$name,
					'link'=>$link,
					'magnet'=>$magnet,
					'seeders'=>$seed,
					'leechers'=>$leech,
					'category_A'=>$categoryA,
					'category_A_link'=>$categoryA_link,
					'category_B'=>$categoryB,					
					'category_B_link'=>$categoryB_link
			]];
		}
		
		
		return response()->json($rows);
	}
	
	public function setMagnet(Request $request){
		$tmp = $request->row;
		
		$download = Download::create([
				'torrent_name'=> $tmp['torrent_name'],
				'details'=> $tmp['details'],
				'type'=> $tmp['type'],
				'name'=>$tmp['name'],
				'link'=>$tmp['link'],
				'magnet'=>$tmp['magnet'],
				'seeders'=>$tmp['seeders'],
				'leechers'=>$tmp['leechers'],
				'category_A'=>$tmp['category_A'],
				'category_A_link'=>$tmp['category_A_link'],
				'category_B'=>$tmp['category_B'],					
				'category_B_link'=>$tmp['category_B_link']
				
			]);
		
		return response()->json($tmp['torrent_name']);
	}
// 	public function getImage($word){
// 		//get the word submitted from the form
// 		//$word = "universo";
// 		$img_pattern = "#<img src=http\S* width=[0-9]* height=[0-9]*>#";
// 		// validate the word
// 		if ($word != '') {
// 			// initialise the session
// 			$ch = curl_init();
// 			// Set the URL
// 			curl_setopt($ch, CURLOPT_URL, "http://images.google.com/images?gbv=1&hl=en&sa=1&q=".urlencode($word)."&btnG=Search+images");
// 			// Return the output from the cURL session rather than displaying in the browser.
// 			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 			//Execute the session, returning the results to $curlout, and close.
// 			$curlout = curl_exec($ch);
// 			curl_close($ch);
// 			preg_match_all($img_pattern, $curlout, $img_tags);
// 			//display the results - I'll leave the formatting to you
// 			print("Resultado de la busqueda $word: ".sizeof($img_tags[0])."<br/>\n");
// 			foreach ($img_tags[0] as $val){
// 				print(" ".$val."\n");
// 			}
// 		}
// 	}
}
