<?php
session_start();

include_once '../header/header_export_pdf.php';
require_once("../model/Utils.php");

// Créez une classe dérivée de FPDF
class PDF extends FPDF
{
    // Méthode pour l'en-tête
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, '', 0, 1, 'C');
        $this->Ln(10);
    }

    // Méthode pour le pied de page
    function Footer()
    {
        // Dessiner une ligne noire
        $this->SetDrawColor(0, 0, 0);
        $this->Line(10, 272, 200, 272);

        // Position à 1.5 cm du bas
        $this->SetY(-22);

        // Arial 7
        $this->SetFont('Arial', '', 7);

        // Ligne 1
        $this->Cell(0, 3.5, Utils::toMbConvertEncoding("S.A.R.L au Capital de 1 000 000 FCFA - Siège Social: ABIDJAN KOUMASSI ZONE INDUSTRIELLE EN FACE DU GARAGE AKWABA LOT 26"), 0, 1, 'C');
        // Ligne 2
        $this->Cell(0, 3.5, Utils::toMbConvertEncoding("RCCM N°: CI-ABJ-03-2024-B13-01193 - Tél. : (+225) 07 89 77 42 45/05 76 05 27 28"), 0, 1, 'C');
        // Ligne 3
        $this->Cell(0, 3.5, Utils::toMbConvertEncoding("E-mail: commercial@cafici.net - Compte Bancaire BDA N° : C193 CI201 01001 109179715491 10"), 0, 1, 'C');

        // Numéro de page
        $this->Cell(0, 10, Utils::toMbConvertEncoding('Page ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
    }

    // Méthode pour calculer le nombre de lignes qu'occupera un MultiCell
    function NbLines($w, $txt)
    {
        // Calcule le nombre de lignes qu'occupera un MultiCell de largeur $w pour $txt
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf(
            '%.2F %.2F %.2F %.2F %.2F %.2F c',
            $x1 * $this->k,
            ($h - $y1) * $this->k,
            $x2 * $this->k,
            ($h - $y2) * $this->k,
            $x3 * $this->k,
            ($h - $y3) * $this->k
        ));
    }

    function RoundedRect($x, $y, $w, $h, $r, $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if ($style == 'F')
            $op = 'f';
        elseif ($style == 'FD' || $style == 'DF')
            $op = 'B';
        else
            $op = 'S';
        $MyArc = 4 / 3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m', ($x + $r) * $k, ($hp - $y) * $k));
        $xc = $x + $w - $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - $y) * $k));
        $this->_Arc($xc + $r * $MyArc, $yc - $r, $xc + $r, $yc - $r * $MyArc, $xc + $r, $yc);
        $xc = $x + $w - $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $yc) * $k));
        $this->_Arc($xc + $r, $yc + $r * $MyArc, $xc + $r * $MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x + $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - ($y + $h)) * $k));
        $this->_Arc($xc - $r * $MyArc, $yc + $r, $xc - $r, $yc + $r * $MyArc, $xc - $r, $yc);
        $xc = $x + $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', $x * $k, ($hp - $yc) * $k));
        $this->_Arc($xc - $r, $yc - $r * $MyArc, $xc - $r * $MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }
}

// Créez un nouvel objet FPDF
$pdf = new PDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->AliasNbPages();

// Dimensions
$logoHeight = 30; // hauteur en mm
$logoWidth = 0;   // largeur automatique selon le ratio
$logoPath = '../logo/' . ($devis['logo'] ?? 'default_logo.jpg');
if (empty($devis['logo']) || !file_exists($logoPath) || !@getimagesize($logoPath)) {
    $logoPath = '../img/logo.png';
}
$enteteX = 10;
$enteteY = 15;
$enteteW = 190;

// --- EN-TÊTE AVEC COULEUR ---
$headerBg = [0, 165, 132];   // #00a584
$headerText = [255, 255, 255]; // blanc

// Rectangle de fond pour l'en-tête
$pdf->SetFillColor($headerBg[0], $headerBg[1], $headerBg[2]);
$pdf->Rect(0, 10, 210, $logoHeight + 10, 'F');

// Logo à gauche
$pdf->Image($logoPath, $enteteX, $enteteY, $logoWidth, $logoHeight);

// --- TEXTE ENTÊTE ---
$textW = 110; // largeur du bloc texte à droite (ajuste si besoin)
$textX = $enteteX + 60; // décale à droite du logo (ajuste selon la largeur max de ton logo)
$textY = $enteteY + 2;

// Préparer les lignes
$lines = [
    [
        'text' => 'CAFICI S.A.R.L',
        'size' => 16,
        'style' => 'B',
        'color' => [0, 0, 0],
        'font' => 'Arial'
    ],
    [
        'text' => 'ACHAT ET VENTE FOURNITURES INDUSTRIELLES',
        'size' => 13,
        'style' => '',
        'color' => [0, 0, 0],
        'font' => 'Arial'
    ],
    [
        'text' => 'MAINTENANCE INDUSTRIELLE - BTP - IMPORT-EXPORT',
        'size' => 13,
        'style' => 'B',
        'color' => [255, 204, 0],
        'font' => 'Arial'
    ],
    [
        'text' => 'COMMERCE GENERAL, DIVERS...',
        'size' => 14,
        'style' => '',
        'color' => [0, 0, 0],
        'font' => 'Arial'
    ],
];

$pdf->SetTextColor($headerText[0], $headerText[1], $headerText[2]);
foreach ($lines as $i => $line) {
    $pdf->SetFont($line['font'], $line['style'], $line['size']);
    $pdf->SetXY($textX, $textY + $i * 8);
    $pdf->Cell($textW, 8, Utils::toMbConvertEncoding($line['text']), 0, 0, 'L');
}

// Remettre la couleur noire pour la suite
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln($logoHeight + 5);

// --- QR CODE À GAUCHE, INFOS CLIENT À DROITE (compact et haut) ---
// Générer le QR code
$qrCodeData = 'https://fidest.ci/devis/request/export_pdf.php?devisId=' . $devis['id'];
$qrCodeFile = '../qrCodeFile/qrcode.png';
QRcode::png($qrCodeData, $qrCodeFile, 'L', 4, 2);

// Dimensions et positions
$qrX = 12;
$qrY = $enteteY + $logoHeight + 12;
$qrSize = 24; // taille du QR code en mm

$blocW = 80; // largeur réduite du bloc client
$blocH = 38; // hauteur augmentée pour tout contenir
$blocX = $qrX + $qrSize + 8; // à droite du QR code
$blocY = $qrY;

// Ombre légère derrière le bloc client
$pdf->SetFillColor(230, 236, 235); // gris très clair
$pdf->Rect($blocX + 2, $blocY + 2, $blocW, $blocH, 'F');

// Bloc principal (fond blanc, bordure verte, coins arrondis)
$pdf->SetDrawColor(0, 165, 132); // vert CAFICI
$pdf->SetLineWidth(0.7);
$pdf->SetFillColor(255, 255, 255); // blanc
$pdf->RoundedRect($blocX, $blocY, $blocW, $blocH, 3, 'DF');

// Afficher le QR code à gauche
$pdf->Image($qrCodeFile, $qrX, $qrY, $qrSize, $qrSize);

$dateEmission = Utils::dateEnToutesLettres($devis['date_emission']);

$ligne1 = "Abidjan, le $dateEmission";

// Ligne 2 : Nom du client
$ligne2 = $client['nom_client'];

// Ligne 3 : Localisation
$ligne3 = $client['localisation_client'];

// Ligne 4 : BP
$ligne4 = $client['bp_client'];

// Texte du bloc client (centré verticalement dans le bloc)
$pdf->SetXY($blocX, $blocY + 4);
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(80, 80, 80); // gris foncé
$pdf->Cell($blocW, 6, Utils::toMbConvertEncoding($ligne1), 0, 2, 'C');

$pdf->SetFont('Arial', 'B', 13);
$pdf->SetTextColor(0, 165, 132); // vert CAFICI
$pdf->Cell($blocW, 8, Utils::toMbConvertEncoding($ligne2), 0, 2, 'C');

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(80, 80, 80);
$pdf->Cell($blocW, 6, Utils::toMbConvertEncoding($ligne3), 0, 2, 'C');
$pdf->Cell($blocW, 6, Utils::toMbConvertEncoding($ligne4), 0, 2, 'C');

// Remettre les couleurs par défaut pour la suite
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetLineWidth(0.2);

// Avance le curseur pour ne pas chevaucher le tableau
$pdf->SetY($blocY + $blocH + 7);

// --- TITRE DEVIS ---
$pdf->SetFont('Arial', 'B', 15);
$pdf->SetTextColor($headerBg[0], $headerBg[1], $headerBg[2]);
$pdf->SetDrawColor($headerBg[0], $headerBg[1], $headerBg[2]);
$pdf->SetFillColor(255, 255, 255);

$refText = Utils::toMbConvertEncoding(strtoupper($offre['reference_offre']));

// Calcul largeur réelle du texte (max 150mm pour éviter d'aller trop loin sur la page)
$maxWidth = 180;
$textWidth = $pdf->GetStringWidth($refText) + 12; // 6mm padding de chaque côté
if ($textWidth > $maxWidth) $textWidth = $maxWidth;

// Positionner au centre
$pageWidth = 210 - 20; // A4 - marges (10mm de chaque côté)
$startX = 10 + ($pageWidth - $textWidth) / 2;

$pdf->SetX($startX);
$pdf->MultiCell($textWidth, 9, $refText, 1, 'C', false);

// Ajouter un petit espace après le bloc
$pdf->Ln(8);

// --- TABLEAU ---
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor($headerBg[0], $headerBg[1], $headerBg[2]);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetDrawColor($headerBg[0], $headerBg[1], $headerBg[2]);

$tableHeight = 7;

$pdf->Cell(10, $tableHeight, Utils::toMbConvertEncoding('N°'), 1, 0, 'C', true);
$pdf->Cell(90, $tableHeight, Utils::toMbConvertEncoding('Désignation'), 1, 0, 'C', true);
$pdf->Cell(25, $tableHeight, Utils::toMbConvertEncoding('Qté'), 1, 0, 'C', true);
$pdf->Cell(35, $tableHeight, Utils::toMbConvertEncoding('PU'), 1, 0, 'C', true);
$pdf->Cell(30, $tableHeight, Utils::toMbConvertEncoding('PT'), 1, 0, 'C', true);
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);

$rowFill = false;
$pos = 1;

foreach ($lignes as $index => $ligne) {
    $pdf->SetFillColor($rowFill ? 245 : 255, $rowFill ? 255 : 255, $rowFill ? 255 : 255); // alternance
    $pdf->Cell(10, $tableHeight, $pos++, 1, 0, 'C', $rowFill);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(90, $tableHeight, Utils::toMbConvertEncoding($ligne['designation']), 1, 0, 'L', $rowFill);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(25, $tableHeight, $ligne['quantite'], 1, 0, 'C', $rowFill);
    $pdf->Cell(35, $tableHeight, number_format($ligne['prix'], 0, ',', ' ') . ' XOF', 1, 0, 'C', $rowFill);
    $pdf->Cell(30, $tableHeight, number_format($ligne['total'], 0, ',', ' ') . ' XOF', 1, 0, 'C', $rowFill);
    $pdf->Ln();
    $rowFill = !$rowFill;
}

// --- TOTAUX ---
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor($headerBg[0], $headerBg[1], $headerBg[2]);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(160, 8, Utils::toMbConvertEncoding('MONTANT HT'), 1, 0, 'C', true);
$pdf->Cell(30, 8, number_format($devis['total_ht'], 0, ',', ' ') . ' XOF', 1, 1, 'C', true);

if ($devis['tva_facturable'] == 1) {
    $pdf->Cell(160, 8, Utils::toMbConvertEncoding('TVA 18%'), 1, 0, 'C', true);
    $pdf->Cell(30, 8, number_format($devis['tva'], 0, ',', ' ') . ' XOF', 1, 1, 'C', true);
    $pdf->Cell(160, 8, Utils::toMbConvertEncoding('MONTANT TTC'), 1, 0, 'C', true);
    $pdf->Cell(30, 8, number_format($devis['total_ttc'], 0, ',', ' ') . ' XOF', 1, 1, 'C', true);
} else {
    $pdf->Cell(160, 8, Utils::toMbConvertEncoding('TVA 18% (non facturée)'), 1, 0, 'C', true);
    $pdf->Cell(30, 8, '(' . number_format(0.18 * $devis['total_ht'], 0, ',', ' ') . ' XOF)', 1, 1, 'C', true);
    $pdf->Cell(160, 8, Utils::toMbConvertEncoding('MONTANT NET À PAYER'), 1, 0, 'C', true);
    $pdf->Cell(30, 8, number_format($devis['total_ht'], 0, ',', ' ') . ' XOF', 1, 1, 'C', true);
}
$pdf->SetTextColor(0, 0, 0);

// --- MONTANT EN LETTRES ---
$pdf->Ln(5);
$pdf->SetFont('Arial', 'U', 8);
$pdf->Cell(0, 6, Utils::toMbConvertEncoding("Arrêtée le présent devis à la somme de :"), 0, 1, 'L');
$montantLettre = Utils::montantEnLettre($devis['total_ttc']);
$pdf->SetFont('Arial', 'B', 10);
$pdf->MultiCell(0, 6, Utils::toMbConvertEncoding(strtoupper($montantLettre)), 0, 'L');

// --- SIGNATURE ---
$pdf->Ln(8);
$pdf->SetFont('Arial', 'U', 10);
$pdf->SetTextColor($headerBg[0], $headerBg[1], $headerBg[2]);
$pdf->Cell(0, 7, Utils::toMbConvertEncoding('DIRECTEUR COMMERCIAL'), 0, 1, 'R');
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 8, Utils::toMbConvertEncoding('NOM DU DIRECTEUR'), 0, 1, 'R');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 20, Utils::toMbConvertEncoding('(Cachet et signature)'), 0, 1, 'R');

ob_clean();

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="devis_' . $devis['id'] . '.pdf"');

$pdf->Output('I', 'devis_' . $devis['id'] . '.pdf');

unset($_SESSION['devisId']);

exit();
