<?php
namespace App\Traits;

use App\Models\Branch;
use App\Models\Voucher;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Models\Account\PaymentApproval;
use App\Models\Stock\Stock;
use App\Models\Stock\StockOpening;

trait  ManagementTrait
{

    public function generateInvoiceInventory($type,$model)
    {
        $nextInvoiceNumber = $model + 1; // Increment the current invoice number by 1
        $currentMonth = date('m'); // month as a zero-padded number (e.g., 07)
        $currentYear = date('Y');  // full numeric representation of a year (e.g., 2024)
        return "{$type}-{$currentMonth}-{$currentYear}/{$nextInvoiceNumber}";
    }


    public function generateInvoice($type)
    {
        $prefix = $this->generateInvoicePrefix($type);
        $lastId = Voucher::where('type', $type)->max('voucher_number') ?? 0;
        $nextId = $lastId + 1;
        $month = date('m');
        $year = date('Y');

        return "{$prefix}-{$month}-{$year}/{$nextId}";
    }
    public function generateInvoicePrefix($type)
    {
        return match ($type) {
            'cash_receipt'     => 'CRV',
            'cash_payment'     => 'CPV',
            'bank_receipt'     => 'BRV',
            'bank_payment'     => 'BPV',
            'journal'          => 'JVS',
            'purchase'         => 'PRV',
            'purchase_return'  => 'PRT',
            'sale'             => 'SRV',
            'sale_return'      => 'SRT',
            'spot_sale'        => 'DSR',
            default            => 'VCH',
        };
    }

    public function generateInvoiceForOpening($type)
    {
        $lastId = Transaction::where('invoice_type', $type)->count();
        $nextId = $lastId + 1;
        $month = date('m');
        $year = date('Y');
        $prefix = 'OPB';
        return "{$prefix}-{$month}-{$year}/{$nextId}";
    }

    public function generateInvoiceForOpeningStock($type)
    {
        $lastId = StockOpening::count();
        $nextId = $lastId + 1;
        $month = date('m');
        $year = date('Y');
        $prefix = 'OPB';
        return "{$prefix}-{$month}-{$year}/{$nextId}";
    }
    public function generateInvoiceForApprovals($type)
    {
        $lastId = PaymentApproval::count();
        $nextId = $lastId + 1;
        $month = date('m');
        $year = date('Y');
        $prefix = 'PA';
        return "{$prefix}-{$month}-{$year}/{$nextId}";
    }



}
