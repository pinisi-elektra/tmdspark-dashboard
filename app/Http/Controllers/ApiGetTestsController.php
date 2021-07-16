<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetTestsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {
				$this->table       = "test";
				$this->permalink   = "get_tests";
				$this->method_type = "get";
		    }
		
			function reverseBytes($input) {
				$result = '';
				// first byte
				$result .= substr($input, 6, 2);
		
				// second byte
				$result .= substr($input, 4, 2);
		
				// third byte
				$result .= substr($input, 2, 2);
		
				// fourth byte
				$result .= substr($input, 0, 2);
		
				return $result;
			}

			public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
				$results = DB::table("test")->orderBy('created_at', 'asc')->get()
                ->map(function($q){
                    $datas = json_decode($q->test);
                    unset($q->test);
                    foreach ($datas as $key => $data) {
                        $q->{$key} .= $data;
                    }

                    try {
                        $payload = $q->data;
                        $lat = substr($payload, 4, 8);
                        $lng = substr($payload, 12, 8);
                        
                        $binarydata32 = pack('H*',$this->reverseBytes($lat));
                        $floatLat = unpack("f", $binarydata32)[1];
        
                        $binarydata32 = pack('H*',$this->reverseBytes($lng));
                        $floatLng = unpack("f", $binarydata32)[1];
                        
                        $q->lat = $floatLat;
                        $q->lng = $floatLng;

						if($q->lat == 0) {
							unset($q);
						}
						
                    }
                    catch(\Exception $e) {
                        unset($q);
                    }

                    return $q;
                })->filter(function ($value, $key) {
					return $value != null;
				})->groupBy('deviceId')->map(function($q) {
					return $q->reverse();
				})->all();

				return response()->json($results)->send();
				exit;
		    }

		    public function hook_query(&$query) {
		        
		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
				
		    }

		}