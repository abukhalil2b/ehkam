<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

class DocumentationController extends Controller
{
    protected $docsPath;

    public function __construct()
    {
        $this->docsPath = base_path('docs');
    }

    /**
     * Display a listing of all documentation files
     */
    public function index()
    {
        $docs = [];

        if (!is_dir($this->docsPath)) {
            return view('docs.index', ['docs' => $docs]);
        }

        $files = glob($this->docsPath . '/*.md');

        foreach ($files as $file) {
            $filename = basename($file);
            $slug = Str::slug(pathinfo($filename, PATHINFO_FILENAME));

            // Read first heading as title
            $content = file_get_contents($file);
            preg_match('/^#\s+(.+)$/m', $content, $matches);
            $title = $matches[1] ?? pathinfo($filename, PATHINFO_FILENAME);

            // Get first paragraph as description
            preg_match('/^(?!#)(.+)$/m', $content, $descMatches);
            $description = isset($descMatches[1]) ? Str::limit($descMatches[1], 150) : '';

            $docs[] = [
                'slug' => $slug,
                'title' => $title,
                'description' => $description,
                'filename' => $filename,
                'modified' => filemtime($file),
            ];
        }

        // Sort by modification time (newest first)
        usort($docs, function ($a, $b) {
            return $b['modified'] - $a['modified'];
        });

        return view('docs.index', compact('docs'));
    }

    /**
     * Display a specific documentation file
     */
    public function show($slug)
    {
        // Security: validate slug to prevent directory traversal
        if (!preg_match('/^[a-z0-9\-_]+$/', $slug)) {
            abort(404);
        }

        // Find the file
        $files = glob($this->docsPath . '/*.md');
        $filePath = null;

        foreach ($files as $file) {
            $fileSlug = Str::slug(pathinfo(basename($file), PATHINFO_FILENAME));
            if ($fileSlug === $slug) {
                $filePath = $file;
                break;
            }
        }

        if (!$filePath || !file_exists($filePath)) {
            abort(404, 'Documentation not found');
        }

        // Read and parse markdown
        $markdown = file_get_contents($filePath);

        // Configure CommonMark with GitHub Flavored Markdown
        $environment = new Environment([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        $converter = new MarkdownConverter($environment);
        $html = $converter->convert($markdown)->getContent();

        // Extract title from first heading
        preg_match('/^#\s+(.+)$/m', $markdown, $matches);
        $title = $matches[1] ?? 'Documentation';

        // Generate table of contents
        $toc = $this->generateTableOfContents($markdown);

        // Get all docs for navigation
        $allDocs = $this->getAllDocs();

        return view('docs.show', compact('html', 'title', 'slug', 'toc', 'allDocs'));
    }

    /**
     * Generate table of contents from markdown headings
     */
    protected function generateTableOfContents($markdown)
    {
        $toc = [];
        preg_match_all('/^(#{2,4})\s+(.+)$/m', $markdown, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $level = strlen($match[1]) - 1; // h2=1, h3=2, h4=3
            $text = $match[2];
            $id = Str::slug($text);

            $toc[] = [
                'level' => $level,
                'text' => $text,
                'id' => $id,
            ];
        }

        return $toc;
    }

    /**
     * Get all documentation files for navigation
     */
    protected function getAllDocs()
    {
        $docs = [];
        $files = glob($this->docsPath . '/*.md');

        foreach ($files as $file) {
            $filename = basename($file);
            $slug = Str::slug(pathinfo($filename, PATHINFO_FILENAME));

            $content = file_get_contents($file);
            preg_match('/^#\s+(.+)$/m', $content, $matches);
            $title = $matches[1] ?? pathinfo($filename, PATHINFO_FILENAME);

            $docs[] = [
                'slug' => $slug,
                'title' => $title,
            ];
        }

        return $docs;
    }
}
