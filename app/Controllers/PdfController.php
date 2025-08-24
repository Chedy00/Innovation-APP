<?php
// PdfController.php
require_once __DIR__ . '/../Models/IdeaModel.php';
require_once __DIR__ . '/../../vendor/fpdf/fpdf.php';

class PdfController {
    public function ideaPdf($id) {
        // Start output buffering to prevent early output
        ob_start();

        $lang = $_GET['lang'] ?? 'fr';
        $ideaModel = new IdeaModel();
        $idea = $ideaModel->getIdeaWithEvaluations($id);
        if (!$idea) {
            die('Idée introuvable');
        }

        // Prepare translation if needed
        if ($lang === 'en') {
            $idea = $this->translateIdea($idea);
        }

        // Ensure 'moyenne_note' is set
        if (!isset($idea['moyenne_note'])) {
            $sum = 0;
            $count = 0;
            if (!empty($idea['evaluations'])) {
                foreach ($idea['evaluations'] as $eval) {
                    if (isset($eval['note']) && is_numeric($eval['note'])) {
                        $sum += (float)$eval['note'];
                        $count++;
                    }
                }
            }
            $idea['moyenne_note'] = $count > 0 ? $sum / $count : null;
        }

        $pdf = new FPDF();
        $pdf->AddPage();

        // Header background (dark blue)
        $pdf->SetFillColor(10, 30, 80);
        $pdf->Rect(0, 0, 210, 45, 'F');

        // Logo
        $logoPath = __DIR__ . '/../../public/assets/img/pngegg.png';
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, 10, 8, 24);
        }

        // Header with app name
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetXY(0, 10);
        $pdf->Cell(0, 10, utf8_decode($lang === 'en' ? 'Innovation Hub - Idea Details' : "Innovation Hub - Détail de l'idée"), 0, 1, 'C');

        // Contact info
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(0, 22);
        $pdf->Cell(0, 8, utf8_decode($lang === 'en' ? 'Location: Tunisia, Tunis Esprit' : 'Localisation: Tunisie, Tunis Esprit'), 0, 1, 'C');
        $pdf->SetXY(0, 30);
        $pdf->Cell(0, 8, utf8_decode($lang === 'en' ? 'Phone: +216 25 836 708' : 'Téléphone: 00216 25 836 708'), 0, 1, 'C');
        $pdf->SetXY(0, 38);
        $pdf->Cell(0, 8, utf8_decode('admin@innovation.tn'), 0, 1, 'C');
        $pdf->Ln(10);

        // Table 1: Idea Information
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetTextColor(10, 30, 80);
        $pdf->Cell(0, 10, utf8_decode($lang === 'en' ? 'Idea Information' : "Informations sur l'idée"), 0, 1, 'L');
        $pdf->SetDrawColor(10, 30, 80);
        $pdf->SetLineWidth(0.7);

        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(240, 245, 255);

        $pdf->Cell(60, 10, utf8_decode($lang === 'en' ? 'Title' : 'Titre'), 1, 0, 'L', true);
        $pdf->Cell(0, 10, utf8_decode($idea['titre']), 1, 1);
        $pdf->Cell(60, 10, utf8_decode($lang === 'en' ? 'Description' : 'Description'), 1, 0, 'L', true);
        $pdf->MultiCell(0, 10, utf8_decode($idea['description']), 1);
        $pdf->Cell(60, 10, utf8_decode($lang === 'en' ? 'Employee' : 'Salarié'), 1, 0, 'L', true);
        $pdf->Cell(0, 10, utf8_decode($idea['salarie_nom'] . ' ' . $idea['salarie_prenom']), 1, 1);
        $pdf->Cell(60, 10, utf8_decode($lang === 'en' ? 'Thematic' : 'Thématique'), 1, 0, 'L', true);
        $pdf->Cell(0, 10, utf8_decode($idea['thematique_nom']), 1, 1);
        $pdf->Cell(60, 10, utf8_decode($lang === 'en' ? 'Submission Date' : 'Date de soumission'), 1, 0, 'L', true);
        $pdf->Cell(0, 10, utf8_decode($idea['date_soumission']), 1, 1);
        $pdf->Cell(60, 10, utf8_decode($lang === 'en' ? 'Average Rating' : 'Note moyenne'), 1, 0, 'L', true);
        $pdf->Cell(0, 10, utf8_decode($idea['moyenne_note'] !== null ? number_format((float)$idea['moyenne_note'], 2) : 'N/A'), 1, 1);
        $pdf->Ln(8);

        // Table 2: Evaluations
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetTextColor(10, 30, 80);
        $pdf->Cell(0, 10, utf8_decode($lang === 'en' ? 'Evaluations' : 'Évaluations'), 0, 1, 'L');
        $pdf->SetDrawColor(10, 30, 80);
        $pdf->SetLineWidth(0.7);

        if (!empty($idea['evaluations'])) {
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetFillColor(240, 245, 255);
            $pdf->Cell(50, 8, utf8_decode($lang === 'en' ? 'Evaluator' : 'Évaluateur'), 1, 0, 'L', true);
            $pdf->Cell(20, 8, utf8_decode($lang === 'en' ? 'Rating' : 'Note'), 1, 0, 'C', true);
            $pdf->Cell(80, 8, utf8_decode($lang === 'en' ? 'Comment' : 'Commentaire'), 1, 0, 'L', true);
            $pdf->Cell(40, 8, utf8_decode($lang === 'en' ? 'Date' : 'Date'), 1, 1, 'C', true);

            $pdf->SetFont('Arial', '', 12);
            foreach ($idea['evaluations'] as $eval) {
                $pdf->Cell(50, 8, utf8_decode($eval['evaluateur_nom'] . ' ' . $eval['evaluateur_prenom']), 1, 0);
                $pdf->Cell(20, 8, utf8_decode($eval['note']), 1, 0, 'C');
                $pdf->Cell(80, 8, utf8_decode($eval['commentaire']), 1, 0);
                $pdf->Cell(40, 8, utf8_decode($eval['date_evaluation']), 1, 1, 'C');
            }
        } else {
            $pdf->Cell(0, 8, utf8_decode($lang === 'en' ? 'No evaluation for this idea.' : 'Aucune évaluation pour cette idée.'), 1, 1);
        }
        $pdf->Ln(10);

        // Footer
        $pdf->SetY(-25);
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->SetTextColor(120, 120, 120);
        $pdf->Cell(0, 8, utf8_decode(($lang === 'en' ? 'Document generated automatically on ' : 'Document généré automatiquement le ') . date('d/m/Y H:i')), 0, 1, 'R');

        // Clean any accidental output before sending PDF
        ob_clean();

        // Output PDF
        $pdf->Output('I', 'idee_' . $id . '.pdf');
        exit;
    }

    private function translateIdea($idea) {
        $fields = ['titre', 'description', 'salarie_nom', 'salarie_prenom', 'thematique_nom'];
        
        foreach ($fields as $field) {
            if (isset($idea[$field])) {
                $idea[$field] = $this->libreTranslate($idea[$field], 'fr', 'en');
            }
        }
        
        if (!empty($idea['evaluations'])) {
            foreach ($idea['evaluations'] as &$eval) {
                if (isset($eval['commentaire'])) {
                    $eval['commentaire'] = $this->libreTranslate($eval['commentaire'], 'fr', 'en');
                }
            }
        }
        return $idea;
    }

    private function libreTranslate($text, $source, $target) {
        if (empty(trim($text))) return $text;

        $url = 'https://libretranslate.de/translate';

        $data = [
            'q' => $text,
            'source' => $source,
            'target' => $target
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-App');

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return $text;
        }

        curl_close($ch);

        $result = json_decode($response, true);

        if (isset($result['translatedText'])) {
            return $result['translatedText'];
        }

        return $text;
    }
}