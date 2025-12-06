<?php

namespace Modules\Common\Console;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use function Laravel\Prompts\progress;
use function Laravel\Prompts\spin;
use Modules\Common\Models\Area as AreaModel;

class Area extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catch:areas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步地区数据';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        spin(function () {
            $this->createAreasTable();
            sleep(1);
        }, '创建地区（areas）表');

        $this->output->newLine();

        $content = spin(function () {
            $zip = new \ZipArchive();

            $zip->open(storage_path() . DIRECTORY_SEPARATOR . 'region.json.zip');

            $fp = $zip->getStream('region.json');

            if (!is_resource($fp)) {
                $this->error('获取地区原始数据失败');
                exit;
            }

            $content = '';
            while (!feof($fp)) {
                $content .= fgets($fp);
            }
            sleep(1);
            return $content;
        }, '获取地区原始数据');


        $areas = json_decode($content, true);

        $chunks = array_chunk($areas, 10);

        progress(
            label: '同步地区数据',
            steps: $chunks,
            callback: fn ($area) => AreaModel::query()->insert($area)
        );

        $this->info('同步成功');
    }

    protected function createAreasTable(): void
    {
        if (!Schema::hasTable('areas')) {
            Schema::create('areas', function (Blueprint $table) {
                $table->integer('id');

                $table->integer('parent_id');

                $table->integer('level');

                $table->string('name');

                $table->string('initial')->comment('拼音首字母');

                $table->string('pinyin');

                $table->string('citycode');

                $table->string('adcode');

                $table->string('lng_lat')->comment('坐标');
            });
        }
    }
}
