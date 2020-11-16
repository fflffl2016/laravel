<?php

namespace App\Console\Commands;

use App\Helper\Requests;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class test extends Command
{
    protected $signature = 'test';
    protected $description = 'test';

    final public function handle(): void
    {
        $a1 = $this->file2();
        $file = file_get_contents('/home/sdhou/Downloads/1.csv');
        foreach (explode("\n", $file) as $line) {
            $num = 12;
            $rows = explode(',', $line);
            if (count($rows) !== 38) {
                Requests::log("err {$line}");
                continue;
            }
            [
                $date, $notice_no, $name, $address, $source, $sell, $get_card_type, $operator, $mobile, $total_price,
                $card_price, $pay_type, $get_price, $commission, $pay_type1, $receivable, $card_name,
                $batch_no1, $sn1_start, $sn1_end, $count1, $price1, $batch_no2, $sn2_start, $sn2_end, $count2, $price2
            ] = $rows;
            if (!isset($a1[$notice_no])) {
                Requests::log('not notice no ' . $line);
                continue;
            }
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', '发卡清单');
            $sheet->mergeCells('A1:I2');

            $sheet->setCellValue('A3', '销售日期：');
            $sheet->mergeCells('A3:B3');
            $sheet->setCellValue('C3', $date);
            $sheet->setCellValue('E3', '通知单号：');
            $sheet->setCellValue('F3', $notice_no);
            $sheet->mergeCells('F3:G3');

            $sheet->setCellValue('A4', '客户名称');
            $sheet->setCellValue('B4', $name);
            $sheet->mergeCells('B4:F4');
            $sheet->setCellValue('G4', '激活确认');
            $sheet->mergeCells('G4:I4');

            $sheet->setCellValue('A5', '客户地址');
            $sheet->setCellValue('B5', $address);
            $sheet->mergeCells('B5:F5');
            $sheet->setCellValue('G5', '是否激活：');
            $sheet->setCellValue('H5', '是');
            $sheet->setCellValue('I5', '否');

            $sheet->setCellValue('A6', '客户来源');
            $sheet->setCellValue('B6', $source);
            $sheet->setCellValue('C6', '销售员');
            $sheet->setCellValue('D6', $sell);
            $sheet->setCellValue('E6', '取卡方式');
            $sheet->setCellValue('F6', $get_card_type);

            $sheet->setCellValue('A7', '经办人');
            $sheet->setCellValue('B7', $operator);
            $sheet->setCellValue('C7', '手机');
            $sheet->setCellValue('D7', $mobile);
            $sheet->setCellValue('E7', '总收入');
            $sheet->setCellValue('F7', $total_price);

            $sheet->setCellValue('G6', '客户确认并签收');
            $sheet->mergeCells('G6:I7');

            $sheet->setCellValue('A8', '卡金额');
            $sheet->setCellValue('B8', $card_price);
            $sheet->setCellValue('C8', '支付方式');
            $sheet->setCellValue('D8', $pay_type);
            $sheet->setCellValue('E8', '已收款');
            $sheet->setCellValue('F8', $get_price);

            $sheet->setCellValue('A9', '手续费');
            $sheet->setCellValue('B9', $commission);
            $sheet->setCellValue('C9', '支付方式');
            $sheet->setCellValue('D9', $pay_type1);
            $sheet->setCellValue('E9', '应收款');
            $sheet->setCellValue('F9', $receivable);
            $sheet->mergeCells('G8:I9');

            $sheet->setCellValue('A10', '购卡信息');
            $sheet->mergeCells('A10:I10');

            $sheet->setCellValue('A11', '批次序号');
            $sheet->setCellValue('B11', '起始序号');
            $sheet->mergeCells('B11:D11');
            $sheet->setCellValue('E11', '终止序号');
            $sheet->mergeCells('E11:F11');
            $sheet->setCellValue('G11', '数量');
            $sheet->setCellValue('H11', '金额');
            $sheet->mergeCells('H11:I11');

            if ($batch_no1) $this->cardInfo($sheet, $batch_no1, $sn1_start, $sn1_end, $count1, $price1, $num);
            if ($batch_no1) $this->cardInfo($sheet, $batch_no2, $sn2_start, $sn2_end, $count2, $price2, $num);
            ++$num;
            $sheet->setCellValue("A{$num}", '备注');
            $sheet->mergeCells("B{$num}:I{$num}");
            ++$num;
            $sheet->setCellValue("A{$num}", '配卡人员：');
            $sheet->setCellValue("C{$num}", $card_name);
            $sheet->setCellValue("E{$num}", '审核人员：');
            $sheet->mergeCells("F{$num}:I{$num}");

            $sheet->getStyle("A1:I{$num}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle("A1:I{$num}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(15);
            $sheet->getColumnDimension('E')->setWidth(15);
            $sheet->getColumnDimension('F')->setWidth(15);
            $sheet->getColumnDimension('G')->setWidth(15);
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '00000000'],
                    ]
                ]
            ];
            $sheet->getStyle("A1:I{$num}")->applyFromArray($styleArray);
            $writer = new Xlsx($spreadsheet);
            $writer->save("/home/sdhou/Downloads/card1/{$notice_no}.xlsx");
        }
    }

    private function cardInfo(Worksheet $sheet, $batch_no, $sn_start, $sn_end, $count, $price, int &$num): void
    {
        $sheet->setCellValue("A{$num}", $batch_no);
        $sheet->setCellValueExplicit("B{$num}", $sn_start, DataType::TYPE_STRING);
        $sheet->mergeCells("B{$num}:D{$num}");
        $sheet->setCellValueExplicit("E{$num}", $sn_end, DataType::TYPE_STRING);
        $sheet->mergeCells("E{$num}:F{$num}");
        $sheet->setCellValue("G{$num}", $count);
        $sheet->setCellValue("H{$num}", $price);
        $sheet->mergeCells("H{$num}:I{$num}");
        ++$num;
    }

    private function file2(): array
    {
        $file = file_get_contents('/home/sdhou/Downloads/2.csv');
        $ret = [];
        foreach (explode("\n", $file) as $line) {
            $rows = explode(',', $line);
            if (count($rows) !== 6) {
                Requests::log($line);
                continue;
            }
            [$notice_no, $batch_no, $start_no, $end_no, $count, $price] = $rows;
            if (!is_numeric($price)) {
                Requests::log($line);
                continue;
            }
            $ret[$notice_no][] = [$batch_no, $start_no, $end_no, $count, $price];
        }
        return $ret;
    }
}
