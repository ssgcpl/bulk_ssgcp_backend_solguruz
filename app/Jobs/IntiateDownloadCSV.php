<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ProductBarcodeCSVFile;
use App\Models\Product;
use App\Models\ProductBarcode;
use App\Models\Helpers\CommonHelper;
use File;

class IntiateDownloadCSV implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use CommonHelper; 
    
    protected $barcodes;
    protected $product_id;
    protected $admin;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($barcodes,$product_id,$admin)
    {
        $this->barcodes = $barcodes;
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
        $dir = public_path('uploads/barcode_csv');
        if (! is_dir($dir))
        mkdir($dir, 0777, true);
        
        $filename="product_all_barcodes_".$this->product_id.".csv";

        // Set the file path to the temporary location
        $handler = $dir ."/". $filename;
         // Open file handler for writing output
        $file = fopen($handler, 'a+');    
       
        $lastId = ProductBarcode::where('product_id',$this->product_id)->OrderBy('id','ASC')->first()->id;
        $firstId = ProductBarcode::where('product_id',$this->product_id)->OrderBy('id','DESC')->first()->id;
        // Send appropriate headers to the browser
        foreach ($this->barcodes as $barcode) {
                if($firstId == $barcode->id){
                    fputs($file, "\xEF\xBB\xBF");
                    $headers = ['id',
                        'Product Title',
                        'Unique Code',
                        'Last Generated on Date/Time'];
                    fputcsv($file, $headers);
                }
                $data_array = ['id' => $barcode->id,
                                    'product_title' => $barcode->product->get_name(),
                                   // 'barcode_value' => $barcode->barcode_value,
                                    'barcode_value' => "\t" . $barcode->barcode_value, // Prefix with tab character
                                    'Last Generated on Date/Time'=>date('Y-m-d H:i A',strtotime($barcode->created_at))];
                fputcsv($file, $data_array); 
                if($lastId == $barcode->id){
                    $csv_file = ProductBarcodeCSVFile::where('product_id',$this->product_id)->where('is_requested','1')->latest()->first();
                    $csv_file->is_requested = '0';
                    $csv_file->is_completed = '1';
                    $csv_file->filename = 'uploads/barcode_csv/'.$filename;
                    $csv_file->save();
                    $product = Product::find($this->product_id);
                    $product_name = $product->get_name();
                    $title = "Product Barcode CSV File";
                    $body = " Hello ".$this->admin->first_name.", Product Barcode CSV file for product ".$product_name." has been generated. Thank you";
                    $slug = 'barcode_csv_file';
                    $this->sendNotification($this->admin,$title,$body,$slug,null,null,'1');
                }
        }
        fclose($file);
        chmod($handler,0777);
    }
}