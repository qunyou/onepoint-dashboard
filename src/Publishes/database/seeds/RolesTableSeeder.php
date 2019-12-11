<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'role_name' => 'Admin',
            'status' => '啟用',
            'sort' => 1,
            'permissions' => '{"read-FaqController": true, "read-RoleController": true, "read-UserController": true, "create-FaqController": true, "delete-FaqController": true, "read-ColorController": true, "read-StyleController": true, "update-FaqController": true, "create-RoleController": true, "create-UserController": true, "delete-RoleController": true, "delete-UserController": true, "read-DemandController": true, "read-LayoutController": true, "read-MemberController": true, "update-RoleController": true, "update-UserController": true, "create-ColorController": true, "create-StyleController": true, "delete-ColorController": true, "delete-StyleController": true, "read-ArticleController": true, "read-SettingController": true, "update-ColorController": true, "update-StyleController": true, "create-LayoutController": true, "create-MemberController": true, "delete-DemandController": true, "delete-LayoutController": true, "delete-MemberController": true, "read-DesignerController": true, "update-DemandController": true, "update-LayoutController": true, "update-MemberController": true, "create-ArticleController": true, "delete-ArticleController": true, "update-ArticleController": true, "update-SettingController": true, "create-DesignerController": true, "delete-DesignerController": true, "read-DesignCaseController": true, "update-DesignerController": true, "read-FaqCategoryController": true, "read-PartnerLogoController": true, "read-TestimonialController": true, "delete-DesignCaseController": true, "read-NaviCategoryController": true, "update-DesignCaseController": true, "create-FaqCategoryController": true, "create-PartnerLogoController": true, "create-TestimonialController": true, "delete-FaqCategoryController": true, "delete-PartnerLogoController": true, "delete-TestimonialController": true, "update-FaqCategoryController": true, "update-PartnerLogoController": true, "update-TestimonialController": true, "create-NaviCategoryController": true, "delete-NaviCategoryController": true, "update-NaviCategoryController": true, "read-ArticleCategoryController": true, "create-ArticleCategoryController": true, "delete-ArticleCategoryController": true, "update-ArticleCategoryController": true, "read-TestimonialCategoryController": true, "create-TestimonialCategoryController": true, "delete-TestimonialCategoryController": true, "update-TestimonialCategoryController": true}'
        ]);
    }
}
