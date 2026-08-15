<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        // ── HOME PAGE ─────────────────────────────────────────────────────────
        $home = Page::where('slug', 'home')->first();
        if ($home) {
            $homeSections = [
                [
                    'key'   => 'hero',
                    'order' => 1,
                    'content' => [
                        'title_en'    => 'Solutions to Transform Your Space',
                        'title_ar'    => 'حلول لتحويل مساحتك',
                        'subtitle_en' => 'Art and Science in Perfect Balance.',
                        'subtitle_ar' => 'الفن والعلم في توازن مثالي.',
                        'cta_en'      => 'Get Started',
                        'cta_ar'      => 'ابدأ الآن',
                    ],
                ],
                [
                    'key'   => 'about_intro',
                    'order' => 2,
                    'content' => [
                        'label_en'   => 'About Our Company',
                        'label_ar'   => 'عن شركتنا',
                        'text_en'    => "Founded in 1983 as a carpentry business, Eslam Abdulghani Designs has grown into a full-service design and construction company. We began operations in Kuwait City in 2018 and expanded to Egypt in 2020.\n\nWe provide creative design solutions with the highest standards of honesty and competence. Our passion for sustainability and quality craftsmanship ensures we exceed customer expectations and make a lasting impact on communities.",
                        'text_ar'    => "تأسست إسلام عبد الغني ديزاينز عام 1983 كمصنع للنجارة، ونمت لتصبح شركة متكاملة للتصميم والإنشاءات. بدأنا عملياتنا في مدينة الكويت عام 2018 وتوسعنا إلى مصر عام 2020.\n\nنقدم حلول تصميم إبداعية بأعلى معايير الأمانة والكفاءة. شغفنا بالاستدامة وجودة الحرفية يضمن تجاوز توقعات عملائنا وترك أثر دائم في المجتمعات.",
                        'cta_en'     => 'Get Started',
                        'cta_ar'     => 'ابدأ الآن',
                    ],
                ],
                [
                    'key'   => 'services_overview',
                    'order' => 3,
                    'content' => [
                        'title_en'    => 'Our Services',
                        'title_ar'    => 'خدماتنا',
                        'subtitle_en' => 'A company specialized in the field of exterior, interior and landscaping design',
                        'subtitle_ar' => 'شركة متخصصة في مجال التصميم الخارجي والداخلي وتنسيق المناظر الطبيعية',
                    ],
                ],
                [
                    'key'   => 'projects_grid',
                    'order' => 4,
                    'content' => [
                        'title_en'    => 'Featured Work',
                        'title_ar'    => 'أعمالنا المميزة',
                        'subtitle_en' => 'Each project reflects our commitment to excellence and our passion for transforming spaces.',
                        'subtitle_ar' => 'كل مشروع يعكس التزامنا بالتميز وشغفنا بتحويل المساحات.',
                    ],
                ],
                [
                    'key'   => 'cta_consultation',
                    'order' => 5,
                    'content' => [
                        'title_en'  => 'Do you have a vision for your project, but are you unsure about the planning implications or how the project will run?',
                        'title_ar'  => 'هل لديك رؤية لمشروعك، لكنك غير متأكد من تداعيات التخطيط أو كيفية تنفيذ المشروع؟',
                        'body_en'   => 'Call us or fill the form to request a 30-minute consultation.',
                        'body_ar'   => 'اتصل بنا أو املأ النموذج لطلب استشارة مدتها 30 دقيقة.',
                        'cta_en'    => 'Give Us a Call',
                        'cta_ar'    => 'تواصل معنا',
                    ],
                ],
                [
                    'key'   => 'contact_section',
                    'order' => 6,
                    'content' => [
                        'title_en' => 'Contact Us',
                        'title_ar' => 'اتصل بنا',
                        'body_en'  => 'Eslam Abdulghani Designs is a leader in providing interior fit-out and design services to its clientele by partnering with them throughout the designing and construction process; while going the extra mile to genuinely understand their unique needs and requirements.',
                        'body_ar'  => 'إسلام عبد الغني ديزاينز رائدة في تقديم خدمات التشييد والتصميم الداخلي لعملائها من خلال الشراكة معهم طوال عملية التصميم والبناء، مع بذل جهد إضافي لفهم احتياجاتهم الفريدة.',
                    ],
                ],
            ];

            foreach ($homeSections as $s) {
                Section::updateOrCreate(
                    ['page_id' => $home->id, 'key' => $s['key']],
                    ['content' => $s['content'], 'order' => $s['order'], 'is_active' => true]
                );
            }
        }

        // ── ABOUT PAGE ────────────────────────────────────────────────────────
        $about = Page::where('slug', 'about')->first();
        if ($about) {
            $aboutSections = [
                [
                    'key'   => 'hero',
                    'order' => 1,
                    'content' => [
                        'title_en'    => 'About Eslam Abdulghani Designs',
                        'title_ar'    => 'عن إسلام عبد الغني ديزاينز',
                        'subtitle_en' => 'Leading the way in creative design and construction excellence since 1983.',
                        'subtitle_ar' => 'ريادة الطريق في الإبداع والتميز الإنشائي منذ عام 1983.',
                        'image'       => '/images/eslam/eslam_executive_seated.jpg',
                    ],
                ],
                [
                    'key'   => 'story',
                    'order' => 2,
                    'content' => [
                        'title_en' => 'Our Legacy',
                        'title_ar' => 'إرثنا',
                        'body_en'  => "Founded in 1983 as a carpentry business, Eslam Abdulghani Designs has grown into a full-service design and construction company. We began operations in Kuwait City in 2018 and expanded to Egypt in 2020.\n\nWe provide creative design solutions with the highest standards of honesty and competence. Our passion for sustainability and quality craftsmanship ensures we exceed customer expectations and make a lasting impact on communities.",
                        'body_ar'  => "تأسست إسلام عبد الغني ديزاينز عام 1983 كمصنع للنجارة، ونمت لتصبح شركة متكاملة للتصميم والإنشاءات. بدأنا عملياتنا في مدينة الكويت عام 2018 وتوسعنا إلى مصر عام 2020.\n\nنقدم حلول تصميم إبداعية بأعلى معايير الأمانة والكفاءة. شغفنا بالاستدامة وجودة الحرفية يضمن تجاوز توقعات عملائنا وترك أثر دائم في المجتمعات.",
                        'image'    => '/images/eslam/eslam_studio_measuring_banner.jpg',
                    ],
                ],
                [
                    'key'   => 'mission',
                    'order' => 3,
                    'content' => [
                        'title_en' => 'Our Mission',
                        'title_ar' => 'مهمتنا',
                        'body_en'  => 'Eslam Abdulghani Designs is a leader in providing interior fit-out and design services to its clientele by partnering with them throughout the designing and construction process; while going the extra mile to genuinely understand their unique needs and requirements. Our commitment to quality and excellence is second to none.',
                        'body_ar'  => 'إسلام عبد الغني ديزاينز رائدة في تقديم خدمات التشييد والتصميم الداخلي لعملائها من خلال الشراكة معهم طوال عملية التصميم والبناء، مع بذل جهد إضافي لفهم احتياجاتهم الفريدة. التزامنا بالجودة والتميز لا مثيل له.',
                        'image'    => '/images/eslam/eslam_consultation_presenting.jpg',
                    ],
                ],
                [
                    'key'   => 'expertise',
                    'order' => 4,
                    'content' => [
                        'title_en' => 'Our Expertise',
                        'title_ar' => 'خبراتنا',
                        'body_en'  => 'We specialize in construction, interior design, finishes, furnishing, house design, landscape, timber and architectural design.',
                        'body_ar'  => 'نتخصص في الإنشاءات، التصميم الداخلي، التشطيبات، الأثاث، تصميم المنازل، تنسيق المواقع، والنجارة والتصميم المعماري.',
                        'image'    => '/images/eslam/eslam_site_visit.jpg',
                    ],
                ],
            ];

            foreach ($aboutSections as $s) {
                Section::updateOrCreate(
                    ['page_id' => $about->id, 'key' => $s['key']],
                    ['content' => $s['content'], 'order' => $s['order'], 'is_active' => true]
                );
            }
        }

        // ── SERVICES PAGE ─────────────────────────────────────────────────────
        $services = Page::where('slug', 'services')->first();
        if ($services) {
            $servicesSections = [
                [
                    'key'   => 'hero',
                    'order' => 1,
                    'content' => [
                        'title_en'    => 'Our Services',
                        'title_ar'    => 'خدماتنا',
                        'subtitle_en' => 'A company specialized in the field of exterior, interior and landscaping design',
                        'subtitle_ar' => 'شركة متخصصة في مجال التصميم الخارجي والداخلي وتنسيق المناظر الطبيعية',
                    ],
                ],
                [
                    'key'   => 'services_grid',
                    'order' => 2,
                    'content' => [
                        'title_en' => 'What We Offer',
                        'title_ar' => 'ما نقدمه',
                    ],
                ],
                [
                    'key'   => 'cta',
                    'order' => 3,
                    'content' => [
                        'title_en' => 'Ready to Start Your Project?',
                        'title_ar' => 'هل أنت مستعد لبدء مشروعك؟',
                        'body_en'  => 'Contact us for a consultation and let us help you transform your space.',
                        'body_ar'  => 'تواصل معنا للحصول على استشارة ودعنا نساعدك في تحويل مساحتك.',
                        'cta_en'   => 'Get Started',
                        'cta_ar'   => 'ابدأ الآن',
                    ],
                ],
            ];

            foreach ($servicesSections as $s) {
                Section::updateOrCreate(
                    ['page_id' => $services->id, 'key' => $s['key']],
                    ['content' => $s['content'], 'order' => $s['order'], 'is_active' => true]
                );
            }
        }

        // ── PROJECTS PAGE ─────────────────────────────────────────────────────
        $projects = Page::where('slug', 'projects')->first();
        if ($projects) {
            $projectsSections = [
                [
                    'key'   => 'hero',
                    'order' => 1,
                    'content' => [
                        'title_en'    => 'Our Projects',
                        'title_ar'    => 'مشاريعنا',
                        'subtitle_en' => 'Explore our portfolio of completed design and construction projects across Kuwait and Egypt.',
                        'subtitle_ar' => 'استكشف محفظة مشاريعنا المنجزة في مجال التصميم والإنشاءات في الكويت ومصر.',
                    ],
                ],
                [
                    'key'   => 'projects_grid',
                    'order' => 2,
                    'content' => [
                        'title_en'    => 'Featured Work',
                        'title_ar'    => 'أعمالنا المميزة',
                        'subtitle_en' => 'Each project reflects our commitment to excellence and our passion for transforming spaces.',
                        'subtitle_ar' => 'كل مشروع يعكس التزامنا بالتميز وشغفنا بتحويل المساحات.',
                    ],
                ],
            ];

            foreach ($projectsSections as $s) {
                Section::updateOrCreate(
                    ['page_id' => $projects->id, 'key' => $s['key']],
                    ['content' => $s['content'], 'order' => $s['order'], 'is_active' => true]
                );
            }
        }

        // ── CONTACT PAGE ──────────────────────────────────────────────────────
        $contact = Page::where('slug', 'contact')->first();
        if ($contact) {
            $contactSections = [
                [
                    'key'   => 'hero',
                    'order' => 1,
                    'content' => [
                        'title_en'    => 'Contact Us',
                        'title_ar'    => 'اتصل بنا',
                        'subtitle_en' => 'Get in touch with our team for a free consultation',
                        'subtitle_ar' => 'تواصل مع فريقنا للحصول على استشارة مجانية',
                    ],
                ],
                [
                    'key'   => 'contact_form',
                    'order' => 2,
                    'content' => [
                        'heading_en'      => 'Send Us a Message',
                        'heading_ar'      => 'أرسل لنا رسالة',
                        'description_en'  => 'Fill out the form below and our team will respond within 24 hours.',
                        'description_ar'  => 'أكمل النموذج أدناه وسيرد فريقنا في غضون 24 ساعة.',
                    ],
                ],
                [
                    'key'   => 'offices',
                    'order' => 3,
                    'content' => [
                        'title_en'   => 'Our Offices',
                        'title_ar'   => 'مكاتبنا',
                        'kuwait_en'  => 'Oula Tower, 3 Khalid Ibn Al Waleed St, Kuwait City',
                        'kuwait_ar'  => 'برج أولى، شارع خالد بن الوليد 3، مدينة الكويت',
                        'egypt_en'   => 'Beverly Hills – The Polygon Business Park, El Sheikh Zayed, Egypt',
                        'egypt_ar'   => 'بيفرلي هيلز – مجمع بولجون للأعمال، الشيخ زايد، مصر',
                        'phone_kw'   => '+965 5505 3010',
                        'phone_eg'   => '+20 100 559 8277',
                        'email'      => 'info@eslamabdulghanidesigns.com',
                    ],
                ],
            ];

            foreach ($contactSections as $s) {
                Section::updateOrCreate(
                    ['page_id' => $contact->id, 'key' => $s['key']],
                    ['content' => $s['content'], 'order' => $s['order'], 'is_active' => true]
                );
            }
        }
    }
}
