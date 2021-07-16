<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetLotsController extends \crocodicstudio\crudbooster\controllers\ApiController {
			protected $lots = [
				"type" => "FeatureCollection"
			];
			
			function __construct() {
				$this->table       = "lots";
				$this->permalink   = "get_lots";
				$this->method_type = "get";
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
				$query->where('geojson', '<>', null);

				// Lot::select('id', 'name', 'address', 'geojson', 'status')->where('geojson', '<>', null)->get()
		    }

		    public function hook_after($postdata,&$result) {
				$result['data']->map(function($q){
					$geo = json_decode($q->geojson);
					foreach($geo->features as $key => $f) {
						$f->properties->id = $q->id;
						$f->properties->name = $q->name;
						$f->properties->address = $q->address;
						$f->properties->status = $q->status;
						$this->lots["features"][] = $f;
					}
				}); 

				return $result['data'] = $this->lots;
		    }

		}