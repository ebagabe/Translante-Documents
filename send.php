<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $email = $_POST['email'];
    $document_files = $_FILES['documents'];
    $from_language = $_POST['from_language'];
    $to_language = $_POST['to_language'];

    $document_filename = 'document.pdf';

    $boundary = md5(time());

    $headers = "From: \"$name $sobrenome\" <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/html; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $body .= "<html><body>";
    $body .= "<h2>Novo Formulario Recebido</h2>";
    $body .= "<p><strong>Nome:</strong> $name</p>";
    $body .= "<p><strong>Sobrenome:</strong> $sobrenome</p>";
    $body .= "<p><strong>Email:</strong> $email</p>";
    $body .= "<p><strong>De:</strong> $from_language</p>";
    $body .= "<p><strong>Para:</strong> $to_language</p>";
    $body .= "</body></html>\r\n";

    if (!empty($document_files['name'][0])) {
        foreach ($document_files['tmp_name'] as $index => $tmp_name) {
            $document_filename = 'document_' . ($index + 1) . '.pdf';

            $attachment = chunk_split(base64_encode(file_get_contents($tmp_name)));
            $body .= "--$boundary\r\n";
            $body .= "Content-Type: application/pdf; name=\"$document_filename\"\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n";
            $body .= "Content-Disposition: attachment; filename=\"$document_filename\"\r\n\r\n";
            $body .= "$attachment\r\n\r\n";
        }
    }

    $to = "Atip Canada <renato@atipcanada.com>";
    $subject = "Novo Formulario Recebido - $name $sobrenome";
    mail($to, $subject, $body, $headers);

    header("Location: index.html");
    exit();
}