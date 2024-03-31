<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ProductBarcode;
use App\Jobs\IntiateDownloadCSV;

use DB,PDF;

class DownloadBarcodeCSV implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $product_id;
    protected $admin;
    public function __construct($product_id,$admin)
    {
        $this->product_id = $product_id;
        $this->admin = $admin;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info("Job dispatched".$this->product_id);
        $data = ProductBarcode::where('product_id',$this->product_id)
                    ->orderBy('id','DESC')
                    ->chunk(500,function($barcodes){
                         //dispatch(new IntiateDownloadCSV($barcodes,$this->product_id,$this->admin));
                         //dispatchSync(new IntiateDownloadCSV($barcodes,$this->product_id));
                         IntiateDownloadCSV::dispatch($barcodes,$this->product_id,$this->admin);
                    });
        $filename="product_all_barcodes_".$this->product_id.".csv";
    }
}