<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        Service::truncate();

        $services = [
            [
                'title_en'       => 'Administrative',
                'title_ar'       => 'إداري',
                'description_en' => 'Elevating Administrative Spaces with Innovative Design Solutions. We specialize in creating efficient, sustainable, and aesthetically pleasing environments tailored to administrative buildings’ unique needs. The significance of effective administrative design lies in its ability to signify organizational advancement and bolster client trust.',
                'description_ar' => 'الارتقاء بالمساحات الإدارية من خلال حلول التصميم المبتكرة. نحن متخصصون في إنشاء بيئات فعالة ومستدامة وجميلة ومصممة خصيصًا لتلبية الاحتياجات الفريدة للمباني الإدارية. تكمن أهمية التصميم الإداري الفعال في قدرته على الإشارة إلى التقدم التنظيمي وتعزيز ثقة العملاء.',
                'image'          => 'https://indesign-co.com/wp-content/uploads/2024/06/SLS02666-scaled.jpg',
                'gallery'        => [
                    'https://indesign-co.com/wp-content/uploads/2024/06/1-scaled.jpg',
                    'https://indesign-co.com/wp-content/uploads/2024/06/2-scaled.jpg',
                    'https://indesign-co.com/wp-content/uploads/2024/06/3-scaled.jpg',
                    'https://indesign-co.com/wp-content/uploads/2024/06/4-scaled.jpg',
                ],
                'icon'           => 'https://indesign-co.com/wp-content/uploads/elementor/thumbs/001-01-qoz8m7bq30sumoc0m0686c97vqcnhwhli2zhfg9kw4.png',
                'order'          => 1,
                'is_active'      => true,
            ],
            [
                'title_en'       => 'Commercial Design',
                'title_ar'       => 'التصميم التجاري',
                'description_en' => 'Commercial design is essential to the success of any company.',
                'description_ar' => 'التصميم التجاري أمر أساسي لنجاح أي شركة.',
                'image'          => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=1200',
                'icon'           => 'https://cdn-icons-png.flaticon.com/512/2859/2859816.png',
                'order'          => 2,
                'is_active'      => true,
            ],
            [
                'title_en'       => 'Residential Design',
                'title_ar'       => 'التصميم السكني',
                'description_en' => 'Beautiful, functional residential spaces that reflect your style.',
                'description_ar' => 'مساحات سكنية جميلة وعملية تعكس أسلوبك.',
                'image'          => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&q=80&w=1200',
                'icon'           => 'https://cdn-icons-png.flaticon.com/512/2544/2544087.png',
                'order'          => 3,
                'is_active'      => true,
            ],
            [
                'title_en'       => 'Exterior Design',
                'title_ar'       => 'التصميم الخارجي',
                'description_en' => 'Exterior design is crucial for making a strong first impression.',
                'description_ar' => 'التصميم الخارجي أمر بالغ الأهمية لترك انطباع أول قوي.',
                'image'          => 'https://images.unsplash.com/photo-1558449028-b53a39d100fc?auto=format&fit=crop&q=80&w=1200',
                'icon'           => 'https://cdn-icons-png.flaticon.com/512/3259/3259166.png',
                'order'          => 4,
                'is_active'      => true,
            ],
            [
                'title_en'       => 'Hospitality Design',
                'title_ar'       => 'تصميم الضيافة',
                'description_en' => 'Creating memorable experiences for guests through luxury and functionality.',
                'description_ar' => 'خلق تجارب لا تُنسى للضيوف من خلال الفخامة والوظيفية.',
                'image'          => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&q=80&w=1200',
                'icon'           => 'https://cdn-icons-png.flaticon.com/512/2313/2313962.png',
                'order'          => 5,
                'is_active'      => true,
            ],
            [
                'title_en'       => 'Landscape Design',
                'title_ar'       => 'تصميم المناظر الطبيعية',
                'description_en' => 'Outdoor spaces that harmonize with nature and architecture.',
                'description_ar' => 'مساحات خارجية تتناغم مع الطبيعة والعمارة.',
                'image'          => 'https://images.unsplash.com/photo-1558449028-b53a39d100fc?auto=format&fit=crop&q=80&w=1200',
                'icon'           => 'https://cdn-icons-png.flaticon.com/512/2544/2544087.png',
                'order'          => 6,
                'is_active'      => true,
            ],
            [
                'title_en'       => 'Retail Design',
                'title_ar'       => 'تصميم التجزئة',
                'description_en' => 'Retail environments that showcase products and foster brand loyalty.',
                'description_ar' => 'بيئات تجزئة تعرض المنتجات وتعزز الولاء للعلامة التجارية.',
                'image'          => 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&q=80&w=1200',
                'icon'           => 'https://cdn-icons-png.flaticon.com/512/2859/2859816.png',
                'order'          => 7,
                'is_active'      => true,
            ],
            [
                'title_en'       => 'Industrial Design',
                'title_ar'       => 'التصميم الصناعي',
                'description_en' => 'Functional industrial spaces that optimize workflow and safety.',
                'description_ar' => 'مساحات صناعية وظيفية تعمل على تحسين سير العمل والسلامة.',
                'image'          => 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&q=80&w=1200',
                'icon'           => 'https://cdn-icons-png.flaticon.com/512/2622/2622814.png',
                'order'          => 8,
                'is_active'      => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
