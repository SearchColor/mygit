<?php
include 'dbconn.php';
$zip = new ZipArchive();
$zipName = "/var/www/html/storage/정회원신청서(JPG).zip";

if ($zip->open($zipName, ZipArchive::CREATE) !== TRUE) {
    exit("ZIP 파일을 생성할 수 없습니다: " . $zip->getStatusString());
}

if (isset($_POST['idxs'])) {
    $idxs = $_POST['idxs'];
    $placeholders = implode(',', array_fill(0, count($idxs), '?'));
    $query = "SELECT pdf_file FROM cms_member WHERE idx IN ($placeholders) AND pdf_file IS NOT NULL";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(str_repeat('i', count($idxs)), ...$idxs);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $pdfPath = $row['pdf_file'];
        if (file_exists($pdfPath) && is_readable($pdfPath)) {
            $fileName = basename($pdfPath);
            $encodedFileName = iconv("UTF-8", "CP949//IGNORE", $fileName);
            $zip->addFile($pdfPath, $encodedFileName);
        }
    }
    $zip->close();

    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . basename($zipName) . '"');
    header('Content-Length: ' . filesize($zipName));
    readfile($zipName);
    unlink($zipName); // ZIP 파일 삭제
}
?>