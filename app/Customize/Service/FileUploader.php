<?php


namespace Customize\Service;


use Eccube\Common\EccubeConfig;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /**
     * @var EccubeConfig
     */
    private $eccubeConfig;

    public function __construct(
        EccubeConfig $eccubeConfig
    ) {
        $this->eccubeConfig = $eccubeConfig;
    }

    public function upload(UploadedFile $file, $staff_id)
    {
        $filename = sprintf('%s_%04d_%s-%s.%s', 'receipt', $staff_id, date('Ymd'), date('His'), $file->guessExtension() );
        $file->move(
            $this->eccubeConfig["eccube_save_image_dir"],
            $filename
        );

        return $filename;
    }

    public function invoiceUpload(UploadedFile $file, $ym, $id)
    {
        $filename = sprintf('%s_%s_%04d_%s-%s.%s', 'Honobono_Seikyusho', $ym, $id, date('Ymd'), date('His'), $file->guessExtension() );
        $file->move(
            $this->eccubeConfig["eccube_save_image_dir"],
            $filename
        );

        return $filename;
    }

    public function payslipUpload(UploadedFile $file, $ym, $id)
    {
        $filename = sprintf('%s_%s_%04d_%s-%s.%s', 'Honobono_Kyuyomeisai', $ym, $id, date('Ymd'), date('His'), $file->guessExtension() );
        $file->move(
            $this->eccubeConfig["eccube_save_image_dir"],
            $filename
        );

        return $filename;
    }
}
