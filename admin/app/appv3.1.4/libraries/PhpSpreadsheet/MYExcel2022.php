<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//include phpSpreadSheet
include_once(SPREADSHEET_LIB_PATH);

use PhpOffice\PhpSpreadsheet\Spreadsheet; // tùy class nào dùng thì use. Do dung theo namespace nên dùng use và để đầu file
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;  // tùy class nào dùng thì use. Do dung theo namespace nên dùng use và để đầu file
use PhpOffice\PhpSpreadsheet\Style\Alignment; // TODO: ko dung
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

$spreadsheet = new Spreadsheet();
class MYExcel
{
    //export excell
    private $next_row  = 1;
    private $total_col = 0;

    public $data_header = [];
    public $cells       = [];
    public $rows        = [];

    public $objPHPExcel = null;

    public function __construct()
    {
        $this->next_row     = 1;
        $this->total_col    = 0;

        $this->objPHPExcel = new Spreadsheet();
        $this->PropertiesPHPExcel('new file');
    }

    public function HeaderExcel($tittle, $fromdate, $todate)
    {

        $dataHeader = [
            'title'     => $tittle,
            'from_date' => $fromdate,
            'to_date'   => $todate
        ];
        $this->data_header = $dataHeader;
    }

    public function text($text)
    {
        $this->add_data_to_col('value', $text);
        $this->add_data_to_col('type', 'text');
        return $this;
    }

    public function svg($svg, $attribute = [])
    {
        $this->add_data_to_col('value', $svg);
        $this->add_data_to_col('attribute', $attribute);
        $this->add_data_to_col('type', 'svg');
        return $this;
    }

    public function chart_IMG($path, $attribute = [])
    {
        $this->add_data_to_col('value', $path);
        $this->add_data_to_col('attribute', $attribute);
        $this->add_data_to_col('type', 'chart_img');
        return $this;
    }

    public function number($number)
    {
        $this->add_data_to_col('value', $number);
        $this->add_data_to_col('type', 'number');
        return $this;
    }

    public function image($image)
    {
        $this->add_data_to_col('value', $image);
        $this->add_data_to_col('type', 'image');
        return $this;
    }

    public function percent($percent)
    {
        $this->add_data_to_col('value', $percent);
        $this->add_data_to_col('type', 'percent');
        return $this;
    }

    public function merge($merge)
    {
        $this->add_data_to_col('merge', $merge);
        return $this;
    }

    public function width($width)
    {
        $this->add_data_to_col('width', $width);
        return $this;
    }

    public function horizontal($horizontal)
    {
        $this->add_data_to_col('horizontal', $horizontal);
        return $this;
    }

    public function row($style = [])
    {
        $this->rows[] = ['cols' => [], 'style' => $style];
        return $this;
    }

    public function col()
    {
        if (!empty($this->rows)) {
            $num_row = count($this->rows);
            $key_row = $num_row == 0 ? 0 : $num_row - 1;
            $this->rows[$key_row]['cols'][] = [];
        }

        return $this;
    }

    public function add_data_to_col($key, $value)
    {
        if (!empty($this->rows)) {

            $num_row = count($this->rows);
            $key_row = $num_row == 0 ? 0 : $num_row - 1;

            if (empty($this->rows[$key_row]['cols'])) {

                $this->rows[$key_row]['cols'][][$key] = $value;
            } else {

                $num_col = count($this->rows[$key_row]['cols']);
                $key_col = $num_col == 0 ? 0 : $num_col - 1;

                $this->rows[$key_row]['cols'][$key_col][$key] = $value;

                if ($num_col > $this->total_col) {
                    $this->total_col = $num_col;
                }
            }
        }
        return $this;
    }

    // BUILD MAIN
    public function BuildHeaderExcel($title, $fromdate, $todate)
    {

        //lay toa do logo 1, logo 2, tittle
        //header rong toi thieu 10 cot
        $max_col = $this->total_col < 10 ? 10 : $this->total_col;
        $logo1_cell       = 'A1:B1';
        $tittle_cell      = 'C1:' . $this->Numeric2Alphabet($max_col - 2) . '1';
        $logo2_cell_start = $this->Numeric2Alphabet($max_col - 1) . '1';
        $logo2_cell_end   = $this->Numeric2Alphabet($max_col) . '1';
        $logo2_cell       = $logo2_cell_start . ':' . $logo2_cell_end;
        $this->objPHPExcel->getActiveSheet()->mergeCells($logo1_cell); //logo 1
        $this->objPHPExcel->getActiveSheet()->mergeCells($tittle_cell); //bao cao tat ca quang cao
        $this->objPHPExcel->getActiveSheet()->mergeCells($logo2_cell); //logo 2
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);

        $this->objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
        $this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', $title);
        $this->objPHPExcel->getActiveSheet()->getStyle("C1")
            ->applyFromArray(array('font' => array('bold' => true, 'size' => 20), 'color' => array('rgb' => '000000')))
            ->getAlignment()->setHorizontal('center')->setVertical('bottom');


        $logo_left_path   = 'images/adx.png';
        $objDrawing = new Drawing();
        $objDrawing->setName('Logo left');
        $objDrawing->setPath('./' . $logo_left_path);
        $objDrawing->setCoordinates('A1');
        $objDrawing->getShadow()->setVisible(true);
        $objDrawing->setHeight(50);
        $objDrawing->setOffsetX(10);
        $objDrawing->setOffsetY(10);
        $objDrawing->setWorksheet($this->objPHPExcel->getActiveSheet());

        $logo_right_path   = 'images/admicro.png';
        $objDrawing = new Drawing();
        $objDrawing->setName('Logo right');
        $objDrawing->setPath('./' . $logo_right_path);
        $objDrawing->setCoordinates($logo2_cell_start);
        $objDrawing->getShadow()->setVisible(true);
        $objDrawing->setHeight(60);
        $objDrawing->setOffsetX(10);
        $objDrawing->setOffsetY(10);
        $objDrawing->setWorksheet($this->objPHPExcel->getActiveSheet());

        $this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'Ngày xuất: ' . date('d/m/Y'));
        $this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', 'Thời gian: ' . date('d/m/Y', strtotime($fromdate)) . ' đến ' . date('d/m/Y', strtotime($todate)));

        $this->next_row = 5;

        return $this;
    }

    public function BuildRowPHPExcel()
    {
        //column start
        $colStart = $this->Alphabet2Numeric('A');
        foreach ($this->rows as $row) {
            $style    = isset($row['style']) ? $row['style'] : [];
            $indexCol = 0;
            foreach ($row['cols'] as $cell) {

                $typeContentCell  = $cell['type'];
                $valueContentCell = $cell['value'];
                $width            = isset($cell['width']) ? $cell['width'] : 15;
                $horizontal_cell  = isset($cell['horizontal']) ? $cell['horizontal'] : '';
                $merge            = isset($cell['merge']) ? $cell['merge'] : 0;

                $indexRow = $this->next_row;
                $nameCol   = $this->Numeric2Alphabet($colStart + $indexCol);
                $cell_name = $nameCol .  $indexRow;

                //merge
                if ($merge > 0) {
                    $indexCol     = $indexCol + $merge;
                    $cellEndMerge = $this->Numeric2Alphabet($colStart + $indexCol) .  $indexRow;
                    $this->objPHPExcel->getActiveSheet()->mergeCells($cell_name . ':' . $cellEndMerge);
                    $this->objPHPExcel->getActiveSheet()->getStyle($cell_name . ':' . $cellEndMerge)->applyFromArray($style);
                }

                //cell width
                $this->objPHPExcel->getActiveSheet()->getColumnDimension($nameCol)->setWidth($width);

                //cell content
                if ($typeContentCell == 'image') {

                    $path = $valueContentCell;
                    $this->BuildIMGExcel($path, $cell_name, $indexRow);
                } else if ($typeContentCell == 'svg') {

                    $svg = $valueContentCell;
                    $this->BuildChartSVGExcel($svg, $cell['attribute']);
                } else if ($typeContentCell == 'text') {

                    $this->objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_name, $valueContentCell);
                    $this->objPHPExcel->getActiveSheet()->getStyle($cell_name)->getAlignment()->setWrapText(true);
                } else if ($typeContentCell == 'number') {

                    $this->objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_name, $this->reset_number($valueContentCell));
                    $this->objPHPExcel->setActiveSheetIndex(0)->getStyle($cell_name)->getNumberFormat()->setFormatCode('#,##0');
                } else if ($typeContentCell == 'percent') {

                    $this->objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_name, $this->reset_percent($valueContentCell));
                    if ($valueContentCell < 0.001) {
                        $this->objPHPExcel->setActiveSheetIndex(0)->getStyle($cell_name)->getNumberFormat()->setFormatCode('0%');
                    } else {
                        $this->objPHPExcel->setActiveSheetIndex(0)->getStyle($cell_name)->getNumberFormat()->setFormatCode('#,##0.000%');
                    }
                }
                else if($typeContentCell == 'chart_img'){
                    $path = $valueContentCell;
                    $this->BuildChartIMGExcel($path, $cell['attribute']);
                }else {

                    $this->objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_name, $valueContentCell);
                }

                //cell style
                $this->objPHPExcel->getActiveSheet()->getStyle($cell_name)->applyFromArray($style);

                //cell horizontal
                if ($horizontal_cell !== '') {

                    $this->objPHPExcel->getActiveSheet()->getStyle($cell_name)->getAlignment()->setHorizontal($horizontal_cell)->setVertical('center');
                } else {

                    if ($typeContentCell == 'number' || $typeContentCell == 'percent')
                        $this->objPHPExcel->getActiveSheet()->getStyle($cell_name)->getAlignment()->setHorizontal('right')->setVertical('center');
                    else if ($typeContentCell == 'text')
                        $this->objPHPExcel->getActiveSheet()->getStyle($cell_name)->getAlignment()->setHorizontal('left')->setVertical('center');
                }

                $indexCol++;
            }

            $this->next_row++;
        }

        return $this;
    }

    function BuildIMGExcel($path, $cell_name, $indexRow)
    {
        $objDrawing = new Drawing();
        $objDrawing->setName('Preview');
        $objDrawing->setDescription('Preview');
        $objDrawing->setPath($path);
        $objDrawing->setCoordinates($cell_name);
        $objDrawing->getShadow()->setVisible(true);
        $objDrawing->setOffsetX(10);
        $objDrawing->setOffsetY(10);
        $objDrawing->setWidthAndHeight(70, 70);
        $objDrawing->setWorksheet($this->objPHPExcel->getActiveSheet());
        $this->objPHPExcel->getActiveSheet()->getRowDimension($indexRow)->setRowHeight(70);
    }

    function run($file_name = 'excel.xlsx')
    {

        $title    = @$this->data_header['title'];
        $fromdate = @$this->data_header['from_date'];
        $todate   = @$this->data_header['to_date'];

        $this->BuildHeaderExcel($title, $fromdate, $todate);
        $this->BuildRowPHPExcel();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $file_name . '"');
        header('Cache-Control: max-age=0');
        // $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5'); //TODO: check
        $objWriter = new Xlsx($this->objPHPExcel);
        $objWriter->save('php://output');
    }

    public  function typeFileStringPHPExcel($type)
    {
        $typeString = '';
        if ($type == 'image/png') {
            $typeString = '-m image/png';
            $ext = 'png';
        } elseif ($type == 'image/jpeg') {
            $typeString = '-m image/jpeg';
            $ext = 'jpg';
        } elseif ($type == 'application/pdf') {
            $typeString = '-m application/pdf';
            $ext = 'pdf';
        } elseif ($type == 'image/svg+xml') {
            $ext = 'svg';
        }
        return $typeString;
    }

    public static function saveImageFromUrl($url, $filePathSave)
    {
        //Get the file
        $rel = false;
        $content = @file_get_contents($url);
        if ($content !== FALSE) {
            //Store in the filesystem.
            $fp = @fopen($filePathSave, "w+");
            if ($fp !== FALSE) {
                @fwrite($fp, $content);
                $rel = true;
            }
            fclose($fp);
        }
        return $rel;
    }

    public static function convertSvgToImage($filename, $svg, $width, $fileType)
    {

        $BATIK_PATH =  BATIK_LIB_PATH . 'batik-rasterizer.jar';

        // generate the temporary file
        if (!file_put_contents("$filename.svg", $svg)) {
            die("Couldn't create temporary file. Check that the directory permissions for
            the /temp directory are set to 777.");
        }
        // do the conversion
        $output = shell_exec("java -jar " . $BATIK_PATH . " -m $fileType" . " -d $filename.png" . " -w $width " . "$filename.svg");

        return $output;
    }

    public function BuildChartSVGExcel($svg, $attribute)
    {
        //width chart image
        $min_width_col = 12; // 1 cot rong toi thieu 12 don vi cell;
        $min_total_col_chart = 10; // chart rong toi thieu 10 cot
        $cell_to_px = 7.037037; //1 don vi cell = 7.037037 px;
        $w = 0; //tong chieu rong tinh theo don vi excel

        $total_col = $this->total_col < $min_total_col_chart ? $min_total_col_chart : $this->total_col;
        for ($i = 1; $i <= $total_col; $i++) {
            $getWidthCol = $this->objPHPExcel->getActiveSheet()->getColumnDimension($this->Numeric2Alphabet($i))->getWidth();
            if($getWidthCol == -1) $getWidthCol = $min_width_col;
            $w = $w + $getWidthCol;
        }
        $setWidth = $w * $cell_to_px - 30; //px
        // $setWidth = $setWidth <= 0 ? $width : $setWidth;
        $setWidth = $attribute == [] ? $setWidth : $attribute['width'];
        $col =  isset($attribute['col']) ? $attribute['col'] : 'A';

        //upload to folder
        $filename = md5(rand());
        $tmpFolder = 'uploads/export/';
        $filename = $tmpFolder . "$filename";

        $this->convertSvgToImage($filename, $svg, $setWidth, 'image/png');

        if (is_file($filename . ".png") || filesize($filename . ".png") > 10) {

            $objDrawing  = new Drawing();
            $objDrawing->setCoordinates($col . $this->next_row);
            $objDrawing->setPath("$filename.png");
            $objDrawing->setName('chart');
            $objDrawing->setDescription('chart');

            $objDrawing->setOffsetY(1);
            $objDrawing->setOffsetX(15);
            $objDrawing->setWidth($setWidth);

            $objDrawing->setWorksheet($this->objPHPExcel->getActiveSheet());

            // $this->next_row++;
        }

        return $this;
    }

    public function BuildChartIMGExcel($img_path, $attribute)
    {
        //width chart image
        $min_width_col = 12; // 1 cot rong toi thieu 12 don vi cell;
        $min_total_col_chart = 10; // chart rong toi thieu 10 cot
        $cell_to_px = 7.037037; //1 don vi cell = 7.037037 px;
        $w = 0; //tong chieu rong tinh theo don vi excel

        $total_col = $this->total_col < $min_total_col_chart ? $min_total_col_chart : $this->total_col;
        for ($i = 1; $i <= $total_col; $i++) {
            $getWidthCol = $this->objPHPExcel->getActiveSheet()->getColumnDimension($this->Numeric2Alphabet($i))->getWidth();
            if($getWidthCol == -1) $getWidthCol = $min_width_col;
            $w = $w + $getWidthCol;
        }
        $setWidth = $w * $cell_to_px - 30; //px
        // $setWidth = $setWidth <= 0 ? $width : $setWidth;
        $setWidth = $attribute == [] ? $setWidth : $attribute['width'];
        $col =  isset($attribute['col']) ? $attribute['col'] : 'A';

        if (is_file($img_path) || filesize($img_path) > 10) {

            $objDrawing  = new Drawing();
            $objDrawing->setCoordinates($col . $this->next_row);
            $objDrawing->setPath($img_path);
            $objDrawing->setName('chart');
            $objDrawing->setDescription('chart');

            $objDrawing->setOffsetY(1);
            $objDrawing->setOffsetX(15);
            $objDrawing->setWidth($setWidth);

            $objDrawing->setWorksheet($this->objPHPExcel->getActiveSheet());

            // $this->next_row++;
        }

        return $this;
    }

    public function PropertiesPHPExcel($name)
    {
        $this->objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle($name)
            ->setSubject($name)
            ->setDescription($name)
            ->setKeywords($name)
            ->setCategory($name);

        //fill color all cell
        $this->objPHPExcel->getActiveSheet()->setShowGridlines(False);
        // $this->objPHPExcel->getActiveSheet()->getStyle('A1:Z1000')->applyFromArray(array('fill' => array('type' => 'solid', 'color' => array('rgb' => 'ffffff'))));
    }

    public function default_border()
    {
        return array(
            'borderStyle' => Border::BORDER_THIN,
            'font' => array('bold' => true, 'size' => 11),
            'color' => array('rgb' => '000000')
        );
    }

    public function style_header()
    {
        $default_border = $this->default_border();
        return array(
            'borders' => array('bottom' => $default_border, 'left' => $default_border, 'top' => $default_border, 'right' => $default_border),
            'fill' => array('fillType' => Fill::FILL_SOLID, 'color' => array('rgb' => '459a00')),
            'font' => array('bold' => true, 'size' => 11, 'color' => array('rgb' => 'FFFFFF'))
        );
    }

    public function style_normal()
    {
        $default_border = $this->default_border();
        return array(
            'font' => array('size' => 11),
            'borders' => array('bottom' => $default_border, 'left' => $default_border, 'top' => $default_border, 'right' => $default_border)
        );
    }

    public function style_font_bold()
    {
        $default_border = $this->default_border();
        return array(
            'font' => array('size' => 11, 'bold' => true),
            'borders' => array('bottom' => $default_border, 'left' => $default_border, 'top' => $default_border, 'right' => $default_border)
        );
    }

    //A=>1, B=>2, Z=>26, AA=>27 ...
    private function Alphabet2Numeric($colName)
    {
        $colNameArr = str_split($colName);
        $countColNameArr  = is_array($colNameArr) ? count($colNameArr) : 0;
        $sum = 0;
        for ($i = 0; $i < $countColNameArr; $i++) {
            $sum = $sum * 26 + ord($colNameArr[$i]) - 64;
        }
        return $sum;
    }

    //1=>A, 2=>B, 26=>Z, 27=>AA ...
    private function Numeric2Alphabet($columnNumber)
    {
        $columnString = "";
        while ($columnNumber > 0) {
            $currentLetterNumber = ($columnNumber - 1) % 26;
            $currentLetter = $currentLetterNumber + 65;
            $columnString = chr($currentLetter) . $columnString;
            $columnNumber = ($columnNumber - ($currentLetterNumber + 1)) / 26;
        }
        return $columnString;
    }

    public function reset_number($number)
    {
        $number = str_replace(",", "", $number);
        return is_numeric($number) ? $number : 0;
    }

    public function reset_percent($number)
    {

        $number = str_replace(",", "", $number);

        if (!is_numeric($number)) return 0;

        return $number / 100;
    }

    public function check_uv($uv, $v)
    {

        $uv = $this->reset_number($uv);
        $v = $this->reset_number($v);

        return  $uv > $v ? $v : $uv;
    }

    public function reach($uv, $v)
    {

        $uv = $this->reset_number($uv);
        $v = $this->reset_number($v);

        $uv = $this->check_uv($uv, $v);

        if ($v == 0) {
            return 0;
        } else {
            return ($uv / $v) * 100;
        }
    }

    public function ctr($click, $view)
    {

        $click = $this->reset_number($click);
        $view = $this->reset_number($view);

        if ($view == 0) {
            return 0;
        } else {
            return ($click / $view) * 100;
        }
    }

    public function get_status_by_number($number, $lang)
    {
        $stt = '';
        if ($number == -1) {
            $stt = $lang['completed'];
        } elseif ($number == 1) {
            $stt = $lang['running'];
        } elseif ($number == 2) {
            $stt = $lang['paused'];
        } elseif ($number == 3) {
            $stt = $lang['waiting'];
        } elseif ($number == 4) {
            $stt = $lang['problem'];
        } elseif ($number == 5) {
            $stt = $lang['outofmoney'];
        } elseif ($number == 6) {
            $stt = $lang['het_ngan_sach'];
        } elseif ($number == 8) {
            $stt = $lang['waittorun'];
        } else
            $stt = $number;

        return $stt;
    }
}
