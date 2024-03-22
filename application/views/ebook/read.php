<!doctype html>
<html lang="en">
<head>
    <base href="<?=base_url()?>"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
    <title>Read Book</title>
    <!--<link rel="stylesheet" src="<?=html_escape('assets/node_modules/pdfjs-dist/web/pdf_viewer.css')?>"/>-->
    <link rel="stylesheet" href="<?=base_url('assets/css/bootstrap.min.css')?>">
    <style>
        #page-jumper {
            width: 10rem;
        }
    </style>
</head>
<body class="bg-body-tertiary">
    <iframe class="w-100 vh-100" id="main-content" src="assets/libs/pdf.js/generic-legacy/web/viewer.html?file=<?=html_escape(base_url('assets/files/ebooks/'.$book['file_1']))?>"></iframe>
</body>
</html>