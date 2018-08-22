<?php 
namespace App\Traits;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
trait ApiResponser
{
	private function successResponse($data,$code)
	{
		return response()->json($data);
	}

	protected function errorResponse($message,$code)
	{
		return response()->json(['error' => $message, 'code' => $code], $code);
	}


	protected function showAllResponse(Collection $collection, $code = 200)
	{
		return $this->successResponse(['data'=> $collection], $code);
	}


	protected function showOneResponse(Model $instance, $code = 200)
	{
		return $this->successResponse(['data'=> $instance], $code);
	}


	protected function showMessage($message, $code=200)
	{
		return $this->successResponse(['data'=> $message], $code);

	}
}



 ?>