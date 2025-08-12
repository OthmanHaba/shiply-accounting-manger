<?php

return [
    // Event Types
    'events' => [
        'created' => 'إنشاء',
        'updated' => 'تحديث',
        'deleted' => 'حذف',
        'restored' => 'استرداد',
    ],

    // Model Names (Subjects)
    'subjects' => [
        'customer' => 'عميل',
        'customers' => 'العملاء',
        'invoice' => 'فاتورة',
        'invoices' => 'الفواتير',
        'receipt' => 'إيصال',
        'receipts' => 'الإيصالات',
        'account' => 'حساب',
        'accounts' => 'الحسابات',
        'user' => 'مستخدم',
        'users' => 'المستخدمين',
        'treasure' => 'خزينة',
        'treasures' => 'الخزائن',
        'currency' => 'عملة',
        'currencies' => 'العملات',
        'item' => 'صنف',
        'items' => 'الأصناف',
    ],

    // Activity Descriptions
    'activities' => [
        // Customer Activities
        'customer_created' => 'تم إنشاء عميل جديد: :subject_name',
        'customer_updated' => 'تم تحديث بيانات العميل: :subject_name',
        'customer_deleted' => 'تم حذف العميل: :subject_name',

        // Invoice Activities
        'invoice_created' => 'تم إنشاء فاتورة جديدة: :subject_name',
        'invoice_updated' => 'تم تحديث الفاتورة: :subject_name',
        'invoice_deleted' => 'تم حذف الفاتورة: :subject_name',

        // Receipt Activities
        'receipt_created' => 'تم إنشاء إيصال جديد بمبلغ :amount',
        'receipt_updated' => 'تم تحديث الإيصال رقم :subject_id',
        'receipt_deleted' => 'تم حذف الإيصال رقم :subject_id',

        // Account Activities
        'account_created' => 'تم إنشاء حساب جديد: :subject_name',
        'account_updated' => 'تم تحديث رصيد الحساب: :subject_name',
        'account_deleted' => 'تم حذف الحساب: :subject_name',

        // User Activities
        'user_created' => 'تم إنشاء مستخدم جديد: :subject_name',
        'user_updated' => 'تم تحديث بيانات المستخدم: :subject_name',
        'user_deleted' => 'تم حذف المستخدم: :subject_name',

        // Treasure Activities
        'treasure_created' => 'تم إنشاء خزينة جديدة: :subject_name',
        'treasure_updated' => 'تم تحديث بيانات الخزينة: :subject_name',
        'treasure_deleted' => 'تم حذف الخزينة: :subject_name',

        // Currency Activities
        'currency_created' => 'تم إنشاء عملة جديدة: :subject_name',
        'currency_updated' => 'تم تحديث بيانات العملة: :subject_name',
        'currency_deleted' => 'تم حذف العملة: :subject_name',

        // Item Activities
        'item_created' => 'تم إنشاء صنف جديد: :subject_name',
        'item_updated' => 'تم تحديث بيانات الصنف: :subject_name',
        'item_deleted' => 'تم حذف الصنف: :subject_name',
    ],

    // Field Names for Changes
    'fields' => [
        // Common Fields
        'name' => 'الاسم',
        'code' => 'الكود',
        'created_at' => 'تاريخ الإنشاء',
        'updated_at' => 'تاريخ التحديث',

        // Customer Fields
        'phone' => 'رقم الهاتف',

        // Invoice Fields
        'customer_id' => 'العميل',
        'type' => 'النوع',
        'note' => 'الملاحظة',
        'discount' => 'الخصم',

        // Receipt Fields
        'amount' => 'المبلغ',
        'currency_id' => 'العملة',
        'treasure_id' => 'الخزينة',

        // Account Fields
        'accountable_id' => 'صاحب الحساب',
        'accountable_type' => 'نوع الحساب',

        // User Fields
        'email' => 'البريد الإلكتروني',

        // Treasure Fields
        'location' => 'الموقع',

        // Activity Log Resource Fields
        'log_name' => 'نوع السجل',
        'event' => 'الحدث',
        'subject_type' => 'نوع الكائن',
        'subject_id' => 'معرف الكائن',
        'description' => 'الوصف',
        'causer' => 'المستخدم',
        'date_from' => 'من تاريخ',
        'date_until' => 'إلى تاريخ',
    ],

    // Change Messages
    'changes' => [
        'changed_from_to' => 'تم تغيير :field من ":old_value" إلى ":new_value"',
        'added_field' => 'تم إضافة :field: ":new_value"',
        'removed_field' => 'تم حذف :field: ":old_value"',
        'no_changes' => 'لا توجد تغييرات',
    ],

    // General Messages
    'messages' => [
        'activity_log' => 'سجل النشاطات',
        'no_activities' => 'لا توجد نشاطات مسجلة',
        'performed_by' => 'تم بواسطة',
        'performed_at' => 'تم في',
        'on_model' => 'على',
        'view_details' => 'عرض التفاصيل',
        'hide_details' => 'إخفاء التفاصيل',
        'changes_made' => 'التغييرات المُجراة',
        'old_values' => 'القيم القديمة',
        'new_values' => 'القيم الجديدة',
        'created_values' => 'القيم المُنشأة',
        'deleted_values' => 'القيم المحذوفة',
        'activity_details' => 'تفاصيل النشاط',
        'system' => 'النظام',
        'raw_data' => 'البيانات الخام',
    ],

    // Log Names
    'log_names' => [
        'customers' => 'العملاء',
        'invoices' => 'الفواتير',
        'receipts' => 'الإيصالات',
        'accounts' => 'الحسابات',
        'users' => 'المستخدمين',
        'treasures' => 'الخزائن',
        'currencies' => 'العملات',
        'items' => 'الأصناف',
        'default' => 'عام',
    ],
];
