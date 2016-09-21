<?php
namespace App\Http\Controllers;

use Goutte\Client;
use App\Search;

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
		
		$pagina_inicio = $client->request('GET', 'http://pbproxy.maik.rocks/s/?q='.$search.'&page=0&orderby=99');
		
		$json = [];
		
		$table = $pagina_inicio->filterXPath('//table/tr');
		$row_count=0;
		
		$rows =[];
		//filas
		foreach ($table as $i => $tr){
				
			$type="";
			$name="";
			$link="";
			$magnet="";
			$seed="";
			$leech="";
				
			$row_count++;
			//thread, tr -> names
			$tds = array();
				
			$ncol=0;
			//columnas
			foreach ($tr->childNodes as $i => $node) {
				// extract the value
				if($ncol==0){
					$type =  str_replace (array("\t","\n"), "",trim ( $node->nodeValue ,"\t\n\r\0\x0B"  ));
				}elseif ($ncol==2){
						
					$name=str_replace (array("\t","\n"), "",trim ($node->nodeValue,"\t\n\r\0\x0B"  ));
					$link=$node->getElementsByTagName ( 'a' )->item(0)->getAttribute('href');
					$magnet=$node->getElementsByTagName ( 'a' )->item(1)->getAttribute('href');
						
				}elseif ($ncol==4){
					$seed=$node->nodeValue;
				}elseif ($ncol==6){
					$leech=$node->nodeValue;
				}
				$ncol++;
		
			}
		
			$rows[] = ['row'=> [
					'type'=> $type,
					'name'=>$name,
					'link'=>$link,
					'magnet'=>$magnet,
					'seeders'=>$seed,
					'leechers'=>$leech
			]];
		}
		
		
		return response()->json($rows);
	}
}
