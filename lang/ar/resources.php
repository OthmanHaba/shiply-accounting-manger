<?php

return [
    'invoice_resource' => [
        // Navigation & General
        'navigation_label' => 'الفواتير',
        'model_label' => 'فاتورة',
        'plural_model_label' => 'الفواتير',

        // Form Sections
        'invoice_details_section' => [
            'title' => 'تفاصيل الفاتورة',
            'description' => 'معلومات الفاتورة الأساسية وتفاصيل العميل',
        ],

        'summary_section' => [
            'title' => 'الملخص',
            'description' => 'إجماليات الفاتورة والحسابات',
        ],

        'invoice_items_section' => [
            'title' => 'عناصر الفاتورة',
            'description' => 'إضافة المنتجات والخدمات لهذه الفاتورة',
        ],

        // Form Fields
        'fields' => [
            'customer_id' => 'العميل',
            'type' => 'نوع الفاتورة',
            'discount' => 'الخصم (%)',
            'notes' => 'الملاحظات',
            'notes_placeholder' => 'ملاحظات أو تعليقات إضافية...',
            'total_price' => 'المبلغ الإجمالي',

            // Invoice Items Fields
            'item_id' => 'المنتج/الخدمة',
            'name' => 'اسم العنصر',
            'item_type' => 'النوع',
            'item_count' => 'الكمية',
            'unit_price' => 'سعر الوحدة',
            'currency_id' => 'العملة',
            'weight' => 'الوزن (كيلو)',
            'description' => 'الوصف',
        ],

        // Repeater
        'repeater' => [
            'item_label' => 'عنصر جديد',
            'add_action_label' => 'إضافة عنصر للفاتورة',
        ],

        // Table Columns
        'table' => [
            'customer' => 'العميل',
            'type' => 'النوع',
            'total' => 'الإجمالي',
            'discount' => 'الخصم',
            'items_count' => 'العناصر',
            'created' => 'تاريخ الإنشاء',
        ],

        // Actions
        'actions' => [
            'view' => 'عرض الفاتورة',
            'edit' => 'تعديل الفاتورة',
            'delete' => 'حذف الفاتورة',
        ],

        // Badge Colors (for reference, these would be used in the resource file)
        'badge_colors' => [
            'sale' => 'بيع',
            'purchase' => 'شراء',
            'service' => 'خدمة',
            'credit' => 'دائن',
            'debit' => 'مدين',
        ],

        // Globally Searchable Attributes (for reference)
        'searchable' => [
            'customer_name' => 'اسم العميل',
            'notes' => 'الملاحظات',
            'type' => 'النوع',
        ],
    ],

    'customer_resource' => [
        // Keep existing customer translations here
    ],
];
