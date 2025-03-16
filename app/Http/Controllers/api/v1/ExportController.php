<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    public function export()
    {
        $userId = Auth::id();

        $contacts = Contact::where('user_id', $userId)->withTrashed()->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nome');
        $sheet->setCellValue('C1', 'Telefone');
        $sheet->setCellValue('D1', 'Email');
        $sheet->setCellValue('E1', 'Observações');
        $sheet->setCellValue('F1', 'Criado em');
        $sheet->setCellValue('G1', 'Atualizado em');
        $sheet->setCellValue('H1', 'Excluído em');

        $row = 2;
        foreach ($contacts as $contact) {
            $sheet->setCellValue('A' . $row, $contact->id);
            $sheet->setCellValue('B' . $row, $contact->name);
            $sheet->setCellValue('C' . $row, $contact->phone);
            $sheet->setCellValue('D' . $row, $contact->email);
            $sheet->setCellValue('E' . $row, $contact->observations);
            $sheet->setCellValue('F' . $row, $contact->created_at);
            $sheet->setCellValue('G' . $row, $contact->updated_at);
            $sheet->setCellValue('H' . $row, $contact->deleted_at);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);

        $fileName = 'contatos.xlsx';

        return new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]
        );
    }
}
