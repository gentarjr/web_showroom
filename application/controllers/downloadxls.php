<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Downloadxls extends CI_Controller {
    function __construct(){
        parent::__construct();
        $this->load->library('PHPExcel');
		$this->load->library('PHPExcel/IOFactory');
        $this->load->model('model_xls'); // memanggil model xls
    }
     public function export(){
        $ambildata = $this->model_xls->export_mobil();
         
        if(count($ambildata)>0){
            $objPHPExcel = new PHPExcel();
            // Set properties
            $objPHPExcel->getProperties()
                  ->setCreator("SAMSUL ARIFIN") //creator
                    ->setTitle("Programmer - Regional Planning and Monitoring, XL AXIATA");  //file title
 
            $objset = $objPHPExcel->setActiveSheetIndex(0); //inisiasi set object
            $objget = $objPHPExcel->getActiveSheet();  //inisiasi get object
 
            $objget->setTitle('Sample Sheet'); //sheet title
             
            $objget->getStyle("A1:L1")->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '92d050')
                    ),
                    'font' => array(
                        'color' => array('rgb' => '000000')
                    )
                )
            );
 
            //table header
            $cols = array("A","B","C","D","E","F","G","H","I","J","K","L");
             
            $val = array("kd_mobil","merk_mobil","harga_beli","harga_jual","no_pol","no_rka","no_msn","tahun","warna","atas_nama","alamat","status");
             
            for ($a=0;$a<12; $a++) 
            {
                $objset->setCellValue($cols[$a].'1', $val[$a]);
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25); 
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25); 
				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);
             
                $style = array(
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    )
                );
                $objPHPExcel->getActiveSheet()->getStyle($cols[$a].'1')->applyFromArray($style);
            }
             
            $baris  = 2;
            foreach ($ambildata as $frow){
                 
                //pemanggilan sesuaikan dengan nama kolom tabel
                $objset->setCellValue("A".$baris, $frow->kd_mobil);
				$objset->setCellValue("B".$baris, $frow->merk_mobil); //membaca data nim
                $objset->setCellValue("C".$baris, $frow->harga_beli); //membaca data nama
                $objset->setCellValue("D".$baris, $frow->harga_jual); //membaca data 
				$objset->setCellValue("E".$baris, $frow->no_pol); //membaca data 
				$objset->setCellValue("F".$baris, $frow->no_rka);
                $objset->setCellValue("G".$baris, $frow->no_msn);
                $objset->setCellValue("H".$baris, $frow->tahun);
				$objset->setCellValue("I".$baris, $frow->warna);
				$objset->setCellValue("J".$baris, $frow->atas_nama);
                $objset->setCellValue("K".$baris, $frow->alamat);
                $objset->setCellValue("L".$baris, $frow->status);
                 
                //Set number value
                $objPHPExcel->getActiveSheet()->getStyle('C1:C'.$baris)->getNumberFormat()->setFormatCode('0');
                 
                $baris++;
            }
             
            $objPHPExcel->getActiveSheet()->setTitle('Data Export');
 
            $objPHPExcel->setActiveSheetIndex(0);  
            $filename = urlencode("Data".date("Y-m-d H:i:s").".xls");
               
              header('Content-Type: application/vnd.ms-excel'); //mime type
              header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
              header('Cache-Control: max-age=0'); //no cache
 
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');                
            $objWriter->save('php://output');
        }
    }
}