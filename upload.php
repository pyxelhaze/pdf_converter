<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $allowedExtensions = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf', 'pdf'];
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

    if (!in_array($fileExtension, $allowedExtensions)) {
        echo json_encode(['success' => false, 'error' => 'Ungültige Dateierweiterung']);
        exit;
    }

    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filePath = $uploadDir . basename($file['name']);
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        $outputPath = $uploadDir . pathinfo($file['name'], PATHINFO_FILENAME) . '.pdf';

        // Pfad zu LibreOffice auf deinem System anpassen
        $libreOfficePath = '"C:\\Program Files\\LibreOffice\\program\\soffice.exe"';
        $command = "$libreOfficePath --headless --convert-to pdf --outdir $uploadDir $filePath";
        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            echo json_encode(['success' => true, 'file_url' => $outputPath]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Konvertierung fehlgeschlagen']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Datei konnte nicht hochgeladen werden']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Keine Datei hochgeladen']);
}