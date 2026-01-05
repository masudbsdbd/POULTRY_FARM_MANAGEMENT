<?php

use App\Http\Controllers\PoultryBatchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return redirect()->back();
})->name('clear.cache');

Route::namespace('Auth')->group(function () {

    //     Route::controller('LoginController')->group(function () {
    //     Route::get('/', 'showLoginForm')->name('login.form');
    //     Route::post('/login', 'login')->name('login');
    //     Route::get('logout', 'logout')->name('logout');
    // });
    Route::controller('LoginController')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login.form');
        Route::redirect('/', '/login');
        Route::get('/register', 'showRegisterForm')->name('register.form');
        Route::post('/register', 'register')->name('register');
        Route::post('/login', 'login')->name('login');
        Route::get('logout', 'logout')->name('logout');
    });

    Route::controller('ForgotPasswordController')->group(function () {
        Route::get('password/reset', 'showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'sendResetCodeEmail');
        Route::get('password/code-verify', 'codeVerify')->name('password.code.verify');
        Route::post('password/verify-code', 'verifyCode')->name('password.verify.code');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset.form');
        Route::post('password/reset/change', 'reset')->name('password.change');
    });
});

Route::middleware('authenticated')->group(function () {
    Route::controller('AdminController')->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate')->name('profile.update');
        Route::post('password', 'passwordUpdate')->name('password.update');
    });

    // Category Management
    Route::controller('CategoryController')->name('category.')->prefix('category')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('delete/{id}', 'delete')->name('delete');
    });

    // Subcategory Management
    Route::controller('SubcategoryController')->name('subcategory.')->prefix('subcategory')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('delete/{id}', 'delete')->name('delete');
    });

    // Subcategory Management
    Route::controller('SubcategoryController')->name('subcategory.')->prefix('subcategory')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('delete/{id}', 'delete')->name('delete');
        Route::get('ajax', 'subcatAjax')->name('ajax');
    });

    // Subcategory Management
    Route::controller('BrandController')->name('brand.')->prefix('brand')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('delete/{id}', 'delete')->name('delete');
    });

    // Product Management
    Route::controller('ProductController')->name('product.')->prefix('product')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::get('/barcode/{code}', 'generateBarcode');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('delete/{id}', 'delete')->name('delete');
        Route::get('print/barcode/{id}', 'printBarcode')->name('print.barcode');
    });

    // Customer Management
    Route::controller('CustomerController')->name('customer.')->prefix('customer')->group(function () {
        Route::get('index/{type?}', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('delete/{id}', 'delete')->name('delete');
        Route::get('advance/index', 'advanceIndex')->name('advance.index');
        Route::post('advance/{id}', 'payment')->name('payment');
    });

    // Setting Management
    Route::controller('SettingController')->name('setting.')->prefix('setting')->group(function () {
        Route::get('customer/type', 'customerType')->name('customer.type');
        Route::post('customer/type/store/{id?}', 'customerTypeStore')->name('customer.type.store');
        Route::post('customer/type/delete/{id?}', 'customerTypeDelete')->name('customer.type.delete');
        Route::get('general-setting', 'generalSetting')->name('general');
        Route::post('general-setting', 'generalSettingUpdate')->name('general.update');
    });

    // Assets Management
    Route::controller('AssetController')->name('asset.')->prefix('asset')->group(function () {
        Route::get('head/index', 'headindex')->name('head.index');
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('head/create', 'headCreate')->name('head.create');
        Route::post('store', 'store')->name('store');
        Route::post('head/store/{id?}', 'headStore')->name('head.store');
        Route::get('head/edit/{id}', 'headEdit')->name('head.edit');
        Route::post('head/delete/{id}', 'headDelete')->name('head.delete');
    });

    // Accounts Payable Head Management
    Route::controller('PayableHeadController')->name('payable.')->prefix('payable')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store/{id?}', 'store')->name('store');
    });

    // Accounts Payable Management
    Route::controller('PayableController')->name('accounts.payable.')->prefix('accounts/payable')->group(function () {
        Route::get('index', 'payableIndex')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::post('pay/{id}', 'payAccPayable')->name('pay');
        Route::post('expense/pay/{id}', 'expensePay')->name('expense.pay');
        Route::post('supplier/due/pay/{id}', 'paySupplierrDue')->name('supplier.due.pay');
    });

    // Accounts Receivable Head Management
    Route::controller('ReceivableHeadController')->name('receivable.')->prefix('receivable')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store/{id?}', 'store')->name('store');
    });

    // Accounts Receivable Management
    Route::controller('ReceivableController')->name('accounts.receivable.')->prefix('accounts/receivable')->group(function () {
        Route::get('index', 'receivableIndex')->name('index');
        // Route::post('store/{id?}', 'store')->name('store');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::post('due/pay/{id}', 'payDue')->name('due.pay');
        Route::post('customer/due/pay/{id}', 'payCustomerDue')->name('customer.due.pay');
        // Route::post('customer/type/delete/{id?}', 'customerTypeDelete')->name('customer.type.delete');
        // Route::get('general-setting', 'generalSetting')->name('general');
        // Route::post('general-setting', 'generalSettingUpdate')->name('general.update');
    });

    // Unit Management
    Route::controller('UnitController')->name('unit.')->prefix('unit')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('delete/{id}', 'delete')->name('delete');
    });

    // Purchase Management
    Route::controller('PurchaseController')->name('purchase.')->prefix('purchase')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('delete/{id}', 'delete')->name('delete');
        Route::post('due/{id}', 'payDue')->name('due');
        Route::get('pdf/{id}', 'generatePdf')->name('pdf');
    });


    Route::controller('WarehouseController')->name('warehouse.')->prefix('warehouse')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('manage', 'manage')->name('manage');
        Route::post("store", "store")->name("store");
        Route::put('update/{id}', 'update')->name('update');
        Route::delete('destroy/{id}', 'destroy')->name('destroy');
        Route::get('manage/create', 'manageCreate')->name('manage.create');
        Route::get('manage/warehouse/{id}', 'manageWarehouse')->name('manage.ajax');
        Route::post("manage/store", "manageStore")->name('manage.store');
    });

    // Return Product for (supplier)
    Route::controller('SupplierReturnController')->name('supplier-return.')->prefix('supplier-return')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('supplier/ajax/{id}', 'returnAjax')->name('supplier.ajax');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('delete/{id}', 'delete')->name('delete');
    });

    // Return Product for (customer)
    Route::controller('CustomerReturnController')->name('customer-return.')->prefix('customer-return')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('customer/ajax/{id}', 'returnAjax')->name('customer.ajax');
        Route::post('store/{id?}', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('delete/{id}', 'delete')->name('delete');
    });

    // Account Management
    Route::controller('AccountController')->name('account.')->prefix('account')->group(function () {
        Route::get('statements', 'accountStatements')->name('statements');
        Route::get('cash/statements', 'cashStatement')->name('cash.statements');
        Route::get('bank/statements', 'BankStatement')->name('bank.statements');
    });

    // B/S Acoount Management
    Route::controller('BsController')->name('bs.account.')->prefix('bs.account')->group(function () {
        Route::get('journal/head/index', 'journalHeadIndex')->name('journal.head.index');
        Route::post('journal/head/store/{id?}', 'journalHeadStore')->name('journal.head.store');
        Route::get('journal/index', 'journalIndex')->name('journal.index');
        Route::get('chart/account', 'chartAccount')->name('journal.chart.account');
        Route::get('journal/create', 'journalCreate')->name('journal.create');
        Route::post('journal/store', 'journalStore')->name('journal.store');
        Route::get('receivable/index', 'receivableIndex')->name('receivable');
    });

    // Investment Management
    Route::controller('InvestmentController')->name('investment.')->prefix('investment')->group(function () {
        Route::get('create', 'create')->name('create');
        Route::get('index', 'index')->name('index');
        Route::post('store', 'store')->name('store');
    });
    // Investor Management
    Route::controller('InvestorController')->name('investor.')->prefix('investor')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store/{id?}', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('delete/{id}', 'delete')->name('delete');
    });

    // Sell Management
    Route::controller('SellController')->name('sell.')->prefix('sell')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('delete/{id}', 'delete')->name('delete');
        Route::post('store/{id?}', 'store')->name('store');
        Route::get('batch/ajax/{id}', 'batchAjax')->name('batch.ajax');
        Route::post('due/{id}', 'payDue')->name('due');
        Route::get('pdf/{id}', 'generatePdf')->name('pdf');
        Route::get('delivery/{id}', 'deliveryView')->name('delivery');
        Route::post('delivery/confirm/{id}', 'deliveryStatusChange')->name('delivery.confirm');
    });

    // Stock
    Route::controller('StockController')->name('stock.')->prefix('stock')->group(function () {
        Route::get('today', 'todayStock')->name('today');
        Route::get('month', 'monthStock')->name('month');
        Route::get('items', 'stockItems')->name('items');
        Route::get('detail/{id}', 'detail')->name('detail');
        Route::get('manage', 'manageStock')->name('manage');
        Route::get('manage/create', 'manageStockCreate')->name('manage.create');
        Route::get('manage/ajax/{id}', 'manageStockAjax')->name('manage.ajax');
        Route::post('manage/store', 'manageStockStore')->name('manage.store');
    });

    // Supplier Management
    Route::controller('SupplierController')->name('supplier.')->prefix('supplier')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('delete/{id}', 'delete')->name('delete');
        Route::get('advance/index', 'advanceIndex')->name('advance.index');
        Route::post('advance/{id}', 'payment')->name('payment');
    });

    // Employee Management
    Route::controller('EmployeeController')->name('employee.')->prefix('employee')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('delete/{id}', 'delete')->name('delete');
    });



    // Quotation item Management
    // Route::controller('QuotationItemController')->name('quotation.item.')->prefix('quotation/item')->group(function () {
    //     Route::get('index', 'index')->name('index');
    //     Route::get('create', 'create')->name('create');
    //     Route::post('store/{id?}', 'store')->name('store');
    // Route::get('edit/{id}', 'edit')->name('edit');
    // Route::post('delete/{id}', 'delete')->name('delete');
    // });

    // Employee Transaction Management
    Route::controller('EmployeeTransactionController')->name('employee.transaction.')->prefix('employee/transaction')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('monthly/details/{id}', 'monthlyTransactionDetails')->name('monthly.details');
        Route::get('details/{date}/{id}', 'transactionDetails')->name('details');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('payment', 'payment')->name('payment');
        Route::post('month/delete/{id}', 'monthlyTransactionDelete')->name('month.delete');
        Route::post('delete/{id}', 'transactionDelete')->name('delete');
        Route::post('salary/liability/{id}', 'salaryLiability')->name('salary.liability');

        Route::get('salary/trx', 'salaryTransaction')->name('salary.trx');
        Route::get('salary/trx/details/{id}', 'salaryTransactionDetails')->name('salary.trx.details');
    });

    // Bank Info Management
    Route::controller('BankController')->name('bank.')->prefix('bank')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('individual/bank/index/{id}', 'individualIndex')->name('individual.trx');
        Route::get('create', 'create')->name('create');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('delete/{id}', 'delete')->name('delete');
    });

    // Bank Transactions Manage
    Route::controller('BankTransactionController')->name('bank.transaction.')->prefix('bank.transaction')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('diposit', 'diposit')->name('diposit');
        Route::get('withdraw', 'withdraw')->name('withdraw');
        Route::post('store/{id?}', 'store')->name('store');
    });

    // Delivery Manage
    Route::controller('DeliveryReportController')->name('delivery.report.')->prefix('delivery/report')->group(function () {
        Route::get('index', 'index')->name('index');
        // Route::get('index', 'index')->name('date.search');
    });

    // Pos Manage 
    Route::controller('PosController')->name('pos.')->prefix('pos')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('get-products-suggestions', 'getProductSuggestions')->name('get-products-suggestions');
        // Route::post('save-order', 'saveOrder')->name('save-order');
    });

    // Purchase damage Manage 
    // Route::controller('DamageController')->name('damage.')->prefix('damage')->group(function () {
    //     Route::get('create', 'create')->name('create');
    //     Route::get('index', 'index')->name('index');
    //     Route::get('batch/ajax/{id}', 'batchAjax')->name('batch.ajax');
    //     Route::post('store/{id?}', 'store')->name('store');
    //     Route::get('edit/{id}', 'edit')->name('edit');
    //     Route::post('delete/{id}', 'delete')->name('delete');
    // });
    // Expense Manage 
    Route::controller('ExpenseController')->name('expense.')->prefix('expense')->group(function () {
        Route::get('create', 'create')->name('create');
        Route::get('head/create', 'headCreate')->name('head.create');
        Route::get('index', 'index')->name('index');
        Route::get('head/index', 'headIndex')->name('head.index');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('pay/{id}', 'expensePay')->name('pay');
        Route::post('head/store/{id?}', 'headStore')->name('head.store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::get('head/edit/{id}', 'headEdit')->name('head.edit');
        Route::post('delete/{id}', 'delete')->name('delete');
        Route::post('head/delete/{id}', 'headDelete')->name('head.delete');
    });

    // Purchase Report Manage 
    Route::controller('PurchaseReportController')->name('purchase-report.')->prefix('purchase-report')->group(function () {
        Route::get('index', 'index')->name('index');
    });

    // Sell Report Manage 
    Route::controller('SellReportController')->name('sell-report.')->prefix('sell-report')->group(function () {
        Route::get('index', 'index')->name('index');
    });
    // Damage Report Manage 
    Route::controller('DamageReportController')->name('damage-report.')->prefix('damage-report')->group(function () {
        Route::get('index', 'index')->name('index');
    });
    // Discount Report Manage
    Route::controller('DiscountReportController')->name('discount-report.')->prefix('discount-report')->group(function () {
        Route::get('purchaseindex', 'purchaseindex')->name('purchase.index');
        Route::get('sellindex', 'sellindex')->name('sell.index');
    });
    // Balance Sheet Report Manage
    Route::controller('BalanceSheetReportController')->name('balance.sheet.report.')->prefix('balance.sheet.report')->group(function () {
        Route::get('index', 'index')->name('index');
    });
    // Expense Report Manage
    Route::controller('ExpenseReportController')->name('expense.report.')->prefix('expense.report')->group(function () {
        Route::get('index', 'index')->name('index');
    });

    // Supplier Ledger Manage
    Route::controller('SupplierLedgerController')->name('supplier.ledger.')->prefix('supplier/ledger')->group(function () {
        Route::get('account/ledger/{id}', 'accountIndex')->name('account.index');
        Route::get('purchase/history/{id}', 'purchaseIndex')->name('purchase.index');
        Route::get('history/print', 'historyLedgerPdf')->name('purchase.ledger');
    });
    // Customer Ledger Manage
    Route::controller('CustomerLedgerController')->name('customer.ledger.')->prefix('customer.ledger')->group(function () {
        Route::get('account/ledger/{id}', 'accountIndex')->name('account.index');
        Route::get('sell/history/{id}', 'sellIndex')->name('sell.index');
    });
    // Profit Manage
    Route::controller('ProfitController')->name('profit.')->prefix('profit')->group(function () {
        Route::get('index', 'index')->name('index');
    });
    // User Manage
    Route::controller('UserController')->name('users.')->prefix('users')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('destroy/{id}', 'destroy')->name('destroy');
        // Route::post('update/{id}', 'update')->name('update');
    });
    // Role Manage
    Route::controller('RoleController')->name('roles.')->prefix('roles')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('store/{id?}', 'store')->name('store');
        Route::delete('destroy/{id}', 'destroy')->name('destroy');
        // Route::post('update/{id}', 'update')->name('update');
    });
    // Income Manage
    Route::controller('IncomeController')->name('income.')->prefix('income')->group(function () {
        Route::get('list/create', 'listCreate')->name('list.create');
        Route::post('list/store/{id?}', 'listStore')->name('list.store');
        Route::get('list/index', 'listIndex')->name('list.index');
        Route::get('list/edit/{id}', 'listEdit')->name('list.edit');
        Route::post('list/delete/{id}', 'listDelete')->name('list.delete');


        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store/{id?}', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('delete/{id}', 'delete')->name('delete');
    });

    // Route::group(['middleware' => ['auth']], function () {
    //     // Route::resource('roles', RoleController::class);
    //     // Route::resource('users', UserController::class);
    // });

    // Route::get('/test', function () {
    //     return view('test');
    // });


    // Quotation Management
    Route::controller('QuotationController')->name('quotation.')->prefix('quotation')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store/{id?}', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::get('view/{quotationId}', 'QuotationView')->name('view');
        Route::get('pdf/{id}', 'generatePdf')->name('pdf');
        Route::delete('delete/{id}', 'delete')->name('delete');
    });

    Route::controller('ChallanController')->name('challan.')->prefix('challan')->group(function () {
        Route::get('all/{id}', 'index')->name("all");
        Route::get('create/{id}', 'manageChallanCreate')->name('create');
        Route::post('store', 'manageChallanStore')->name('store');
        Route::get('edit/{id}', 'manageChallanEdit')->name('edit');
        Route::get('download/{id}', 'challanDownload')->name('download');
        Route::get('challanItems/{quotationId}/{productId}', 'getChallanItems')->name('challanItems');
        Route::post('update/{id}', 'manageChallanUpdate')->name('update');
        Route::delete('delete/{id}', 'deleteChallan')->name('delete');
        Route::get('used_history', 'productUsedHistory')->name('used_history');
    });

    Route::controller('InvoiceController')->name('invoice.')->prefix('invoice')->group(function () {
        Route::get('all/{id}', 'index')->name('all');
        Route::get('create/{id}', 'manageInvoiceCreate')->name('create');
        Route::post('store', 'manageInvoiceStore')->name('store');
        Route::get('view/{invoiceId}', 'viewInvoiceInfo')->name('view');
        Route::get('items_info/{invoiceId}/{productId}', 'itemsInfoView')->name('items_info');
        Route::get('download/{invoiceId}', 'download')->name('download');
        Route::get('edit/{invoiceId}', 'manageInvoiceEdit')->name('edit');
        Route::get('invoicedItems/{quotationId}/{productId}', 'getInvoicedItems')->name('invoicedItems');
        Route::post('update/{invoiceId}', 'manageInvoiceUpdate')->name('update');
        Route::delete('delete/{invoiceId}', 'deleteInvoice')->name('delete');
    });

    Route::controller('PaymentController')->name('payment.')->prefix('payment')->group(function () {
        Route::get('all/{invoiceId}', 'index')->name('all');
        Route::get('create/{invoiceId}', 'managePaymentCreate')->name('create');
        Route::post('store', 'managePaymentStore')->name('store');
        Route::delete('delete/{invoiceId}', 'deletePayment')->name('delete');
        Route::get('payment_items/{paymentId}/{invoiceId?}', 'getPaymentItems')->name('payment_items');
        Route::get('history', 'paymentHistory')->name('history');
        Route::get("payment_history_details/{paymentId}", "paymentHistoryDetails")->name("payment_history_details");
    });

    Route::controller('ApprovalController')->name('approval.')->prefix('approval')->group(function () {
        Route::get('all/{quotationIds}', 'index')->name('all');
        Route::get('create/{quotationId}', 'manageApprovalCreate')->name('create');
        Route::post('store', 'manageApprovalStore')->name('store');
        Route::get('print/{id}', 'print')->name('print');
        Route::get('update/{id}', 'updateApproval')->name('update');
        Route::post('updatedb/{id}', 'updateApprovalDb')->name('update.db');
        Route::delete('delete/{id}', 'deleteApproval')->name('delete');
        Route::get('approvedItems/{quotationId}/{product_id}', 'approvedItems')->name('approvedItems');
    });

    Route::controller('FloorController')->name('floor.')->prefix('floor')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('create/{id?}', 'store')->name('store');
        Route::post('delete/{id}', 'delete')->name('delete');
    });

    Route::controller('BuildingController')->name('building.')->prefix('building')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('create/{id?}', 'store')->name('store');
        Route::post('delete/{id}', 'delete')->name('delete');
    });


    // Customer Management
    Route::resource('poultrybatch', PoultryBatchController::class);


    // Customer Management
    Route::controller('CustomerController')->name('customer.')->prefix('customer')->group(function () {
        Route::get('batches/{customer_id}', 'batches')->name('batches');
        Route::get('batches/create/{customer_id}', 'createBatch')->name('createBatch');
        Route::get('batches/manage/{batch_id}', 'manageBatch')->name('manageBatch');
    });


    // Customer Management
    Route::controller('PoultryDeathController')->name('death.')->prefix('death')->group(function () {
        Route::get('list/{batch_id}', 'deathList')->name('list');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('delete/{id}', 'delete')->name('delete');
    });

    // Customer Management
    Route::controller('PoultryExpenseController')->name('poultry.expense.')->prefix('poultry/expense')->group(function () {
        Route::get('/{batch_id}', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{expense}', 'update')->name('update');
        Route::post('/{expense}', 'destroy')->name('destroy');
    });

    //     Route::prefix('poultry/expense')->name('poultry.expense.')->group(function () {
    //     Route::get('/{batch_id}', [PoultryExpenseController::class, 'index'])->name('index');
    //     Route::post('/', [PoultryExpenseController::class, 'store'])->name('store');
    //     Route::put('/{expense}', [PoultryExpenseController::class, 'update'])->name('update');
    //     Route::delete('/{expense}', [PoultryExpenseController::class, 'destroy'])->name('destroy');
    // });
});
