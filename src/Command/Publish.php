<?php

/**
 * @author Edwin Xu <171336747@qq.com>
 * @version 2022-10-29
 */

declare(strict_types=1);

namespace Edwinhuish\ThinkAuth\Command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class Publish extends Command
{
    protected function configure()
    {
        $this->setName('auth:publish')->setDescription('Publish the config file and migration files.');
    }

    protected function execute(Input $input, Output $output)
    {
        $root = $this->app->getRootPath();

        $this->copyTo(__DIR__ . '/../../config/', $this->app->getConfigPath());
        $this->copyTo(__DIR__ . '/../../database/migrations/', $root . '/database/migrations/');

        $output->writeln('成功复制文件到项目文件夹。');
    }

    private function copyTo($sourcePath, $targetPath)
    {
        if (! is_dir($targetPath)) {
            mkdir($targetPath, 0755, true);
        }

        $handle = dir($sourcePath);

        while ($entry = $handle->read()) {
            if (('.' != $entry) && ('..' != $entry)) {
                if (is_file($sourcePath . $entry)) {
                    @copy($sourcePath . $entry, $targetPath . $entry);
                }
            }
        }
    }
}
