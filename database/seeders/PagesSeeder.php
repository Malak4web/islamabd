<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Section;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'home',
                'title_en' => 'Home',
                'title_ar' => 'الرئيسية',
                'meta_title' => 'InDesign | Premium Interior Design',
                'meta_description' => 'Bespoke interior design solutions in Dubai.',
                'sections' => [
                    [
                        'key' => 'hero',
                        'order' => 1,
                        'content' => [
                            'title_en' => 'Crafting Exceptional Spaces',
                            'title_ar' => 'نصنع مساحات استثنائية',
                            'subtitle_en' => 'Premium Interior Design for Luxury Living',
                            'subtitle_ar' => 'تصميم داخلي راقٍ لحياة فاخرة',
                            'cta_text_en' => 'View Projects',
                            'cta_link' => '/projects'
                        ]
                    ],
                    [
                        'key' => 'stats',
                        'order' => 2,
                        'content' => [
                            'item1_val' => '150+', 'item1_label_en' => 'Projects Done',
                            'item2_val' => '12', 'item2_label_en' => 'Years Experience',
                        ]
                    ]
                ]
            ],
            [
                'slug' => 'about',
                'title_en' => 'About Us',
                'title_ar' => 'من نحن',
                'meta_title' => 'About InDesign | Our Story',
                'sections' => [
                    [
                        'key' => 'story',
                        'order' => 1,
                        'content' => [
                            'title_en' => 'Our Journey',
                            'description_en' => 'Founded in 2012, InDesign has been...'
                        ]
                    ]
                ]
            ],
            [ 'slug' => 'services', 'title_en' => 'Services', 'title_ar' => 'خدماتنا' ],
            [ 'slug' => 'projects', 'title_en' => 'Projects', 'title_ar' => 'مشاريعنا' ],
            [ 'slug' => 'contact', 'title_en' => 'Contact Us', 'title_ar' => 'اتصل بنا' ],
        ];

        foreach ($pages as $pData) {
            $sections = $pData['sections'] ?? [];
            unset($pData['sections']);

            $page = Page::updateOrCreate(['slug' => $pData['slug']], $pData);

            foreach ($sections as $sData) {
                Section::updateOrCreate(
                    ['page_id' => $page->id, 'key' => $sData['key']],
                    $sData
                );
            }
        }
    }
}
