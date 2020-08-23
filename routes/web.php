<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'HomeController@goto_login')->name('goto.login');
Route::get('/home', 'DashboardController@index')->name('goto.home');

Auth::routes();



Route::group(['middleware' => 'auth'], function () {
	Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {


		Route::get('pdf/trial/{month}/{year}','PDFController@trial')->name('pdf.trial');
		Route::get('pdf/income/statement/{month}/{year}','PDFController@income_statement')->name('pdf.income.statement');
		Route::get('pdf/balance/sheet/{month}/{year}','PDFController@balance_sheet')->name('pdf.balance.sheet');

 		Route::get('dashboard','DashboardController@index')->name('dashboard');
		Route::get('/profile', 'AccountsController@settings')->name('settings');
		Route::get('/change/password', 'AccountsController@change_password')->name('change.password');

		Route::post('/settings/update/{user}', 'AccountsController@profile_update')->name('profile.update');
		Route::post('/password/update/{user}', 'AccountsController@password_update')->name('password.update');


			

		Route::get('accounts/employee','AccountsController@index')->name('accounts.index');
		Route::post('accounts/store','AccountsController@store')->name('accounts.store');
		Route::post('accounts/{account}','AccountsController@update')->name('accounts.update');
		Route::get('accounts/{account}/edit','AccountsController@edit')->name('accounts.edit');
		Route::post('accounts/{account}/edit','AccountsController@edit')->name('accounts.update');
		Route::post('accounts/{account}/delete','AccountsController@destroy')->name('accounts.destroy');
		Route::get('accounts/{account}/show','AccountsController@show')->name('accounts.show');

		Route::get('attendance','AttendanceController@index')->name('attendance');
		Route::get('biometrics','BiometricsController@index')->name('biometrics');
		Route::post('biometrics/generate','BiometricsController@import_biometrics')->name('biometrics.generate');
		Route::get('biometrics/generate/{employee_id}/{month}/{year}','BiometricsController@monthly_records')->name('biometrics.generate.monthly.records');

		Route::post('biometric/{bio_id}/delete','BiometricsController@destroy')->name('biometrics.destroy');

		Route::post('generate/payroll','PayrollController@import_biometrics')->name('generate.payroll.create');

		Route::get('payroll/create/{employee_id}/{month}/{year}','PayrollController@monthly_records')->name('payroll.generate.monthly.records');

		Route::post('biometrics/store/data','BiometricsController@store')->name('biometric.store.data');
		Route::post('biometrics/store/leaved/data','BiometricsController@leaved')->name('biometric.store.leaved.data');
		Route::post('payroll/biometric/store/data','PayrollController@biometric_store')->name('payroll.biometric.store.data');


		Route::get('biometrics/import','BiologsController@importExportView')->name('biometrics.import.view');
		Route::get('export','BiologsController@export')->name('biologs.export');
		Route::post('import','BiologsController@import')->name('biologs.import');

		Route::get('create/payroll','PayrollController@create_payroll')->name('payroll.create');
 		Route::get('generate/payroll','PayrollController@select_month')->name('payroll.generate');
 		Route::post('payroll/load/data','PayrollController@load_data')->name('payroll.load.data');
 		Route::get('generate/payroll/month/{month}/{year}','PayrollController@index')->name('payroll.month');
 		Route::get('payroll/import','PayrollController@importExportView')->name('payroll.import.view');
		Route::get('payroll/export','PayrollController@export')->name('payroll.export');
		Route::post('payroll/import','PayrollController@import')->name('payroll.import');
		Route::get('payslip/pdf/{payslip_id}/show','PayslipController@pdf')->name('payslip.index');
		Route::get('payslip/month/{month}/{year}','PayslipController@email')->name('payslip.month');
    	Route::post('payroll/store/data','PayrollController@store')->name('payroll.store.data');

		Route::get('trial/balance','TrialBalanceController@index')->name('trial.balance');
		Route::post('trial/balance/period/store','PeriodController@trial_balance_store')->name('trial.balance.period.store');
		Route::get('trial/balance/period/month/{month}/{year}','TrialBalanceController@selected_month')->name('trial.balance.view');
		Route::post('trial/balance/store','TrialBalanceController@store')->name('trial.balance.store');
		Route::get('trial/balance/{trial_balance_id}/edit','TrialBalanceController@edit')->name('trial.balance.edit');
		Route::post('trial/balance/{trial_balance_id}/edit','TrialBalanceController@update')->name('trial.balance.update');
		Route::post('trial/balance/{trial_balance_id}/delete','TrialBalanceController@destroy')->name('trial.balance.edit');


		Route::get('income/statement','IncomeStatementController@index')->name('income.statement');
	    Route::post('income/statement/period/store','PeriodController@income_statement_store')->name('income.statement.period.store');
		 Route::get('income/statement/period/month/{month}/{year}','IncomeStatementController@selected_month')->name('income.statement.view');
		Route::post('income/statement/store','IncomeStatementController@store')->name('income.statement.store');
		Route::get('income/statement/{trial_balance_id}/edit','IncomeStatementController@edit')->name('income.statement.edit');
		Route::post('income/statement/{trial_balance_id}/edit','IncomeStatementController@update')->name('income.statement.update');
		Route::post('income/statement/{trial_balance_id}/delete','IncomeStatementController@destroy')->name('income.statement.edit');

		Route::get('balance/sheet','BalanceSheetController@index')->name('balance.sheet');
	    Route::post('balance/sheet/period/store','PeriodController@balance_sheet_store')->name('balance.sheet.period.store');
		 Route::get('balance/sheet/period/month/{month}/{year}','BalanceSheetController@selected_month')->name('balance.sheet.view');
		Route::post('balance/sheet/store','BalanceSheetController@store')->name('balance.sheet.store');
		Route::get('balance/sheet/{trial_balance_id}/edit','BalanceSheetController@edit')->name('balance.sheet.edit');
		Route::post('balance/sheet/{trial_balance_id}/edit','BalanceSheetController@update')->name('balance.sheet.update');
		Route::post('balance/sheet/{trial_balance_id}/delete','BalanceSheetController@destroy')->name('balance.sheet.edit');

		

   });
});