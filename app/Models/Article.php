<?php

namespace App\Models;

use Illuminate\Support\Facades\File;
use League\CommonMark\CommonMarkConverter;

class Article
{
    public static function all()
    {
        $files = File::files(storage_path('articles'));
        return collect($files)->map(fn($file) => self::parse($file->getPathname()))->sortByDesc('date');
    }

    public static function find($slug)
    {
        $path = storage_path("articles/{$slug}.md");
        return File::exists($path) ? self::parse($path) : null;
    }

    private static function parse($path)
    {
        $filename = pathinfo($path, PATHINFO_FILENAME); // Get filename without extension
        $content = File::get($path);
        [$meta, $body] = explode("---\n", $content, 2);

        preg_match_all('/(.*?):\s*(.*)/', $meta, $matches);
        $metadata = array_combine($matches[1], $matches[2]);

        // Ensure required fields exist
        $metadata = array_merge([
            'title' => ucfirst(str_replace('-', ' ', $filename)), // Use filename if title is missing
            'date' => now()->toDateString(),
            'category' => 'Uncategorized',
        ], $metadata);

        $metadata['slug'] = strtolower(str_replace(' ', '-', $metadata['title'])); // Generate slug

        $converter = new CommonMarkConverter();
        $metadata['content'] = $converter->convert($body);

        return (object) $metadata;
    }


}
