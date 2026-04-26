<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::truncate();

        $base = 'https://indesign-co.com/wp-content/uploads/';

        $projects = [
            // ─────────────────────────────────────────────
            // 1. LA VIDA SALON  (commercial)
            //    SLS series (Nov 2023, Jun 2024, Oct 2024) + DSC05xxx + 431xxx FB posts
            // ─────────────────────────────────────────────
            [
                'title_en'       => 'La Vida Salon',
                'title_ar'       => 'صالون لاڤيدا',
                'category'       => 'commercial',
                'description_en' => 'A luxury salon featuring a modern interior with premium finishes, elegant mirror walls, and sophisticated lighting — where style meets comfort.',
                'description_ar' => 'صالون فاخر يتميز بتصميم داخلي عصري مع لمسات نهائية راقية ومرايا أنيقة وإضاءة متطورة — حيث تلتقي الأناقة بالراحة.',
                'cover_image'    => $base . '2024/06/IMG_1064-scaled.jpg',
                'gallery'        => [
                    // Facebook social posts
                    $base . '2024/10/431521273_249354248260879_9084258774243773194_n.jpg',
                    $base . '2024/10/431069338_249354318260872_9127296206807023491_n.jpg',
                    $base . '2024/10/431064703_249353734927597_2057987504516509134_n.jpg',
                    // Professional photoshoot – SLS series
                    $base . '2024/10/SLS02143-scaled.jpg',
                    $base . '2024/06/SLS02666-scaled.jpg',
                    $base . '2024/06/SLS02698-scaled.jpg',
                    $base . '2024/06/SLS02492-1-scaled.jpg',
                    $base . '2024/06/SLS02274-1-scaled.jpg',
                    $base . '2024/06/SLS02271-scaled.jpg',
                    $base . '2024/06/SLS02161-scaled.jpg',
                    $base . '2024/06/SLS02157-scaled.jpg',
                    $base . '2024/06/SLS02155-scaled.jpg',
                    $base . '2024/06/SLS02151-scaled.jpg',
                    $base . '2024/06/SLS02128-scaled.jpg',
                    $base . '2024/06/SLS02122-scaled.jpg',
                    $base . '2024/06/SLS02118-scaled.jpg',
                    $base . '2024/06/SLS02109-scaled.jpg',
                    $base . '2024/06/SLS02106-scaled.jpg',
                    $base . '2024/06/SLS02096-scaled.jpg',
                    // DSC interior shots (Jul 2024)
                    $base . '2024/07/DSC05362-scaled.jpg',
                    $base . '2024/07/DSC05331-scaled.jpg',
                    $base . '2024/07/DSC05328-scaled.jpg',
                    $base . '2024/07/DSC05307-scaled.jpg',
                    $base . '2024/07/DSC05294-scaled.jpg',
                    $base . '2024/07/DSC05286-scaled.jpg',
                    $base . '2024/07/DSC05239-scaled.jpg',
                    $base . '2024/07/DSC05184-scaled.jpg',
                    $base . '2024/07/DSC05178-scaled.jpg',
                    $base . '2024/07/DSC05156-scaled.jpg',
                    $base . '2024/07/DSC05155-scaled.jpg',
                    $base . '2024/07/DSC05135-scaled.jpg',
                    $base . '2024/07/DSC05130-scaled.jpg',
                    $base . '2024/07/DSC05124-scaled.jpg',
                    $base . '2024/07/DSC05117-scaled.jpg',
                    $base . '2024/07/DSC05097-scaled.jpg',
                    $base . '2024/07/DSC05095-scaled.jpg',
                    $base . '2024/07/DSC05094-scaled.jpg',
                    $base . '2024/07/DSC05083-scaled.jpg',
                    $base . '2024/07/DSC05076-scaled.jpg',
                    $base . '2024/07/DSC05050-scaled.jpg',
                    $base . '2024/07/DSC05049-scaled.jpg',
                    $base . '2024/07/DSC05040-scaled.jpg',
                    $base . '2024/07/DSC05033-scaled.jpg',
                    $base . '2024/07/DSC05029-scaled.jpg',
                    $base . '2024/07/DSC05025-scaled.jpg',
                    $base . '2024/07/DSC05021-scaled.jpg',
                    $base . '2024/07/DSC05002-scaled.jpg',
                    $base . '2024/07/DSC04991-scaled.jpg',
                    $base . '2024/07/DSC04990-scaled.jpg',
                    $base . '2024/07/DSC04980-scaled.jpg',
                    $base . '2024/07/DSC04977-scaled.jpg',
                    $base . '2024/07/DSC04974-scaled.jpg',
                    $base . '2024/07/DSC04961-scaled.jpg',
                    $base . '2024/07/DSC04930-scaled.jpg',
                ],
                'is_featured' => true,
                'is_active'   => true,
                'order'       => 1,
            ],

            // ─────────────────────────────────────────────
            // 2. AL ABDALI FARM  (exterior)
            //    Dec 2023 numbered + Jun 2024 IMG_0xxx/1xxx + DJI drone shots
            // ─────────────────────────────────────────────
            [
                'title_en'       => 'Al Abdali Farm',
                'title_ar'       => 'مزرعة أل عبدلي',
                'category'       => 'exterior',
                'description_en' => 'A sprawling farm estate transformed with contemporary architectural design and curated exterior landscaping, harmonising nature with modern living.',
                'description_ar' => 'مزرعة واسعة تحوّلت بتصميم معماري عصري وتنسيق حدائق خارجي يجمع بين الطبيعة والحياة الحديثة.',
                'cover_image'    => $base . '2024/07/DJI_0692-1-scaled.jpg',
                'gallery'        => [
                    $base . '2024/07/1X5A4687-scaled.jpg',
                    $base . '2024/07/1X5A4690-scaled.jpg',
                    $base . '2024/07/1X5A4700-scaled.jpg',
                    $base . '2024/07/1X5A4701-scaled.jpg',
                    $base . '2024/07/1X5A4702-scaled.jpg',
                    $base . '2024/07/1X5A4703-scaled.jpg',
                    $base . '2024/07/1X5A4704-scaled.jpg',
                    $base . '2024/07/1X5A4709-scaled.jpg',
                    $base . '2024/07/1X5A4710-scaled.jpg',
                    $base . '2024/07/1X5A4714-scaled.jpg',
                    $base . '2024/07/1X5A4715-scaled.jpg',
                    $base . '2024/07/1X5A4716-scaled.jpg',
                    $base . '2024/07/1X5A4717-scaled.jpg',
                    $base . '2024/07/1X5A4718-scaled.jpg',
                    $base . '2024/07/DJI_0681-scaled.jpg',
                    $base . '2024/07/DJI_0682-scaled.jpg',
                    $base . '2024/07/DJI_0683-scaled.jpg',
                    $base . '2024/07/DJI_0684-scaled.jpg',
                    $base . '2024/07/1X5A4761-scaled.jpg',
                    $base . '2024/07/1X5A4790-scaled.jpg',
                    $base . '2024/07/1X5A4807-scaled.jpg',
                    $base . '2024/07/1X5A4772-scaled.jpg',
                    $base . '2024/07/1X5A4774-scaled.jpg',
                    $base . '2024/07/1X5A4776-scaled.jpg',
                    $base . '2024/07/1X5A4777-scaled.jpg',
                    $base . '2024/07/1X5A4778-scaled.jpg',
                    $base . '2024/07/1X5A4789-1-scaled.jpg',
                    $base . '2024/07/1X5A4793-scaled.jpg',
                    $base . '2024/07/1X5A4800-scaled.jpg',
                    $base . '2024/07/1X5A4803-scaled.jpg',
                    $base . '2024/07/1X5A4806-scaled.jpg',
                ],
                'is_featured' => true,
                'is_active'   => true,
                'order'       => 2,
            ],

            // ─────────────────────────────────────────────
            // 3. AL SEEF HOSPITAL  (administrative)
            //    Oct 2024 "reception final" renders + 431063/064 FB posts
            //    + Jul 2024 numbered 1-10 + f1-f8 series
            // ─────────────────────────────────────────────
            [
                'title_en'       => 'Al Seef Hospital',
                'title_ar'       => 'مستشفى السيف',
                'category'       => 'administrative',
                'description_en' => 'A state-of-the-art hospital interior prioritising patient wellbeing, with welcoming reception areas and calming waiting spaces designed to reduce anxiety.',
                'description_ar' => 'تصميم داخلي متطور لمستشفى يضع راحة المريض أولاً، مع مناطق استقبال ترحيبية وقاعات انتظار مريحة.',
                'cover_image'    => $base . '2024/07/IMGL9030-scaled.jpg',
                'gallery'        => [
                    $base . '2024/10/431063630_249353781594259_5689123842104526545_n.jpg',
                    // Reception renders (Oct 2024)
                    $base . '2024/10/reception-final-1-scaled.jpg',
                    $base . '2024/10/reception-final-2-scaled.jpg',
                    $base . '2024/10/reception-final-4-scaled.jpg',
                    $base . '2024/10/reception-final-5-scaled.jpg',
                    $base . '2024/10/reception-final-6-scaled.jpg',
                    $base . '2024/10/reception-final-7-scaled.jpg',
                    $base . '2024/10/reception-final-8-scaled.jpg',
                    // Numbered series 1-10 (Oct 2024)
                    $base . '2024/10/1-1-scaled.jpg',
                    $base . '2024/10/2-1-scaled.jpg',
                    $base . '2024/10/3-1-scaled.jpg',
                    $base . '2024/10/4-1-scaled.jpg',
                    $base . '2024/10/5-1-scaled.jpg',
                    $base . '2024/10/6-1-scaled.jpg',
                    $base . '2024/10/7-1-scaled.jpg',
                    $base . '2024/10/8-1-scaled.jpg',
                    $base . '2024/10/9-1-scaled.jpg',
                    $base . '2024/10/10-scaled.jpg',
                    // f-series (Oct 2024)
                    $base . '2024/10/f1-1-scaled.jpg',
                    $base . '2024/10/f5-1-scaled.jpg',
                    $base . '2024/10/f6-1-scaled.jpg',
                    $base . '2024/10/f7-1-scaled.jpg',
                    $base . '2024/10/f8-1-scaled.jpg',
                    $base . '2024/10/121-scaled.jpg',
                ],
                'is_featured' => true,
                'is_active'   => true,
                'order'       => 3,
            ],

            // ─────────────────────────────────────────────
            // 4. ALHADI HOSPITAL  (administrative)
            //    430xxx/429xxx/410xxx/409xxx Facebook series (Oct 2024)
            //    + Jul 2024 f1/f5/f6/f7/f8 + numbered 1-9
            // ─────────────────────────────────────────────
            [
                'title_en'       => 'Alhadi Hospital',
                'title_ar'       => 'مستشفى هادي',
                'category'       => 'administrative',
                'description_en' => 'A comprehensive healthcare interior fit-out combining clinical precision with a warm, humanised environment to enhance the experience of patients and staff.',
                'description_ar' => 'تجهيز داخلي شامل لمنشأة صحية يجمع بين الدقة السريرية وبيئة دافئة إنسانية تُحسّن تجربة المرضى والطاقم الطبي.',
                'cover_image'    => $base . '2024/07/IMG_0021-1-scaled.jpg',
                'gallery'        => [
                    $base . '2024/10/430277216_1352287925461347_1482441092481871420_n.jpg',
                    $base . '2024/10/430187672_1163210438027805_4478764908786592930_n.jpg',
                    $base . '2024/10/430179274_769656151421679_8402067980927927391_n.jpg',
                    $base . '2024/10/429570926_316724571386081_5150029571726570143_n.jpg',
                    $base . '2024/10/410395563_1051760962692460_1337822609701656133_n.jpg',
                    $base . '2024/10/410255682_738169971494585_4293157350355911939_n.jpg',
                    $base . '2024/10/409995927_1372748510299834_5922128585773870750_n.jpg',
                    $base . '2024/10/409770019_797370845486775_2885072288991995327_n.jpg',
                    $base . '2024/10/409514150_346654444648260_7924834200601429968_n.jpg',
                    $base . '2024/10/409457672_1121905602508203_6425203796692482657_n.jpg',
                    $base . '2024/10/409219001_869605344810146_6016965710121084028_n.jpg',
                    $base . '2024/10/409186189_3592189707735519_623123727450809135_n.jpg',
                    $base . '2024/10/409182981_261238606645794_8017927131335275523_n.jpg',
                    $base . '2024/10/409079728_841744651291193_6891918843774932265_n.jpg',
                    // Jul 2024 – numbered 1-9 + f-series
                    $base . '2024/07/1.jpg',
                    $base . '2024/07/3.jpg',
                    $base . '2024/07/5-1.jpg',
                    $base . '2024/07/7.jpg',
                    $base . '2024/07/9.jpg',
                    $base . '2024/07/f1-scaled.jpg',
                    $base . '2024/07/f5-scaled.jpg',
                    $base . '2024/07/f6-scaled.jpg',
                    $base . '2024/07/f7-scaled.jpg',
                    $base . '2024/07/f8-scaled.jpg',
                ],
                'is_featured' => false,
                'is_active'   => true,
                'order'       => 4,
            ],

            // ─────────────────────────────────────────────
            // 5. INTERNATIONAL HOSPITAL  (administrative)
            //    393xxx/392xxx/391xxx/386xxx FB series (Oct 2024)
            //    + Apr 2024 photo1/photo2 + numbered Apr 2024
            // ─────────────────────────────────────────────
            [
                'title_en'       => 'International Hospital',
                'title_ar'       => 'المستشفى الدولي',
                'category'       => 'administrative',
                'description_en' => 'An internationally-inspired hospital design upholding the highest global standards of medical facility aesthetics, comfort, and functional workflow.',
                'description_ar' => 'تصميم مستشفى دولي يرتقي بأعلى المعايير العالمية لجماليات المنشآت الطبية، الراحة، والكفاءة التشغيلية.',
                'cover_image'    => $base . '2024/07/1X5A0045-scaled.jpg',
                'gallery'        => [
                    $base . '2024/10/392930496_1291836064824358_4658950987903062964_n.jpg',
                    $base . '2024/10/392928961_839797971151476_1861535071263112828_n.jpg',
                    $base . '2024/10/392831420_1079854069712318_7985987945997610485_n.jpg',
                    $base . '2024/10/392799060_1379846469408289_7819370333900157346_n.jpg',
                    $base . '2024/10/391472076_285503621110982_2906889662971802851_n.jpg',
                    $base . '2024/10/391437502_863705191645618_9068991527210653072_n.jpg',
                    $base . '2024/10/391427395_1004895070621491_49609551471208718_n.jpg',
                    $base . '2024/10/391408060_2023948158004469_9027478810253374342_n.jpg',
                    $base . '2024/10/386898039_3357245487900018_4018734360447413512_n.jpg',
                    // Apr 2024 hospital renders
                    $base . '2024/04/photo1-scaled.jpg',
                    $base . '2024/04/photo1-1-scaled.jpg',
                    $base . '2024/04/photo1-2-scaled.jpg',
                    $base . '2024/04/photo2-scaled.jpg',
                    $base . '2024/04/1-scaled.jpg',
                    $base . '2024/04/1-1-scaled.jpg',
                    $base . '2024/04/1-2-scaled.jpg',
                    $base . '2024/04/2-scaled.jpg',
                    $base . '2024/04/2-1-scaled.jpg',
                    $base . '2024/04/4-scaled.jpg',
                    $base . '2024/04/5-1-scaled.jpg',
                    $base . '2024/04/6-scaled.jpg',
                    $base . '2024/04/7-scaled.jpg',
                    $base . '2024/04/8-scaled.jpg',
                    $base . '2024/04/9-scaled.jpg',
                ],
                'is_featured' => false,
                'is_active'   => true,
                'order'       => 5,
            ],

            // ─────────────────────────────────────────────
            // 6. MOUNTAIN VIEW  (residential)
            //    447xxx FB series (Oct 2024) + 1X5Axxx / DJI_068x / IMGLxxx (Jul 2024)
            // ─────────────────────────────────────────────
            [
                'title_en'       => 'Mountain View',
                'title_ar'       => 'ماونتن فيو',
                'category'       => 'residential',
                'description_en' => 'A premium residential project capturing panoramic views through thoughtful space planning, refined materials, and seamless indoor-outdoor living.',
                'description_ar' => 'مشروع سكني راقٍ يجسّد الإطلالات البانورامية من خلال تخطيط فضائي مدروس ومواد راقية وتكامل سلس بين الداخل والخارج.',
                'cover_image'    => $base . '2024/06/2-1.jpg',
                'gallery'        => [
                    $base . '2023/10/14.png',
                    $base . '2023/10/13.png',
                    $base . '2023/10/12.png',
                    $base . '2023/10/10.png',
                    $base . '2023/10/9.png',
                    $base . '2023/10/8.png',
                    $base . '2023/10/7.png',
                    $base . '2023/10/6.png',
                    $base . '2023/10/5.png',
                    $base . '2023/10/4.png',
                    $base . '2023/10/3.png',
                    $base . '2023/10/2.png',
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
