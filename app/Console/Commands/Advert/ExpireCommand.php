<?php

namespace App\Console\Commands\Advert;

use App\Entity\Adverts\Advert\Advert;
use App\UseCases\Adverts\AdvertService;
use App\UseCases\Auth\RegisterService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireCommand extends Command
{
    protected $signature = 'advert:expire';

    protected $description = 'Set status "expired" for add adverts with expired date.';

    private $service;

    public function __construct(AdvertService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function handle(): bool
    {
        $success = true;
        foreach (Advert::active()->where('axpired_at', '<', Carbon::now()->cursor()) as $advert) {
            try {
                $advert->service->expire($advert);
            } catch (\DomainException $e) {
                $this->error($e->getMessage());
                $success = false;
            }
        }
        return $success;
    }
}
