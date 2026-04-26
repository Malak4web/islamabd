<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug'             => 'home',
                'title_en'         => 'Home',
                'title_ar'         => 'الرئيسية',
                'meta_title'       => 'Home – InDesign | Design & Built',
                'meta_description' => 'InDesign is a full-service design and construction company specializing in administrative, commercial, residential, and exterior design in Kuwait and Egypt.',
                'og_image'         => null,
            ],
            [
                'slug'             => 'about',
                'title_en'         => 'About Us',
                'title_ar'         => 'من نحن',
                'meta_title'       => 'About Us – InDesign | Design & Built',
                'meta_description' => 'Founded in 1983, InDesign has grown from a carpentry business into a full-service design and construction company operating in Kuwait and Egypt.',
                'og_image'         => null,
            ],
            [
                'slug'             => 'services',
                'title_en'         => 'Our Services',
                'title_ar'         => 'خدماتنا',
                'meta_title'       => 'Services – InDesign | Design & Built',
                'meta_description' => 'Explore InDesign\'s comprehensive design services: Administrative, Commercial, Residential, and Exterior design solutions.',
                'og_image'         => null,
            ],
            [
                'slug'             => 'projects',
                'title_en'         => 'Projects',
                'title_ar'         => 'مشاريعنا',
                'meta_title'       => 'Projects – InDesign | Design & Built',
                'meta_description' => 'Browse InDesign\'s portfolio of completed projects including salons, hospitals, farms, and residential developments in Kuwait and Egypt.',
                'og_image'         => null,
            ],
            [
                'slug'             => 'contact',
                'title_en'         => 'Contact Us',
                'title_ar'         => 'اتصل بنا',
                'meta_title'       => 'Contact Us – InDesign | Design & Built',
                'meta_description' => 'Get in touch with InDesign for a free consultation. We have offices in Kuwait City and El Sheikh Zayed, Egypt.',
                'og_image'         => null,
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], $page);
        }
    }
}
