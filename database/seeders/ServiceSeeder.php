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
                'image'          => '/images/services/administrative-1080.jpg',
                'gallery'        => [],
                'icon'           => null,
                'order'          => 1,
                'is_active'      => true,
            ],
            [
                'title_en'       => 'Commercial Design',
                'title_ar'       => 'التصميم التجاري',
                'description_en' => 'Commercial design is essential to the success of any company.',
                'description_ar' => 'التصميم التجاري أمر أساسي لنجاح أي شركة.',
                'image'          => '/images/services/commercial-1080.jpg',
                'icon'           => null,
                'order'          => 2,
                'is_active'      => true,
            ],
            [
                'title_en'       => 'Residential Design',
                'title_ar'       => 'التصميم السكني',
                'description_en' => 'Beautiful, functional residential spaces that reflect your style.',
                'description_ar' => 'مساحات سكنية جميلة وعملية تعكس أسلوبك.',
                'image'          => '/images/services/residential-1080.jpg',
                'icon'           => null,
                'order'          => 3,
                'is_active'      => true,
            ],
            [
                'title_en'       => 'Exterior Design',
                'title_ar'       => 'التصميم الخارجي',
                'description_en' => 'Exterior design is crucial for making a strong first impression.',
                'description_ar' => 'التصميم الخارجي أمر بالغ الأهمية لترك انطباع أول قوي.',
                'image'          => '/images/services/exterior-1080.jpg',
                'icon'           => null,
                'order'          => 4,
                'is_active'      => true,
            ],
            [
                'title_en'       => 'Hospitality Design',
                'title_ar'       => 'تصميم الضيافة',
                'description_en' => 'Creating memorable experiences for guests through luxury and functionality.',
                'description_ar' => 'خلق تجارب لا تُنسى للضيوف من خلال الفخامة والوظيفية.',
                'image'          => '/images/services/hospitality-1080.jpg',
                'icon'           => null,
                'order'          => 5,
                'is_active'      => true,
            ],
            [
                'title_en'       => 'Landscape Design',
                'title_ar'       => 'تصميم المناظر الطبيعية',
                'description_en' => 'Outdoor spaces that harmonize with nature and architecture.',
                'description_ar' => 'مساحات خارجية تتناغم مع الطبيعة والعمارة.',
                'image'          => '/images/services/landscape-1080.jpg',
                'icon'           => null,
                'order'          => 6,
                'is_active'      => true,
            ],
            [
                'title_en'       => 'Retail Design',
                'title_ar'       => 'تصميم التجزئة',
                'description_en' => 'Retail environments that showcase products and foster brand loyalty.',
                'description_ar' => 'بيئات تجزئة تعرض المنتجات وتعزز الولاء للعلامة التجارية.',
                'image'          => '/images/services/retail-1080.jpg',
                'icon'           => null,
                'order'          => 7,
                'is_active'      => true,
            ],
            [
                'title_en'       => 'Industrial Design',
                'title_ar'       => 'التصميم الصناعي',
                'description_en' => 'Functional industrial spaces that optimize workflow and safety.',
                'description_ar' => 'مساحات صناعية وظيفية تعمل على تحسين سير العمل والسلامة.',
                'image'          => '/images/services/industrial-1080.jpg',
                'icon'           => null,
                'order'          => 8,
                'is_active'      => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
