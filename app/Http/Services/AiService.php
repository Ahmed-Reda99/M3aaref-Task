<?php

namespace App\Http\Services;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Log;

class AiService
{
    public function generateTitles($category)
    {
        // work can be done in a background job for better performance and user experience but no need here
        try {
            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')->generateContent("Generate 10-20 course titles related to this category: $category in Arabic, separated by commas and if you can't generate for this category just say 'illegal category'");

            $result->text(); // Course1, Course2, Course3, ...

            return explode(', ', $result->text());
        } catch (\Throwable $th) {
            Log::error('Error generating titles: ' . $th->getMessage());
            throw $th;
        }
    }
}
