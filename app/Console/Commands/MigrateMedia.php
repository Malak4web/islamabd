<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\Service;
use App\Models\Section;
use Illuminate\Console\Command;

class MigrateMedia extends Command
{
    protected $signature = 'media:migrate {--force : Force re-download even if media exists}';
    protected $description = 'Download external images from WordPress and Unsplash and attach to models via MediaLibrary';

    public function handle()
    {
        $this->info('Starting media migration...');

        $this->migrateServices();
        $this->migrateProjects();
        $this->migrateSections();

        $this->info('Media migration completed!');
    }

    private function migrateServices()
    {
        $services = Service::all();
        $this->info("Processing " . $services->count() . " services...");

        foreach ($services as $service) {
            $this->info("Processing service: {$service->title_en}");

            // Main Image
            if ($service->image && $this->isExternalUrl($service->image)) {
                $url = $this->download($service, $service->image, 'main_image');
                if ($url) {
                    $service->image = $url;
                }
            }

            // Icon
            if ($service->icon && $this->isExternalUrl($service->icon)) {
                $url = $this->download($service, $service->icon, 'icon');
                if ($url) {
                    $service->icon = $url;
                }
            }

            // Gallery
            if ($service->gallery && is_array($service->gallery)) {
                $newGallery = [];
                foreach ($service->gallery as $url) {
                    if ($this->isExternalUrl($url)) {
                        $localUrl = $this->download($service, $url, 'gallery');
                        $newGallery[] = $localUrl ?: $url;
                    } else {
                        $newGallery[] = $url;
                    }
                }
                $service->gallery = $newGallery;
            }

            $service->save();
        }
    }

    private function migrateProjects()
    {
        $projects = Project::all();
        $this->info("Processing " . $projects->count() . " projects...");

        foreach ($projects as $project) {
            $this->info("Processing project: {$project->title_en}");

            // Cover Image
            if ($project->cover_image && $this->isExternalUrl($project->cover_image)) {
                $url = $this->download($project, $project->cover_image, 'cover_image');
                if ($url) {
                    $project->cover_image = $url;
                }
            }

            // Gallery
            if ($project->gallery && is_array($project->gallery)) {
                $newGallery = [];
                foreach ($project->gallery as $url) {
                    if ($this->isExternalUrl($url)) {
                        $localUrl = $this->download($project, $url, 'gallery');
                        $newGallery[] = $localUrl ?: $url;
                    } else {
                        $newGallery[] = $url;
                    }
                }
                $project->gallery = $newGallery;
            }

            $project->save();
        }
    }

    private function migrateSections()
    {
        $sections = Section::all();
        $this->info("Processing " . $sections->count() . " sections...");

        foreach ($sections as $section) {
            $this->info("Processing section: {$section->key}");

            if ($section->content && is_array($section->content)) {
                $content = $section->content;
                $updated = false;

                $content = $this->processContentArray($section, $content, $updated);

                if ($updated) {
                    $section->content = $content;
                    $section->save();
                }
            }
        }
    }

    private function processContentArray($model, $array, &$updated)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->processContentArray($model, $value, $updated);
            } elseif (is_string($value) && $this->isExternalUrl($value)) {
                $localUrl = $this->download($model, $value, 'content');
                if ($localUrl) {
                    $array[$key] = $localUrl;
                    $updated = true;
                }
            }
        }
        return $array;
    }

    private function isExternalUrl($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        // Check for old WP domains or localhost (which we want to make relative)
        $domainsToFix = [
            'indesign-co.com',
            'unsplash.com',
            'flaticon.com',
            'cdn-icons-png.flaticon.com',
            'localhost'
        ];

        foreach ($domainsToFix as $domain) {
            if (str_contains($url, $domain)) {
                return true;
            }
        }

        return false;
    }

    private function download($model, $url, $collection)
    {
        try {
            $filename = basename(parse_url($url, PHP_URL_PATH));
            
            // Check if already exists in MediaLibrary
            $existingMedia = $model->getMedia($collection)->first(function ($media) use ($filename) {
                return $media->file_name === $filename;
            });

            if (!$this->option('force') && $existingMedia) {
                $this->line("  - Already exists: {$filename}");
                return $this->getRelativePath($existingMedia);
            }

            $this->comment("  Downloading: {$url}");
            $media = $model->addMediaFromUrl($url)
                ->preservingOriginal()
                ->toMediaCollection($collection);
            
            $this->info("    ✓ Success");
            return $this->getRelativePath($media);
        } catch (\Exception $e) {
            $this->error("    ✗ Failed: " . $e->getMessage());
            return null;
        }
    }

    private function getRelativePath($media)
    {
        // Spatie returns the full path relative to the disk root
        // e.g. "1/filename.jpg"
        return $media->id . '/' . $media->file_name;
    }
}
