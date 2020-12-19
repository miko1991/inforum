<?php


namespace Kernel;


use Helpers\Helpers;

class Updater
{
    public static function getVersion(): string {
        $currentJsonString = file_get_contents("version.json");
        $currentJson = json_decode($currentJsonString);
        return $currentJson->version;
    }

    public static function checkHasUpdate(): bool {
        $context = stream_context_create([
           "http" => [
               "method" => "GET",
               "header" => "Cache-Control: no-cache"
           ]
        ]);
        $updateJsonString = file_get_contents("https://raw.githubusercontent.com/miko1991/inforum3/master/version.json", false, $context);
        $updateJson = json_decode($updateJsonString);

        $currentJsonString = file_get_contents("version.json");
        $currentJson = json_decode($currentJsonString);

        return version_compare($currentJson->version, $updateJson->version, "<");
    }

    public static function update($updates): string {
        $firstDirname = "";

        foreach ($updates as $update) {
            $randomString = Helpers::generateRandomString(8);
            $target_file = "./Updates/".$randomString.".zip";

            file_put_contents($target_file,
                file_get_contents("http://localhost:5001/api".$update["downloadPath"])
            );

            $zip = new \ZipArchive;
            $res = $zip->open($target_file);

            if ($res === TRUE) {
                // extract it to the path we determined above
                $zip->extractTo("./Updates/".$randomString);
                $zip->close();
            }

            $files = scandir("./Updates/".$randomString);
            array_shift($files);
            array_shift($files);

            Helpers::copy("./Updates/".$randomString."/".$files[0]."/app", "./");
        }

        return $firstDirname;
    }
}