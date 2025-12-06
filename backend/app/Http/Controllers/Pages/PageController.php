<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PageController extends Controller
{

    use ApiResponse;

    public function addPage(Request $request)
    {
        $isValid =  $request->validate(["page_name" => 'required|string']);

        if (!$isValid) {
            return $this->errorResponse("page_name is required", 400);
        }

        $page = Page::create(['page_name' => $request->page_name]);

        return $this->successResponse($page, "Successfully Added Page", 200);
    }

    public function getPage(Request $request, $id = null)
    {
        $page = $id ? Page::findOrFail($id) : Page::all();

        return $this->successResponse($page, "Get Page Successfully", 200);
    }

    public function deletePage(Request $request, $id)
    {

        if (!$id) {
            return $this->errorResponse("id required", 401);
        }

        $page = Page::where('id',$id) -> delete();

        return $this -> successMessage("Successfully Deleted", 200);
    }
}
