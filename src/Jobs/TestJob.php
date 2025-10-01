<?php

namespace Biigle\Modules\AnnotationTags\Jobs;

use Biigle\Jobs\Job;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use File;

class TestJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;


    private $annotations;

    private $tags;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($annotations, $tags)
    {
        $this->annotations = $annotations;
        $this->tags = $tags;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $file = tempnam(sys_get_temp_dir(), 'tag_report');
        File::put($file, json_encode([
            'coco'=>$this->annotations,
            'tags'=>$this->tags,
        ]));
        $code = 0;
        $python = config('annotation_tags.python');
        $script = config('annotation_tags.script');
        $lines = [];
        $command = "{$python} {$script} {$file} 2>&1";
        
        $output = json_decode(exec($command, $lines, $code));
        dd($output);
    }

    /**
     * Handle a job failure.
     *
     * @return void
     */
    public function failed()
    {
        $print = new \Symfony\Component\Console\Output\ConsoleOutput();
        $print->writeln('failed...');
    }
}
