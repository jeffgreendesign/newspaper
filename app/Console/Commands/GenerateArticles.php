<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Faker\Factory as Faker;
use Carbon\Carbon;

class GenerateArticles extends Command
{
    protected $signature = 'articles:generate {count=50}';
    protected $description = 'Generate sample markdown articles';

    // Define categories that make sense for a newspaper
    protected $categories = [
        'Politics',
        'Technology',
        'Business',
        'Science',
        'Health',
        'Arts',
        'Sports',
        'Opinion',
        'World News'
    ];

    // Define authors for more realistic content
    protected $authors = [
        'Sarah Johnson',
        'Michael Chen',
        'Emma Williams',
        'James Rodriguez',
        'Rachel Thompson',
        'David Kim',
        'Maria Garcia',
        'Ahmed Hassan'
    ];

    public function handle()
    {
        $faker = Faker::create();
        $count = $this->argument('count');

        // Create the articles directory if it doesn't exist
        $directory = storage_path('articles');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $this->info("Generating {$count} articles...");
        $bar = $this->output->createProgressBar($count);

        for ($i = 0; $i < $count; $i++) {
            // Generate a realistic publication date within the last year
            $date = Carbon::now()->subDays(rand(0, 365))->format('Y-m-d');

            // Create an engaging headline
            $title = $this->generateNewsTitle($faker);

            // Generate the article content
            $content = $this->generateArticleContent($faker);

            // Create the markdown file with YAML front matter
            $markdown = <<<EOD
---
title: {$title}
author: {$faker->randomElement($this->authors)}
category: {$faker->randomElement($this->categories)}
date: {$date}
---

{$content}
EOD;

            // Create a slug-based filename
            $filename = str($title)->slug() . '.md';
            File::put($directory . '/' . $filename, $markdown);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Articles generated successfully!');
    }

    protected function generateNewsTitle($faker)
    {
        // Create more newspaper-like headlines
        $templates = [
            "Breaking: " . $faker->sentence(rand(4, 6)),
            $faker->sentence(rand(5, 8)),
            $faker->words(rand(4, 7), true),
            "New Study Shows " . $faker->sentence(rand(4, 6)),
            "Exclusive: " . $faker->sentence(rand(4, 6)),
            $faker->catchPhrase() . ": " . $faker->words(rand(3, 5), true)
        ];

        return $faker->randomElement($templates);
    }

    protected function generateArticleContent($faker)
    {
        // Create a structured article with paragraphs and quotes
        $paragraphs = [];

        // Lead paragraph
        $paragraphs[] = $faker->paragraph(rand(3, 5));

        // Add a quote
        $paragraphs[] = sprintf(
            '> "%s," says %s, %s.',
            $faker->sentence(rand(10, 15)),
            $faker->name,
            $faker->jobTitle
        );

        // Body paragraphs
        for ($i = 0; $i < rand(3, 6); $i++) {
            $paragraphs[] = $faker->paragraph(rand(4, 8));
        }

        // Add some subheadings and lists for variety
        $subheading = "## " . $faker->sentence(rand(3, 5));
        array_splice($paragraphs, 2, 0, $subheading);

        return implode("\n\n", $paragraphs);
    }
}