<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\Service;
use Illuminate\Console\Command;

class ImportFromWordpress extends Command
{
    protected $signature   = 'import:wordpress {--sql= : Path to the SQL dump file}';
    protected $description = 'Import services and projects from the WordPress SQL dump';

    private string $base = 'https://indesign-co.com/wp-content/uploads/';

    public function handle(): int
    {
        $this->info('Starting WordPress import...');
        $this->importServices();
        $this->importProjects();
        $this->info('Import complete!');
        return 0;
    }

    // ──────────────────────────────────────────────
    // SERVICES  (4 service types from the WP site)
    // ──────────────────────────────────────────────
    private function importServices(): void
    {
        $this->info('Importing services...');
        Service::truncate();

        $b = $this->base;

        $services = [
            [
                'title_en'       => 'Administrative',
                'title_ar'       => 'إداري',
                'description_en' => "The significance of effective administrative design lies in its ability to signify organizational advancement and bolster client trust. A well-crafted administrative space enhances operational efficiency and leaves a positive impression on clients and visitors.\n\nAt INDESIGN, we focus on two core aspects in our administrative designs: purpose and excellence. Our wide array of administrative designs encompasses corporate offices, business centers, and other professional settings.\n\nKey Principles of Administrative Design:\n• User Analysis: Identifying the number of occupants using the administrative facility.\n• Service Provision: Ensuring all necessary amenities (such as restrooms, offices, etc.) are available within the building.\n• Layout Planning: Determining the spatial configuration, whether it’s open-plan or compartmentalized.\n• Staff Assessment: Accounting for the number of employees operating within the space.\n• Safety Measures: Adhering to stringent safety and security protocols to handle emergencies.",
                'description_ar' => "التصميم الإداري في ان ديزين\nتكمن أهمية التصميم الإداري الفعال في قدرته على الدلالة على التقدم التنظيمي وتعزيز ثقة العملاء. ويعزز الحيز الإداري الجيد الصياغة الكفاءة التشغيلية ويترك انطباعا إيجابيا لدى العملاء والزوار.\n\nفي ان ديزين ، نركز على جانبين أساسيين في تصميماتنا الإدارية: الغرض والتميز. تشمل مجموعتنا الواسعة من التصميمات الإدارية مكاتب الشركات ومراكز الأعمال والإعدادات المهنية الأخرى.\n\nفي ان ديزين تتميز تصميماتنا الإدارية بالعديد من الفوائد الرئيسية:\n• إعطاء الأولوية لأمن ورفاه جميع مستخدمي المباني.\n• الحفاظ على سرية وخصوصية العمليات.\n• استخدام العناصر الطبيعية لإنشاء فواصل فعالة.\n• تعظيم استخدام المساحة المتاحة بطريقة عملية ومريحة.\n• تدابير السلامة: الالتزام ببروتوكولات السلامة والأمن الصارمة للتعامل مع حالات الطوارئ.",
                'image'          => $b . '2024/06/SLS02666-scaled.jpg',
                'gallery'        => [
                    // SLS 2024/06 photoshoot
                    $b . '2024/06/SLS02096-scaled.jpg',
                    $b . '2024/06/SLS02106-scaled.jpg',
                    $b . '2024/06/SLS02109-scaled.jpg',
                    $b . '2024/06/SLS02118-scaled.jpg',
                    $b . '2024/06/SLS02122-scaled.jpg',
                    $b . '2024/06/SLS02128-scaled.jpg',
                    $b . '2024/06/SLS02151-scaled.jpg',
                    $b . '2024/06/SLS02155-scaled.jpg',
                    $b . '2024/06/SLS02157-scaled.jpg',
                    $b . '2024/06/SLS02161-scaled.jpg',
                    $b . '2024/06/SLS02271-scaled.jpg',
                    $b . '2024/06/SLS02274-1-scaled.jpg',
                    $b . '2024/06/SLS02492-1-scaled.jpg',
                    $b . '2024/06/SLS02666-scaled.jpg',
                    $b . '2024/06/SLS02698-scaled.jpg',
                    // Real project images from the admin service page
                    $b . '2024/06/1-scaled.jpg',
                    $b . '2024/06/2-scaled.jpg',
                    $b . '2024/06/3-scaled.jpg',
                    $b . '2024/06/1-1-scaled.jpg',
                    // 2023/10 numbered admin renders
                    $b . '2023/10/01-scaled.jpg',
                    $b . '2023/10/02-scaled.jpg',
                    $b . '2023/10/03-scaled.jpg',
                    $b . '2023/10/04-scaled.jpg',
                    $b . '2023/10/05-scaled.jpg',
                    $b . '2023/10/06-scaled.jpg',
                    $b . '2023/10/07-scaled.jpg',
                    $b . '2023/10/08-scaled.jpg',
                    $b . '2023/10/09-scaled.jpg',
                    $b . '2023/10/10-scaled.jpg',
                    $b . '2023/10/11-scaled.jpg',
                    $b . '2023/10/12-scaled.jpg',
                    $b . '2023/10/13-scaled.jpg',
                    $b . '2023/10/14-scaled.jpg',
                    $b . '2023/10/15-scaled.jpg',
                    $b . '2023/10/16-scaled.jpg',
                    $b . '2023/10/17-scaled.jpg',
                    $b . '2023/10/18-scaled.jpg',
                ],
                'icon'           => $b . 'elementor/thumbs/001-01-qoz8m7bq30sumoc0m0686c97vqcnhwhli2zhfg9kw4.png',
                'order'          => 1,
                'is_active'      => true,
            ],
            [
                'title_en'       => 'Commercial',
                'title_ar'       => 'تجاري',
                'description_en' => "Commercial Design at INDESIGN\n\nIn the modern business environment, commercial design is essential to the success of any company. It enhances the business’s value, fosters customer trust, and creates a comfortable atmosphere. Effective commercial design for offices and shopping centres underscores the significance of the location and is vital for the success of numerous projects.\n\nThe Significance of Commercial Design\nA well-planned and structured commercial design greatly contributes to business success by:\n• Increasing Sales: Boosting the company’s revenue.\n• Fostering Trust: Creating a comfortable atmosphere for clients.\n\nAt INDESIGN | Design & Built, we are committed to delivering the highest standards of quality through our talented and creative team. Our approach is based on honesty and precision, ensuring that every project we handle is executed to perfection and tailored to meet our client’s unique needs.",
                'description_ar' => "في بيئة الأعمال الحديثة، التصميم التجاري ضروري لنجاح أي شركة. إنه يعزز قيمة العمل، ويعزز ثقة العملاء، ويخلق جوًا مريحًا. يؤكد التصميم التجاري الفعال للمكاتب ومراكز التسوق على أهمية الموقع وهو حيوي لنجاح العديد من المشاريع.\n\nأهمية التصميم التجاري\nيسهم التصميم التجاري المخطط جيدًا والمنظم بشكل كبير في نجاح الأعمال من خلال:\n• زيادة المبيعات: زيادة إيرادات الشركة.\n• تعزيز الثقة: خلق شعور بالموثوقية والأمان بين الشركة وعملائها.\n• تعزيز وجود العلامة التجارية: تعزيز مكانة الشركة وتأثيرها بين المنافسين.\n• جذب عملاء جدد: جذب عملاء إضافيين.\n\nفي ان ديزين ، نحن ملتزمون بتقديم أعلى معايير الجودة من خلال فريقنا الموهوب والمبدع. يعتمد نهجنا على الصدق والدقة، مما يضمن أن كل مشروع نتعامل معه يتم تنفيذه بشكل مثالي ومصمم لتلبية الاحتياجات الفريدة لعملائنا.",
                'image'          => $b . '2023/10/wide-1-scaled.jpg',
                'gallery'        => [
                    $b . '2023/10/wide-1-scaled.jpg',
                    $b . '2023/10/wide-2-scaled.jpg',
                    $b . '2023/10/wide-3-scaled.jpg',
                    $b . '2023/10/wide-4-scaled.jpg',
                    $b . '2023/10/salon-1.jpg',
                    $b . '2023/10/salon-2.jpg',
                    $b . '2023/10/salon-3.jpg',
                    $b . '2023/10/salon-4.jpg',
                    $b . '2023/10/salon-5.jpg',
                    $b . '2023/10/salon-6.jpg',
                    $b . '2023/10/salon-7.jpg',
                    $b . '2023/10/salon-8.jpg',
                    $b . '2023/10/aa-1-scaled.jpg',
                    $b . '2023/10/aa-2-scaled.jpg',
                    $b . '2023/10/aa-3-scaled.jpg',
                    $b . '2023/10/01-1-scaled.jpg',
                    $b . '2023/10/02-1-scaled.jpg',
                    $b . '2023/10/03-1-scaled.jpg',
                    $b . '2023/10/04-1-scaled.jpg',
                    $b . '2023/10/05-1-scaled.jpg',
                    $b . '2023/10/06-1-scaled.jpg',
                    $b . '2024/07/0-scaled.jpg',
                    $b . '2024/07/000.jpg',
                    $b . '2024/07/11.jpg',
                    $b . '2024/07/12.jpg',
                    $b . '2024/07/122-scaled.jpg',
                    $b . '2024/07/123-scaled.jpg',
                    $b . '2024/07/14-scaled.jpg',
                    $b . '2024/07/15-scaled.jpg',
                    $b . '2024/07/17-scaled.jpg',
                    $b . '2024/07/18-scaled.jpg',
                    $b . '2024/07/19.jpg',
                    $b . '2024/07/22.jpg',
                    $b . '2024/07/41-scaled.jpg',
                    $b . '2024/07/42-scaled.jpg',
                ],
                'icon'           => $b . 'elementor/thumbs/002-qoz8ps3m3bp4t94yq1u45xrd9ipxsfotnsd06cyn5g.png',
                'order'          => 2,
                'is_active'      => true,
            ],
            [
                'title_en'       => 'Residential',
                'title_ar'       => 'سكني',
                'description_en' => "Our residential design focuses on creating harmony between interior elements. We prioritize optimal lighting, ventilation, and safety standards, ensuring a cohesive and secure environment.\n\nKey Elements\n• Lighting & Ventilation: Ensuring brightness and airflow.\n• Safety: Adhering to strict safety protocols.\n• Colours & Furniture: Choosing comfortable colours and easy-to-move furniture.\n• Design Unity: Achieving a harmonious and consistent look.\n\nThese factors allow us to offer top-notch residential design services. View our standout projects and contact us for exceptional design solutions. Our team of experts is ready to assist you.",
                'description_ar' => "يركز تصميمنا السكني على خلق الانسجام بين العناصر الداخلية. نحن نعطي الأولوية للإضاءة المثلى والتهوية ومعايير السلامة، مما يضمن بيئة متماسكة وآمنة.\n\n• العناصر الرئيسية للإضاءة والتهوية: ضمان السطوع وتدفق الهواء.\n• السلامة: الالتزام ببروتوكولات السلامة الصارمة.\n• الألوان والأثاث: اختيار الألوان المريحة والأثاث سهل الحركة.\n• وحدة التصميم: تحقيق نظرة متناغمة ومتسقة.\n\nهذه العوامل تسمح لنا بتقديم خدمات التصميم السكني من الدرجة الأولى. شاهد مشاريعنا المميزة واتصل بنا للحصول على حلول تصميم استثنائية. فريق الخبراء لدينا على استعداد لمساعدتك.",
                'image'          => $b . '2024/07/1-2-scaled.jpg',
                'gallery'        => [
                    $b . '2024/07/0-1-scaled.jpg',
                    $b . '2024/07/1-2-scaled.jpg',
                    $b . '2024/07/1-3-scaled.jpg',
                    $b . '2024/07/2-1-scaled.jpg',
                    $b . '2024/07/3-1-scaled.jpg',
                    $b . '2024/07/4-scaled.jpg',
                    $b . '2024/07/5-3-scaled.jpg',
                    $b . '2024/07/6-1-scaled.jpg',
                    $b . '2024/07/7-2-scaled.jpg',
                    $b . '2024/07/8-1-scaled.jpg',
                    $b . '2024/07/10-1-scaled.jpg',
                    $b . '2024/02/Classic.jpg',
                    $b . '2024/02/Minimalism.jpg',
                    $b . '2024/02/Moodern.jpg',
                    $b . '2024/02/new-Classic.jpg',
                    $b . '2023/12/01.jpg',
                    $b . '2023/12/02.jpg',
                    $b . '2023/12/03.jpg',
                    $b . '2023/12/04.jpg',
                    $b . '2023/12/05.jpg',
                    $b . '2023/12/06.jpg',
                    $b . '2023/12/07.jpg',
                    $b . '2023/12/08.jpg',
                ],
                'icon'           => $b . 'elementor/thumbs/004-qoz8oome4a739mqb4krs97q0bc4it5c3icym0ql6h0.png',
                'order'          => 3,
                'is_active'      => true,
            ],
            [
                'title_en'       => 'Exterior',
                'title_ar'       => 'خارجي',
                'description_en' => "Exterior design is crucial for making a strong first impression. We create stunning outdoor spaces that harmonize with the natural environment and architectural style.\n\nOur Approach\n• Harmonious Integration: Blending the exterior with the surrounding environment.\n• Sustainability: Using eco-friendly materials and energy-efficient lighting.\n• Aesthetic Appeal: Creating striking facades and beautiful landscapes.\n• Functional Spaces: Designing outdoor areas that are both beautiful and usable.",
                'description_ar' => "يعد التصميم الخارجي أمرًا بالغ الأهمية لترك انطباع أول قوي. يعزز المظهر الخارجي المصمم جيدًا الجاذبية الجمالية للمبنى ووظائفه، مما يجعله جذابًا وعمليًا.\n\nأهمية التصميم الخارجي\n• إنشاء واجهات مذهلة وجذابة بصريًا.\n• ضمان أن يكون المظهر الخارجي عمليًا ويخدم الغرض المقصود منه.\n• استخدام المواد التي تتحمل الطقس والوقت.\n• تكامل المناظر الطبيعية.\n\nفي ان ديزين ، متخصصون في صياغة تصميمات خارجية ليست جميلة فحسب، بل متينة وعملية أيضًا. استكشف محفظتنا للحصول على أمثلة لعملنا، واتصل بنا للحصول على حلول تصميم خارجية خبيرة. فريقنا مستعد لمساعدتك في تحقيق المظهر الخارجي المثالي لمشروعك.",
                'image'          => $b . '2023/10/f1-scaled.jpg',
                'gallery'        => [
                    $b . '2023/10/0.jpg',
                    $b . '2023/10/1-6.jpg',
                    $b . '2023/10/2-7.jpg',
                    $b . '2023/10/3-6.jpg',
                    $b . '2023/10/f1-scaled.jpg',
                    $b . '2023/10/f5-scaled.jpg',
                    $b . '2023/10/f6-scaled.jpg',
                    $b . '2023/10/f7-scaled.jpg',
                    $b . '2023/10/f8-scaled.jpg',
                    $b . '2024/02/1.jpg',
                    $b . '2024/02/1N.jpg',
                    $b . '2024/02/2.jpg',
                    $b . '2024/02/2N.jpg',
                    $b . '2024/02/3-scaled.jpg',
                    $b . '2024/02/4-scaled.jpg',
                    $b . '2024/02/5-scaled.jpg',
                    $b . '2024/02/6-scaled.jpg',
                    $b . '2024/07/0-1-scaled.jpg',
                    $b . '2024/07/1-2-scaled.jpg',
                    $b . '2024/07/3-1-scaled.jpg',
                    $b . '2024/07/f6-scaled.jpg',
                    $b . '2024/07/f7-scaled.jpg',
                    $b . '2024/07/f8-scaled.jpg',
                ],
                'icon'           => $b . 'elementor/thumbs/00-01-qoz8nowiwku70g66x5bymgqhqp0koneapg65s8251g.png',
                'order'          => 4,
                'is_active'      => true,
            ],
        ];

        foreach ($services as $data) {
            Service::create($data);
        }

        $this->info('  ✓ ' . count($services) . ' services imported.');
    }

    // ──────────────────────────────────────────────
    // PROJECTS (6 projects from the WP site)
    // ──────────────────────────────────────────────
    private function importProjects(): void
    {
        $this->info('Importing projects...');
        Project::truncate();

        $b = $this->base;

        $projects = [
            // 1. La Vida Salon — Commercial
            [
                'title_en'       => 'La Vida Salon',
                'title_ar'       => 'صالون لاڤيدا',
                'category'       => 'commercial',
                'description_en' => 'A luxury salon featuring a modern interior with premium finishes, elegant mirror walls, and sophisticated lighting — where style meets comfort.',
                'description_ar' => 'صالون فاخر يتميز بتصميم داخلي عصري مع لمسات نهائية راقية ومرايا أنيقة وإضاءة متطورة.',
                'cover_image'    => $b . '2024/06/IMG_1064-scaled.jpg',
                'gallery'        => [
                    $b . '2024/06/SLS02096-scaled.jpg',
                    $b . '2024/06/SLS02106-scaled.jpg',
                    $b . '2024/06/SLS02109-scaled.jpg',
                    $b . '2024/06/SLS02118-scaled.jpg',
                    $b . '2024/06/SLS02122-scaled.jpg',
                    $b . '2024/06/SLS02128-scaled.jpg',
                    $b . '2024/06/SLS02151-scaled.jpg',
                    $b . '2024/06/SLS02155-scaled.jpg',
                    $b . '2024/06/SLS02157-scaled.jpg',
                    $b . '2024/06/SLS02161-scaled.jpg',
                    $b . '2024/06/SLS02271-scaled.jpg',
                    $b . '2024/06/SLS02274-1-scaled.jpg',
                    $b . '2024/06/SLS02492-1-scaled.jpg',
                    $b . '2024/06/SLS02666-scaled.jpg',
                    $b . '2024/06/SLS02698-scaled.jpg',
                    $b . '2024/10/SLS02143-scaled.jpg',
                    $b . '2024/07/DSC04930-scaled.jpg',
                    $b . '2024/07/DSC04961-scaled.jpg',
                    $b . '2024/07/DSC04977-scaled.jpg',
                    $b . '2024/07/DSC04990-scaled.jpg',
                    $b . '2024/07/DSC05002-scaled.jpg',
                    $b . '2024/07/DSC05021-scaled.jpg',
                    $b . '2024/07/DSC05040-scaled.jpg',
                    $b . '2024/07/DSC05076-scaled.jpg',
                    $b . '2024/07/DSC05094-scaled.jpg',
                    $b . '2024/07/DSC05117-scaled.jpg',
                    $b . '2024/07/DSC05135-scaled.jpg',
                    $b . '2024/07/DSC05178-scaled.jpg',
                    $b . '2024/07/DSC05239-scaled.jpg',
                    $b . '2024/07/DSC05286-scaled.jpg',
                    $b . '2024/07/DSC05362-scaled.jpg',
                ],
                'is_featured'    => true,
                'is_active'      => true,
                'order'          => 1,
            ],

            // 2. Al Abdali Farm — Exterior
            [
                'title_en'       => 'Al Abdali Farm',
                'title_ar'       => 'مزرعة أل عبدلي',
                'category'       => 'exterior',
                'description_en' => 'A sprawling farm estate transformed with contemporary architectural design and curated exterior landscaping.',
                'description_ar' => 'مزرعة واسعة تحوّلت بتصميم معماري عصري وتنسيق حدائق خارجي يجمع بين الطبيعة والحياة الحديثة.',
                'cover_image'    => $b . '2024/07/DJI_0692-1-scaled.jpg',
                'gallery'        => [
                    $b . '2024/07/1X5A4687-scaled.jpg',
                    $b . '2024/07/1X5A4690-scaled.jpg',
                    $b . '2024/07/1X5A4700-scaled.jpg',
                    $b . '2024/07/1X5A4701-scaled.jpg',
                    $b . '2024/07/1X5A4702-scaled.jpg',
                    $b . '2024/07/1X5A4703-scaled.jpg',
                    $b . '2024/07/1X5A4704-scaled.jpg',
                    $b . '2024/07/1X5A4709-scaled.jpg',
                    $b . '2024/07/1X5A4710-scaled.jpg',
                    $b . '2024/07/1X5A4714-scaled.jpg',
                    $b . '2024/07/1X5A4715-scaled.jpg',
                    $b . '2024/07/1X5A4716-scaled.jpg',
                    $b . '2024/07/1X5A4717-scaled.jpg',
                    $b . '2024/07/1X5A4718-scaled.jpg',
                    $b . '2024/07/1X5A4761-scaled.jpg',
                    $b . '2024/07/1X5A4772-scaled.jpg',
                    $b . '2024/07/1X5A4774-scaled.jpg',
                    $b . '2024/07/1X5A4776-scaled.jpg',
                    $b . '2024/07/1X5A4777-scaled.jpg',
                    $b . '2024/07/1X5A4778-scaled.jpg',
                    $b . '2024/07/1X5A4790-scaled.jpg',
                    $b . '2024/07/1X5A4793-scaled.jpg',
                    $b . '2024/07/1X5A4800-scaled.jpg',
                    $b . '2024/07/1X5A4803-scaled.jpg',
                    $b . '2024/07/1X5A4806-scaled.jpg',
                    $b . '2024/07/1X5A4807-scaled.jpg',
                    $b . '2024/07/DJI_0681-scaled.jpg',
                    $b . '2024/07/DJI_0682-scaled.jpg',
                    $b . '2024/07/DJI_0683-scaled.jpg',
                    $b . '2024/07/DJI_0684-scaled.jpg',
                    $b . '2024/07/DJI_0692-scaled.jpg',
                    $b . '2024/07/DJI_0573-scaled.jpg',
                    $b . '2024/07/IMGL9030-scaled.jpg',
                ],
                'is_featured'    => true,
                'is_active'      => true,
                'order'          => 2,
            ],

            // 3. Al Seef Hospital — Administrative
            [
                'title_en'       => 'Al Seef Hospital',
                'title_ar'       => 'مستشفى السيف',
                'category'       => 'administrative',
                'description_en' => 'A state-of-the-art hospital interior prioritising patient wellbeing, with welcoming reception areas and calming waiting spaces designed to reduce anxiety.',
                'description_ar' => 'تصميم داخلي متطور لمستشفى يضع راحة المريض أولاً، مع مناطق استقبال ترحيبية وقاعات انتظار مريحة.',
                'cover_image'    => $b . '2024/07/IMGL9030-scaled.jpg',
                'gallery'        => [
                    $b . '2024/10/reception-final-1-scaled.jpg',
                    $b . '2024/10/reception-final-2-scaled.jpg',
                    $b . '2024/10/reception-final-4-scaled.jpg',
                    $b . '2024/10/reception-final-5-scaled.jpg',
                    $b . '2024/10/reception-final-6-scaled.jpg',
                    $b . '2024/10/reception-final-7-scaled.jpg',
                    $b . '2024/10/reception-final-8-scaled.jpg',
                    $b . '2024/10/1-1-scaled.jpg',
                    $b . '2024/10/2-1-scaled.jpg',
                    $b . '2024/10/3-1-scaled.jpg',
                    $b . '2024/10/4-1-scaled.jpg',
                    $b . '2024/10/5-1-scaled.jpg',
                    $b . '2024/10/6-1-scaled.jpg',
                    $b . '2024/10/7-1-scaled.jpg',
                    $b . '2024/10/8-1-scaled.jpg',
                    $b . '2024/10/9-1-scaled.jpg',
                    $b . '2024/10/10-scaled.jpg',
                    $b . '2024/10/f1-1-scaled.jpg',
                    $b . '2024/10/f5-1-scaled.jpg',
                    $b . '2024/10/f6-1-scaled.jpg',
                    $b . '2024/10/f7-1-scaled.jpg',
                    $b . '2024/10/f8-1-scaled.jpg',
                    $b . '2024/10/121-scaled.jpg',
                ],
                'is_featured'    => true,
                'is_active'      => true,
                'order'          => 3,
            ],

            // 4. Alhadi Hospital — Administrative
            [
                'title_en'       => 'Alhadi Hospital',
                'title_ar'       => 'مستشفى هادي',
                'category'       => 'administrative',
                'description_en' => 'A comprehensive healthcare interior fit-out combining clinical precision with a warm, humanised environment.',
                'description_ar' => 'تجهيز داخلي شامل لمنشأة صحية يجمع بين الدقة السريرية وبيئة دافئة إنسانية.',
                'cover_image'    => $b . '2024/07/IMG_0021-1-scaled.jpg',
                'gallery'        => [
                    $b . '2024/07/1.jpg',
                    $b . '2024/07/3.jpg',
                    $b . '2024/07/5-1.jpg',
                    $b . '2024/07/7.jpg',
                    $b . '2024/07/9.jpg',
                    $b . '2024/07/f1-scaled.jpg',
                    $b . '2024/07/f5-scaled.jpg',
                    $b . '2024/07/f6-scaled.jpg',
                    $b . '2024/07/f7-scaled.jpg',
                    $b . '2024/07/f8-scaled.jpg',
                    $b . '2024/10/430277216_1352287925461347_1482441092481871420_n.jpg',
                    $b . '2024/10/430187672_1163210438027805_4478764908786592930_n.jpg',
                    $b . '2024/10/429570926_316724571386081_5150029571726570143_n.jpg',
                    $b . '2024/10/409995927_1372748510299834_5922128585773870750_n.jpg',
                    $b . '2024/10/409770019_797370845486775_2885072288991995327_n.jpg',
                    $b . '2024/10/409514150_346654444648260_7924834200601429968_n.jpg',
                ],
                'is_featured'    => false,
                'is_active'      => true,
                'order'          => 4,
            ],

            // 5. International Hospital — Administrative
            [
                'title_en'       => 'International Hospital',
                'title_ar'       => 'المستشفى الدولي',
                'category'       => 'administrative',
                'description_en' => 'An internationally-inspired hospital design upholding the highest global standards of medical facility aesthetics and functional workflow.',
                'description_ar' => 'تصميم مستشفى دولي يرتقي بأعلى المعايير العالمية لجماليات المنشآت الطبية والكفاءة التشغيلية.',
                'cover_image'    => $b . '2024/07/1X5A0045-scaled.jpg',
                'gallery'        => [
                    $b . '2024/04/photo1-scaled.jpg',
                    $b . '2024/04/photo1-1-scaled.jpg',
                    $b . '2024/04/photo1-2-scaled.jpg',
                    $b . '2024/04/photo2-scaled.jpg',
                    $b . '2024/04/1-scaled.jpg',
                    $b . '2024/04/1-1-scaled.jpg',
                    $b . '2024/04/2-scaled.jpg',
                    $b . '2024/04/2-1-scaled.jpg',
                    $b . '2024/04/4-scaled.jpg',
                    $b . '2024/04/5-1-scaled.jpg',
                    $b . '2024/04/6-scaled.jpg',
                    $b . '2024/04/7-scaled.jpg',
                    $b . '2024/04/8-scaled.jpg',
                    $b . '2024/04/9-scaled.jpg',
                    $b . '2024/10/392930496_1291836064824358_4658950987903062964_n.jpg',
                    $b . '2024/10/391472076_285503621110982_2906889662971802851_n.jpg',
                    $b . '2024/10/386898039_3357245487900018_4018734360447413512_n.jpg',
                ],
                'is_featured'    => false,
                'is_active'      => true,
                'order'          => 5,
            ],

            // 6. Mountain View — Residential
            [
                'title_en'       => 'Mountain View',
                'title_ar'       => 'ماونتن فيو',
                'category'       => 'residential',
                'description_en' => 'A premium residential project capturing panoramic views through thoughtful space planning, refined materials, and seamless indoor-outdoor living.',
                'description_ar' => 'مشروع سكني راقٍ يجسّد الإطلالات البانورامية من خلال تخطيط فضائي مدروس ومواد راقية.',
                'cover_image'    => $b . '2023/10/14.png',
                'gallery'        => [
                    $b . '2023/10/2.png',
                    $b . '2023/10/3.png',
                    $b . '2023/10/4.png',
                    $b . '2023/10/5.png',
                    $b . '2023/10/6.png',
                    $b . '2023/10/7.png',
                    $b . '2023/10/8.png',
                    $b . '2023/10/9.png',
                    $b . '2023/10/10.png',
                    $b . '2023/10/12.png',
                    $b . '2023/10/13.png',
                    $b . '2023/10/14.png',
                ],
                'is_featured'    => false,
                'is_active'      => true,
                'order'          => 6,
            ],
        ];

        foreach ($projects as $data) {
            Project::create($data);
        }

        $this->info('  ✓ ' . count($projects) . ' projects imported.');
    }
}
