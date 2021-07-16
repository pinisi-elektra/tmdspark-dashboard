<?php namespace App\Http\Controllers;

        use Session;
        use Request;
        use DB;
        use CRUDBooster;

        class ApiTestController extends \crocodicstudio\crudbooster\controllers\ApiController
        {
            public function __construct()
            {
                $this->table       = "test";
                $this->permalink   = "test";
                $this->method_type = "post";
            }
        

            public function hook_before(&$postdata)
            {
                //This method will be execute before run the main process
                $new = [
					'test' => json_encode(request()->all())
				];
                DB::table($this->table)->insert($new);

                response()->json([
                    'success' => true,
                    'message' => 'Sukses'
                ])->send();
                die;
            }

            public function hook_query(&$query)
            {
                //This method is to customize the sql query
            }

            public function hook_after($postdata, &$result)
            {
                //This method will be execute after run the main process
            }
        }
