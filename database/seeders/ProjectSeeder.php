<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::truncate();

        $projects = [
            // ─────────────────────────────────────────────
            // 1. LA VIDA SALON  (commercial)
            // ─────────────────────────────────────────────
            [
                'title_en'       => 'La Vida Salon',
                'title_ar'       => 'صالون لاڤيدا',
                'category'       => 'commercial',
                'description_en' => 'A luxury salon featuring a modern interior with premium finishes, elegant mirror walls, and sophisticated lighting — where style meets comfort.',
                'description_ar' => 'صالون فاخر يتميز بتصميم داخلي عصري مع لمسات نهائية راقية ومرايا أنيقة وإضاءة متطورة — حيث تلتقي الأناقة بالراحة.',
                'cover_image'    => '/images/projects/commercial-1-1600.jpg',
                'gallery'        => [
                    '/images/projects/commercial-1-1600.jpg',
                    '/images/projects/commercial-2-1600.jpg',
                    '/images/projects/retail-1-1600.jpg',
                    '/images/projects/hospitality-1-1600.jpg',
                ],
                'is_featured' => true,
                'is_active'   => true,
                'order'       => 1,
            ],

            // ─────────────────────────────────────────────
            // 2. AL ABDALI FARM  (landscape / exterior)
            // ─────────────────────────────────────────────
            [
                'title_en'       => 'Al Abdali Farm',
                'title_ar'       => 'مزرعة أل عبدلي',
                'category'       => 'landscape',
                'description_en' => 'A sprawling farm estate transformed with contemporary architectural design and curated exterior landscaping, harmonising nature with modern living.',
                'description_ar' => 'مزرعة واسعة تحوّلت بتصميم معماري عصري وتنسيق حدائق خارجي يجمع بين الطبيعة والحياة الحديثة.',
                'cover_image'    => '/images/projects/landscape-1-1600.jpg',
                'gallery'        => [
                    '/images/projects/landscape-1-1600.jpg',
                    '/images/projects/exterior-1-1600.jpg',
                    '/images/projects/exterior-2-1600.jpg',
                ],
                'is_featured' => true,
                'is_active'   => true,
                'order'       => 2,
            ],

            // ─────────────────────────────────────────────
            // 3. AL SEEF HOSPITAL  (commercial / hospitality)
            // ─────────────────────────────────────────────
            [
                'title_en'       => 'Al Seef Hospital',
                'title_ar'       => 'مستشفى السيف',
                'category'       => 'hospitality',
                'description_en' => 'A state-of-the-art hospital interior prioritising patient wellbeing, with welcoming reception areas and calming waiting spaces designed to reduce anxiety.',
                'description_ar' => 'تصميم داخلي متطور لمستشفى يضع راحة المريض أولاً، مع مناطق استقبال ترحيبية وقاعات انتظار مريحة.',
                'cover_image'    => '/images/projects/hospitality-1-1600.jpg',
                'gallery'        => [
                    '/images/projects/hospitality-1-1600.jpg',
                    '/images/projects/hospitality-2-1600.jpg',
                    '/images/projects/commercial-2-1600.jpg',
                ],
                'is_featured' => true,
                'is_active'   => true,
                'order'       => 3,
            ],

            // ─────────────────────────────────────────────
            // 4. ALHADI HOSPITAL  (commercial)
            // ─────────────────────────────────────────────
            [
                'title_en'       => 'Alhadi Hospital',
                'title_ar'       => 'مستشفى هادي',
                'category'       => 'commercial',
                'description_en' => 'A comprehensive healthcare interior fit-out combining clinical precision with a warm, humanised environment to enhance the experience of patients and staff.',
                'description_ar' => 'تجهيز داخلي شامل لمنشأة صحية يجمع بين الدقة السريرية وبيئة دافئة إنسانية تُحسّن تجربة المرضى والطاقم الطبي.',
                'cover_image'    => '/images/projects/hospitality-2-1600.jpg',
                'gallery'        => [
                    '/images/projects/hospitality-2-1600.jpg',
                    '/images/projects/commercial-1-1600.jpg',
                    '/images/projects/commercial-2-1600.jpg',
                ],
                'is_featured' => false,
                'is_active'   => true,
                'order'       => 4,
            ],

            // ─────────────────────────────────────────────
            // 5. INTERNATIONAL HOSPITAL  (commercial)
            // ─────────────────────────────────────────────
            [
                'title_en'       => 'International Hospital',
                'title_ar'       => 'المستشفى الدولي',
                'category'       => 'commercial',
                'description_en' => 'An internationally-inspired hospital design upholding the highest global standards of medical facility aesthetics, comfort, and functional workflow.',
                'description_ar' => 'تصميم مستشفى دولي يرتقي بأعلى المعايير العالمية لجماليات المنشآت الطبية، الراحة، والكفاءة التشغيلية.',
                'cover_image'    => '/images/projects/commercial-2-1600.jpg',
                'gallery'        => [
                    '/images/projects/commercial-2-1600.jpg',
                    '/images/projects/hospitality-1-1600.jpg',
                    '/images/projects/retail-1-1600.jpg',
                ],
                'is_featured' => false,
                'is_active'   => true,
                'order'       => 5,
            ],

            // ─────────────────────────────────────────────
            // 6. MOUNTAIN VIEW  (residential)
            // ─────────────────────────────────────────────
            [
                'title_en'       => 'Mountain View',
                'title_ar'       => 'ماونتن فيو',
                'category'       => 'residential',
                'description_en' => 'A premium residential project capturing panoramic views through thoughtful space planning, refined materials, and seamless indoor-outdoor living.',
                'description_ar' => 'مشروع سكني راقٍ يجسّد الإطلالات البانورامية من خلال تخطيط فضائي مدروس ومواد راقية وتكامل سلس بين الداخل والخارج.',
                'cover_image'    => '/images/projects/residential-1-1600.jpg',
                'gallery'        => [
                    '/images/projects/residential-1-1600.jpg',
                    '/images/projects/residential-2-1600.jpg',
                    '/images/projects/exterior-1-1600.jpg',
                ],
                'is_featured' => false,
                'is_active'   => true,
                'order'       => 6,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
