<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;
use Carbon\Carbon;

class GenerateJellyfinArticles extends Command
{
    protected $signature = 'articles:generate-jellyfin {count=50}';
    protected $description = 'Generate sample Jellyfin news articles';

    protected $categories = [
        'Feature Updates',
        'Community News',
        'Plugin Releases',
        'Server Administration',
        'Client Apps',
        'Performance',
        'User Guides',
        'Development',
        'Media Management'
    ];

    protected $authors = [
        'Alex Chen - Jellyfin Contributor',
        'Maria Garcia - Plugin Developer',
        'Sam Wilson - Community Manager',
        'Jessica Park - Documentation Writer',
        'David Kumar - Server Expert'
    ];

    // Jellyfin-specific word banks
    protected $features = [
        'Hardware Acceleration',
        'Live TV & DVR',
        'User Management',
        'Subtitle Support',
        'Metadata Scraping',
        'Watch History',
        'Transcoding',
        'Collections',
        'Playback Reporting',
        'Smart Home Integration'
    ];

    protected $clients = [
        'Android TV',
        'iOS App',
        'Android Mobile',
        'Web Client',
        'Roku',
        'Fire TV',
        'Kodi',
        'MPV Shim',
        'Windows App',
        'macOS Client'
    ];

    protected $plugins = [
        'Intro Skip',
        'Anime Metadata',
        'Fanart',
        'Theme Songs',
        'OpenSubtitles',
        'Playback Reporting',
        'Auto Organize',
        'Merge Versions',
        'Trakt Integration',
        'TMDb Metadata'
    ];

    public function handle()
    {
        $faker = Faker::create();
        $count = $this->argument('count');

        if (!Storage::exists('articles')) {
            Storage::makeDirectory('articles');
        }

        $this->info("Generating {$count} Jellyfin articles...");
        $bar = $this->output->createProgressBar($count);

        for ($i = 0; $i < $count; $i++) {
            $date = Carbon::now()->subDays(rand(0, 365))->format('Y-m-d');
            $title = $this->generateJellyfinTitle($faker);
            $content = $this->generateJellyfinContent($faker);
            
            $markdown = <<<EOD
---
title: {$title}
author: {$faker->randomElement($this->authors)}
category: {$faker->randomElement($this->categories)}
date: {$date}
---

{$content}
EOD;

            $filename = str($title)->slug() . '.md';
            Storage::put('articles/' . $filename, $markdown);
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Jellyfin articles generated successfully!');
    }

    protected function generateJellyfinTitle($faker)
    {
        $templates = [
            "New Release: Jellyfin " . $faker->semver . " Brings " . $faker->randomElement($this->features),
            $faker->randomElement($this->plugins) . " Plugin Updated with New Features",
            "Community Spotlight: " . $faker->randomElement($this->clients) . " Improvements",
            "Guide: Optimizing " . $faker->randomElement($this->features) . " in Jellyfin",
            "Jellyfin for " . $faker->randomElement($this->clients) . " Gets Major Update",
            "How to Set Up " . $faker->randomElement($this->features) . " in Jellyfin",
            "Performance Boost: " . $faker->randomElement($this->features) . " Optimization",
            "Community Update: " . $faker->date('F Y') . " Development Progress"
        ];
        
        return $faker->randomElement($templates);
    }

    protected function generateJellyfinContent($faker)
    {
        $paragraphs = [];
        
        // Lead paragraph
        $feature = $faker->randomElement($this->features);
        $paragraphs[] = "The Jellyfin media server continues to evolve with exciting improvements to {$feature}. " . 
            "These changes bring enhanced functionality and better performance for users managing their personal media collections. " .
            $faker->paragraph(2);
        
        // Community quote
        $roles = ['Core Developer', 'Plugin Maintainer', 'Community Contributor', 'Documentation Manager'];
        $paragraphs[] = sprintf(
            '> "%s," says %s, %s in the Jellyfin community.',
            $this->generateJellyfinQuote($faker),
            $faker->name,
            $faker->randomElement($roles)
        );
        
        // Technical details
        $paragraphs[] = "## Implementation Details\n\n" . 
            "The improvements to " . $feature . " include several key changes:\n\n" .
            "* Enhanced support for " . $faker->randomElement($this->clients) . "\n" .
            "* Improved integration with " . $faker->randomElement($this->plugins) . " plugin\n" .
            "* Better handling of " . strtolower($faker->randomElement($this->features)) . "\n" .
            "* Optimized performance for large media libraries\n\n" .
            $faker->paragraph(2);
        
        // User impact
        $paragraphs[] = "## User Benefits\n\n" . 
            "Users will notice significant improvements when using " . 
            $faker->randomElement($this->clients) . ". " . 
            $faker->paragraph(2);
        
        // Setup or configuration
        $paragraphs[] = "## Configuration\n\n" .
            "To take advantage of these new features, users should:\n\n" .
            "1. Update to the latest version\n" .
            "2. Configure " . strtolower($faker->randomElement($this->features)) . " settings\n" .
            "3. Review their " . strtolower($faker->randomElement($this->features)) . " configuration\n\n" .
            $faker->paragraph(2);
        
        return implode("\n\n", $paragraphs);
    }

    protected function generateJellyfinQuote($faker)
    {
        $templates = [
            "These improvements to " . $faker->randomElement($this->features) . " show our commitment to user experience",
            "The community's work on " . $faker->randomElement($this->clients) . " has been outstanding",
            "We're excited about the potential of " . $faker->randomElement($this->plugins) . " plugin integration",
            "The future of personal media streaming is being shaped by improvements like " . $faker->randomElement($this->features),
            "Our focus on " . $faker->randomElement($this->features) . " reflects user feedback and needs"
        ];
        
        return $faker->randomElement($templates) . ". " . $faker->sentence(6);
    }
}
