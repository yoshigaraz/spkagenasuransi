<?php

use App\Http\Controllers\AlternativeController;
use App\Http\Controllers\BonusController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\DataCriteriaController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\RatioAlternativeController;
use App\Http\Controllers\RatioCriteriaController;
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
//     return view('layouts.print');
// });

Route::middleware(['auth'])->group(function () {
    Route::get('/', [Controller::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [Controller::class, 'dashboard'])->name('dashboard');

    Route::get('/karyawan', [EmployeController::class, 'index'])->name('karyawan');
    Route::post('/inputKaryawan', [EmployeController::class, 'store'])->name('inputKaryawan');
    Route::post('/updateKaryawan', [EmployeController::class, 'update'])->name('updateKaryawan');
    Route::post('/inputKaryawan', [EmployeController::class, 'store'])->name('inputKaryawan');
    Route::get('/deleteKaryawan/{employe}', [EmployeController::class, 'destroy'])->name('deleteKaryawan');

    Route::get('/ratioAlternative', [RatioAlternativeController::class, 'index'])->name('ratioAlternative');
    Route::post('/addRatioAlternative', [RatioAlternativeController::class, 'store'])->name('addRatioAlternative');
    Route::get('/resultAlternative', function () {

        $data = RatioAlternativeController::showAlternative();

        if (!$data) {
            return redirect('ratioAlternative')->with(['message' => 'data belum lengkap']);
        }
        return view('pages.ratioAlternative')->with('data', $data);
    })->name('resultAlternative');
    Route::get('/deleteRatioAlternative/{criterias_id}/{v_id}/{h_id}', [RatioAlternativeController::class, 'destroy'])->name('deleteRatioAlternative');
    Route::post('/massRatioAlternative', [RatioAlternativeController::class, 'massUpdate'])->name('massRatioAlternative');

    Route::get('/criteria', [CriteriaController::class, 'index'])->name('criteria');
    Route::post('/addCriteria', [CriteriaController::class, 'store'])->name('addCriteria');
    Route::get('/deleteCriteria/{criteria}', [CriteriaController::class, 'destroy'])->name('deleteCriteria');

    Route::get('/alternative/', [AlternativeController::class, 'index'])->name('alternative');
    Route::post('/addAlternative', [AlternativeController::class, 'store'])->name('addAlternative');
    Route::get('/deleteAlternative/{alternative}', [AlternativeController::class, 'destroy'])->name('deleteAlternative');

//    Route::get('/criteria_comparison', [CriteriaController::class, 'index'])->name('criteria_comparison');
//    Route::post('/addCriteriaComparison', [CriteriaController::class, 'store'])->name('addCriteriaComparison');
//    Route::get('/deleteCriteriaComparison/{criteria}', [CriteriaController::class, 'destroy'])->name('deleteCriteria');

    Route::get('/ratioCriteria', [RatioCriteriaController::class, 'index'])->name('ratioCriteria');
    Route::post('/addRatioCriteria', [CriteriaController::class, 'storeRatio'])->name('addRatioCriteria');
    Route::post('/massRatioCriteria', [CriteriaController::class, 'massUpdate'])->name('massRatioCriteria');
    Route::get('/deleteRatioCriteria/{v_id}/{h_id}', [RatioCriteriaController::class, 'destroy'])->name('deleteRatioCriteria');

    Route::get('/payout', [PayoutController::class, 'index'])->name('payout');
    Route::get('/filterpayout', [PayoutController::class, 'show'])->name('filterpayout');
    Route::post('/addPayout', [PayoutController::class, 'store'])->name('addPayout');
    Route::post('/updatePayout', [PayoutController::class, 'update'])->name('updatePayout');
    Route::get('/deletePayout/{id}', [PayoutController::class, 'destroy'])->name('deletePayout');

    Route::post('/addBonus', [BonusController::class, 'store'])->name('addBonus');
    Route::post('/updateBonus', [BonusController::class, 'update'])->name('updateBonus');
    Route::get('/deleteBonus/{id}', [BonusController::class, 'destroy'])->name('deleteBonus');

    Route::get('/criteriaData', [DataCriteriaController::class, 'index'])->name('criteriaData');
    Route::post('/upsertData', [DataCriteriaController::class, 'store'])->name('upsertData');
    Route::get('/deleteData/{id}', [DataCriteriaController::class, 'destroy'])->name('deleteData');


    Route::get('/userList', [Controller::class, 'index'])->name('userList');
    Route::post('/addUser', [Controller::class, 'save'])->name('addUser');
    Route::get('/deleteUser/{id}', [Controller::class, 'deleteUser'])->name('deleteUser');

    Route::get('/print/{date}', [Controller::class, 'print'])->name('print');
});


require __DIR__ . '/auth.php';
