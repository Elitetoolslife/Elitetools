<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Here are the complete routes defined in `routes/web.php` based on the rewrite rules from `buyer/.htaccess`, `admin/.htaccess`, `seller/.htaccess`, and `support/.htaccess`:

```php
use Illuminate\Support\Facades\Route;

// Routes from buyer/.htaccess
Route::get('index', function () {
    return view('index');
});

Route::get('ajaxinfo', function () {
    return view('ajax');
});

Route::get('mailer', function () {
    return view('mailer');
});

Route::get('shell', function () {
    return view('shell');
});

Route::get('leads', function () {
    return view('leads');
});

Route::get('premium', function () {
    return view('premium');
});

Route::get('addBalance', function () {
    return view('addBalance');
});

Route::get('divPage1', function () {
    return view('divPage1');
});

Route::get('divPage2', function () {
    return view('divPage2');
});

Route::get('divPage3', function () {
    return view('divPage3');
});

Route::get('divPage4', function () {
    return view('divPage4');
});

Route::get('divPage5', function () {
    return view('divPage5');
});

Route::get('divPage6', function () {
    return view('divPage6');
});

Route::get('divPage7', function () {
    return view('divPage7');
});

Route::get('divPage8', function () {
    return view('divPage8');
});

Route::get('divPage9', function () {
    return view('divPage9');
});

Route::get('divPage10', function () {
    return view('divPage10');
});

Route::get('divPage11', function () {
    return view('divPage11');
});

Route::get('divPage12', function () {
    return view('divPage12');
});

Route::get('divPage13', function () {
    return view('divPage13');
});

Route::get('divPage14', function () {
    return view('divPage14');
});

Route::get('divPage15', function () {
    return view('divPage15');
});

Route::get('divPage0', function () {
    return view('divPage0');
});

Route::get('settingEdit', function () {
    return view('settingEdit');
});

Route::get('CreateTicket', function () {
    return view('tticket');
});

Route::get('CreateReport', function () {
    return view('treport');
});

Route::get('funds', function () {
    return view('funds');
});

Route::get('addBalanceAction', function () {
    return view('addBalanceAction');
});

Route::get('rdp', function () {
    return view('rdp');
});

Route::get('tutorial', function () {
    return view('tutorial');
});

Route::get('MakePayment', function () {
    return view('pay');
});

Route::get('BitcoinPayment', function () {
    return view('btc3');
});

Route::get('banks', function () {
    return view('banks');
});

Route::get('PerfectMoneyPayment', function () {
    return view('pm3');
});

Route::get('tickets', function () {
    return view('tickets');
});

Route::get('becomeseller', function () {
    return view('becomeseller');
});

Route::get('scampage', function () {
    return view('scampage');
});

Route::get('logout', function () {
    return view('logout');
});

Route::get('active', function () {
    return view('active');
});

Route::get('orders', function () {
    return view('orders');
});

Route::get('setting', function () {
    return view('setting');
});

Route::get('static', function () {
    return view('static');
});

Route::get('rindex', function () {
    return view('rindex');
});

Route::get('smtp', function () {
    return view('smtp');
});

Route::get('AddSingleTool', function () {
    return view('addt');
});

Route::get('cPanel', function () {
    return view('cPanel');
});

Route::get('checkEmailChange', function () {
    return view('checkEmailChange');
});

Route::get('reports', function () {
    return view('reports');
});

Route::get('Rules', function () {
    return view('shoprules');
});

Route::get('account', function () {
    return view('profile');
});

Route::get('AddCards', function () {
    return view('addc');
});

Route::get('vt-{id}', function ($id) {
    return view('vt', ['id' => $id]);
});

Route::get('vr-{id}', function ($id) {
    return view('vr', ['id' => $id]);
});

Route::get('divPageticket{id}', function ($id) {
    return view('divPageticket', ['id' => $id]);
});

Route::get('divPagereport{id}', function ($id) {
    return view('divPagereport', ['id' => $id]);
});

Route::get('showTicket{id}', function ($id) {
    return view('showTicket', ['id' => $id]);
});

Route::get('CheckShell{id}', function ($id) {
    return view('check2shell', ['id' => $id]);
});

Route::get('CheckSMTP{id}', function ($id) {
    return view('check2smtp', ['id' => $id]);
});

Route::get('CheckCpanel{id}', function ($id) {
    return view('check2cp', ['id' => $id]);
});

Route::get('CheckMailer{id}', function ($id) {
    return view('check2mailer', ['id' => $id]);
});

Route::get('showOrder{id}', function ($id) {
    return view('openorder', ['id' => $id]);
});

Route::get('addReply{id}', function ($id) {
    return view('addReply', ['id' => $id]);
});

Route::get('addReportReply{id}', function ($id) {
    return view('addReportReply', ['id' => $id]);
});

Route::get('divPagepayment{p_data}', function ($p_data) {
    return view('divPagepayment', ['p_data' => $p_data]);
});

Route::get('Check', function () {
    return view('check');
});

Route::get('PMCheck', function () {
    return view('PMcheck');
});

Route::get('Payment', function () {
    return view('payment');
});

// Routes from admin/.htaccess
Route::get('admin/index.html', function () {
    return view('admin.index');
});

Route::get('admin/Status.html', function () {
    return view('admin.dollar');
});

Route::get('admin/Tools.html', function () {
    return view('admin.toolsvis');
});

Route::get('admin/NewsBuyer.html', function () {
    return view('admin.news');
});

Route::get('admin/NewsSeller.html', function () {
    return view('admin.newseller');
});

Route::get('admin/Tickets.html', function () {
    return view('admin.ticket');
});

Route::get('admin/Reports.html', function () {
    return view('admin.reports');
});

Route::get('admin/Users.html', function () {
    return view('admin.users');
});

Route::get('admin/WithdrawalRequests.html', function () {
    return view('admin.withdrawalreq');
});

Route::get('admin/Sellers.html', function () {
    return view('admin.resseller');
});

Route::get('admin/PaySeller.html', function () {
    return view('admin.rpay');
});

Route::get('admin/Logout.html', function () {
    return view('admin.lougout');
});

Route::get('admin/UsersBalance.html', function () {
    return view('admin.usersb');
});

Route::get('admin/SearchUser.html', function () {
    return view('admin.userss');
});

Route::get('admin/login.html', function () {
    return view('admin.login');
});

Route::get('admin/Sales.html', function () {
    return view('admin.sales');
});

// Routes from seller/.htaccess
Route::get('seller/index.html', function () {
    return view('seller.index');
});

Route::get('seller/ajaxinfo.html', function () {
    return view('seller.ajax');
});

Route::get('seller/shell.html', function () {
    return view('seller.shells');
});

Route::get('seller/shellMass.html', function () {
    return view('seller.shellMass');
});

Route::get('seller/shellAdd.html', function () {
    return view('seller.shellAdd');
});

Route::get('seller/shellTab1.html', function () {
    return view('seller.shellTab1');
});

Route::get('seller/shellTab2.html', function () {
    return view('seller.shellTab2');
});

Route::get('seller/shellTab3.html', function () {
    return view('seller.shellTab3');
});

Route::get('seller/rdp.html', function () {
    return view('seller.rdps');
});

Route::get('seller/rdpAdd.html', function () {
    return view('seller.rdpAdd');
});

Route::get('seller/rdpTab1.html', function () {
    return view('seller.rdpTab1');
});

Route::get('seller/rdpTab2.html', function () {
    return view('seller.rdpTab2');
});

Route::get('seller/sales.html', function () {
    return view('seller.sales');
});

Route::get('seller/withdraw.html', function () {
    return view('seller.withdrawal');
});

Route::get('seller/reports.html', function () {
    return view('seller.reports');
});

Route::get('seller/reportsTab1.html', function () {
    return view('seller.reportsTab1');
});

Route::get('seller/reportsTab2.html', function () {
    return view('seller.reportsTab2');
});

Route::get('seller/premium.html', function () {
    return view('seller.premium');
});

Route::get('seller/premiumAdd.html', function () {
    return view('seller.premiumAdd');
});

Route::get('seller/premiumTab1.html', function () {
    return view('seller.premiumTab1');
});

Route::get('seller/premiumTab2.html', function () {
    return view('seller.premiumTab2');
});

Route::get('seller/banks/index', function () {
    return view('banks.index');
});

Route::get('seller/banks/Add', function () {
    return view('banks.Add');
});

Route::get('seller/banks/tab1', function () {
    return view('banks.tab1');
});

Route::get('seller/banks/tab2', function () {
    return view('banks.tab2');
});

Route::get('seller/smtp.html', function () {
    return view('seller.smtps');
});

Route::get('seller/smtpAdd.html', function () {
    return view('seller.smtpAdd');
});

Route::get('seller/smtpTab1.html', function () {
    return view('seller.smtpTab1');
});

Route::get('seller/smtpTab2.html', function () {
    return view('seller.smtpTab2');
});

Route::get('seller/tutorial.html', function () {
    return view('seller.tutorials');
});

Route::get('seller/tutorialAdd.html', function () {
    return view('seller.tutorialAdd');
});

Route::get('seller/tutorialTab1.html', function () {
    return view('seller.tutorialTab1');
});

Route::get('seller/tutorialTab2.html', function () {
    return view('seller.tutorialTab2');
});

Route::get('seller/mailer.html', function () {
    return view('seller.mailers');
});

Route::get('seller/mailerAdd.html', function () {
    return view('seller.mailerAdd');
});

Route::get('seller/mailerTab1.html', function () {
    return view('seller.mailerTab1');
});

Route::get('seller/mailerTab2.html', function () {
    return view('seller.mailerTab2');
});

Route::get('seller/cpanel.html', function () {
    return view('seller.cpanels');
});

Route::get('seller/cpanelAdd.html', function () {
    return view('seller.cpanelAdd');
});

Route::get('seller/cpanelMass.html', function () {
    return view('seller.cpanelMass');
});

Route::get('seller/cpanelTab1.html', function () {
    return view('seller.cpanelTab1');
});

Route::get('seller/cpanelTab2.html', function () {
    return view('seller.cpanelTab2');
});

Route::get('seller/cpanelTab3.html', function () {
    return view('seller.cpanelTab3');
});

Route::get('seller/leads.html', function () {
    return view('seller.leads');
});

Route::get('seller/leadAdd.html', function () {
    return view('seller.leadAdd');
});

Route::get('seller/leadTab1.html', function () {
    return view('seller.leadTab1');
});

Route::get('seller/leadTab2.html', function () {
    return view('seller.leadTab2');
});

Route::get('seller/scampage.html', function () {
    return view('seller.scams');
});

Route::get('seller/scampageAdd.html', function () {
    return view('seller.scampageAdd');
});

Route::get('seller/scampageTab1.html', function () {
    return view('seller.scampageTab1');
});

Route::get('seller/scampageTab2.html', function () {
    return view('seller.scampageTab2');
});

Route::get('seller/vt-{id}.html', function ($id) {
    return view('seller.vt', ['id' => $id]);
});

Route::get('seller/vr-{id}.html', function ($id) {
    return view('seller.vr', ['id' => $id]);
});

Route::get('seller/refund-{id}', function ($id) {
    return view('seller.refund', ['id' => $id]);
});

Route::get('seller/showOrder{id}', function ($id) {
    return view('seller.openorder', ['id' => $id]);
});

// Routes from support/.htaccess
Route::get('support/index.html', function () {
    return view('support.index');
});

Route::get('support/Status.html', function () {
    return view('support.dollar');
});

Route::get('support/News.html', function () {
    return view('support.news');
});

Route::get('support/Tickets.html', function () {
    return view('support.ticket');
});

Route::get('support/Reports.html', function () {
    return view('support.reports');
});

Route::get('support/Users.html', function () {
    return view('support.users');
});

Route::get('support/WithdrawalRequests.html', function () {
    return view('support.withdrawalreq');
});

Route::get('support/Sellers.html', function () {
    return view('support.resseller');
});

Route::get('support/PaySeller.html', function () {
    return view('support.rpay');
});

Route::get('support/Logout.html', function () {
    return view('support.lougout');
});

Route::get('support/UsersBalance.html', function () {
    return view('support.usersb');
});

Route::get('support/SearchUser.html', function () {
    return view('support.userss');
});

Route::get('support/login.html', function () {
    return view('support.login');
});

Route::get('support/Sales.html', function () {
    return view('support.sales');
});
```

Add these routes to your `routes/web.php` file in your Laravel project.