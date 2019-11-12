<?php

namespace App\MessageHandler\Crawler\DataGovRo\PostalCodes;

use App\Entity\OpenData\Source;
use App\Message\DataGovRo\PostalCodes\ProcessPostalCodes;
use App\MessageHandler\AbstractMessageHandler;
use App\MessageHandler\Crawler\DataGovRo\FileSystemAwareTrait;
use App\Repository\Entity\Administrative\AdministrativeUnitRepository;
use Symfony\Component\Process\Process;

/**
 * Class ProcessPostalCodes
 * @package App\MessageHandler\Crawler\DataGovRo\PostalCodes
 */
class ProcessPostalCodesHandler extends AbstractMessageHandler
{
    use FileSystemAwareTrait;

    /**
     * @var AdministrativeUnitRepository
     */
    private $administrativeUnitRepository;

    /**
     * ProcessPostalCodesHandler constructor.
     * @param AdministrativeUnitRepository $administrativeUnitRepository
     */
    public function __construct(AdministrativeUnitRepository $administrativeUnitRepository)
    {
        parent::__construct();
        $this->administrativeUnitRepository = $administrativeUnitRepository;
    }

    /**
     * @param ProcessPostalCodes $message
     */
    public function __invoke(ProcessPostalCodes $message)
    {
        $latestFile = "infocod-cu-siruta-mai-2016.xls";

        foreach ($message->getSource()->getChildren() as $child) {
            $fileName = pathinfo(parse_url($child->getUrl(), PHP_URL_PATH), PATHINFO_BASENAME);
            if ($fileName == $latestFile) {
                $this->loadPostalCodes($child);
                return;
            }
        }
        $this->log("Could not find $latestFile");
    }

    private function loadPostalCodes(Source $source)
    {
        $this->log("Processing postal codes");
        if (!$this->xlsToCSVs($source)) {
            throw new \RuntimeException("Could not convert xls to csv");
        }
        $localPath = $this->generateLocalFilePath($source);
        for ($counter = 0; $counter <= 2; $counter++) {
            $sheetCsv = "{$localPath}-{$counter}.csv";
            if (!file_exists($sheetCsv)) {
                throw new \RuntimeException("Corrupt source found");
            }
            $this->log("Processing {$sheetCsv}");
            switch ($counter) {
                case 0:
//                    $this->processCSVFromPath(
//                        $sheetCsv,
//                        function ($row) {
//                        }
//                    );
                    //bucuresti
                    break;
                case 1:
                    $this->processCSVFromPath(
                        $sheetCsv,
                        function ($row) {
                            print_r($row);
                        }
                    );
                    //>50.000
                    break;
                case 2:
                    //<50.000
                    /* $progress = $this->createProgressBar($this->getNoLinesFromPath($sheetCsv));
                     $this->processCSVFromPath(
                         $sheetCsv,
                         function ($row) use ($progress) {
                             $administrativeUnit = $this->administrativeUnitRepository->getCachedBySiruta(intval($row['SIRUTA']));
                             if (is_null($administrativeUnit)) {
                                 $this->log("\nCannot find {$row['SIRUTA']} " . json_encode($row));
                                 return;
                             }
                             $administrativeUnit->addPostalCode($row['Codpostal']);
                             $this->administrativeUnitRepository->persist($administrativeUnit, true);
                             $this->graphEntityManager->clear();
                             $progress->advance();
                         }
                     );
                     $progress->finish();;
                    */
                    break;
            }
        }

    }

    private function xlsToCSVs(Source $source)
    {
        $localFilePath = $this->generateLocalFilePath($source);

        $process = new Process("ssconvert -S {$localFilePath} {$localFilePath}-%n.csv");
        $process->run();

        return $process->isSuccessful();
    }
}
