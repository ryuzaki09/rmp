<?php
namespace App\Http\Helpers;

class FileExporter
{
    private $fileToExport;
    private $headers;
    private $dataRows;
    private $allowedFiles = array("csv");
    private $extension;
    private $fileName;

    public function __construct($file)
    {
        $this->checkFileToExport($file);
    }

    private function checkFileToExport($file)
    {
        // if (in_array($this->allowedFiles, strtolower($file))) {
        if (in_array(strtolower($file), $this->allowedFiles)) {
            $this->extension = $file;
            return true;
        }

        throw new \Exception("file not permitted to export");
    }

    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    public function setData(array $data)
    {
        $this->dataRows = $data;
    }

    public function setFileName($filename)
    {
        $this->fileName = (string) $filename;
    }

    public function export()
    {
        if (!$this->fileName) {
            throw new \Exception("File name has not been set");
        }

        $filename = $this->fileName."_".time().".".$this->extension;
        $fp = fopen($filename, "w");
        
        fputcsv($fp, $this->headers);
        
        if (!empty($this->dataRows)) {
            foreach ($this->dataRows as $data) {
                fputcsv($fp, $data);
            }
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header("Content-Length: " . filesize($filename));
        readfile($filename);

        unlink($filename);
    }


}
