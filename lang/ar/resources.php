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
            'code' => 'رقم الفاتورة',
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
            'print' => 'طباعة الفاتورة',
        ],

        // Info List Labels
        'info_list' => [
            'created_date' => 'تاريخ الإنشاء',
            'last_updated' => 'آخر تحديث',
            'customer_name' => 'اسم العميل',
            'customer_code' => 'رمز العميل',
            'phone_number' => 'رقم الهاتف',
            'customer_since' => 'عميل منذ',
            'no_notes' => 'لا توجد ملاحظات',
            'no_discount' => 'لا يوجد خصم',
            'invoice_totals' => 'إجماليات الفاتورة حسب العملة',
            'detailed_breakdown' => 'تفصيل مفصل لعناصر الفاتورة',
            'item_name' => 'اسم العنصر',
            'type' => 'النوع',
            'quantity' => 'الكمية',
            'unit_price' => 'سعر الوحدة',
            'total' => 'الإجمالي',
            'weight_kg' => 'الوزن (كيلو)',
            'currency' => 'العملة',
            'description' => 'الوصف',
            'not_specified' => 'غير محدد',
            'no_description' => 'لا يوجد وصف',
            'system_information' => 'معلومات النظام',
            'invoice_id' => 'رقم الفاتورة',
            'total_items' => 'إجمالي العناصر',
            'related_receipts' => 'الإيصالات المرتبطة',
            'invoice_code_copied' => 'تم نسخ رمز الفاتورة!',
        ],

        // Print Template
        'print' => [
            'title' => 'فاتورة',
            'print_button' => 'طباعة',
            'invoice_title' => 'فاتورة',
            'invoice_code' => 'رمز الفاتورة',
            'date' => 'التاريخ',
            'type' => 'النوع',
            'customer_info' => 'معلومات العميل',
            'customer_name' => 'اسم العميل',
            'customer_code' => 'رمز العميل',
            'customer_phone' => 'الهاتف',
            'not_available' => 'غير متوفر',
            'notes' => 'الملاحظات',
            'items' => 'العناصر',
            'item_name' => 'اسم العنصر',
            'quantity' => 'الكمية',
            'price' => 'السعر',
            'total' => 'الإجمالي',
            'discount' => 'الخصم',
            'thank_you' => 'شكراً لتعاملكم معنا!',
            'generated_at' => 'تم الإنشاء في',
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

    'receipt_resource' => [
        // Navigation & General
        'navigation_label' => 'الإيصالات',
        'model_label' => 'إيصال',
        'plural_model_label' => 'الإيصالات',

        // Form Sections
        'receipt_details_section' => [
            'title' => 'تفاصيل الإيصال',
            'description' => 'معلومات الإيصال الأساسية وتفاصيل العميل',
        ],

        'payment_section' => [
            'title' => 'معلومات الدفع',
            'description' => 'المبلغ والعملة وتفاصيل الدفع',
        ],

        'invoice_selection_section' => [
            'title' => 'اختيار الفواتير',
            'description' => 'اختر الفواتير التي سيتم دفعها بهذا الإيصال',
        ],

        // Form Fields
        'fields' => [
            'note' => 'ملاحظة الإيصال',
            'note_placeholder' => 'أدخل وصف الإيصال أو الملاحظة...',
            'amount' => 'المبلغ',
            'amount_placeholder' => 'أدخل مبلغ الدفع',
            'type' => 'نوع الإيصال',
            'customer_id' => 'العميل',
            'customer_accounts' => 'حسابات العميل',
            'treasure_id' => 'الخزينة',
            'currency_id' => 'العملة',
            'invoices' => 'الفواتير المرتبطة',
            'created_at' => 'تاريخ الإنشاء',
            'updated_at' => 'تاريخ التحديث',
        ],

        // Table Columns
        'table' => [
            'note' => 'الملاحظة',
            'customer' => 'العميل',
            'type' => 'النوع',
            'amount' => 'المبلغ',
            'currency' => 'العملة',
            'invoices_count' => 'الفواتير',
            'created' => 'تاريخ الإنشاء',
        ],

        // Actions
        'actions' => [
            'view' => 'عرض الإيصال',
            'edit' => 'تعديل الإيصال',
            'delete' => 'حذف الإيصال',
            'create' => 'إنشاء إيصال',
            'print' => 'طباعة الإيصال',
        ],

        // Messages
        'messages' => [
            'created' => 'تم إنشاء الإيصال بنجاح',
            'updated' => 'تم تحديث الإيصال بنجاح',
            'deleted' => 'تم حذف الإيصال بنجاح',
            'no_accounts_found' => 'لا توجد حسابات لهذا العميل',
            'credit' => 'دائن',
            'debit' => 'مدين',
        ],

        // Print translations
        'print' => [
            'title' => 'طباعة الإيصال',
            'receipt_title' => 'إيصال ',
            'print_button' => 'طباعة',
            'company_info' => 'معلومات الشركة',
            'company_name' => 'اسم الشركة',
            'company_phone' => 'هاتف الشركة',
            'company_email' => 'البريد الإلكتروني',
            'company_address' => 'العنوان',
            'receipt_info' => 'معلومات الإيصال',
            'receipt_number' => 'رقم الإيصال',
            'date' => 'التاريخ',
            'type' => 'النوع',
            'treasure' => 'الخزينة',
            'customer_info' => 'معلومات العميل',
            'customer_name' => 'اسم العميل',
            'customer_code' => 'كود العميل',
            'customer_phone' => 'هاتف العميل',
            'not_available' => 'غير متوفر',
            'amount_section' => 'المبلغ',
            'amount' => 'المبلغ',
            'notes' => 'الملاحظات',
            'related_invoices' => 'الفواتير المرتبطة',
            'transaction_summary' => 'ملخص العملية',
            'operation_type' => 'نوع العملية',
            'customer' => 'العميل',
            'thank_you' => 'شكراً لك',
            'generated_at' => 'تم إنشاؤه في',
            'receipt_validity' => 'هذا الإيصال صحيح ومعتمد',
            'cut_here' => 'قص من هنا',
        ],

        // Validation
        'validation' => [
            'note_required' => 'ملاحظة الإيصال مطلوبة',
            'amount_required' => 'المبلغ مطلوب',
            'amount_numeric' => 'المبلغ يجب أن يكون رقماً',
            'customer_required' => 'العميل مطلوب',
            'type_required' => 'نوع الإيصال مطلوب',
        ],
    ],

    'treasure_resource' => [
        // Navigation & General
        'navigation_label' => 'الخزائن',
        'model_label' => 'خزينة',
        'plural_model_label' => 'الخزائن',

        // Form Sections
        'treasure_details_section' => [
            'title' => 'معلومات الخزينة',
            'description' => 'تفاصيل الخزينة الأساسية ومعلومات الموقع',
        ],

        // Form Fields
        'fields' => [
            'name' => 'اسم الخزينة',
            'name_placeholder' => 'أدخل اسم الخزينة...',
            'location' => 'الموقع',
            'location_placeholder' => 'أدخل موقع الخزينة...',
            'created_at' => 'تاريخ الإنشاء',
            'updated_at' => 'تاريخ التحديث',
        ],

        // Table Columns
        'table' => [
            'name' => 'الاسم',
            'location' => 'الموقع',
            'created' => 'تاريخ الإنشاء',
        ],

        // Actions
        'actions' => [
            'view' => 'عرض الخزينة',
            'edit' => 'تعديل الخزينة',
            'delete' => 'حذف الخزينة',
            'create' => 'إنشاء خزينة',
        ],

        // Messages
        'messages' => [
            'created' => 'تم إنشاء الخزينة بنجاح',
            'updated' => 'تم تحديث الخزينة بنجاح',
            'deleted' => 'تم حذف الخزينة بنجاح',
        ],

        // Validation
        'validation' => [
            'name_required' => 'اسم الخزينة مطلوب',
            'location_required' => 'الموقع مطلوب',
        ],

        // Relation Manager
        'accounts_relation' => [
            'title' => 'حسابات العملات',
            'description' => 'إدارة حسابات الخزينة للعملات المختلفة',
            'fields' => [
                'code' => 'رمز الحساب',
                'code_placeholder' => 'مثال: TREAS-001-USD',
                'currency_id' => 'العملة',
                'amount' => 'الرصيد',
                'amount_placeholder' => '0.00',
            ],
            'table' => [
                'code' => 'رمز الحساب',
                'currency' => 'العملة',
                'balance' => 'الرصيد',
                'created' => 'تاريخ الإنشاء',
                'updated' => 'تاريخ التحديث',
            ],
            'actions' => [
                'add_account' => 'إضافة حساب',
                'edit_account' => 'تعديل الحساب',
                'delete_account' => 'حذف الحساب',
            ],
            'filters' => [
                'currency' => 'العملة',
                'positive_balance' => 'رصيد موجب',
                'negative_balance' => 'رصيد سالب',
            ],
            'empty_state' => [
                'heading' => 'لا توجد حسابات بعد',
                'description' => 'أنشئ حساباً للبدء في إدارة العملات.',
            ],
        ],
    ],

    'customer_resource' => [
        // Navigation & General
        'navigation_label' => 'العملاء',
        'model_label' => 'عميل',
        'plural_model_label' => 'العملاء',
        'navigation_group' => 'الإعدادات',

        // Form Sections
        'customer_info_section' => [
            'title' => 'معلومات العميل',
            'description' => 'تفاصيل العميل الأساسية ومعلومات الاتصال',
        ],

        // Form Fields
        'fields' => [
            'name' => 'الاسم الكامل',
            'name_placeholder' => 'أدخل الاسم الكامل للعميل',
            'code' => 'رمز العميل',
            'code_placeholder' => 'مثال: CUST-001',
            'phone' => 'رقم الهاتف',
            'phone_placeholder' => 'مثال: +966 50 123 4567',
        ],

        // Table Columns
        'table' => [
            'name' => 'اسم العميل',
            'code' => 'الرمز',
            'phone' => 'الهاتف',
            'account_balance' => 'رصيد الحساب',
        ],

        // Actions
        'actions' => [
            'view' => 'عرض العميل',
            'edit' => 'تعديل العميل',
            'delete' => 'حذف العميل',
            'create' => 'إنشاء عميل',
            'add_receipt' => 'إضافة إيصال',
        ],

        // Messages
        'messages' => [
            'phone_copied' => 'تم نسخ رقم الهاتف!',
            'created' => 'تم إنشاء العميل بنجاح',
            'updated' => 'تم تحديث العميل بنجاح',
            'deleted' => 'تم حذف العميل بنجاح',
        ],

        // Validation
        'validation' => [
            'name_required' => 'اسم العميل مطلوب',
            'code_required' => 'رمز العميل مطلوب',
            'phone_required' => 'رقم الهاتف مطلوب',
            'code_unique' => 'رمز العميل يجب أن يكون فريداً',
            'phone_unique' => 'رقم الهاتف يجب أن يكون فريداً',
        ],

        // Relation Manager
        'accounts_relation' => [
            'title' => 'حسابات العميل',
            'description' => 'إدارة حسابات العميل للعملات المختلفة',
            'fields' => [
                'code' => 'رمز الحساب',
                'code_placeholder' => 'مثال: CUST-001-USD',
                'currency_id' => 'العملة',
                'amount' => 'الرصيد',
                'amount_placeholder' => '0.00',
            ],
            'table' => [
                'code' => 'رمز الحساب',
                'currency' => 'العملة',
                'balance' => 'الرصيد',
                'created' => 'تاريخ الإنشاء',
                'updated' => 'تاريخ التحديث',
            ],
            'actions' => [
                'add_account' => 'إضافة حساب',
                'edit_account' => 'تعديل الحساب',
                'delete_account' => 'حذف الحساب',
            ],
            'filters' => [
                'currency' => 'العملة',
                'positive_balance' => 'رصيد موجب',
                'negative_balance' => 'رصيد سالب',
            ],
            'empty_state' => [
                'heading' => 'لا توجد حسابات بعد',
                'description' => 'أنشئ حساباً للبدء في إدارة العملات.',
            ],
        ],
    ],

    // Reports Resource
    'reports' => [
        'navigation_label' => 'التقارير',
        'title' => 'التقارير المالية',

        // Stats
        'stats' => [
            'total_receipts' => 'إجمالي الإيصالات',
            'total_invoices' => 'إجمالي الفواتير',
            'total_customers' => 'إجمالي العملاء',
            'total_treasures' => 'إجمالي الخزائن',
        ],

        // Financial Reports
        'financial_reports' => [
            'title' => 'التقارير المالية حسب العملة',
            'description' => 'عرض شامل للإيرادات والمصروفات والأرصدة لكل عملة',
            'total_deposits' => 'إجمالي الإيداعات',
            'total_withdrawals' => 'إجمالي السحوبات',
            'net_credits' => 'صافي الرصيد',
            'total_invoices' => 'إجمالي الفواتير',
            'transactions' => 'معاملة',
            'invoices' => 'فاتورة',
            'balance' => 'الرصيد',
        ],

        // Actions
        'actions' => [
            'print' => 'طباعة التقرير',
            'print_customer_deposits' => 'طباعة تقرير إيداعات العملاء',
            'print_customer_debts' => 'طباعة تقرير ديون العملاء',
        ],

        // No Data
        'no_data' => [
            'title' => 'لا توجد بيانات',
            'description' => 'لا توجد معاملات مالية للعرض في الوقت الحالي.',
        ],

        // Print translations
        'print' => [
            'title' => 'طباعة التقارير المالية',
            'report_title' => 'التقرير المالي الشامل',
            'print_button' => 'طباعة',
            'company_phone' => 'هاتف الشركة',
            'company_email' => 'البريد الإلكتروني',
            'company_address' => 'العنوان',
            'generated_at' => 'تم إنشاؤه في',
            'report_validity' => 'هذا التقرير صحيح ومعتمد',
        ],

        // Customer Deposits Report
        'customer_deposits' => [
            'print' => [
                'title' => 'تقرير إيداعات العملاء',
                'report_title' => 'تقرير إيداعات العملاء',
                'print_button' => 'طباعة',
                'company_phone' => 'هاتف الشركة',
                'company_email' => 'البريد الإلكتروني',
                'company_address' => 'العنوان',
                'customer_code' => 'كود العميل',
                'customer_phone' => 'هاتف العميل',
                'not_available' => 'غير متوفر',
                'currency' => 'العملة',
                'total_deposits' => 'إجمالي الإيداعات',
                'transactions_count' => 'عدد المعاملات',
                'no_deposits_found' => 'لا توجد إيداعات للعملاء',
                'summary_title' => 'ملخص الإيداعات حسب العملة',
                'total_for_currency' => 'إجمالي :currency',
                'customers' => 'عميل',
                'generated_at' => 'تم إنشاؤه في',
                'report_validity' => 'هذا التقرير صحيح ومعتمد',
            ],
        ],

        // Customer Debts Report
        'customer_debts' => [
            'print' => [
                'title' => 'تقرير ديون العملاء',
                'report_title' => 'تقرير ديون العملاء',
                'print_button' => 'طباعة',
                'company_phone' => 'هاتف الشركة',
                'company_email' => 'البريد الإلكتروني',
                'company_address' => 'العنوان',
                'customer_code' => 'كود العميل',
                'customer_phone' => 'هاتف العميل',
                'not_available' => 'غير متوفر',
                'account_id' => 'رقم الحساب',
                'currency' => 'العملة',
                'account_balance' => 'رصيد الحساب',
                'debt_amount' => 'مبلغ الدين',
                'customer_total_debts' => 'إجمالي ديون العميل',
                'total_debt' => 'إجمالي الدين',
                'no_debts_title' => 'لا توجد ديون',
                'no_debts_found' => 'لا يوجد عملاء لديهم ديون في الوقت الحالي',
                'summary_title' => 'ملخص الديون حسب العملة',
                'total_debt_for_currency' => 'إجمالي الديون بعملة :currency',
                'customers_with_debt' => 'عميل مدين',
                'important_note' => 'ملاحظة مهمة',
                'debt_explanation' => 'الأرصدة السالبة تمثل ديون العملاء للشركة',
                'generated_at' => 'تم إنشاؤه في',
                'report_validity' => 'هذا التقرير صحيح ومعتمد',
            ],
        ],
    ],

];
