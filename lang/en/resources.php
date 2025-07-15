<?php

return [
    'receipt_resource' => [
        // Navigation & General
        'navigation_label' => 'Receipts',
        'model_label' => 'Receipt',
        'plural_model_label' => 'Receipts',

        // Form Sections
        'receipt_details_section' => [
            'title' => 'Receipt Details',
            'description' => 'Basic receipt information and customer details',
        ],

        'payment_section' => [
            'title' => 'Payment Information',
            'description' => 'Amount, currency, and payment details',
        ],

        'invoice_selection_section' => [
            'title' => 'Invoice Selection',
            'description' => 'Select invoices to be paid by this receipt',
        ],

        // Form Fields
        'fields' => [
            'note' => 'Receipt Note',
            'note_placeholder' => 'Enter receipt description or note...',
            'amount' => 'Amount',
            'amount_placeholder' => 'Enter the payment amount',
            'type' => 'Receipt Type',
            'customer_id' => 'Customer',
            'treasure_id' => 'Treasure',
            'currency_id' => 'Currency',
            'invoices' => 'Related Invoices',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ],

        // Table Columns
        'table' => [
            'note' => 'Note',
            'customer' => 'Customer',
            'type' => 'Type',
            'amount' => 'Amount',
            'currency' => 'Currency',
            'invoices_count' => 'Invoices',
            'created' => 'Created',
        ],

        // Actions
        'actions' => [
            'view' => 'View Receipt',
            'edit' => 'Edit Receipt',
            'delete' => 'Delete Receipt',
            'create' => 'Create Receipt',
        ],

        // Messages
        'messages' => [
            'created' => 'Receipt created successfully',
            'updated' => 'Receipt updated successfully',
            'deleted' => 'Receipt deleted successfully',
        ],

        // Validation
        'validation' => [
            'note_required' => 'Receipt note is required',
            'amount_required' => 'Amount is required',
            'amount_numeric' => 'Amount must be a number',
            'customer_required' => 'Customer is required',
            'type_required' => 'Receipt type is required',
        ],
    ],

    'invoice_resource' => [
        // Navigation & General
        'navigation_label' => 'Invoices',
        'model_label' => 'Invoice',
        'plural_model_label' => 'Invoices',

        // Form Sections
        'invoice_details_section' => [
            'title' => 'Invoice Details',
            'description' => 'Basic invoice information and customer details',
        ],

        'summary_section' => [
            'title' => 'Summary',
            'description' => 'Invoice totals and calculations',
        ],

        'invoice_items_section' => [
            'title' => 'Invoice Items',
            'description' => 'Add products and services to this invoice',
        ],

        // Form Fields
        'fields' => [
            'code' => 'Invoice Number',
            'customer_id' => 'Customer',
            'type' => 'Invoice Type',
            'discount' => 'Discount (%)',
            'notes' => 'Notes',
            'notes_placeholder' => 'Additional notes or comments...',
            'total_price' => 'Total Amount',

            // Invoice Items Fields
            'item_id' => 'Product/Service',
            'name' => 'Item Name',
            'item_type' => 'Type',
            'item_count' => 'Quantity',
            'unit_price' => 'Unit Price',
            'currency_id' => 'Currency',
            'weight' => 'Weight (kg)',
            'description' => 'Description',
        ],

        // Repeater
        'repeater' => [
            'item_label' => 'New Item',
            'add_action_label' => 'Add Item to Invoice',
        ],

        // Table Columns
        'table' => [
            'customer' => 'Customer',
            'type' => 'Type',
            'total' => 'Total',
            'discount' => 'Discount',
            'items_count' => 'Items',
            'created' => 'Created Date',
        ],

        // Actions
        'actions' => [
            'view' => 'View Invoice',
            'edit' => 'Edit Invoice',
            'delete' => 'Delete Invoice',
            'print' => 'Print Invoice',
        ],

        // Info List Labels
        'info_list' => [
            'created_date' => 'Created Date',
            'last_updated' => 'Last Updated',
            'customer_name' => 'Customer Name',
            'customer_code' => 'Customer Code',
            'phone_number' => 'Phone Number',
            'customer_since' => 'Customer Since',
            'no_notes' => 'No notes provided',
            'no_discount' => 'No discount',
            'invoice_totals' => 'Invoice totals by currency',
            'detailed_breakdown' => 'Detailed breakdown of invoice items',
            'item_name' => 'Item Name',
            'type' => 'Type',
            'quantity' => 'Quantity',
            'unit_price' => 'Unit Price',
            'total' => 'Total',
            'weight_kg' => 'Weight (kg)',
            'currency' => 'Currency',
            'description' => 'Description',
            'not_specified' => 'Not specified',
            'no_description' => 'No description',
            'system_information' => 'System Information',
            'invoice_id' => 'Invoice ID',
            'total_items' => 'Total Items',
            'related_receipts' => 'Related Receipts',
            'invoice_code_copied' => 'Invoice code copied!',
        ],

        // Print Template
        'print' => [
            'title' => 'Invoice',
            'print_button' => 'Print',
            'invoice_title' => 'Invoice',
            'invoice_code' => 'Invoice Code',
            'date' => 'Date',
            'type' => 'Type',
            'customer_info' => 'Customer Information',
            'customer_name' => 'Customer Name',
            'customer_code' => 'Customer Code',
            'customer_phone' => 'Phone',
            'not_available' => 'N/A',
            'notes' => 'Notes',
            'items' => 'Items',
            'item_name' => 'Item Name',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'total' => 'Total',
            'discount' => 'Discount',
            'thank_you' => 'Thank you for your business!',
            'generated_at' => 'Generated at',
        ],
    ],

    'treasure_resource' => [
        // Navigation & General
        'navigation_label' => 'Treasures',
        'model_label' => 'Treasure',
        'plural_model_label' => 'Treasures',

        // Form Sections
        'treasure_details_section' => [
            'title' => 'Treasure Information',
            'description' => 'Basic treasure details and location information',
        ],

        // Form Fields
        'fields' => [
            'name' => 'Treasure Name',
            'name_placeholder' => 'Enter treasure name...',
            'location' => 'Location',
            'location_placeholder' => 'Enter treasure location...',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ],

        // Table Columns
        'table' => [
            'name' => 'Name',
            'location' => 'Location',
            'created' => 'Created',
        ],

        // Actions
        'actions' => [
            'view' => 'View Treasure',
            'edit' => 'Edit Treasure',
            'delete' => 'Delete Treasure',
            'create' => 'Create Treasure',
        ],

        // Messages
        'messages' => [
            'created' => 'Treasure created successfully',
            'updated' => 'Treasure updated successfully',
            'deleted' => 'Treasure deleted successfully',
        ],

        // Validation
        'validation' => [
            'name_required' => 'Treasure name is required',
            'location_required' => 'Location is required',
        ],

        // Relation Manager
        'accounts_relation' => [
            'title' => 'Currency Accounts',
            'description' => 'Manage treasure accounts for different currencies',
            'fields' => [
                'code' => 'Account Code',
                'code_placeholder' => 'e.g. TREAS-001-USD',
                'currency_id' => 'Currency',
                'amount' => 'Balance',
                'amount_placeholder' => '0.00',
            ],
            'table' => [
                'code' => 'Account Code',
                'currency' => 'Currency',
                'balance' => 'Balance',
                'created' => 'Created',
                'updated' => 'Updated',
            ],
            'actions' => [
                'add_account' => 'Add Account',
                'edit_account' => 'Edit Account',
                'delete_account' => 'Delete Account',
            ],
            'filters' => [
                'currency' => 'Currency',
                'positive_balance' => 'Positive Balance',
                'negative_balance' => 'Negative Balance',
            ],
            'empty_state' => [
                'heading' => 'No accounts yet',
                'description' => 'Create an account to get started with currency management.',
            ],
        ],
    ],

    'customer_resource' => [
        // Navigation & General
        'navigation_label' => 'Customers',
        'model_label' => 'Customer',
        'plural_model_label' => 'Customers',
        'navigation_group' => 'Settings',

        // Form Sections
        'customer_info_section' => [
            'title' => 'Customer Information',
            'description' => 'Basic customer details and contact information',
        ],

        // Form Fields
        'fields' => [
            'name' => 'Full Name',
            'name_placeholder' => 'Enter customer full name',
            'code' => 'Customer Code',
            'code_placeholder' => 'e.g. CUST-001',
            'phone' => 'Phone Number',
            'phone_placeholder' => 'e.g. +1 (555) 123-4567',
        ],

        // Table Columns
        'table' => [
            'name' => 'Customer Name',
            'code' => 'Code',
            'phone' => 'Phone',
            'account_balance' => 'Account Balance',
        ],

        // Actions
        'actions' => [
            'view' => 'View Customer',
            'edit' => 'Edit Customer',
            'delete' => 'Delete Customer',
            'create' => 'Create Customer',
        ],

        // Messages
        'messages' => [
            'phone_copied' => 'Phone number copied!',
            'created' => 'Customer created successfully',
            'updated' => 'Customer updated successfully',
            'deleted' => 'Customer deleted successfully',
        ],

        // Validation
        'validation' => [
            'name_required' => 'Customer name is required',
            'code_required' => 'Customer code is required',
            'phone_required' => 'Phone number is required',
            'code_unique' => 'Customer code must be unique',
            'phone_unique' => 'Phone number must be unique',
        ],

        // Relation Manager
        'accounts_relation' => [
            'title' => 'Customer Accounts',
            'description' => 'Manage customer accounts for different currencies',
            'fields' => [
                'code' => 'Account Code',
                'code_placeholder' => 'e.g. CUST-001-USD',
                'currency_id' => 'Currency',
                'amount' => 'Balance',
                'amount_placeholder' => '0.00',
            ],
            'table' => [
                'code' => 'Account Code',
                'currency' => 'Currency',
                'balance' => 'Balance',
                'created' => 'Created',
                'updated' => 'Updated',
            ],
            'actions' => [
                'add_account' => 'Add Account',
                'edit_account' => 'Edit Account',
                'delete_account' => 'Delete Account',
            ],
            'filters' => [
                'currency' => 'Currency',
                'positive_balance' => 'Positive Balance',
                'negative_balance' => 'Negative Balance',
            ],
            'empty_state' => [
                'heading' => 'No accounts yet',
                'description' => 'Create an account to get started with currency management.',
            ],
        ],
    ],

    'invoice_resource' => [
        'print' => [
            'title' => 'Invoice',
            'print_button' => 'Print Invoice',
            'invoice_title' => 'INVOICE',
            'invoice_code' => 'Invoice No',
            'date' => 'Date',
            'type' => 'Type',
            'company_info' => 'Company Information',
            'customer_info' => 'Customer Information',
            'customer_name' => 'Customer Name',
            'customer_code' => 'Customer Code',
            'customer_phone' => 'Phone',
            'company_name' => 'Company Name',
            'company_phone' => 'Phone',
            'company_email' => 'Email',
            'company_address' => 'Address',
            'company_website' => 'Website',
            'notes' => 'Notes',
            'items' => 'Items',
            'item_name' => 'Item Name',
            'quantity' => 'Quantity',
            'price' => 'Unit Price',
            'total' => 'Total',
            'discount' => 'Discount',
            'thank_you' => 'Thank you for your business!',
            'generated_at' => 'Generated at',
            'not_available' => 'N/A',
        ],
    ],
];
