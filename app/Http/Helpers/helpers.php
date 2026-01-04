<?php

use App\Models\Account;
use App\Models\Bank;
use App\Models\BankTransaction;
use App\Models\EmployeeMonthlyTransaction;
use App\Models\EmployeeTransaction;
use App\Models\GeneralSetting;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Exceptions\ImageException;
use Mpdf\Mpdf;

if (!function_exists('gs')) {
    function gs()
    {
        $generalSetting = GeneralSetting::find(1);
        return $generalSetting;
    }
}

if (!function_exists('randomCode')) {
    function randomCode($static)
    {
        return $static . strtoupper(Str::random(6));
    }
}

if (!function_exists('uploadImage')) {
    function uploadImage($uploadedFile, $path, $size, $oldFile = null)
    {
        try {
            if (!$uploadedFile) {
                throw new \Exception('No file was uploaded.');
            }

            $manager = new ImageManager(new Driver());
            $image = $manager->read($uploadedFile);
            $image->scale(width: $size);

            $fileName = time() . '.' . $uploadedFile->getClientOriginalExtension();
            $publicPath = public_path('uploads/' . $path . '/' . $fileName);

            if (!file_exists(public_path('uploads/' . $path))) {
                if (!mkdir(public_path('uploads/' . $path), 0755, true)) {
                    throw new \Exception('Failed to create directory.');
                }
            }

            if ($oldFile) {
                $oldLogoPath = public_path('uploads/' . $path . '/' . $oldFile);
                if (file_exists($oldLogoPath)) {
                    unlink($oldLogoPath);
                }
            }

            $image->save($publicPath);
        } catch (ImageException $e) {
            $notify[] = ['error', 'Image processing failed.'];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            $notify[] = ['error', 'Image upload failed.'];
            return back()->withNotify($notify);
        }
        return $fileName;
    }
}

if (!function_exists('showDateTime')) {
    function showDateTime($date, $time = false)
    {
        if ($time == true) {
            return Carbon::parse($date)->translatedFormat('d M Y, h:i A');
        } else {
            return Carbon::parse($date)->translatedFormat('d M Y');
        }
    }
}

if (!function_exists('showDateName')) {
    function showDateName($date)
    {
        $monthName = Carbon::createFromFormat('Y-m', $date)->format('F');
        $year = Carbon::createFromFormat('Y-m', $date)->format('Y');

        return $monthName . ' ' . $year;
    }
}

if (!function_exists('showAmount')) {
    function showAmount($amount, $decimal = 2, $separate = true, $exceptZeros = false)
    {
        $separator = '';
        if ($separate) {
            $separator = ',';
        }

        $printAmount = number_format($amount, $decimal, '.', $separator);
        if ($exceptZeros) {
            $exp = explode('.', $printAmount);
            if ($exp[1] * 1 == 0) {
                $printAmount = $exp[0];
            }
        }
        return $printAmount;
    }
}

if (!function_exists('updateAcc')) {
    function updateAcc($data, $operation, $id = 0, $type)
    {

        if ($operation == 'NTR') {
            $account = new Account();
        } else {
            $findAccount = Account::where($operation, $id)->whereType($type)->first();
            $account = $id > 0 ? (isset($findAccount) ? $findAccount : new Account()) : new Account();
        }

        $account->purchase_id         = $data['purchase_id'] ?? 0;
        $account->sell_id             = $data['sell_id'] ?? 0;
        $account->expense_id          = $data['expense_id'] ?? 0;
        $account->sell_return_id      = $data['sell_return_id'] ?? 0;
        $account->purchase_return_id  = $data['purchase_return_id'] ?? 0;
        $account->damage_id           = $data['damage_id'] ?? 0;
        $account->income_id           = $data['income_id'] ?? 0;
        $account->investment_id       = $data['investment_id'] ?? 0;
        $account->payable_id          = $data['payable_id'] ?? 0;
        $account->receivable_id       = $data['receivable_id'] ?? 0;
        $account->asset_id            = $data['asset_id'] ?? 0;
        $account->type                = $data['type'];
        $account->credit              = $data['credit'] ?? 0;
        $account->debit               = $data['debit'] ?? 0;
        $account->amount              = $data['amount'] ?? 0;
        $account->description         = $data['description'] ?? '';
        $account->customer_id         = $data['customer_id'] ?? 0;
        $account->supplier_id         = $data['supplier_id'] ?? 0;
        $account->employee_id         = $data['employee_id'] ?? 0;
        $account->entry_type          = $data['entry_type'] ?? 0;
        $account->payment_method      = $data['payment_method'] ?? 0;
        $account->entry_by            = auth()->user()->id;
        $account->status              = isset($data['debit']) ? 1 : (isset($data['credit']) ? 2 : 0);
        $account->entry_date          = now();
        $account->save();

        $total_debit        = Account::sum('debit');
        $total_credit       = Account::sum('credit');
        $final_balance      = $total_credit - $total_debit;
        $account->balance   = $final_balance;
        $account->save();

        return $account;
    }
}

if (!function_exists('bankTr')) {
    function bankTr($account, $data)
    {
        $validator = Validator::make($data, [
            'bank_id'  => 'required',
            isset($data['debit']) ? 'withdrawer_name' : 'depositor_name'  => 'required',
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $transactionExist = BankTransaction::whereAccountId($account->id)->first();
        $bankTransaction = $transactionExist ?? new BankTransaction();

        if ($bankTransaction->exists) {
            $bank = Bank::whereId($bankTransaction->bank_id)->first();
            $bank->balance = isset($data['debit']) ? ($bank->balance + $bankTransaction->debit) : ($bank->balance - $bankTransaction->credit);
            $bank->save();
        }

        $bankTransaction->account_id        =  $data['account_id'];
        $bankTransaction->bank_id           =  $data['bank_id'];
        $bankTransaction->check_no          =  $data['check_no'] ?? '';
        $bankTransaction->description       =  $data['description'] ?? '';
        $bankTransaction->credit            =  $data['credit'] ?? 0;
        $bankTransaction->debit             =  $data['debit'] ?? 0;
        $bankTransaction->withdrawer_name   =  $data['withdrawer_name'] ?? '';
        $bankTransaction->depositor_name    =  $data['depositor_name'] ?? '';
        $bankTransaction->expense_id        =  $data['expense_id'] ?? 0;
        $bankTransaction->entry_by          =  auth()->user()->id;
        $bankTransaction->entry_date        =  now();
        $bankTransaction->status            =  isset($data['debit']) ? 1 : 2;
        $bankTransaction->save();

        $total_debit = BankTransaction::sum('debit');
        $total_credit = BankTransaction::sum('credit');
        $final_balance = $total_credit - $total_debit;
        $bankTransaction->balance = $final_balance;
        $bankTransaction->save();

        $bank = Bank::whereId($bankTransaction->bank_id)->first();
        $bank->balance = isset($data['debit']) ? ($bank->balance - $data['debit']) : ($bank->balance + $data['credit']);
        $bank->save();
    }
}


if (!function_exists('cashStatement')) {
    function statement($method = 0)
    {
        if ($method == 0) {
            $total_debit   = Account::sum('debit');
            $total_credit  = Account::sum('credit');
        } else {
            $total_debit   = Account::wherePaymentMethod($method)->sum('debit');
            $total_credit  = Account::wherePaymentMethod($method)->sum('credit');
        }
        $balance = $total_credit - $total_debit;

        return $balance;
    }
}

if (!function_exists('getSum')) {
    function getSum($method = 0)
    {
        if ($method == 0) {
            $total_debit   = Account::sum('debit');
            $total_credit  = Account::sum('credit');

            $arr = [$total_debit, $total_credit];
        } else {
            $total_debit   = Account::wherePaymentMethod($method)->sum('debit');
            $total_credit  = Account::wherePaymentMethod($method)->sum('credit');

            $arr = [$total_debit, $total_credit];
        }

        return $arr;
    }
}

if (!function_exists('getEmpTrSum')) {
    function getEmpTrSum($column, $id = 0, $monthly = false, $date = null)
    {
        if (isset($date)) {
            $month = Carbon::createFromFormat('Y-m', $date)->month;
            $year = Carbon::createFromFormat('Y-m', $date)->year;
            $trxSum = EmployeeTransaction::whereEmployeeId($id)->whereYear('salary_date', $year)
                ->whereMonth('salary_date', $month)
                ->notDeleted()
                ->sum($column);
        }

        if ($id > 0) {
            $sum  = $monthly == true ? EmployeeMonthlyTransaction::whereEmployeeId($id)->notDeleted()->sum($column) : $trxSum;
        } else {
            $sum  = $monthly == true ? EmployeeMonthlyTransaction::notDeleted()->sum($column) : $trxSum;
        }

        return $sum;
    }
}


if (!function_exists('setPdf')) {
    function setPdf($orientation = 'P')
    {
        $fontPath = public_path('fonts/kalpurush.ttf');
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $path = public_path('fonts');

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => $orientation,
            'fontDir' => array_merge($fontDirs, [$path]),
            'fontdata' => $fontData + [
                'kalpurush' => [
                    'R' => 'kalpurush.ttf',
                    'useOTL' => 0xFF,
                ],
            ],
            'default_font' => 'kalpurush',
        ]);

        return $mpdf;
    }
}

if (!function_exists('paymentMethod')) {
    function paymentMethod($method = '1')
    {
        if ($method == '1') {
            return 'Cash';
        } else if ($method == '2') {
            return 'Bank';
        } else if ($method == '3') {
            return 'Cheque';
        }

        return 'Unknown-Payment-Method';
    }
}
