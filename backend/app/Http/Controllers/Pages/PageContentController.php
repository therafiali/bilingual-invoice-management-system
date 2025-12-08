<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PageContentController extends Controller
{
    use ApiResponse;

    public function addPageContent(Request $request)
    {

        $request->validate([
            "page_id" => 'required|integer|exists:pages,id',
            "category" => "required|in:heading,subheading,description",
            "text" => 'required|string',
            "lang" => 'boolean',
        ]);

        $page = PageContent::create([
            'page_id' => $request->page_id,
            'category' => $request->category,
            'lang' => $request->lang,
            'text' => $request->text,
            'media_links' => $request->media_links,
        ]);

        return $this->successResponse($page, "Successfully add page content", 201);
    }

    public function updatePageContent(Request $request, $id)
    {
        if (!$id) {
            return $this->errorResponse("id required", 401);
        }

        $validated =   $request->validate([
            'page_id' => 'sometimes|integer|exists:pages,id',
            'category' => 'sometimes|in:heading,subheading,description',
            'lang' => 'sometimes|boolean',
            'text' => 'sometimes|string',
            'media_links' => 'nullable|array',
            'media_links.*' => 'url'
        ]);

        $pageContent = PageContent::find($id);

        if (!$pageContent) {
            return $this->errorResponse("Page content not found", 404);
        }

        $pageContent->update($validated);

        return $this->successResponse($pageContent, "Successfully update page content", 200);
    }

    public function deletePageContent(Request $request, $id)
    {

        if (!$id) {
            return $this->errorResponse("id required", 401);
        }

        $pageContent = PageContent::find($id);

        if (!$pageContent) {
            return $this->errorResponse("Page content not found", 404);
        }

        $pageContent->delete();

        return $this->successMessage("Successfully deleted page content", 200);
    }

    public function getPageContent(Request $request, $id)
    {

        if (!$id) {
            return $this->errorResponse("id required", 401);
        }

        $pageContent = PageContent::find($id);

        if (!$pageContent) {
            return $this->errorResponse("Page content not found", 404);
        }

        return $this->successResponse($pageContent, "Succuessfully Get data", 200);
    }
}
