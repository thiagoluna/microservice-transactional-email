<?php

namespace App\Console\Commands;

use App\Kafka\EmailHandler;
use App\Models\Email;
use App\Services\EmailService;
use Illuminate\Console\Command;
use Psr\Container\ContainerInterface;

class SendEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Email via CLI';
    protected $emailService;
    /**
     * @var \Illuminate\Contracts\Foundation\Application|mixed
     */
    private $emailHandler;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->emailService = app(EmailService::class);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->initialInteraction();
        } catch (\Exception $e) {
            $this->info($e->getMessage());
            return $e->getCode();
        }

        $this->fillForm();
    }

    public function initialInteraction()
    {
        $this->info('');
        $this->info('---------------------------------------');
        $this->info('Send Email Application!');
        $this->info('---------------------------------------');
        $this->info('Choose one option below:');
        $this->info('0- Exit');
        $this->info('1- Send Email');
        $option = $this->ask('Enter the option number of your choice:');
        $this->chooseOption($option);
    }

    public function fillForm($validation = 0)
    {
        if ($validation) {
            $emailTo = $this->ask('Please, enter a Valid Recipient Email');
        } else {
            $emailTo = $this->ask('Please, enter the Recipient Email');
        }
        $data['email'] = $emailTo;

        if(!$validate = $this->emailService->validateEmail($emailTo))
            $this->fillForm(1);

        $data['name'] = $this->askName();
        $data['subject'] = $this->askSubject();
        $data['content'] = $this->askMessage();

        $result = $this->emailService->store($data);

        if ($result)
            $this->info("Email to {$data['name']} was sent to queue.");

        $this->lastOptionn();
    }

    public function askName(): ?string
    {
        $name = $this->ask('Please, enter the Recipient Name');
        if ($name == '')
            $this->askName();

        return $name;
    }

    public function askSubject(): ?string
    {
        $subject = $this->ask('Please, enter the Subject');
        if ($subject == '')
            $this->askSubject();

        return $subject;
    }

    public function askMessage(): ?string
    {
        $content = $this->ask('Please, enter the Message');
        if  ($content == '')
            $this->askMessage();

        return $content;
    }

    public function lastOptionn()
    {
        $option = $this->ask('Enter 0 to Exit or 1 to send email.');
        $this->chooseOption($option);
    }

    public function chooseOption($option)
    {
        if ($option == 0)
            $this->exit();

        if ($option == 1)
            $this->fillForm();

        if (!in_array($option, [0, 1])) {
            $this->info('Wrong option. Choose again:');
            $this->lastOptionn();
        }
    }

    /**
     * @throws \Exception
     */
    public function exit()
    {
        throw new \Exception('By for now!', 0);
    }
}
