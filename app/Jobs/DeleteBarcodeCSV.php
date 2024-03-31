<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteBarcodeCSV implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected  $product_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($product_id)
    {
        $this->product_id = $product_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //fetch barcode file to delete
        $dir = public_path('uploads/barcode_csv');
       
        $filename="product_all_barcodes_".$this->product_id.".csv";
        \Log::info("Delete CSV ".$filename); 
        // Set the file path to the temporary location
        $handler = $dir ."/". $filename;
        unlink($handler);
    }
}
